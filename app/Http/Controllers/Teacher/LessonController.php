<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreLessonRequest;
use App\Http\Requests\Teacher\UpdateLessonRequest;
use App\Models\ClassRoom;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Term;

class LessonController extends Controller
{
    public function index(Request $request, ClassRoom $classroom)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        $lessons = $classroom->lessons()
            ->orderBy('position')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Lesson $l) => [
                'id'           => $l->id,
                'title'        => $l->title,
                'body_preview' => \Str::limit(strip_tags($l->body), 120),
                'position'     => $l->position,
                'is_published' => $l->is_published,
                'created_at'   => $l->created_at?->toIso8601String(),
            ]);

        $payload = [
            'classroom' => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'section' => $classroom->section?->name,
            ],
            'lessons' => $lessons,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Teacher/Lessons/Index', $payload);
    }

    public function create(Request $request, ClassRoom $classroom)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        return Inertia::render('Teacher/Lessons/Create', [
            'classroom' => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'section' => $classroom->section?->name,
            ],
        ]);
    }

    public function store(StoreLessonRequest $request, ClassRoom $classroom)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        $lesson = $classroom->lessons()->create([
            'title'           => $request->validated('title'),
            'body'            => $request->validated('body'),
            'class_module_id' => $request->validated('class_module_id'),
            'position'        => $request->validated('position') ?? 0,
            'is_published'    => $request->boolean('is_published', false),
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->storeAs(
                    "lesson-attachments/{$classroom->id}/{$lesson->id}",
                    Str::uuid() . '.' . $file->getClientOriginalExtension(),
                    'local'
                );

                $lesson->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return $request->wantsJson()
            ? response()->json(['message' => 'Material created.', 'lesson' => $lesson->load('attachments')], 201)
            : redirect()->route('teacher.classes.lessons.index', $classroom->id)
                        ->with('success', 'Material created.');
    }

    public function show(Request $request, Lesson $lesson)
    {
        abort_unless($lesson->classroom->isTaughtBy($request->user()), 403);

        $payload = [
            'classroom' => [
                'id'      => $lesson->classroom->id,
                'subject' => $lesson->classroom->subject?->name,
                'section' => $lesson->classroom->section?->name,
            ],
            'lesson' => [
                'id'           => $lesson->id,
                'title'        => $lesson->title,
                'body'         => $lesson->body,
                'position'     => $lesson->position,
                'is_published' => $lesson->is_published,
                'created_at'   => $lesson->created_at?->toIso8601String(),
            ],
            'attachments' => $lesson->attachments->map(fn ($a) => [
                'id'        => $a->id,
                'file_name' => $a->file_name,
                'file_size' => $a->file_size,
                'mime_type' => $a->mime_type,
            ]),
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Teacher/Lessons/Show', $payload);
    }

    public function edit(Request $request, Lesson $lesson)
    {
        abort_unless($lesson->classroom->isTaughtBy($request->user()), 403);

        return Inertia::render('Teacher/Lessons/Edit', [
            'classroom' => [
                'id'      => $lesson->classroom->id,
                'subject' => $lesson->classroom->subject?->name,
                'section' => $lesson->classroom->section?->name,
            ],
            'lesson' => [
                'id'           => $lesson->id,
                'title'        => $lesson->title,
                'body'         => $lesson->body,
                'position'     => $lesson->position,
                'is_published' => $lesson->is_published,
            ],
            'attachments' => $lesson->attachments->map(fn ($a) => [
                'id'        => $a->id,
                'file_name' => $a->file_name,
                'file_size' => $a->file_size,
                'mime_type' => $a->mime_type,
            ]),
        ]);
    }

    public function update(UpdateLessonRequest $request, Lesson $lesson)
    {
        abort_unless($lesson->classroom->isTaughtBy($request->user()), 403);

        $lesson->update($request->validated());

        return $request->wantsJson()
            ? response()->json(['message' => 'Material updated.', 'lesson' => $lesson->fresh()])
            : redirect()->route('teacher.lessons.show', $lesson->id)
                        ->with('success', 'Material updated.');
    }

    public function destroy(Request $request, Lesson $lesson)
    {
        abort_unless($lesson->classroom->isTaughtBy($request->user()), 403);

        $lesson->delete();

        return $request->wantsJson()
            ? response()->json(['message' => 'Material deleted.'])
            : redirect()->route('teacher.resources.index')
                        ->with('success', 'Material deleted.');
    }

    public function downloadAttachment(Request $request, \App\Models\LessonAttachment $attachment)
    {
        abort_unless($attachment->lesson->classroom->isTaughtBy($request->user()), 403);
        abort_unless(Storage::exists($attachment->file_path), 404);

        return Storage::download($attachment->file_path, $attachment->file_name);
    }

    /**
     * Cross-class lessons hub — every learning material the teacher owns.
     */
    public function allResources(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        $activeTerm = Term::where('is_active', true)->first();

        $classrooms = ClassRoom::query()
            ->where('teacher_id', $teacher->id)
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
            ->with(['subject:id,code,name', 'section:id,name'])
            ->withCount('lessons')
            ->orderBy('section_id')
            ->get();

        $classroomIds = $classrooms->pluck('id');

        $lessons = Lesson::query()
            ->whereIn('class_id', $classroomIds)
            ->with([
                'classroom:id,subject_id,section_id',
                'classroom.subject:id,name,code',
                'classroom.section:id,name',
                'attachments',
            ])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($l) => [
                'id'                => $l->id,
                'class_id'          => $l->class_id,
                'title'             => $l->title,
                'body_preview'      => \Str::limit(strip_tags($l->body), 140),
                'position'          => $l->position,
                'is_published'      => (bool) $l->is_published,
                'created_at'        => $l->created_at?->toIso8601String(),
                'attachments_count' => $l->attachments->count(),
                'subject'           => $l->classroom?->subject?->name,
                'subject_code'      => $l->classroom?->subject?->code,
                'section'           => $l->classroom?->section?->name,
            ]);

        return Inertia::render('Teacher/Lessons/Index', [
            'lessons' => $lessons,
            'classrooms' => $classrooms->map(fn ($c) => [
                'id'            => $c->id,
                'subject'       => $c->subject?->name,
                'subject_code'  => $c->subject?->code,
                'section'       => $c->section?->name,
                'lessons_count' => $c->lessons_count,
            ]),
            'stats' => [
                'total'       => $lessons->count(),
                'published'   => $lessons->where('is_published', true)->count(),
                'draft'       => $lessons->where('is_published', false)->count(),
                'attachments' => $lessons->sum('attachments_count'),
            ],
            'active_term' => $activeTerm?->name,
        ]);
    }
}