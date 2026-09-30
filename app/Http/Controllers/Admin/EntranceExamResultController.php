<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RecordExamResultRequest;
use App\Models\EntranceExamResult;
use App\Services\ApplicantConversionService;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Http\Request;
class EntranceExamResultController extends Controller
{
    public function __construct(
        protected ApplicantConversionService $converter,
        protected NotificationService $notifications,
    ) {}

    public function index(Request $request): \Inertia\Response
    {
        return \Inertia\Inertia::render('Admin/ExamRecords/Index', [
            'exams' => \App\Models\EntranceExam::select('id', 'exam_name', 'exam_date')
                ->orderByDesc('exam_date')
                ->get(),
            'strands' => \App\Models\Strand::select('id', 'code', 'name')->orderBy('code')->get(),
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

        $query = \App\Models\EntranceExamResult::query()
            ->with([
                'exam:id,exam_name,exam_date,school_year_id',
                'applicant:id,reference_number,first_name,middle_name,last_name,lrn,email,desired_grade_level,strand_id',
                'applicant.strand:id,code,name',
                'recorder:id,name',
            ]);

        if (! empty($validated['exam_id'])) {
            $query->where('entrance_exam_id', $validated['exam_id']);
        }
        if (! empty($validated['result'])) {
            $query->where('result', $validated['result']);
        }
        if (! empty($validated['date_from'])) {
            $query->whereHas('exam', fn ($q) => $q->whereDate('exam_date', '>=', $validated['date_from']));
        }
        if (! empty($validated['date_to'])) {
            $query->whereHas('exam', fn ($q) => $q->whereDate('exam_date', '<=', $validated['date_to']));
        }
        if (! empty($validated['grade_level'])) {
            $query->whereHas('applicant', fn ($q) => $q->where('desired_grade_level', $validated['grade_level']));
        }
        if (! empty($validated['strand_id'])) {
            $query->whereHas('applicant', fn ($q) => $q->where('strand_id', $validated['strand_id']));
        }
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
            'id'             => $r->id,
            'applicant_id'   => $r->applicant_id,
            'reference'      => $r->applicant?->reference_number,
            'name'           => $r->applicant?->full_name,
            'lrn'            => $r->applicant?->lrn,
            'grade_level'    => $r->applicant?->desired_grade_level,
            'strand'         => $r->applicant?->strand?->name,
            'exam_name'      => $r->exam?->exam_name,
            'exam_date'      => $r->exam?->exam_date?->toDateString(),
            'score'          => $r->score,
            'result'         => $r->result,
            'remarks'        => $r->remarks,
            'recorded_at'    => $r->recorded_at?->toIso8601String(),
            'recorder'       => $r->recorder?->name,
        ]);

        // Stats across filtered set (unpaginated)
        $base = \App\Models\EntranceExamResult::query();
        if (! empty($validated['exam_id'])) $base->where('entrance_exam_id', $validated['exam_id']);
        if (! empty($validated['grade_level'])) {
            $base->whereHas('applicant', fn ($q) => $q->where('desired_grade_level', $validated['grade_level']));
        }
        if (! empty($validated['strand_id'])) {
            $base->whereHas('applicant', fn ($q) => $q->where('strand_id', $validated['strand_id']));
        }

        $total    = (clone $base)->count();
        $passed   = (clone $base)->where('result', 'Passed')->count();
        $failed   = (clone $base)->where('result', 'Failed')->count();
        $absent   = (clone $base)->where('result', 'Absent')->count();
        $pending  = (clone $base)->where('result', 'Pending')->count();
        $passRate = ($passed + $failed) > 0
            ? round(($passed / ($passed + $failed)) * 100, 1)
            : 0;

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
            'passing_score'  => \App\Models\SystemSetting::passingGrade(),
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