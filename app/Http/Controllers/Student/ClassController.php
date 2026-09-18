<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;

        if (! $student) {
            abort(403, 'No student profile linked to this account.');
        }

        $activeTerm = Term::where('is_active', true)->first();

        $classes = ClassRoom::with(['subject:id,code,name', 'section:id,name,grade_level', 'teacher.user:id,name'])
            ->whereHas('students', fn ($q) => $q->where('students.id', $student->id))
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
            ->orderBy('section_id')
            ->get()
            ->map(fn (ClassRoom $k) => [
                'id'           => $k->id,
                'subject'      => $k->subject?->name,
                'subject_code' => $k->subject?->code,
                'section'      => $k->section?->name,
                'teacher'      => $k->teacher?->user?->name,
                'term'         => $k->term?->name,
            ]);

        return Inertia::render('Student/Classes/Index', [
            'classes'    => $classes,
            'activeTerm' => $activeTerm?->name,
        ]);
    }

    public function show(Request $request, ClassRoom $classroom)
    {
        $student = $request->user()->student;

        // Authorization: student must be enrolled in this class
        $isEnrolled = $classroom->students()->where('students.id', $student?->id)->exists();

        if (! $student || ! $isEnrolled) {
            abort(403);
        }

        $classroom->load(['subject', 'section', 'term', 'teacher.user:id,name']);

        return Inertia::render('Student/Classes/Show', [
            'classroom' => [
                'id'           => $classroom->id,
                'subject'      => $classroom->subject?->name,
                'subject_code' => $classroom->subject?->code,
                'section'      => $classroom->section?->name,
                'teacher'      => $classroom->teacher?->user?->name,
                'term'         => $classroom->term?->name,
            ],
        ]);
    }
}