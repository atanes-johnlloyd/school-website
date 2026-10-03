<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RecordExamResultRequest;
use App\Models\EntranceExam;
use App\Models\EntranceExamResult;
use App\Models\Section;
use App\Models\Strand;
use App\Services\ApplicantConversionService;
use App\Services\Notification\NotificationService;
use App\Support\AuditContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EntranceExamResultController extends Controller
{
    public function __construct(
        protected ApplicantConversionService $converter,
        protected NotificationService $notifications,
    ) {}

    public function index(Request $request): \Inertia\Response
    {
        return Inertia::render('Admin/ExamRecords/Index', [
            'exams' => EntranceExam::select('id', 'exam_name', 'exam_date')
                ->orderByDesc('exam_date')->get(),
            'strands' => Strand::select('id', 'code', 'name')->orderBy('code')->get(),
        ]);
    }

    public function list(Request $request)
    {
        $validated = $request->validate([
            'exam_id'     => ['nullable', 'integer', 'exists:entrance_exams,id'],
            'result'      => ['nullable', 'in:Pending,Passed,Failed,Absent,For Interview'],
            'grade_level' => ['nullable', 'in:11,12'],
            'strand_id'   => ['nullable', 'integer', 'exists:strands,id'],
            'date_from'   => ['nullable', 'date'],
            'date_to'     => ['nullable', 'date'],
            'search'      => ['nullable', 'string', 'max:100'],
            'per_page'    => ['nullable', 'integer', 'min:10', 'max:100'],
            'sort_by'     => ['nullable', 'in:student,exam_date,score,result'],
            'sort_dir'    => ['nullable', 'in:asc,desc'],
        ]);

        $sortBy  = $validated['sort_by']  ?? 'exam_date';
        $sortDir = $validated['sort_dir'] ?? 'desc';

        $query = EntranceExamResult::query()->with([
            'exam:id,exam_name,exam_date,school_year_id',
            'applicant:id,reference_number,first_name,middle_name,last_name,lrn,email,desired_grade_level,strand_id',
            'applicant.strand:id,code,name',
            'recorder:id,name',
        ]);

        if (! empty($validated['exam_id']))     $query->where('entrance_exam_id', $validated['exam_id']);
        if (! empty($validated['result']))      $query->where('result', $validated['result']);
        if (! empty($validated['date_from']))   $query->whereHas('exam', fn ($q) => $q->whereDate('exam_date', '>=', $validated['date_from']));
        if (! empty($validated['date_to']))     $query->whereHas('exam', fn ($q) => $q->whereDate('exam_date', '<=', $validated['date_to']));
        if (! empty($validated['grade_level'])) $query->whereHas('applicant', fn ($q) => $q->where('desired_grade_level', $validated['grade_level']));
        if (! empty($validated['strand_id']))   $query->whereHas('applicant', fn ($q) => $q->where('strand_id', $validated['strand_id']));

        if (! empty($validated['search'])) {
            $s = $validated['search'];
            $query->whereHas('applicant', function ($q) use ($s) {
                $q->where('reference_number', 'like', "%{$s}%")
                  ->orWhere('lrn', 'like', "%{$s}%")
                  ->orWhere('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%");
            });
        }

        switch ($sortBy) {
            case 'student':
                $query->join('applicants', 'applicants.id', '=', 'entrance_exam_results.applicant_id')
                    ->orderBy('applicants.last_name', $sortDir)
                    ->select('entrance_exam_results.*');
                break;
            case 'exam_date':
                $query->join('entrance_exams', 'entrance_exams.id', '=', 'entrance_exam_results.entrance_exam_id')
                    ->orderBy('entrance_exams.exam_date', $sortDir)
                    ->select('entrance_exam_results.*');
                break;
            default:
                $query->orderBy($sortBy, $sortDir);
        }

        $results = $query->paginate($validated['per_page'] ?? 15);

        $results->getCollection()->transform(fn ($r) => [
            'id'           => $r->id,
            'applicant_id' => $r->applicant_id,
            'reference'    => $r->applicant?->reference_number,
            'name'         => $r->applicant?->full_name,
            'lrn'          => $r->applicant?->lrn,
            'grade_level'  => $r->applicant?->desired_grade_level,
            'strand'       => $r->applicant?->strand?->name,
            'exam_name'    => $r->exam?->exam_name,
            'exam_date'    => $r->exam?->exam_date?->toDateString(),
            'score'        => $r->score,
            'result'       => $r->result,
            'remarks'      => $r->remarks,
            'recorded_at'  => $r->recorded_at?->toIso8601String(),
            'recorder'     => $r->recorder?->name,
        ]);

        $base = EntranceExamResult::query();
        if (! empty($validated['exam_id']))     $base->where('entrance_exam_id', $validated['exam_id']);
        if (! empty($validated['grade_level'])) $base->whereHas('applicant', fn ($q) => $q->where('desired_grade_level', $validated['grade_level']));
        if (! empty($validated['strand_id']))   $base->whereHas('applicant', fn ($q) => $q->where('strand_id', $validated['strand_id']));

        $total    = (clone $base)->count();
        $passed   = (clone $base)->where('result', 'Passed')->count();
        $failed   = (clone $base)->where('result', 'Failed')->count();
        $absent   = (clone $base)->where('result', 'Absent')->count();
        $pending  = (clone $base)->where('result', 'Pending')->count();
        $passRate = ($passed + $failed) > 0 ? round(($passed / ($passed + $failed)) * 100, 1) : 0;

        return response()->json([
            'records' => $results,
            'filters' => [
                'exam_id'     => $validated['exam_id']     ?? null,
                'result'      => $validated['result']      ?? null,
                'grade_level' => $validated['grade_level'] ?? null,
                'strand_id'   => $validated['strand_id']   ?? null,
                'date_from'   => $validated['date_from']   ?? null,
                'date_to'     => $validated['date_to']     ?? null,
                'search'      => $validated['search']      ?? null,
            ],
            'sort'   => ['by' => $sortBy, 'dir' => $sortDir],
            'counts' => compact('total', 'passed', 'failed', 'absent', 'pending', 'passRate'),
        ]);
    }

    public function update(RecordExamResultRequest $request, EntranceExamResult $result)
    {
        if (! in_array($result->exam->computed_status, ['Ongoing', 'Completed'], true)) {
            return response()->json(['message' => 'Cannot record results for a future exam.'], 422);
        }

        $validated      = $request->validated();
        $previousResult = $result->result;

        if ($previousResult === 'Passed' && $validated['result'] !== 'Passed') {
            if (empty($validated['override_reason'] ?? null)) {
                return response()->json([
                    'message'           => 'This applicant already passed and was converted to a student. Supply an override reason to change the result.',
                    'requires_override' => true,
                ], 422);
            }

            Log::warning('Exam result reversed', [
                'result_id'    => $result->id,
                'applicant_id' => $result->applicant_id,
                'from'         => 'Passed',
                'to'           => $validated['result'],
                'reason'       => $validated['override_reason'],
                'admin_id'     => $request->user()->id,
            ]);
        }

        AuditContext::wrap('save_exam_result', function () use ($result, $validated, $request) {
            $result->update([
                'score'       => $validated['score'] ?? null,
                'result'      => $validated['result'],
                'remarks'     => $validated['remarks'] ?? null,
                'recorded_by' => $request->user()->id,
                'recorded_at' => now(),
            ]);
        });

        $resultChanged = $previousResult !== $validated['result'];

        $conversion = null;
        if ($validated['result'] === 'Passed' && $previousResult !== 'Passed') {
            try {
                $conversion = AuditContext::wrap('auto_convert_from_exam', function () use ($result) {
                    return $this->converter->convert($result->applicant);
                });

                if (! ($conversion['success'] ?? false) && ($conversion['reason'] ?? null) === 'no_section') {
                    Log::warning('Conversion skipped — no section available', [
                        'applicant_id' => $result->applicant_id,
                        'grade_level'  => $result->applicant?->desired_grade_level,
                        'strand_id'    => $result->applicant?->strand_id,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error('Applicant conversion failed', [
                    'applicant_id' => $result->applicant_id,
                    'error'        => $e->getMessage(),
                ]);
            }
        }

        if ($resultChanged) {
            try {
                $this->sendResultEmail($result->fresh(), $conversion);
            } catch (\Throwable $e) {
                Log::error('Result email failed', [
                    'result_id' => $result->id,
                    'error'     => $e->getMessage(),
                ]);
            }
        }

        $msg = 'Result saved.';
        if ($conversion) {
            $msg .= ' ' . ($conversion['message'] ?? '');
        }

        return response()->json([
            'message'    => trim($msg),
            'result'     => $result->fresh(),
            'conversion' => $conversion,
        ]);
    }

    public function bulkUpdate(Request $request, EntranceExam $exam)
    {
        $validated = $request->validate([
            'results'           => ['required', 'array', 'min:1'],
            'results.*.id'      => ['required', 'integer', 'exists:entrance_exam_results,id'],
            'results.*.score'   => ['nullable', 'numeric', 'min:0', 'max:100'],
            'results.*.result'  => ['required', 'in:Pending,Passed,Failed,Absent,For Interview'],
            'results.*.remarks' => ['nullable', 'string', 'max:500'],
            'override_reason'   => ['nullable', 'string', 'max:500'],
        ]);

        if (! in_array($exam->computed_status, ['Ongoing', 'Completed'], true)) {
            return response()->json(['message' => 'Cannot record results for a future exam.'], 422);
        }

        $updated      = 0;
        $converted    = 0;
        $unassigned   = 0;
        $emailsToSend = [];
        $rowErrors    = [];

        foreach ($validated['results'] as $row) {
            $result = EntranceExamResult::where('id', $row['id'])
                ->where('entrance_exam_id', $exam->id)
                ->first();

            if (! $result) {
                $rowErrors[] = "Record #{$row['id']} is not part of this exam.";
                continue;
            }

            $previousResult = $result->result;

            if ($previousResult === 'Passed' && $row['result'] !== 'Passed') {
                if (empty($validated['override_reason'] ?? null)) {
                    $rowErrors[] = "Row #{$row['id']}: cannot change a Passed result without an override reason.";
                    continue;
                }

                Log::warning('Exam result reversed (bulk)', [
                    'result_id'    => $result->id,
                    'applicant_id' => $result->applicant_id,
                    'from'         => 'Passed',
                    'to'           => $row['result'],
                    'reason'       => $validated['override_reason'],
                    'admin_id'     => $request->user()->id,
                ]);
            }

            AuditContext::wrap('bulk_save_exam_result', function () use ($result, $row, $request) {
                $result->update([
                    'score'       => $row['score'] ?? null,
                    'result'      => $row['result'],
                    'remarks'     => $row['remarks'] ?? null,
                    'recorded_by' => $request->user()->id,
                    'recorded_at' => now(),
                ]);
            });

            $updated++;

            if ($row['result'] === 'Passed' && $previousResult !== 'Passed') {
                try {
                    $conv = AuditContext::wrap('auto_convert_from_exam', function () use ($result) {
                        return $this->converter->convert($result->applicant);
                    });

                    if ($conv['success'] ?? false) {
                        $converted++;
                    } elseif (($conv['reason'] ?? null) === 'no_section') {
                        $unassigned++;
                    }
                } catch (\Throwable $e) {
                    Log::error('Bulk applicant conversion failed', [
                        'applicant_id' => $result->applicant_id,
                        'error'        => $e->getMessage(),
                    ]);
                    $rowErrors[] = "Conversion failed for {$result->applicant?->full_name}.";
                }
            }

            if ($previousResult !== $row['result']) {
                $emailsToSend[] = $result->fresh();
            }
        }

        foreach ($emailsToSend as $r) {
            try {
                $this->sendResultEmail($r, null);
            } catch (\Throwable $e) {
                Log::error('Result email failed', [
                    'result_id' => $r->id,
                    'error'     => $e->getMessage(),
                ]);
            }
        }

        $msg = "Saved {$updated} result" . ($updated === 1 ? '' : 's') . '.';
        if ($converted > 0) {
            $msg .= " {$converted} applicant" . ($converted === 1 ? '' : 's') . ' converted to student' . ($converted === 1 ? '' : 's') . '.';
        }
        if ($unassigned > 0) {
            $msg .= " ⚠ {$unassigned} passer" . ($unassigned === 1 ? '' : 's')
                  . ' could not be placed — no section with capacity. They remain approved; assign sections manually.';
        }
        if (! empty($rowErrors)) {
            $msg .= ' ' . count($rowErrors) . ' issue' . (count($rowErrors) === 1 ? '' : 's') . '.';
        }

        return response()->json([
            'message'    => $msg,
            'updated'    => $updated,
            'converted'  => $converted,
            'unassigned' => $unassigned,
            'errors'     => $rowErrors,
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
            'passing_score'  => \App\Models\SystemSetting::passingGrade(),
        ];

        if ($result->result === 'Passed') {
            $sectionName = null;
            $adviser     = 'N/A';

            if (! empty($conversion['section_id'])) {
                $sec = Section::with('adviser.user:id,name')->find($conversion['section_id']);
                if ($sec) {
                    $sectionName = $sec->name;
                    $adviser     = $sec->adviser?->user?->name ?? 'N/A';
                }
            }

            $placeholders = array_merge($basePlaceholders, [
                'grade_level' => $applicant->desired_grade_level,
                'section'     => $sectionName ?? 'Pending assignment',
                'adviser'     => $adviser,
                'school_year' => $result->exam->schoolYear?->label ?? '',
                'exam_result' => 'Passed',
            ]);

            if (! empty($conversion['temp_password'])) {
                $placeholders['username']          = $applicant->email;
                $placeholders['temp_password']     = $conversion['temp_password'];
                $placeholders['login_link']        = $conversion['login_url'] ?? url('/login');
                $placeholders['credentials_block'] = true;
            } else {
                $placeholders['credentials_block'] = false;
            }

            $this->notifications->send(
                $applicant->email,
                'Congratulations! You Passed the Exam - Salawag Senior High School',
                'passed-exam',
                $placeholders
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