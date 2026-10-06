<?php

namespace App\Services\Payment;

use App\Models\Contribution;
use App\Models\ContributionAssignment;
use App\Models\Payment;
use App\Models\PaymentAuthorization;
use App\Models\Student;
use App\Models\StudentGuardian;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ContributionService
{
    public function __construct(protected NotificationService $notifications) {}

    /* ══════════════════════════════════════════════════════ */
    /* ASSIGNMENT                                               */
    /* ══════════════════════════════════════════════════════ */

    public function assignToStudent(Contribution $contribution, Student $student): ContributionAssignment
    {
        return ContributionAssignment::firstOrCreate(
            [
                'contribution_id' => $contribution->id,
                'student_id'      => $student->id,
            ],
            [
                'amount_owed' => $contribution->amountForStudent() ?? 0,
                'status'      => ContributionAssignment::STATUS_PENDING,
            ]
        );
    }

    public function assignToStudents(Contribution $contribution, iterable $studentIds): int
    {
        $created = 0;
        DB::transaction(function () use ($contribution, $studentIds, &$created) {
            foreach ($studentIds as $id) {
                $student = Student::find($id);
                if (! $student) continue;
                $assignment = $this->assignToStudent($contribution, $student);
                if ($assignment->wasRecentlyCreated) $created++;
            }
        });
        return $created;
    }

    /* ══════════════════════════════════════════════════════ */
    /* NOTIFY GUARDIAN                                          */
    /* ══════════════════════════════════════════════════════ */

    /**
     * Send (or resend) a payment-request email to the primary guardian.
     * Invalidates any outstanding tokens for this assignment first.
     */
    public function notifyGuardian(
        ContributionAssignment $assignment,
        ?StudentGuardian $guardian = null,
        ?string $overrideEmail = null
    ): PaymentAuthorization {
        $assignment->loadMissing(['contribution', 'student.user']);

        $guardian ??= $this->pickPrimaryGuardian($assignment->student);
        $email = $overrideEmail ?? $guardian?->email;

        if (! $email) {
            throw new \RuntimeException(
                'No guardian email on file for ' . ($assignment->student->user?->name ?? 'this student') . '.'
            );
        }

        // Invalidate any older unused tokens
        PaymentAuthorization::where('contribution_assignment_id', $assignment->id)
            ->whereNull('action')
            ->where('expires_at', '>', now())
            ->update(['expires_at' => now()]);

        $token = bin2hex(random_bytes(32));

        $auth = PaymentAuthorization::create([
            'contribution_assignment_id' => $assignment->id,
            'guardian_id'                => $guardian?->id,
            'token'                      => $token,
            'expires_at'                 => now()->addDays(14),
        ]);

        $assignment->update([
            'status'               => ContributionAssignment::STATUS_NOTIFIED,
            'guardian_id'          => $guardian?->id,
            'last_consent_sent_at' => now(),
            'consent_resend_count' => $assignment->consent_resend_count + 1,
        ]);

        $this->sendPaymentRequestEmail($assignment, $auth, $email);

        return $auth;
    }

    /* ══════════════════════════════════════════════════════ */
    /* GUARDIAN CHOICE                                          */
    /* ══════════════════════════════════════════════════════ */

    public function recordGuardianChoice(
        PaymentAuthorization $auth,
        string $choice,
        string $ip,
        ?string $userAgent,
        ?string $reason = null
    ): void {
        if ($auth->isExpired()) {
            throw new \RuntimeException('This payment link has expired.');
        }
        if ($auth->isUsed()) {
            throw new \RuntimeException('This payment link has already been used.');
        }

        if (! in_array($choice, [
            PaymentAuthorization::ACTION_PAY_ONLINE,
            PaymentAuthorization::ACTION_PAY_CASH,
            PaymentAuthorization::ACTION_DECLINE,
        ], true)) {
            throw new \RuntimeException('Invalid choice.');
        }

        DB::transaction(function () use ($auth, $choice, $ip, $userAgent, $reason) {
            $auth->update([
                'action'         => $choice,
                'action_at'      => now(),
                'decline_reason' => $choice === PaymentAuthorization::ACTION_DECLINE ? $reason : null,
                'ip_address'     => $ip,
                'user_agent'     => $userAgent,
            ]);

            $assignment = $auth->assignment;

            if ($choice === PaymentAuthorization::ACTION_PAY_CASH) {
                $assignment->update(['status' => ContributionAssignment::STATUS_CASH_PENDING]);
            } elseif ($choice === PaymentAuthorization::ACTION_DECLINE) {
                $assignment->update([
                    'status'         => ContributionAssignment::STATUS_DECLINED,
                    'declined_at'    => now(),
                    'decline_reason' => $reason,
                ]);
            }
            // pay_online: don't change assignment status — webhook will do it
        });
    }

    /* ══════════════════════════════════════════════════════ */
    /* PAYMENT                                                  */
    /* ══════════════════════════════════════════════════════ */

    public function createPaymentFor(
        ContributionAssignment $assignment,
        ?StudentGuardian $guardian = null,
        ?float $amount = null
    ): Payment {
        $assignment->loadMissing(['contribution', 'student.user']);

        if ($assignment->isPaid()) {
            throw new \RuntimeException('This contribution is already paid.');
        }

        $finalAmount = $amount ?? (float) $assignment->amount_owed;

        if ($finalAmount <= 0) {
            throw new \RuntimeException('Amount must be greater than zero.');
        }

        return Payment::create([
            'payable_type'  => ContributionAssignment::class,
            'payable_id'    => $assignment->id,
            'payer_user_id' => null,
            'guardian_id'   => $guardian?->id ?? $assignment->guardian_id,
            'student_id'    => $assignment->student_id,
            'amount'        => $finalAmount,
            'currency'      => config('paymongo.currency', 'PHP'),
            'reference_no'  => Payment::nextReference(),
            'status'        => 'pending',
            'expires_at'    => now()->addMinutes((int) config('paymongo.checkout_ttl_minutes', 60)),
        ]);
    }

    public function markPaid(Payment $payment, array $providerPayload = []): void
    {
        if ($payment->isPaid()) return;

        DB::transaction(function () use ($payment, $providerPayload) {
            $payment->update([
                'status'                     => 'paid',
                'paid_at'                    => now(),
                'payment_method'             => $providerPayload['method'] ?? $payment->payment_method,
                'paymongo_payment_id'        => $providerPayload['payment_id'] ?? $payment->paymongo_payment_id,
                'paymongo_payment_intent_id' => $providerPayload['intent_id'] ?? $payment->paymongo_payment_intent_id,
                'raw_response'               => $providerPayload['raw'] ?? $payment->raw_response,
            ]);

            $assignment = $payment->payable;
            if ($assignment instanceof ContributionAssignment) {
                $assignment->update([
                    'status'  => ContributionAssignment::STATUS_PAID,
                    'paid_at' => now(),
                ]);
            }
        });

        $this->sendReceiptEmail($payment->fresh(['payable.contribution', 'payable.student.user', 'payable.guardian']));
    }

    /* ══════════════════════════════════════════════════════ */
    /* CASH — TEACHER CONFIRMS                                  */
    /* ══════════════════════════════════════════════════════ */

    public function markCashReceived(ContributionAssignment $assignment, int $teacherUserId): void
    {
        if ($assignment->isPaid()) return;

        $assignment->update([
            'status'  => ContributionAssignment::STATUS_PAID,
            'paid_at' => now(),
        ]);

        // Create a Payment record for the audit trail (offline cash)
        Payment::create([
            'payable_type'  => ContributionAssignment::class,
            'payable_id'    => $assignment->id,
            'guardian_id'   => $assignment->guardian_id,
            'student_id'    => $assignment->student_id,
            'amount'        => $assignment->amount_owed,
            'currency'      => 'PHP',
            'reference_no'  => Payment::nextReference(),
            'status'        => 'paid',
            'payment_method'=> 'cash',
            'paid_at'       => now(),
            'raw_response'  => [
                'method'      => 'cash',
                'confirmed_by'=> $teacherUserId,
                'confirmed_at'=> now()->toIso8601String(),
            ],
        ]);

        $this->sendReceiptEmail(
            $assignment->fresh(['contribution', 'student.user', 'guardian'])
        );
    }

    public function rejectCash(ContributionAssignment $assignment, string $reason): void
    {
        $assignment->update([
            'status'         => ContributionAssignment::STATUS_NOTIFIED,
            'decline_reason' => 'Cash rejected: ' . $reason,
        ]);

        // Re-notify the guardian so they can try again
        try {
            $this->notifyGuardian($assignment->fresh(['contribution', 'student.user']));
        } catch (\Throwable $e) {
            Log::warning('Re-notify after cash rejection failed', ['error' => $e->getMessage()]);
        }
    }

    /* ══════════════════════════════════════════════════════ */
    /* HELPERS                                                  */
    /* ══════════════════════════════════════════════════════ */

    protected function pickPrimaryGuardian(Student $student): ?StudentGuardian
    {
        return StudentGuardian::where('student_id', $student->id)
            ->whereNotNull('email')
            ->orderByDesc('is_primary')
            ->orderBy('id')
            ->first();
    }

    protected function sendPaymentRequestEmail(
        ContributionAssignment $a,
        PaymentAuthorization $auth,
        string $email
    ): void {
        $link          = route('guardian.pay.show', ['token' => $auth->token]);
        $requiresConsent = (bool) $a->contribution->requires_guardian_consent;
        $studentName   = $a->student->user?->name ?? 'your child';

        try {
            $this->notifications->send(
                $email,
                $requiresConsent
                    ? "Approve & Pay: {$a->contribution->title} — ₱" . number_format((float) $a->amount_owed, 2)
                    : "Payment Due: {$a->contribution->title} — ₱" . number_format((float) $a->amount_owed, 2),
                'contribution-payment-request',
                [
                    'guardian_name'    => $auth->guardian?->full_name ?? 'Parent/Guardian',
                    'student_name'     => $studentName,
                    'teacher_name'     => $a->contribution->creator?->name ?? 'Teacher',
                    'contribution'     => $a->contribution->title,
                    'purpose'          => $a->contribution->purpose ?? '',
                    'description'      => $a->contribution->description ?? '',
                    'amount'           => '₱' . number_format((float) $a->amount_owed, 2),
                    'deadline'         => $a->contribution->deadline_at?->format('F d, Y') ?? 'No deadline',
                    'requires_consent' => $requiresConsent,
                    'pay_link'         => $link,
                    'expires_at'       => $auth->expires_at->format('F d, Y g:i A'),
                ]
            );
        } catch (\Throwable $e) {
            Log::error('Guardian payment-request email failed', [
                'assignment_id' => $a->id,
                'error'         => $e->getMessage(),
            ]);
        }
    }

    protected function sendReceiptEmail($payable): void
    {
        // Called both from markPaid (Payment) and markCashReceived (Assignment)
        $assignment = $payable instanceof Payment
            ? $payable->payable
            : $payable;

        if (! $assignment instanceof ContributionAssignment) return;

        $student  = $assignment->student;
        $guardian = $assignment->guardian;

        // Amount: prefer payment amount, fall back to assignment
        $amount = $payable instanceof Payment
            ? (float) $payable->amount
            : (float) $assignment->amount_owed;

        $reference = $payable instanceof Payment
            ? $payable->reference_no
            : 'CASH-' . str_pad($assignment->id, 6, '0', STR_PAD_LEFT);

        $method = $payable instanceof Payment
            ? strtoupper((string) $payable->payment_method)
            : 'CASH';

        $paidAt = $payable instanceof Payment
            ? $payable->paid_at?->format('F d, Y g:i A')
            : $assignment->paid_at?->format('F d, Y g:i A');

        $commonPlaceholders = [
            'reference'    => $reference,
            'contribution' => $assignment->contribution->title,
            'amount'       => '₱' . number_format($amount, 2),
            'paid_at'      => $paidAt,
            'method'       => $method,
        ];

        if ($guardian?->email) {
            try {
                $this->notifications->send(
                    $guardian->email,
                    'Payment Receipt — ' . $assignment->contribution->title,
                    'payment-receipt',
                    array_merge($commonPlaceholders, ['recipient_name' => $guardian->full_name])
                );
            } catch (\Throwable $e) {
                Log::error('Guardian receipt email failed', ['error' => $e->getMessage()]);
            }
        }

        if ($student->user?->email) {
            try {
                $this->notifications->send(
                    $student->user->email,
                    'Contribution Paid — ' . $assignment->contribution->title,
                    'payment-receipt',
                    array_merge($commonPlaceholders, ['recipient_name' => $student->user->name])
                );
            } catch (\Throwable $e) {
                Log::error('Student receipt email failed', ['error' => $e->getMessage()]);
            }
        }
    }
}