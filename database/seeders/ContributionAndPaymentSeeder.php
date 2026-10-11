<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentGuardian;
use App\Models\Teacher;
use App\Models\Term;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * ContributionAndPaymentSeeder
 * ----------------------------
 * Seeds the guardian-driven contribution/payment system end-to-end.
 */
class ContributionAndPaymentSeeder extends Seeder
{
    public function run(): void
    {
        $activeYear = SchoolYear::where('is_active', true)->first();
        if (! $activeYear) {
            $this->command->error('  ! No active school year — aborting.');
            return;
        }

        // Use the active term regardless of naming (1st Semester vs 1st Term).
        $term = Term::where('school_year_id', $activeYear->id)
            ->where('is_active', true)
            ->first()
            ?? Term::where('school_year_id', $activeYear->id)
                ->orderBy('start_date')
                ->first();

        if (! $term) {
            $this->command->error('  ! No active term — aborting.');
            return;
        }

        $sections = Section::where('school_year_id', $activeYear->id)
            ->orderBy('id')
            ->get();

        if ($sections->isEmpty()) {
            $this->command->error('  ! No sections for active year — aborting.');
            return;
        }

        $defaultCreator = User::where('email', 'coordinator@salawagshs.deped.gov.ph')->first()
                       ?? User::where('email', 'sysadmin@salawagshs.deped.gov.ph')->first();

        if (! $defaultCreator) {
            $this->command->error('  ! No admin user — aborting.');
            return;
        }

        $this->command->info('  · Seeding contributions for SY ' . $activeYear->label);

        $contributions = $this->createContributions($activeYear, $sections, $defaultCreator);

        foreach ($contributions as $entry) {
            [$contributionId, $scope, $targetSections, $targetClasses, $plan] = $entry;

            $studentIds = $this->studentsInScope($targetSections, $targetClasses);

            foreach ($studentIds as $idx => $studentId) {
                $this->createAssignmentForStudent(
                    contributionId: $contributionId,
                    studentId:      $studentId,
                    plan:           $plan,
                    seedIdx:        $idx,
                );
            }
        }

        $this->command->info('✔ Contributions seeded.');
        $this->command->info('   contributions:           ' . DB::table('contributions')->count());
        $this->command->info('   contribution_assignments:' . DB::table('contribution_assignments')->count());
        $this->command->info('   payment_authorizations:  ' . DB::table('payment_authorizations')->count());
        $this->command->info('   payments:                ' . DB::table('payments')->count());
        $this->command->info('   paymongo_webhook_events: ' . DB::table('paymongo_webhook_events')->count());
    }

    /* =================================================================
     | CONTRIBUTIONS
     *================================================================= */
    protected function createContributions(
        SchoolYear $year,
        \Illuminate\Support\Collection $sections,
        User $defaultCreator,
    ): array {
        $now = Carbon::now();

        $allActive        = $sections;
        $g11Sections      = $sections->where('grade_level', '11')->values();
        $g12Sections      = $sections->where('grade_level', '12')->values();

        $stemSections = $sections
            ->filter(fn ($s) => str_starts_with(strtoupper($s->name), 'STEM'))
            ->values();

        $ictClass = ClassRoom::whereHas('section', function ($q) use ($year) {
                $q->where('school_year_id', $year->id)
                  ->where('name', 'like', 'ICT 12%');
            })
            ->whereHas('subject', fn ($q) => $q->where('code', 'ICT-CSS'))
            ->first()
            ?? ClassRoom::whereHas('section', function ($q) use ($year) {
                $q->where('school_year_id', $year->id)
                  ->where('name', 'like', 'ICT%');
            })->first();

        $ptaPlan = [
            'paid', 'paid', 'paid', 'paid', 'paid', 'paid',
            'cash_pending', 'cash_pending', 'cash_pending',
            'notified', 'notified', 'notified',
            'declined', 'declined',
            'waived',
            'pending', 'pending',
        ];

        $fieldTripPlan = [
            'paid', 'paid', 'paid', 'paid',
            'notified', 'notified', 'notified',
            'declined', 'pending',
        ];

        $capstonePlan = [
            'paid', 'paid', 'paid', 'cash_pending', 'notified', 'pending',
        ];

        $gradPlan = [
            'notified', 'notified', 'notified', 'notified',
            'paid', 'paid',
            'pending', 'pending',
            'cash_pending',
            'waived',
        ];

        $christmasPlan = [
            'paid', 'paid', 'paid', 'paid', 'paid',
            'cash_pending', 'cash_pending',
            'notified', 'notified',
            'declined', 'waived', 'pending',
        ];

        $contributions = [
            ['scope' => 'section', 'title' => 'PTA Contribution — S.Y. 2026-2027',
             'description' => 'Annual Parent-Teacher Association contribution used to fund classroom repairs, small equipment, and co-curricular activities for the school year.',
             'purpose' => 'PTA fund — classroom maintenance and learner activities',
             'amount_type' => 'fixed', 'amount' => 500.00, 'is_required' => false,
             'deadline_at' => Carbon::parse('2026-10-30 23:59:59'),
             'is_published' => true, 'published_at' => Carbon::parse('2026-08-10 08:00:00'),
             'requires_guardian_consent' => true,
             'sections' => $allActive, 'classes' => collect(), 'plan' => $ptaPlan],

            ['scope' => 'section', 'title' => 'Brigada Eskwela 2026 — Voluntary Donation',
             'description' => 'Annual Brigada Eskwela donation drive. Any amount helps provide cleaning materials, paint, and minor repairs. Minimum ₱100.',
             'purpose' => 'Brigada Eskwela 2026 — campus repairs and cleaning materials',
             'amount_type' => 'open', 'amount' => null, 'min_amount' => 100.00, 'target_amount' => 25000.00,
             'is_required' => false,
             'deadline_at' => Carbon::parse('2026-08-05 23:59:59'),
             'is_published' => true, 'published_at' => Carbon::parse('2026-07-20 08:00:00'),
             'requires_guardian_consent' => true,
             'sections' => $g11Sections, 'classes' => collect(), 'plan' => $ptaPlan],

            ['scope' => 'section', 'title' => 'Field Trip — National Museum Manila',
             'description' => 'Educational field trip to the National Museum Complex, Manila. Fee covers bus rental, entrance fees, and packed lunch. Schedule: November 20, 2026.',
             'purpose' => 'Educational field trip to Manila',
             'amount_type' => 'fixed', 'amount' => 850.00, 'is_required' => false,
             'deadline_at' => Carbon::parse('2026-11-15 23:59:59'),
             'is_published' => true, 'published_at' => Carbon::parse('2026-10-15 08:00:00'),
             'requires_guardian_consent' => true,
             'sections' => $stemSections, 'classes' => collect(), 'plan' => $fieldTripPlan],

            ['scope' => 'class', 'title' => 'ICT Capstone — Web Hosting & Domain',
             'description' => 'Shared cost of domain registration and one-year hosting for the Grade 12 ICT Capstone web projects.',
             'purpose' => 'Capstone project — hosting & domain',
             'amount_type' => 'fixed', 'amount' => 300.00, 'is_required' => true,
             'deadline_at' => Carbon::parse('2026-10-30 23:59:59'),
             'is_published' => true, 'published_at' => Carbon::parse('2026-10-10 08:00:00'),
             'requires_guardian_consent' => true,
             'sections' => collect(), 'classes' => collect([$ictClass])->filter(), 'plan' => $capstonePlan],

            ['scope' => 'section', 'title' => 'Graduation Fee 2027',
             'description' => 'Covers graduation toga rental, diploma printing, ceremony logistics, and venue. Final deadline is February 15, 2027.',
             'purpose' => 'Graduation ceremony 2027',
             'amount_type' => 'fixed', 'amount' => 1200.00, 'is_required' => true,
             'deadline_at' => Carbon::parse('2027-02-15 23:59:59'),
             'is_published' => true, 'published_at' => Carbon::parse('2026-11-02 08:00:00'),
             'requires_guardian_consent' => true,
             'sections' => $g12Sections, 'classes' => collect(), 'plan' => $gradPlan],

            ['scope' => 'section', 'title' => 'Class Christmas Party 2026',
             'description' => 'Class Christmas party potluck and activity fund. Covers venue decorations, simple tokens, and shared food for the December 18 celebration.',
             'purpose' => 'Christmas party potluck fund',
             'amount_type' => 'fixed', 'amount' => 250.00, 'is_required' => false,
             'deadline_at' => Carbon::parse('2026-12-10 23:59:59'),
             'is_published' => true, 'published_at' => Carbon::parse('2026-11-25 08:00:00'),
             'requires_guardian_consent' => true,
             'sections' => $g11Sections, 'classes' => collect(), 'plan' => $christmasPlan],
        ];

        $result = [];

        foreach ($contributions as $row) {
            if ($row['scope'] === 'class' && $row['classes']->isEmpty()) {
                continue;
            }

            $firstTarget = $row['sections']->first() ?? ($row['classes']->first()?->section ?? null);
            $creator = $defaultCreator;

            if ($firstTarget && $firstTarget->adviser_id) {
                $adviser = Teacher::find($firstTarget->adviser_id);
                if ($adviser?->user_id) {
                    $creator = User::find($adviser->user_id) ?? $defaultCreator;
                }
            }

            DB::table('contributions')->updateOrInsert(
                [
                    'school_year_id' => $year->id,
                    'title'          => $row['title'],
                ],
                [
                    'created_by'      => $creator->id,
                    'scope'           => $row['scope'],
                    'class_id'        => $row['classes']->first()?->id,
                    'section_id'      => $row['sections']->first()?->id,
                    'strand_id'       => null,
                    'grade_level'     => $row['sections']->first()?->grade_level,
                    'title'           => $row['title'],
                    'description'     => $row['description'],
                    'purpose'         => $row['purpose'],
                    'amount_type'     => $row['amount_type'],
                    'amount'          => $row['amount'] ?? null,
                    'min_amount'      => $row['min_amount'] ?? null,
                    'target_amount'   => $row['target_amount'] ?? null,
                    'is_required'     => $row['is_required'],
                    'deadline_at'     => $row['deadline_at'],
                    'is_published'    => $row['is_published'],
                    'published_at'    => $row['published_at'],
                    'requires_guardian_consent' => $row['requires_guardian_consent'],
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]
            );

            $id = DB::table('contributions')
                ->where('school_year_id', $year->id)
                ->where('title', $row['title'])
                ->value('id');

            if (! $id) {
                continue;
            }

            $result[] = [$id, $row['scope'], $row['sections'], $row['classes'], $row['plan']];
        }

        return $result;
    }

    /* =================================================================
     | STUDENTS IN SCOPE
     *================================================================= */
    protected function studentsInScope(
        \Illuminate\Support\Collection $sections,
        \Illuminate\Support\Collection $classes,
    ): array {
        $ids = collect();

        if ($sections->isNotEmpty()) {
            $sectionIds = $sections->pluck('id')->all();
            $ids = $ids->merge(
                DB::table('enrollments')
                    ->whereIn('section_id', $sectionIds)
                    ->where('status', 'enrolled')
                    ->pluck('student_id')
            );
        }

        if ($classes->isNotEmpty()) {
            $classIds = $classes->pluck('id')->all();
            $ids = $ids->merge(
                DB::table('class_students')
                    ->whereIn('class_id', $classIds)
                    ->where('status', 'active')
                    ->pluck('student_id')
            );
        }

        return $ids->unique()->values()->all();
    }

    /* =================================================================
     | ASSIGNMENT + AUTHORIZATION + PAYMENT
     *================================================================= */
    protected function createAssignmentForStudent(
        int $contributionId,
        int $studentId,
        array $plan,
        int $seedIdx,
    ): void {
        $now = Carbon::now();

        $status = $plan[$seedIdx % count($plan)];

        $contribution = DB::table('contributions')->where('id', $contributionId)->first();
        if (! $contribution) {
            return;
        }

        $amountOwed = (float) ($contribution->amount ?? $contribution->min_amount ?? 0.0);

        $guardian = StudentGuardian::where('student_id', $studentId)
            ->where('is_primary', true)
            ->first()
            ?? StudentGuardian::where('student_id', $studentId)->first();

        $notifiedAt   = null;
        $authorizedAt = null;
        $declinedAt   = null;
        $declineReason= null;
        $paidAt       = null;

        if (in_array($status, ['notified', 'cash_pending', 'paid', 'declined'], true)) {
            $notifiedAt = Carbon::now()->subDays(5);
        }
        if (in_array($status, ['cash_pending', 'paid'], true)) {
            $authorizedAt = Carbon::now()->subDays(3);
        }
        if ($status === 'paid') {
            $paidAt = Carbon::now()->subDays(1);
        }
        if ($status === 'declined') {
            $declinedAt    = Carbon::now()->subDays(2);
            $declineReason = 'Hindi na po kaya sa budget ngayong buwan — susubukan po sa susunod.';
        }

        DB::table('contribution_assignments')->updateOrInsert(
            [
                'contribution_id' => $contributionId,
                'student_id'      => $studentId,
            ],
            [
                'amount_owed'           => $amountOwed,
                'status'                => $status,
                'guardian_id'           => $guardian?->id,
                'authorized_at'         => $authorizedAt,
                'declined_at'           => $declinedAt,
                'decline_reason'        => $declineReason,
                'paid_at'               => $paidAt,
                'consent_resend_count'  => 0,
                'last_consent_sent_at'  => $notifiedAt,
                'created_at'            => $now,
                'updated_at'            => $now,
            ]
        );

        $assignmentId = DB::table('contribution_assignments')
            ->where('contribution_id', $contributionId)
            ->where('student_id', $studentId)
            ->value('id');

        if (! $assignmentId || ! $guardian) {
            return;
        }

        if (in_array($status, ['notified', 'cash_pending', 'paid', 'declined'], true)) {
            $this->createPaymentAuthorization(
                assignmentId: $assignmentId,
                guardianId:   $guardian->id,
                status:       $status,
                notifiedAt:   $notifiedAt,
                actionAt:     $authorizedAt ?? $declinedAt,
                declineReason:$declineReason,
                amount:       $amountOwed,
            );
        }

        if (in_array($status, ['cash_pending', 'paid'], true)) {
            $this->createPayment(
                assignmentId: $assignmentId,
                studentId:    $studentId,
                guardianId:   $guardian->id,
                amount:       $amountOwed,
                isPaid:       $status === 'paid',
                paidAt:       $paidAt,
            );
        }
    }

    /* =================================================================
     | PAYMENT AUTHORIZATION
     *================================================================= */
    protected function createPaymentAuthorization(
        int $assignmentId,
        int $guardianId,
        string $status,
        ?Carbon $notifiedAt,
        ?Carbon $actionAt,
        ?string $declineReason,
        float $amount,
    ): void {
        $now = Carbon::now();

        $token = hash('sha256', "assign-{$assignmentId}-guardian-{$guardianId}-salawagshs");

        $action = match ($status) {
            'paid'         => 'pay_online',
            'cash_pending' => 'pay_cash',
            'declined'     => 'decline',
            'notified'     => null,
            default        => null,
        };

        $expiresAt = ($notifiedAt ?? $now)->copy()->addHours(72);

        DB::table('payment_authorizations')->updateOrInsert(
            ['token' => $token],
            [
                'contribution_assignment_id' => $assignmentId,
                'guardian_id'                => $guardianId,
                'action'                     => $action,
                'action_at'                  => $actionAt,
                'decline_reason'             => $declineReason,
                'ip_address'                 => '203.177.' . (($assignmentId % 250) + 1) . '.' . (($guardianId % 250) + 1),
                'user_agent'                 => 'Mozilla/5.0 (Linux; Android 13) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128 Mobile Safari/537.36',
                'expires_at'                 => $expiresAt,
                'created_at'                 => $now,
                'updated_at'                 => $now,
            ]
        );
    }

    /* =================================================================
     | PAYMENTS (+ PayMongo webhooks)
     *================================================================= */
    protected function createPayment(
        int $assignmentId,
        int $studentId,
        int $guardianId,
        float $amount,
        bool $isPaid,
        ?Carbon $paidAt,
    ): void {
        $now = Carbon::now();

        $referenceNo = 'PAY-' . $now->format('Y') . '-' . str_pad((string) $assignmentId, 6, '0', STR_PAD_LEFT);
        $isOnline    = $isPaid && ($assignmentId % 3 !== 0);

        $paymentMethod = $isOnline ? 'gcash' : 'cash';

        $paymongoCheckoutId   = $isOnline ? 'cs_' . Str::random(24) : null;
        $paymongoPaymentId    = ($isOnline && $isPaid) ? 'pay_' . Str::random(24) : null;
        $paymongoPaymentIntent= ($isOnline && $isPaid) ? 'pi_' . Str::random(24) : null;
        $checkoutUrl          = $isOnline ? "https://checkout.paymongo.com/{$paymongoCheckoutId}" : null;

        $status = $isPaid ? 'paid' : 'pending';

        DB::table('payments')->updateOrInsert(
            ['reference_no' => $referenceNo],
            [
                'payable_type'                => 'App\\Models\\ContributionAssignment',
                'payable_id'                  => $assignmentId,
                'payer_user_id'               => null,
                'guardian_id'                 => $guardianId,
                'student_id'                  => $studentId,
                'amount'                      => $amount,
                'currency'                    => 'PHP',
                'paymongo_checkout_id'        => $paymongoCheckoutId,
                'paymongo_payment_id'         => $paymongoPaymentId,
                'paymongo_payment_intent_id'  => $paymongoPaymentIntent,
                'checkout_url'                => $checkoutUrl,
                'status'                      => $status,
                'payment_method'              => $paymentMethod,
                'paid_at'                     => $isPaid ? ($paidAt ?? $now) : null,
                'expires_at'                  => $isPaid ? null : $now->copy()->addHours(72),
                'raw_response'                => $isOnline && $isPaid ? json_encode([
                    'id'          => $paymongoPaymentId,
                    'status'      => 'paid',
                    'amount'      => (int) ($amount * 100),
                    'currency'    => 'PHP',
                    'source'      => ['type' => 'gcash'],
                ]) : null,
                'created_at'                  => $now,
                'updated_at'                  => $now,
            ]
        );

        if ($isOnline && $isPaid && $paymongoPaymentId) {
            $eventId = 'evt_' . Str::random(24);

            DB::table('paymongo_webhook_events')->updateOrInsert(
                ['event_id' => $eventId],
                [
                    'type'        => 'payment.paid',
                    'payload'     => json_encode([
                        'data' => [
                            'id'       => $paymongoPaymentId,
                            'type'     => 'payment',
                            'attributes' => [
                                'amount'   => (int) ($amount * 100),
                                'currency' => 'PHP',
                                'status'   => 'paid',
                                'source'   => ['type' => 'gcash'],
                                'paid_at'  => ($paidAt ?? $now)->timestamp,
                            ],
                        ],
                    ]),
                    'received_at'  => $paidAt ?? $now,
                    'processed_at' => ($paidAt ?? $now)->copy()->addSeconds(3),
                    'error'        => null,
                ]
            );
        }
    }
}