<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RecordExamResultRequest;
use App\Models\EntranceExamResult;
use App\Services\ApplicantConversionService;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\DB;

class EntranceExamResultController extends Controller
{
    public function __construct(
        protected ApplicantConversionService $converter,
        protected NotificationService $notifications,
    ) {}

    public function update(RecordExamResultRequest $request, EntranceExamResult $result)
    {
        if ($result->exam->computed_status !== 'Completed'
            && $result->exam->computed_status !== 'Ongoing') {
            return response()->json([
                'message' => 'Cannot record results for a future exam.',
            ], 422);
        }

        $validated = $request->validated();

        DB::transaction(function () use ($result, $validated, $request) {
            $result->update([
                'score'       => $validated['score'] ?? null,
                'result'      => $validated['result'],
                'remarks'     => $validated['remarks'] ?? null,
                'recorded_by' => $request->user()->id,
                'recorded_at' => now(),
            ]);
        });

        $conversion = null;
        if ($validated['result'] === 'Passed') {
            $conversion = $this->converter->convert($result->applicant);
        }

        $this->sendResultEmail($result->fresh(), $conversion);

        return response()->json([
            'message'    => 'Result saved.' . ($conversion ? ' ' . $conversion['message'] : ''),
            'result'     => $result->fresh(),
            'conversion' => $conversion,
        ]);
    }

    protected function sendResultEmail(EntranceExamResult $result, ?array $conversion): void
    {
        $applicant = $result->applicant;
        if (! $applicant) return;

        $basePlaceholders = [
            'student_name'   => $applicant->full_name,
            'control_number' => $applicant->reference_number,
            'exam_name'      => $result->exam->exam_name,
            'strand'         => $applicant->strand?->name ?? 'N/A',
            'exam_score'     => $result->score ?? 'N/A',
            'passing_score'  => 75,
        ];

        if ($result->result === 'Passed') {
            $section = 'N/A';
            $adviser = 'N/A';

            if (! empty($conversion['section_id'])) {
                $sec = \App\Models\Section::with('adviser.user:id,name')->find($conversion['section_id']);
                if ($sec) {
                    $section = $sec->name;
                    $adviser = $sec->adviser?->user?->name ?? 'N/A';
                }
            }

            $this->notifications->send(
                $applicant->email,
                'Congratulations! You Passed the Exam - Salawag Senior High School',
                'passed-exam',
                array_merge($basePlaceholders, [
                    'grade_level'  => $applicant->desired_grade_level,
                    'section'      => $section,
                    'adviser'      => $adviser,
                    'school_year'  => $result->exam->schoolYear?->label ?? '',
                    'exam_result'  => 'Passed',
                ])
            );
        } elseif ($result->result === 'Failed') {
            $this->notifications->send(
                $applicant->email,
                'Exam Result - Salawag Senior High School',
                'not-pass-exam',
                $basePlaceholders
            );
        } elseif ($result->result === 'Absent') {
            $this->notifications->send(
                $applicant->email,
                'Absent from Exam - Salawag Senior High School',
                'absent-notice',
                $basePlaceholders
            );
        }
    }
}