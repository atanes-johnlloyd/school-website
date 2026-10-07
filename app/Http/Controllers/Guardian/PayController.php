<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\ContributionAssignment;
use App\Models\Payment;
use App\Models\PaymentAuthorization;
use App\Services\Payment\ContributionService;
use App\Services\Payment\PayMongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PayController extends Controller
{
    public function __construct(
        protected ContributionService $contributions,
        protected PayMongoService $paymongo,
    ) {}

    /* ══════════════════════════════════════════════════════ */
    /* SHOW                                                      */
    /* ══════════════════════════════════════════════════════ */

    public function show(Request $request, string $token)
    {
        $auth = $this->resolve($token);

        if (! $auth) {
            return Inertia::render('Guardian/Pay/Invalid', ['reason' => 'Token not found.']);
        }

        if ($auth->isExpired() && ! $auth->isUsed()) {
            return Inertia::render('Guardian/Pay/Invalid', ['reason' => 'This payment link has expired.']);
        }

        $auth->loadMissing(['assignment.contribution.creator', 'assignment.student.user', 'guardian']);

        $assignment = $auth->assignment;
        $contribution = $assignment->contribution;

        // If already acted on, show the outcome
        if ($auth->isUsed()) {
            return Inertia::render('Guardian/Pay/Decided', [
                'action'       => $auth->action,
                'action_at'    => $auth->action_at?->toIso8601String(),
                'student'      => $assignment->student->user?->name,
                'contribution' => $contribution->title,
                'amount'       => (float) $assignment->amount_owed,
                'status'       => $assignment->status,
                'decline_reason' => $auth->decline_reason,
            ]);
        }

        // If already paid (via another token), show the receipt
        if ($assignment->isPaid()) {
            return Inertia::render('Guardian/Pay/AlreadyPaid', [
                'student'      => $assignment->student->user?->name,
                'contribution' => $contribution->title,
                'amount'       => (float) $assignment->amount_owed,
                'paid_at'      => $assignment->paid_at?->toIso8601String(),
            ]);
        }

        return Inertia::render('Guardian/Pay/Show', [
            'token'        => $token,
            'guardian'     => $auth->guardian?->full_name ?? 'Parent/Guardian',
            'student'      => $assignment->student->user?->name ?? 'Student',
            'teacher'      => $contribution->creator?->name ?? 'Teacher',
            'contribution' => [
                'title'              => $contribution->title,
                'purpose'            => $contribution->purpose,
                'description'        => $contribution->description,
                'deadline'           => $contribution->deadline_at?->toIso8601String(),
                'requires_consent'   => (bool) $contribution->requires_guardian_consent,
                'amount_type'        => $contribution->amount_type,
            ],
            'amount'       => (float) $assignment->amount_owed,
            'expires_at'   => $auth->expires_at?->toIso8601String(),
        ]);
    }

    /* ══════════════════════════════════════════════════════ */
    /* CHOOSE: pay online                                        */
    /* ══════════════════════════════════════════════════════ */

        /* ══════════════════════════════════════════════════════ */
    /* CHOOSE: pay online                                        */
    /* ══════════════════════════════════════════════════════ */

    public function payOnline(Request $request, string $token)
    {
        $auth = $this->resolve($token);
        abort_unless($auth, 404);
        abort_if($auth->isExpired() || $auth->isUsed(), 422, 'This link is no longer valid.');

        $assignment = $auth->assignment()->with(['contribution', 'student.user'])->first();

        if ($assignment->isPaid()) {
            return response()->json(['message' => 'Already paid.'], 422);
        }

        try {
            // 1. Create the local payment record
            $payment = $this->contributions->createPaymentFor($assignment, $auth->guardian);

            // 2. Attempt to create the PayMongo checkout session
            $session = $this->paymongo->createCheckoutSession($payment, [
                'description' => $assignment->contribution->title,
                'success_url' => route('guardian.pay.return', ['ref' => $payment->reference_no, 'status' => 'success']),
                'cancel_url'  => route('guardian.pay.return', ['ref' => $payment->reference_no, 'status' => 'cancelled']),
            ]);

            // 3. Update the payment with the checkout details
            $payment->update([
                'paymongo_checkout_id' => $session['checkout_id'],
                'checkout_url'         => $session['checkout_url'],
                'raw_response'         => $session['raw'],
            ]);

            // 4. ONLY mark the authorization token as used AFTER everything succeeds
            $this->contributions->recordGuardianChoice(
                $auth,
                PaymentAuthorization::ACTION_PAY_ONLINE,
                $request->ip(),
                $request->userAgent()
            );

            return response()->json([
                'checkout_url' => $session['checkout_url'],
                'reference'    => $payment->reference_no,
            ]);
        } catch (\Throwable $e) {
            Log::error('Guardian pay-online failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /* ══════════════════════════════════════════════════════ */
    /* CHOOSE: pay in cash                                       */
    /* ══════════════════════════════════════════════════════ */

    public function payCash(Request $request, string $token)
    {
        $auth = $this->resolve($token);
        abort_unless($auth, 404);

        try {
            $this->contributions->recordGuardianChoice(
                $auth,
                PaymentAuthorization::ACTION_PAY_CASH,
                $request->ip(),
                $request->userAgent()
            );
            return response()->json(['message' => 'Recorded. Please send cash with your child. The teacher will confirm receipt.']);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /* ══════════════════════════════════════════════════════ */
    /* CHOOSE: decline                                           */
    /* ══════════════════════════════════════════════════════ */

    public function decline(Request $request, string $token)
    {
        $auth = $this->resolve($token);
        abort_unless($auth, 404);

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $this->contributions->recordGuardianChoice(
                $auth,
                PaymentAuthorization::ACTION_DECLINE,
                $request->ip(),
                $request->userAgent(),
                $validated['reason'] ?? null
            );
            return response()->json(['message' => 'Your response has been recorded.']);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /* ══════════════════════════════════════════════════════ */
    /* RETURN (from PayMongo)                                    */
    /* ══════════════════════════════════════════════════════ */

    public function returnFromCheckout(Request $request)
    {
        $ref    = $request->query('ref');
        $status = $request->query('status', 'unknown');

        $payment = Payment::where('reference_no', $ref)->first();

        return Inertia::render('Guardian/Pay/Return', [
            'reference' => $ref,
            'status'    => $status,
            'payment'   => $payment ? [
                'id'     => $payment->id,
                'amount' => (float) $payment->amount,
                'status' => $payment->status,
                'paid_at'=> $payment->paid_at?->toIso8601String(),
            ] : null,
        ]);
    }

    public function pollStatus(string $reference)
    {
        $payment = Payment::where('reference_no', $reference)->first();
        abort_unless($payment, 404);

        return response()->json([
            'status'  => $payment->status,
            'paid_at' => $payment->paid_at?->toIso8601String(),
            'method'  => $payment->payment_method,
        ]);
    }

    /* ══════════════════════════════════════════════════════ */

    protected function resolve(string $token): ?PaymentAuthorization
    {
        if (strlen($token) !== 64) return null;
        return PaymentAuthorization::where('token', $token)->first();
    }
}