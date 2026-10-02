<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Services\GradeCalculator;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GradebookController extends Controller
{
    public function __construct(protected GradeCalculator $calculator) {}
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        $activeTerm = \App\Models\Term::where('is_active', true)->first();

        $classrooms = \App\Models\ClassRoom::query()
            ->where('teacher_id', $teacher->id)
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
            ->with(['subject:id,code,name', 'section:id,name', 'term:id,name'])
            ->orderBy('section_id')
            ->get();

        if ($classrooms->isEmpty()) {
            return Inertia::render('Teacher/Gradebook/Index', [
                'classrooms' => [],
                'classroom'  => null,
                'weights'    => ['written_work' => 0, 'performance_task' => 0, 'quarterly_exam' => 0],
                'assignments'=> [],
                'students'   => [],
                'summary'    => ['total_students' => 0, 'passing' => 0, 'failing' => 0, 'class_average' => null],
                'activeTerm' => $activeTerm?->name,
            ]);
        }

        $selectedId = (int) $request->input('class_id', $classrooms->first()->id);
        $classroom = $classrooms->firstWhere('id', $selectedId) ?? $classrooms->first();

        $assignments = $classroom->assignments()
            ->where('is_published', true)
            ->orderBy('category')
            ->orderBy('due_at')
            ->get(['id', 'title', 'category', 'points']);

        $students = $classroom->students()->with('user:id,name')->orderBy('lrn')->get();

        $studentRows = $students->map(function ($student) use ($classroom) {
            $computed = $this->calculator->compute($student, $classroom);
            return [
                'id'               => $student->id,
                'name'             => $student->user?->name,
                'lrn'              => $student->lrn,
                'grades'           => $computed['grades'],
                'written_work'     => $computed['written_work'],
                'performance_task' => $computed['performance_task'],
                'quarterly_exam'   => $computed['quarterly_exam'],
                'final_grade'      => $computed['final_grade'],
                'remarks'          => $computed['remarks'],
                'is_complete'      => $computed['is_complete'],
            ];
        });

        return Inertia::render('Teacher/Gradebook/Index', [
            'classrooms' => $classrooms->map(fn ($c) => [
                'id'      => $c->id,
                'subject' => $c->subject?->name,
                'code'    => $c->subject?->code,
                'section' => $c->section?->name,
            ]),
            'classroom' => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'code'    => $classroom->subject?->code,
                'section' => $classroom->section?->name,
                'term'    => $classroom->term?->name,
            ],
            'weights' => [
                'written_work'     => (float) $classroom->weight_written_work,
                'performance_task' => (float) $classroom->weight_performance_task,
                'quarterly_exam'   => (float) $classroom->weight_quarterly_exam,
            ],
            'assignments' => $assignments->map(fn ($a) => [
                'id'       => $a->id,
                'title'    => $a->title,
                'category' => $a->category,
                'points'   => (float) $a->points,
            ]),
            'students' => $studentRows,
            'summary' => [
                'total_students' => $studentRows->count(),
                'passing'        => $studentRows->where('remarks', '!=', 'did_not_meet_expectations')->whereNotNull('final_grade')->count(),
                'failing'        => $studentRows->where('remarks', 'did_not_meet_expectations')->count(),
                'class_average'  => $studentRows->whereNotNull('final_grade')->avg('final_grade'),
            ],
            'activeTerm' => $activeTerm?->name,
        ]);
    }
    public function show(Request $request, ClassRoom $classroom)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        $assignments = $classroom->assignments()
            ->where('is_published', true)
            ->orderBy('category')
            ->orderBy('due_at')
            ->get(['id', 'title', 'category', 'points']);

        $students = $classroom->students()
            ->with('user:id,name')
            ->orderBy('lrn')
            ->get();

        $studentRows = $students->map(function ($student) use ($classroom) {
            $computed = $this->calculator->compute($student, $classroom);

            return [
                'id'                 => $student->id,
                'name'               => $student->user?->name,
                'lrn'                => $student->lrn,
                'grades'             => $computed['grades'],   // assignment_id => { grade, points }
                'written_work'       => $computed['written_work'],
                'performance_task'   => $computed['performance_task'],
                'quarterly_exam'     => $computed['quarterly_exam'],
                'final_grade'        => $computed['final_grade'],
                'remarks'            => $computed['remarks'],
                'is_complete'        => $computed['is_complete'],
            ];
        });

        $payload = [
            'classroom' => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'code'    => $classroom->subject?->code,
                'section' => $classroom->section?->name,
                'term'    => $classroom->term?->name,
            ],
            'weights' => [
                'written_work'     => (float) $classroom->weight_written_work,
                'performance_task' => (float) $classroom->weight_performance_task,
                'quarterly_exam'   => (float) $classroom->weight_quarterly_exam,
            ],
            'assignments' => $assignments->map(fn ($a) => [
                'id'       => $a->id,
                'title'    => $a->title,
                'category' => $a->category,
                'points'   => $a->points,
            ]),
            'students' => $studentRows,
            'summary' => [
                'total_students' => $studentRows->count(),
                'passing'        => $studentRows->where('remarks', '!=', 'did_not_meet_expectations')->whereNotNull('final_grade')->count(),
                'failing'        => $studentRows->where('remarks', 'did_not_meet_expectations')->count(),
                'class_average'  => $studentRows->whereNotNull('final_grade')->avg('final_grade'),
            ],
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Teacher/Gradebook/Show', $payload);
    }
}