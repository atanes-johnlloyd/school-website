<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\PaymentAuthorization;
use App\Services\Payment\ContributionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ConsentController extends Controller
{
    public function __construct(protected ContributionService $contributions) {}

    /** Landing page — shows the decision UI. */
    public function show(Request $request, string $token)
    {
        $auth = $this->resolveAuthorization($token);

        if (! $auth) {
            return Inertia::render('Guardian/Consent/Invalid', [
                'reason' => 'Token not found.',
            ]);
        }

        if ($auth->isExpired()) {
            return Inertia::render('Guardian/Consent/Invalid', [
                'reason' => 'This consent link has expired.',
            ]);
        }

        if ($auth->action !== null) {
            return Inertia::render('Guardian/Consent/AlreadyDecided', [
                'action'      => $auth->action,
                'action_at'   => $auth->action_at?->toIso8601String(),
                'student'     => $auth->assignment->student->user?->name,
                'contribution'=> $auth->assignment->contribution->title,
                'amount'      => (float) $auth->assignment->amount_owed,
            ]);
        }

        $auth->loadMissing(['assignment.contribution', 'assignment.student.user', 'guardian']);

        return Inertia::render('Guardian/Consent/Show', [
            'token'        => $token,
            'guardian'     => $auth->guardian?->full_name ?? 'Parent/Guardian',
            'student'      => $auth->assignment->student->user?->name ?? 'Student',
            'contribution' => [
                'title'     => $auth->assignment->contribution->title,
                'purpose'   => $auth->assignment->contribution->purpose,
                'description'=> $auth->assignment->contribution->description,
                'deadline'  => $auth->assignment->contribution->deadline_at?->toIso8601String(),
            ],
            'amount'       => (float) $auth->assignment->amount_owed,
            'expires_at'   => $auth->expires_at?->toIso8601String(),
        ]);
    }

    /** Handle POST decision. */
    public function decide(Request $request, string $token)
    {
        $auth = $this->resolveAuthorization($token);

        if (! $auth) {
            return response()->json(['message' => 'Invalid link.'], 404);
        }

        $validated = $request->validate([
            'action' => ['required', 'in:approve,decline'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            if ($validated['action'] === 'approve') {
                $this->contributions->recordApproval($auth, $request->ip(), $request->userAgent());
                return response()->json(['message' => 'Consent recorded. Thank you!']);
            }

            $this->contributions->recordDecline(
                $auth,
                $request->ip(),
                $request->userAgent(),
                $validated['reason'] ?? null
            );
            return response()->json(['message' => 'Response recorded.']);
        } catch (\Throwable $e) {
            Log::error('Guardian decision failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    protected function resolveAuthorization(string $token): ?PaymentAuthorization
    {
        if (strlen($token) !== 64) return null;
        return PaymentAuthorization::where('token', $token)->first();
    }
}