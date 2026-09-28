<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Term;
use App\Services\GradeCalculator;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GradeController extends Controller
{
    public function __construct(protected GradeCalculator $calculator) {}

    /**
     * Report card — all classes for the active term, with grades.
     */
    public function index(Request $request)
    {
        $student = $request->user()->student;
        abort_unless($student, 403);

        $activeTerm = Term::where('is_active', true)->first();

        $classrooms = $student->classes()
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
            ->with(['subject:id,code,name', 'section:id,name', 'teacher.user:id,name'])
            ->get();

        $rows = $classrooms->map(function (ClassRoom $classroom) use ($student) {
            $computed = $this->calculator->compute($student, $classroom);

            return [
                'class_id'         => $classroom->id,
                'subject'          => $classroom->subject?->name,
                'subject_code'     => $classroom->subject?->code,
                'section'          => $classroom->section?->name,
                'teacher'          => $classroom->teacher?->user?->name,
                'written_work'     => $computed['written_work'],
                'performance_task' => $computed['performance_task'],
                'quarterly_exam'   => $computed['quarterly_exam'],
                'final_grade'      => $computed['final_grade'],
                'remarks'          => $computed['remarks'],
                'is_complete'      => $computed['is_complete'],
            ];
        });

        $payload = [
            'active_term' => $activeTerm?->name,
            'classes'     => $rows,
            'general_average' => $rows->whereNotNull('final_grade')->avg('final_grade'),
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Student/Grades/Index', $payload);
    }

    /**
     * Detail for one class — category breakdown + per-assignment scores.
     */
    public function show(Request $request, ClassRoom $classroom)
    {
        $student = $request->user()->student;
        abort_unless($student, 403);
        abort_unless($classroom->hasStudent($request->user()), 403);

        $computed = $this->calculator->compute($student, $classroom);

        $assignments = $classroom->assignments()
            ->where('is_published', true)
            ->with(['submissions' => fn ($q) => $q->where('student_id', $student->id)])
            ->orderBy('category')
            ->orderBy('due_at')
            ->get()
            ->map(function ($a) {
                $sub = $a->submissions->first();
                return [
                    'id'         => $a->id,
                    'title'      => $a->title,
                    'category'   => $a->category,
                    'points'     => $a->points,
                    'due_at'     => $a->due_at?->toIso8601String(),
                    'grade'      => $sub?->grade,
                    'graded_at'  => $sub?->graded_at?->toIso8601String(),
                ];
            });

        $payload = [
            'classroom' => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'section' => $classroom->section?->name,
                'teacher' => $classroom->teacher?->user?->name,
            ],
            'grades' => [
                'written_work'     => $computed['written_work'],
                'performance_task' => $computed['performance_task'],
                'quarterly_exam'   => $computed['quarterly_exam'],
                'final_grade'      => $computed['final_grade'],
                'remarks'          => $computed['remarks'],
                'is_complete'      => $computed['is_complete'],
            ],
            'weights'     => $computed['weights'],
            'assignments' => $assignments,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Student/Grades/Show', $payload);
    }
}