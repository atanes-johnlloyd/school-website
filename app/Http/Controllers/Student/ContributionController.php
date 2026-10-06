<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ContributionAssignment;
use App\Models\Payment;
use App\Services\Payment\ContributionService;
use App\Services\Payment\PayMongoService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContributionController extends Controller
{
    public function __construct(
        protected ContributionService $contributions,
        protected PayMongoService $paymongo,
    ) {}

    /** List all contribution assignments for the logged-in student. */
    public function index(Request $request)
    {
        $student = $request->user()->student;
        abort_unless($student, 403);

        $assignments = ContributionAssignment::query()
            ->where('student_id', $student->id)
            ->with([
                'contribution.creator:id,name',
                'contribution.classroom.subject:id,name',
                'contribution.classroom.section:id,name',
                'guardian:id,full_name,email',
            ])
            ->orderByDesc('id')
            ->get()
            ->map(fn (ContributionAssignment $a) => [
                'id'                => $a->id,
                'contribution_id'   => $a->contribution_id,
                'title'             => $a->contribution->title,
                'purpose'           => $a->contribution->purpose,
                'description'       => $a->contribution->description,
                'amount_owed'       => (float) $a->amount_owed,
                'amount_type'       => $a->contribution->amount_type,
                'min_amount'        => $a->contribution->min_amount ? (float) $a->contribution->min_amount : null,
                'status'            => $a->status,
                'is_required'       => (bool) $a->contribution->is_required,
                'deadline_at'       => $a->contribution->deadline_at?->toIso8601String(),
                'requires_consent'  => (bool) $a->contribution->requires_guardian_consent,
                'guardian'          => $a->guardian ? [
                    'name'  => $a->guardian->full_name,
                    'email' => $a->guardian->email,
                ] : null,
                'authorized_at'     => $a->authorized_at?->toIso8601String(),
                'paid_at'           => $a->paid_at?->toIso8601String(),
                'decline_reason'    => $a->decline_reason,
                'can_request_consent'=> in_array($a->status, ['pending', 'declined'], true)
                    && $a->contribution->requires_guardian_consent,
                'can_pay'           => $a->status === 'authorized'
                    || ($a->status === 'pending' && ! $a->contribution->requires_guardian_consent),
                'is_paid'           => $a->status === 'paid',
            ]);

        $counts = [
            'all'        => $assignments->count(),
            'pending'    => $assignments->whereIn('status', ['pending', 'awaiting_guardian'])->count(),
            'ready'      => $assignments->where('status', 'authorized')->count(),
            'paid'       => $assignments->where('status', 'paid')->count(),
            'declined'   => $assignments->where('status', 'declined')->count(),
        ];

        $payload = ['assignments' => $assignments, 'counts' => $counts];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Student/Contributions/Index', $payload);
    }

    /** Show one assignment detail. */
    public function show(Request $request, ContributionAssignment $assignment)
    {
        $this->authorizeStudent($request, $assignment);

        $assignment->load([
            'contribution.creator:id,name',
            'contribution.classroom.subject:id,name',
            'contribution.classroom.section:id,name',
            'guardian:id,full_name,email,contact_number,relationship',
            'authorizations' => fn ($q) => $q->orderByDesc('id'),
            'payments' => fn ($q) => $q->orderByDesc('id'),
        ]);

        return response()->json(['assignment' => $assignment]);
    }

    /** Request guardian consent — sends email. */
    public function requestConsent(Request $request, ContributionAssignment $assignment)
    {
        $this->authorizeStudent($request, $assignment);

        if (! in_array($assignment->status, ['pending', 'declined'], true)) {
            return response()->json([
                'message' => 'This contribution is already past the consent stage.',
            ], 422);
        }

        // Rate limit: max 5 sends per hour
        if ($assignment->last_consent_sent_at
            && $assignment->last_consent_sent_at->diffInMinutes(now()) < 10
            && $assignment->consent_resend_count > 0) {
            return response()->json([
                'message' => 'Please wait a few minutes before re-sending.',
            ], 429);
        }

        try {
            $this->contributions->requestGuardianConsent($assignment);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'message' => 'Consent request sent to your guardian.',
            'status'  => 'awaiting_guardian',
        ]);
    }

    /** Initiate PayMongo checkout, return redirect URL. */
    public function checkout(Request $request, ContributionAssignment $assignment)
    {
        $this->authorizeStudent($request, $assignment);

        if ($assignment->status === 'paid') {
            return response()->json(['message' => 'Already paid.'], 422);
        }

        if ($assignment->contribution->requires_guardian_consent
            && $assignment->status !== 'authorized') {
            return response()->json([
                'message' => 'Guardian approval is required before payment.',
            ], 422);
        }

        $validated = $request->validate([
            'amount' => ['nullable', 'numeric', 'min:1', 'max:100000'],
        ]);

        $amount = null;
        if ($assignment->contribution->amount_type === 'open' && isset($validated['amount'])) {
            $min = (float) ($assignment->contribution->min_amount ?? 0);
            if ((float) $validated['amount'] < $min) {
                return response()->json([
                    'message' => 'Amount is below the minimum ₱' . number_format($min, 2),
                ], 422);
            }
            $amount = (float) $validated['amount'];
        }

        try {
            $payment = $this->contributions->createPaymentFor($assignment, $amount);

            $session = $this->paymongo->createCheckoutSession($payment, [
                'description' => $assignment->contribution->title,
                'success_url' => route('student.contributions.return', ['ref' => $payment->reference_no, 'status' => 'success']),
                'cancel_url'  => route('student.contributions.return', ['ref' => $payment->reference_no, 'status' => 'cancelled']),
            ]);

            $payment->update([
                'paymongo_checkout_id' => $session['checkout_id'],
                'checkout_url'         => $session['checkout_url'],
                'raw_response'         => $session['raw'],
            ]);

            return response()->json([
                'checkout_url' => $session['checkout_url'],
                'reference'    => $payment->reference_no,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /** Student returns from PayMongo redirect. */
    public function returnFromCheckout(Request $request)
    {
        $ref = $request->query('ref');
        $status = $request->query('status', 'unknown');

        $payment = Payment::where('reference_no', $ref)->first();

        return Inertia::render('Student/Contributions/Return', [
            'reference'  => $ref,
            'status'     => $status,
            'payment'    => $payment ? [
                'id'     => $payment->id,
                'amount' => (float) $payment->amount,
                'status' => $payment->status,
                'paid_at'=> $payment->paid_at?->toIso8601String(),
            ] : null,
        ]);
    }

    /** Poll payment status (for the return page). */
    public function status(Request $request, Payment $payment)
    {
        $student = $request->user()->student;
        abort_unless($student && $payment->student_id === $student->id, 403);

        return response()->json([
            'status'   => $payment->status,
            'paid_at'  => $payment->paid_at?->toIso8601String(),
            'method'   => $payment->payment_method,
        ]);
    }

    protected function authorizeStudent(Request $request, ContributionAssignment $assignment): void
    {
        $student = $request->user()->student;
        abort_unless($student, 403);
        abort_unless($assignment->student_id === $student->id, 403);
    }
}