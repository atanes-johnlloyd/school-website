<?php

namespace App\Services;

use App\Models\Applicant;
use App\Models\EntranceExam;
use App\Models\EntranceExamResult;
use App\Services\Notification\NotificationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamAssignmentService
{
    public const MIN_DAYS_BEFORE_AUTO_ASSIGN = 7;

    public function __construct(protected NotificationService $notifications) {}

    /* ═══════════════════════════════════════════════════════════
       PUBLIC API
       ═══════════════════════════════════════════════════════════ */

    /**
     * Assign a list of applicants to an exam (from the picker).
     */
    public function assign(EntranceExam $exam, array $applicantIds): array
    {
        if ($exam->status === 'Cancelled') {
            return [
                'assigned' => 0, 'moved' => 0, 'skipped' => 0,
                'errors' => ['Cannot assign to a cancelled exam.'],
                'assigned_ids' => [],
            ];
        }

        $assigned = 0; $moved = 0; $skipped = 0;
        $errors = [];
        $assignedIds = [];

        DB::transaction(function () use (
            $exam, $applicantIds, &$assigned, &$moved, &$skipped, &$errors, &$assignedIds
        ) {
            foreach ($applicantIds as $applicantId) {
                $applicantId = (int) $applicantId;
                if (! $applicantId) continue;

                $applicant = Applicant::with('strand:id,track_id')->find($applicantId);
                if (! $applicant) {
                    $skipped++;
                    $errors[] = "Applicant #{$applicantId} not found.";
                    continue;
                }

                if ($applicant->status !== 'approved') {
                    $skipped++;
                    $errors[] = "{$applicant->full_name} is not approved.";
                    continue;
                }

                if ($applicant->converted_student_id) {
                    $skipped++;
                    $errors[] = "{$applicant->full_name} is already a student.";
                    continue;
                }

                $existing = EntranceExamResult::where('applicant_id', $applicantId)->first();

                // Terminal result → cannot reassign
                if ($existing && in_array($existing->result, ['Passed', 'Failed', 'Absent'], true)) {
                    $skipped++;
                    $errors[] = "{$applicant->full_name} already has a final result.";
                    continue;
                }

                // Already on this exam → nothing to do
                if ($existing && (int) $existing->entrance_exam_id === (int) $exam->id) {
                    $skipped++;
                    continue;
                }

                // Move from another exam
                if ($existing) {
                    $currentExam = EntranceExam::find($existing->entrance_exam_id);

                    // Only allow move if new exam is chronologically later
                    if ($currentExam && $this->examStartsAt($exam) && $this->examStartsAt($currentExam)
                        && $this->examStartsAt($exam)->lt($this->examStartsAt($currentExam))) {
                        $skipped++;
                        $errors[] = "{$applicant->full_name}: new exam must be after current exam.";
                        continue;
                    }

                    $existing->update(['entrance_exam_id' => $exam->id]);
                    $moved++;
                    $assignedIds[] = $applicantId;

                    $this->safeNotify(fn () => $this->notifyMoved($applicantId, $exam, $currentExam));
                    continue;
                }

                // Fresh assignment — capacity check
                if ($exam->remainingCapacity() <= 0) {
                    $skipped++;
                    $errors[] = "{$applicant->full_name} skipped — exam is at full capacity.";
                    continue;
                }

                EntranceExamResult::create([
                    'entrance_exam_id' => $exam->id,
                    'applicant_id'     => $applicantId,
                    'result'           => 'Pending',
                ]);
                $assigned++;
                $assignedIds[] = $applicantId;

                $this->safeNotify(fn () => $this->notifyScheduled($applicantId, $exam));
            }
        });

        return [
            'assigned'     => $assigned,
            'moved'        => $moved,
            'skipped'      => $skipped,
            'errors'       => $errors,
            'assigned_ids' => $assignedIds,
        ];
    }

    /**
     * Auto-assign eligible approved applicants to an exam.
     *
     * Excludes only TERMINAL results. Applicants pending on another exam
     * are MOVED here (not skipped, not duplicated).
     *
     * Only runs if the exam is at least MIN_DAYS_BEFORE_AUTO_ASSIGN days away.
     */
    public function autoAssignPending(EntranceExam $exam): int
    {
        if ($exam->status === 'Cancelled') {
            return 0;
        }

        $startsAt = $this->examStartsAt($exam);
        if (! $startsAt) {
            return 0;
        }

        if (now()->diffInDays($startsAt, false) < self::MIN_DAYS_BEFORE_AUTO_ASSIGN) {
            return 0;
        }

        $remaining = $exam->remainingCapacity();
        if ($remaining <= 0) {
            return 0;
        }

        // Applicants already on this exam → skip
        $alreadyOnExam = EntranceExamResult::where('entrance_exam_id', $exam->id)
            ->pluck('applicant_id')
            ->all();

        $applicants = $this->eligibleApplicantsQuery($exam)
            ->whereNotIn('id', $alreadyOnExam)
            ->orderBy('submitted_at')
            ->limit($remaining)
            ->get();

        if ($applicants->isEmpty()) {
            return 0;
        }

        $count = 0;

        foreach ($applicants as $applicant) {
            DB::transaction(function () use ($exam, $applicant, &$count) {
                // Move if pending on another exam
                $existing = EntranceExamResult::where('applicant_id', $applicant->id)
                    ->where('entrance_exam_id', '!=', $exam->id)
                    ->first();

                if ($existing) {
                    $oldExam = EntranceExam::find($existing->entrance_exam_id);
                    $existing->update(['entrance_exam_id' => $exam->id]);
                    $this->safeNotify(fn () => $this->notifyMoved($applicant->id, $exam, $oldExam));
                } else {
                    EntranceExamResult::create([
                        'entrance_exam_id' => $exam->id,
                        'applicant_id'     => $applicant->id,
                        'result'           => 'Pending',
                    ]);
                    $this->safeNotify(fn () => $this->notifyScheduled($applicant->id, $exam));
                }

                $count++;
            });
        }

        return $count;
    }

    /**
     * Reconcile assignments after the exam's grade/track/capacity changed.
     */
    public function reassignAfterEdit(EntranceExam $exam, array $originalAttributes): array
    {
        $reassigned = [];
        $cancelled = 0;

        $gradeChanged    = ($originalAttributes['grade_level'] ?? null) !== $exam->grade_level;
        $trackChanged    = ($originalAttributes['track_id']    ?? null) !== $exam->track_id;
        $capacityChanged = ($originalAttributes['max_capacity']?? null) !== $exam->max_capacity;

        $toRemove = collect();

        // Grade / track scope changed → drop non-matching
        if ($gradeChanged || $trackChanged) {
            foreach ($exam->results()->with('applicant.strand:id,track_id')->get() as $result) {
                $app = $result->applicant;
                if (! $app) continue;

                if ($exam->grade_level !== 'All'
                    && (string) $app->desired_grade_level !== (string) $exam->grade_level) {
                    $toRemove->push($app->id);
                    continue;
                }
                if ($exam->track_id
                    && (int) optional($app->strand)->track_id !== (int) $exam->track_id) {
                    $toRemove->push($app->id);
                }
            }
        }

        // Capacity reduced → drop overflow (newest first)
        if ($capacityChanged && $exam->max_capacity !== null
            && $exam->enrolledCount() > $exam->max_capacity) {
            $overflow = $exam->enrolledCount() - $exam->max_capacity;
            $ids = $exam->results()->orderByDesc('id')->limit($overflow)->pluck('applicant_id');
            $toRemove = $toRemove->merge($ids);
        }

        $toRemove = $toRemove->unique()->values();

        foreach ($toRemove as $applicantId) {
            $nextExam = $this->findNextAvailableExam($exam, $applicantId);

            if ($nextExam) {
                EntranceExamResult::where('applicant_id', $applicantId)
                    ->where('entrance_exam_id', $exam->id)
                    ->update(['entrance_exam_id' => $nextExam->id]);

                $this->safeNotify(fn () => $this->notifyMoved($applicantId, $nextExam, $exam));
                $reassigned[] = ['applicant_id' => $applicantId, 'to_exam' => $nextExam->id];
            } else {
                EntranceExamResult::where('applicant_id', $applicantId)
                    ->where('entrance_exam_id', $exam->id)
                    ->delete();

                $this->safeNotify(fn () => $this->notifyCancelled($applicantId, $exam));
                $cancelled++;
            }
        }

        $autoAssigned = $this->autoAssignPending($exam);

        return [
            'reassigned'    => $reassigned,
            'cancelled'     => $cancelled,
            'auto_assigned' => $autoAssigned,
        ];
    }

    /**
     * Cancel exam: move everyone to the next available exam, or drop them.
     */
    public function cancel(EntranceExam $exam): array
    {
        $nextExam = $this->findNextAvailableExam($exam);
        $moved = 0; $cancelled = 0;

        $results = $exam->results()->with('applicant')->get();

        foreach ($results as $result) {
            if ($nextExam) {
                $result->update(['entrance_exam_id' => $nextExam->id]);
                $this->safeNotify(fn () => $this->notifyMoved($result->applicant_id, $nextExam, $exam));
                $moved++;
            } else {
                $result->delete();
                $this->safeNotify(fn () => $this->notifyCancelled($result->applicant_id, $exam));
                $cancelled++;
            }
        }

        $exam->update(['status' => 'Cancelled']);

        return ['moved' => $moved, 'cancelled' => $cancelled, 'next_exam_id' => $nextExam?->id];
    }

    /* ═══════════════════════════════════════════════════════════
       CANONICAL ELIGIBILITY QUERY
       Used by auto-assign AND the picker. Never diverge.
       ═══════════════════════════════════════════════════════════ */

    /**
     * Applicants who can be placed on this exam:
     *   - approved, not yet converted to a student
     *   - no terminal result (Passed / Failed / Absent) anywhere
     *   - matching grade level (skipped if exam is 'All')
     *   - matching track (skipped if exam has no track)
     *
     * Applicants pending on another exam ARE included — the caller decides
     * whether to move or skip them.
     */
    public function eligibleApplicantsQuery(EntranceExam $exam): Builder
    {
        return Applicant::query()
            ->where('status', 'approved')
            ->whereNull('converted_student_id')

            // Only terminal results disqualify
            ->whereDoesntHave('entranceExamResults', function ($q) {
                $q->whereIn('result', ['Passed', 'Failed', 'Absent']);
            })

            // 'All' means no grade filter
            ->when($exam->grade_level !== 'All', function ($q) use ($exam) {
                $q->where('desired_grade_level', (string) $exam->grade_level);
            })

            // null track means no track filter
            ->when(! is_null($exam->track_id), function ($q) use ($exam) {
                $q->whereHas('strand', fn ($s) => $s->where('track_id', $exam->track_id));
            });
    }

    /* ═══════════════════════════════════════════════════════════
       HELPERS
       ═══════════════════════════════════════════════════════════ */

    /**
     * Combine exam_date + exam_time into a Carbon instance.
     * Returns null if the exam has no date.
     */
    protected function examStartsAt(EntranceExam $exam): ?\Carbon\Carbon
    {
        if (! $exam->exam_date) {
            return null;
        }

        $startsAt = $exam->exam_date instanceof \Carbon\Carbon
            ? $exam->exam_date->copy()
            : \Carbon\Carbon::parse($exam->exam_date);

        if ($exam->exam_time) {
            $time = (string) $exam->exam_time;
            // Handles both "17:46:00" and "17:46"
            [$h, $m] = array_pad(explode(':', $time), 2, '0');
            $startsAt->setTime((int) $h, (int) $m, 0);
        } else {
            $startsAt->setTime(8, 0, 0);
        }

        return $startsAt;
    }

    /**
     * Find the earliest upcoming exam that would accept this applicant.
     */
    protected function findNextAvailableExam(EntranceExam $source, ?int $applicantId = null): ?EntranceExam
    {
        $applicant = $applicantId ? Applicant::with('strand:id,track_id')->find($applicantId) : null;

        $grade   = $applicant?->desired_grade_level ?? $source->grade_level;
        $trackId = optional($applicant?->strand)->track_id ?? $source->track_id;

        $startsAt = $this->examStartsAt($source);
        $cutoff   = $startsAt
            ? $startsAt->copy()->addDays(self::MIN_DAYS_BEFORE_AUTO_ASSIGN)
            : now()->addDays(self::MIN_DAYS_BEFORE_AUTO_ASSIGN);

        return EntranceExam::query()
            ->where('id', '!=', $source->id)
            ->where('status', '!=', 'Cancelled')
            ->whereDate('exam_date', '>=', $cutoff->toDateString())

            // Exam must accept this grade
            ->when($grade !== 'All', function ($q) use ($grade) {
                $q->where(function ($inner) use ($grade) {
                    $inner->where('grade_level', 'All')
                          ->orWhere('grade_level', (string) $grade);
                });
            })

            // Exam must accept this track (exam track = null means all tracks)
            ->when($trackId, function ($q) use ($trackId) {
                $q->where(function ($inner) use ($trackId) {
                    $inner->whereNull('track_id')
                          ->orWhere('track_id', $trackId);
                });
            })

            ->orderBy('exam_date')
            ->orderBy('exam_time')
            ->first();
    }

    /**
     * Run a notification callback without letting failures break the caller.
     */
    protected function safeNotify(callable $fn): void
    {
        try {
            $fn();
        } catch (\Throwable $e) {
            Log::warning('Exam assignment notification failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /* ═══════════════════════════════════════════════════════════
       NOTIFICATIONS
       ═══════════════════════════════════════════════════════════ */

    protected function notifyScheduled(int $applicantId, EntranceExam $exam): void
    {
        $applicant = Applicant::with('strand:id,name')->find($applicantId);
        if (! $applicant) return;

        $this->notifications->send(
            $applicant->email,
            'Entrance Exam Schedule - Salawag Senior High School',
            'exam-details',
            $this->buildExamPlaceholders($applicant, $exam)
        );
    }

    protected function notifyMoved(int $applicantId, EntranceExam $newExam, ?EntranceExam $oldExam): void
    {
        $applicant = Applicant::with('strand:id,name')->find($applicantId);
        if (! $applicant) return;

        $placeholders = $this->buildExamPlaceholders($applicant, $newExam);
        $placeholders['old_exam_name'] = $oldExam?->exam_name ?? '';
        $placeholders['old_exam_date'] = $oldExam?->exam_date?->format('F d, Y') ?? '';
        $placeholders['old_exam_time'] = $oldExam?->exam_time ?? '';

        $this->notifications->send(
            $applicant->email,
            'Exam Schedule Updated - Salawag Senior High School',
            'exam-moved',
            $placeholders
        );
    }

    protected function notifyCancelled(int $applicantId, EntranceExam $exam): void
    {
        $applicant = Applicant::with('strand:id,name')->find($applicantId);
        if (! $applicant) return;

        $this->notifications->send(
            $applicant->email,
            'Exam Cancelled - Salawag Senior High School',
            'exam-cancelled',
            [
                'student_name'   => $applicant->full_name,
                'control_number' => $applicant->reference_number,
                'exam_name'      => $exam->exam_name,
                'strand'         => $applicant->strand?->name ?? 'N/A',
                'school_year'    => $exam->schoolYear?->label ?? '',
            ]
        );
    }

    protected function buildExamPlaceholders(Applicant $applicant, EntranceExam $exam): array
    {
        return [
            'student_name'    => $applicant->full_name,
            'control_number'  => $applicant->reference_number,
            'exam_name'       => $exam->exam_name,
            'exam_id'         => $exam->id,
            'school_year'     => $exam->schoolYear?->label ?? '',
            'grade_level'     => $applicant->desired_grade_level,
            'track_name'      => $exam->track?->name ?? 'All',
            'exam_date'       => $exam->exam_date?->format('F d, Y') ?? '',
            'exam_time'       => $exam->exam_time ?? '',
            'venue'           => $exam->venue ?? 'TBA',
            'applicant_count' => $exam->enrolledCount(),
            'status'          => $exam->computed_status,
            'status_class'    => match ($exam->computed_status) {
                'Ongoing'   => 'status-pending',
                'Completed' => 'status-approved',
                default     => 'status-under-review',
            },
        ];
    }
}