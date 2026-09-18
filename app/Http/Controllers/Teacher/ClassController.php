<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;

        if (! $teacher) {
            abort(403, 'No teacher profile linked to this account.');
        }

        $activeTerm = Term::where('is_active', true)->first();

        $classes = ClassRoom::with(['subject:id,code,name', 'section:id,name,grade_level', 'term:id,name'])
            ->where('teacher_id', $teacher->id)
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
            ->orderBy('section_id')
            ->get()
            ->map(fn (ClassRoom $k) => [
                'id'             => $k->id,
                'subject'        => $k->subject?->name,
                'subject_code'   => $k->subject?->code,
                'section'        => $k->section?->name,
                'grade_level'    => $k->section?->grade_level,
                'term'           => $k->term?->name,
                'is_published'   => $k->is_published,
            ]);

        return Inertia::render('Teacher/Classes/Index', [
            'classes'    => $classes,
            'activeTerm' => $activeTerm?->name,
        ]);
    }

    public function show(Request $request, ClassRoom $classroom)
    {
        $teacher = $request->user()->teacher;

        // Authorization: teacher must own this class
        if (! $teacher || $classroom->teacher_id !== $teacher->id) {
            abort(403);
        }

        $classroom->load([
            'subject', 'section', 'term',
            'students.user:id,name,email',
        ]);

        return Inertia::render('Teacher/Classes/Show', [
            'classroom' => [
                'id'           => $classroom->id,
                'subject'      => $classroom->subject?->name,
                'subject_code' => $classroom->subject?->code,
                'section'      => $classroom->section?->name,
                'grade_level'  => $classroom->section?->grade_level,
                'term'         => $classroom->term?->name,
                'schedule'     => null, // TODO: from class_schedules
            ],
            'students' => $classroom->students->map(fn ($s) => [
                'id'    => $s->id,
                'name'  => $s->user?->name,
                'lrn'   => $s->lrn,
                'email' => $s->user?->email,
            ]),
        ]);
    }
}