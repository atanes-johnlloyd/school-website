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