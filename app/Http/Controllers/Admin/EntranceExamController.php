<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignApplicantsRequest;
use App\Http\Requests\Admin\StoreEntranceExamRequest;
use App\Http\Requests\Admin\UpdateEntranceExamRequest;
use App\Models\Applicant;
use App\Models\EntranceExam;
use App\Models\EntranceExamResult;
use App\Models\SchoolYear;
use App\Models\Track;
use App\Services\ExamAssignmentService;
use App\Services\Notification\NotificationService;
use App\Support\AuditContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EntranceExamController extends Controller
{
    public function __construct(
        protected ExamAssignmentService $assigner,
        protected NotificationService $notifications,
    ) {}

    public function index(Request $request)
    {
        return Inertia::render('Admin/EntranceExams/Index', [
            'tracks'      => Track::select('id', 'code', 'name')->orderBy('name')->get(),
            'schoolYears' => SchoolYear::select('id', 'label')->orderByDesc('start_date')->get(),
        ]);
    }

    public function list(Request $request)
    {
        $validated = $request->validate([
            'grade_level'    => ['nullable', 'in:11,12,All'],
            'status'         => ['nullable', 'in:upcoming,ongoing,completed,cancelled'],
            'school_year_id' => ['nullable', 'integer', 'exists:school_years,id'],
            'search'         => ['nullable', 'string', 'max:100'],
            'per_page'       => ['nullable', 'integer', 'min:10', 'max:100'],
            'sort_by'        => ['nullable', 'in:exam_name,exam_date,status,grade_level'],
            'sort_dir'       => ['nullable', 'in:asc,desc'],
        ]);

        $sortBy  = $validated['sort_by']  ?? 'exam_date';
        $sortDir = $validated['sort_dir'] ?? 'desc';

        $query = EntranceExam::query()
            ->with(['track:id,name', 'schoolYear:id,label'])
            ->withCount('results');

        if (! empty($validated['grade_level']) && $validated['grade_level'] !== 'All') {
            $query->forGrade($validated['grade_level']);
        }
        if (! empty($validated['school_year_id'])) {
            $query->where('school_year_id', $validated['school_year_id']);
        }
        if (! empty($validated['search'])) {
            $query->where('exam_name', 'like', '%' . $validated['search'] . '%');
        }

        if ($sortBy === 'status') {
            $query->orderBy('status', $sortDir)->orderBy('exam_date', 'desc');
        } else {
            $query->orderBy($sortBy, $sortDir);
        }

        $all = $query->get()->filter(function (EntranceExam $e) use ($validated) {
            if (empty($validated['status'])) return true;
            return strtolower($e->computed_status) === $validated['status'];
        })->values();

        $perPage = $validated['per_page'] ?? 10;
        $page    = max(1, (int) $request->input('page', 1));
        $total   = $all->count();
        $items   = $all->slice(($page - 1) * $perPage, $perPage)->values();

        $exams = new \Illuminate\Pagination\LengthAwarePaginator(
            $items, $total, $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $exams->getCollection()->transform(fn (EntranceExam $e) => [
            'id'              => $e->id,
            'exam_name'       => $e->exam_name,
            'exam_date'       => $e->exam_date?->toDateString(),
            'exam_time'       => $e->exam_time,
            'venue'           => $e->venue,
            'grade_level'     => $e->grade_level,
            'track'           => $e->track?->name,
            'school_year'     => $e->schoolYear?->label,
            'max_capacity'    => $e->max_capacity,
            'applicant_count' => $e->results_count,
            'remaining'       => $e->remainingCapacity(),
            'status'          => $e->computed_status,
        ]);

        return response()->json([
            'exams'   => $exams,
            'filters' => [
                'grade_level'    => $validated['grade_level']    ?? null,
                'status'         => $validated['status']         ?? null,
                'school_year_id' => $validated['school_year_id'] ?? null,
                'search'         => $validated['search']         ?? null,
            ],
            'sort'   => ['by' => $sortBy, 'dir' => $sortDir],
            'counts' => $this->computeStats(),
        ]);
    }

    public function store(StoreEntranceExamRequest $request)
    {
        $exam = AuditContext::wrap('schedule_exam', function () use ($request) {
            return EntranceExam::create([
                'school_year_id' => $request->validated('school_year_id'),
                'track_id'       => $request->validated('track_id'),
                'exam_name'      => $request->validated('exam_name'),
                'exam_date'      => $request->validated('exam_date'),
                'exam_time'      => strlen($t = $request->validated('exam_time')) === 5 ? $t . ':00' : $t,
                'venue'          => $request->validated('venue'),
                'max_capacity'   => $request->validated('max_capacity') ?? 30,
                'grade_level'    => $request->validated('grade_level'),
                'status'         => 'Upcoming',
            ]);
        });

        $autoAssigned = $this->assigner->autoAssignPending($exam);

        return response()->json([
            'message'       => "Exam created. {$autoAssigned} applicant(s) auto-assigned.",
            'exam'          => $exam,
            'auto_assigned' => $autoAssigned,
        ], 201);
    }

    public function show(Request $request, EntranceExam $exam)
    {
        $exam->load(['track:id,name', 'schoolYear:id,label']);

        $results = $exam->results()
            ->with(['applicant:id,reference_number,first_name,last_name,lrn,email,desired_grade_level,strand_id', 'applicant.strand:id,name'])
            ->orderBy('id')
            ->get()
            ->map(fn (EntranceExamResult $r) => [
                'id'             => $r->id,
                'applicant_id'   => $r->applicant_id,
                'reference'      => $r->applicant?->reference_number,
                'name'           => $r->applicant?->full_name,
                'lrn'            => $r->applicant?->lrn,
                'email'          => $r->applicant?->email,
                'grade_level'    => $r->applicant?->desired_grade_level,
                'strand'         => $r->applicant?->strand?->name,
                'score'          => $r->score,
                'result'         => $r->result,
                'remarks'        => $r->remarks,
                'recorded_at'    => $r->recorded_at?->toIso8601String(),
            ]);

        return response()->json([
            'exam' => [
                'id'              => $exam->id,
                'exam_name'       => $exam->exam_name,
                'exam_date'       => $exam->exam_date?->toDateString(),
                'exam_time'       => $exam->exam_time,
                'venue'           => $exam->venue,
                'grade_level'     => $exam->grade_level,
                'track_id'        => $exam->track_id,
                'track'           => $exam->track?->name,
                'school_year'     => $exam->schoolYear?->label,
                'max_capacity'    => $exam->max_capacity,
                'applicant_count' => $results->count(),
                'remaining'       => $exam->remainingCapacity(),
                'status'          => $exam->computed_status,
            ],
            'applicants'    => $results,
            'passing_grade' => \App\Models\SystemSetting::passingGrade(),
        ]);
    }

    public function update(UpdateEntranceExamRequest $request, EntranceExam $exam)
    {
        if ($exam->computed_status === 'Completed') {
            return response()->json(['message' => 'Cannot edit a completed exam.'], 422);
        }

        $original = [
            'grade_level'  => $exam->grade_level,
            'track_id'     => $exam->track_id,
            'max_capacity' => $exam->max_capacity,
        ];

        AuditContext::wrap('update_exam', function () use ($exam, $request) {
            $exam->update($request->validated());
        });

        $result = $this->assigner->reassignAfterEdit($exam, $original);

        return response()->json([
            'message'      => 'Exam updated.',
            'exam'         => $exam->fresh(),
            'reassignment' => $result,
        ]);
    }

    public function cancel(Request $request, EntranceExam $exam)
    {
        if ($exam->computed_status === 'Completed') {
            return response()->json(['message' => 'Cannot cancel a completed exam.'], 422);
        }
        if ($exam->status === 'Cancelled') {
            return response()->json(['message' => 'Exam is already cancelled.'], 422);
        }

        $result = AuditContext::wrap('cancel_exam', function () use ($exam) {
            return $this->assigner->cancel($exam);
        });

        return response()->json([
            'message' => 'Exam cancelled.',
            'outcome' => $result,
        ]);
    }

    public function destroy(Request $request, EntranceExam $exam)
    {
        if ($exam->results()->exists()) {
            return response()->json([
                'message' => 'Cannot delete: applicants are assigned. Cancel instead.',
            ], 422);
        }

        AuditContext::wrap('delete_exam', function () use ($exam) {
            $exam->delete();
        });

        return response()->json(['message' => 'Exam deleted.']);
    }

    public function eligibleApplicants(Request $request, EntranceExam $exam)
    {
        $applicants = $this->assigner->eligibleApplicantsQuery($exam)
            ->with(['strand:id,name,track_id', 'strand.track:id,name'])
            ->get()
            ->map(fn (Applicant $a) => [
                'id'                 => $a->id,
                'reference_number'   => $a->reference_number,
                'full_name'          => $a->full_name,
                'lrn'                => $a->lrn,
                'email'              => $a->email,
                'desired_grade_level'=> $a->desired_grade_level,
                'strand'             => $a->strand?->name,
                'track'              => $a->strand?->track?->name,
                'current_exam_id'    => $a->currentExamResult?->entrance_exam_id,
            ]);

        return response()->json(['applicants' => $applicants]);
    }

    public function assign(AssignApplicantsRequest $request, EntranceExam $exam)
    {
        if ($exam->computed_status === 'Completed') {
            return response()->json(['message' => 'Cannot assign to a completed exam.'], 422);
        }

        $result = AuditContext::wrap('assign_to_exam', function () use ($exam, $request) {
            return $this->assigner->assign($exam, $request->validated('applicant_ids'));
        }, ['exam_id' => $exam->id]);

        $assignedIds = $result['assigned_ids'] ?? [];
        foreach (Applicant::whereIn('id', $assignedIds)->get() as $a) {
            $this->sendAssignmentEmail($a, $exam);
        }

        $msg = "{$result['assigned']} new, {$result['moved']} moved, {$result['skipped']} skipped.";
        if (! empty($result['errors'])) {
            $msg .= ' Issues: ' . implode(' ', $result['errors']);
        }

        return response()->json([
            'message' => $msg,
            'outcome' => $result,
        ]);
    }

    public function removeApplicant(Request $request, EntranceExam $exam)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer', 'exists:applicants,id'],
            'reason'       => ['nullable', 'string', 'max:500'],
        ]);

        $deleted = AuditContext::wrap('remove_from_exam', function () use ($exam, $validated) {
            return EntranceExamResult::where('entrance_exam_id', $exam->id)
                ->where('applicant_id', $validated['applicant_id'])
                ->delete();
        }, [
            'exam_id'      => $exam->id,
            'applicant_id' => $validated['applicant_id'],
            'reason'       => $validated['reason'] ?? null,
        ]);

        return response()->json([
            'success' => (bool) $deleted,
            'message' => $deleted ? 'Applicant removed.' : 'No matching record.',
        ]);
    }

    protected function computeStats(): array
    {
        $all = EntranceExam::where('status', '!=', 'Cancelled')->get();

        return [
            'total'     => $all->count(),
            'upcoming'  => $all->where('computed_status', 'Upcoming')->count(),
            'ongoing'   => $all->where('computed_status', 'Ongoing')->count(),
            'completed' => $all->where('computed_status', 'Completed')->count(),
            'cancelled' => EntranceExam::where('status', 'Cancelled')->count(),
        ];
    }

    protected function sendAssignmentEmail(Applicant $applicant, EntranceExam $exam): void
    {
        if (! $applicant->email) return;

        try {
            $this->notifications->send(
                $applicant->email,
                'Entrance Exam Scheduled - Salawag Senior High School',
                'exam-scheduled',
                [
                    'student_name'   => $applicant->full_name,
                    'control_number' => $applicant->reference_number,
                    'exam_name'      => $exam->exam_name,
                    'exam_date'      => $exam->exam_date->format('F d, Y'),
                    'exam_time'      => $exam->exam_time,
                    'venue'          => $exam->venue ?: 'TBA',
                    'grade_level'    => $applicant->desired_grade_level,
                ]
            );
        } catch (\Throwable $e) {
            \Log::error('Exam assignment email failed', [
                'applicant_id' => $applicant->id,
                'exam_id'      => $exam->id,
                'error'        => $e->getMessage(),
            ]);
        }
    }
}