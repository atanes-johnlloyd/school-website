<?php

namespace App\Services;

use App\Models\Applicant;
use App\Models\EntranceExam;
use App\Models\EntranceExamResult;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\DB;

class ExamAssignmentService
{
    public const MIN_DAYS_BEFORE_AUTO_ASSIGN = 7;

    public function __construct(protected NotificationService $notifications) {}

    /**
     * Assign a list of applicants to an exam.
     *
     * @param  array  $applicantIds
     * @return array{assigned:int, moved:int, skipped:int, errors:array}
     */
    public function assign(EntranceExam $exam, array $applicantIds): array
    {
        $assigned = 0; $moved = 0; $skipped = 0; $errors = [];

        foreach ($applicantIds as $applicantId) {
            $applicantId = (int) $applicantId;
            if (! $applicantId) continue;

            $existing = EntranceExamResult::where('applicant_id', $applicantId)->first();

            // Skip finalized results
            if ($existing && in_array($existing->result, ['Passed', 'Failed', 'Absent'], true)) {
                $errors[] = "Applicant #{$applicantId} already has a final result.";
                continue;
            }

            if ($existing && $existing->entrance_exam_id === $exam->id) {
                $skipped++;
                continue;
            }

            if ($existing) {
                // Move: new exam must be later than current
                $currentExam = EntranceExam::find($existing->entrance_exam_id);
                if ($currentExam && $exam->starts_at <= $currentExam->starts_at) {
                    $errors[] = "Applicant #{$applicantId}: new exam must be after current exam.";
                    continue;
                }

                $existing->update(['entrance_exam_id' => $exam->id]);
                $moved++;

                $this->notifyMoved($applicantId, $exam, $currentExam);
            } else {
                // Capacity check
                if ($exam->remainingCapacity() <= 0) {
                    $errors[] = "Exam is at full capacity.";
                    break;
                }

                EntranceExamResult::create([
                    'entrance_exam_id' => $exam->id,
                    'applicant_id'     => $applicantId,
                    'result'           => 'Pending',
                ]);
                $assigned++;

                $this->notifyScheduled($applicantId, $exam);
            }
        }

        return compact('assigned', 'moved', 'skipped', 'errors');
    }

    /**
     * Auto-assign approved applicants who don't have an exam yet.
     * Only runs if the exam is >= 7 days away.
     */
    public function autoAssignPending(EntranceExam $exam): int
    {
        if (! $exam->starts_at) return 0;

        if (now()->diffInDays($exam->starts_at, false) < self::MIN_DAYS_BEFORE_AUTO_ASSIGN) {
            return 0;
        }

        $remaining = $exam->remainingCapacity();
        if ($remaining <= 0) return 0;

        $applicants = Applicant::query()
            ->where('status', 'approved')
            ->whereNull('converted_student_id')
            ->whereDoesntHave('entranceExamResults')
            ->when($exam->grade_level !== 'All',
                fn ($q) => $q->where('desired_grade_level', $exam->grade_level))
            ->when($exam->track_id, function ($q) use ($exam) {
                $q->whereHas('strand', fn ($sq) => $sq->where('track_id', $exam->track_id));
            })
            ->orderBy('submitted_at')
            ->limit($remaining)
            ->get();

        $count = 0;
        foreach ($applicants as $applicant) {
            EntranceExamResult::create([
                'entrance_exam_id' => $exam->id,
                'applicant_id'     => $applicant->id,
                'result'           => 'Pending',
            ]);
            $this->notifyScheduled($applicant->id, $exam);
            $count++;
        }

        return $count;
    }

    /**
     * Handle edit: reassign applicants who no longer match + auto-assign new ones.
     */
    public function reassignAfterEdit(EntranceExam $exam, array $originalAttributes): array
    {
        $reassigned = [];
        $cancelled = 0;

        $gradeChanged    = $originalAttributes['grade_level'] !== $exam->grade_level;
        $trackChanged    = $originalAttributes['track_id'] !== $exam->track_id;
        $capacityChanged = $originalAttributes['max_capacity'] !== $exam->max_capacity;

        $toRemove = collect();

        // Grade level / track changed → remove non-matching
        if ($gradeChanged || $trackChanged) {
            foreach ($exam->results()->with('applicant.strand')->get() as $result) {
                $app = $result->applicant;
                if (! $app) continue;

                if ($exam->grade_level !== 'All' && $app->desired_grade_level !== $exam->grade_level) {
                    $toRemove->push($app->id);
                    continue;
                }
                if ($exam->track_id && $app->strand?->track_id !== $exam->track_id) {
                    $toRemove->push($app->id);
                }
            }
        }

        // Capacity reduced → remove overflow (newest first)
        if ($capacityChanged && $exam->max_capacity < $exam->enrolledCount()) {
            $overflow = $exam->enrolledCount() - $exam->max_capacity;
            $ids = $exam->results()->orderByDesc('id')->limit($overflow)->pluck('applicant_id');
            $toRemove = $toRemove->merge($ids);
        }

        $toRemove = $toRemove->unique()->values();

        // Reassign each removed applicant to the next available exam
        foreach ($toRemove as $applicantId) {
            $nextExam = $this->findNextAvailableExam($exam, $applicantId);

            if ($nextExam) {
                EntranceExamResult::where('applicant_id', $applicantId)
                    ->where('entrance_exam_id', $exam->id)
                    ->update(['entrance_exam_id' => $nextExam->id]);

                $this->notifyMoved($applicantId, $nextExam, $exam);
                $reassigned[] = ['applicant_id' => $applicantId, 'to_exam' => $nextExam->id];
            } else {
                EntranceExamResult::where('applicant_id', $applicantId)
                    ->where('entrance_exam_id', $exam->id)
                    ->delete();

                $this->notifyCancelled($applicantId, $exam);
                $cancelled++;
            }
        }

        // Auto-assign new eligible applicants if date >= 7 days
        $autoAssigned = $this->autoAssignPending($exam);

        return [
            'reassigned'    => $reassigned,
            'cancelled'     => $cancelled,
            'auto_assigned' => $autoAssigned,
        ];
    }

    /**
     * Cancel exam: move all applicants to next exam, or cancel them.
     */
    public function cancel(EntranceExam $exam): array
    {
        $nextExam = $this->findNextAvailableExam($exam);
        $moved = 0; $cancelled = 0;

        $results = $exam->results()->with('applicant')->get();

        foreach ($results as $result) {
            if ($nextExam) {
                $result->update(['entrance_exam_id' => $nextExam->id]);
                $this->notifyMoved($result->applicant_id, $nextExam, $exam);
                $moved++;
            } else {
                $result->delete();
                $this->notifyCancelled($result->applicant_id, $exam);
                $cancelled++;
            }
        }

        $exam->update(['status' => 'Cancelled']);

        return ['moved' => $moved, 'cancelled' => $cancelled, 'next_exam_id' => $nextExam?->id];
    }

    // ─── Helpers ────────────────────────────────────

    protected function findNextAvailableExam(EntranceExam $source, ?int $applicantId = null): ?EntranceExam
    {
        $applicant = $applicantId ? Applicant::find($applicantId) : null;
        $grade     = $applicant?->desired_grade_level ?? $source->grade_level;
        $trackId   = $applicant?->strand?->track_id ?? $source->track_id;

        return EntranceExam::query()
            ->where('id', '!=', $source->id)
            ->where('status', '!=', 'Cancelled')
            ->whereDate('exam_date', '>=', $source->exam_date->copy()->addDays(self::MIN_DAYS_BEFORE_AUTO_ASSIGN))
            ->forGrade($grade)
            ->forTrack($trackId)
            ->orderBy('exam_date')
            ->first();
    }

    protected function notifyScheduled(int $applicantId, EntranceExam $exam): void
    {
        $applicant = Applicant::find($applicantId);
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
        $applicant = Applicant::find($applicantId);
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
        $applicant = Applicant::find($applicantId);
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
            'student_name'   => $applicant->full_name,
            'control_number' => $applicant->reference_number,
            'exam_name'      => $exam->exam_name,
            'exam_id'        => $exam->id,
            'school_year'    => $exam->schoolYear?->label ?? '',
            'grade_level'    => $applicant->desired_grade_level,
            'track_name'     => $exam->track?->name ?? 'All',
            'exam_date'      => $exam->exam_date?->format('F d, Y') ?? '',
            'exam_time'      => $exam->exam_time ?? '',
            'venue'          => $exam->venue ?? 'TBA',
            'applicant_count'=> $exam->enrolledCount(),
            'status'         => $exam->computed_status,
            'status_class'   => match ($exam->computed_status) {
                'Ongoing'  => 'status-pending',
                'Completed'=> 'status-approved',
                default    => 'status-under-review',
            },
        ];
    }
}