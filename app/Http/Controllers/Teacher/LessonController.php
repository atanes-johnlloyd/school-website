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

        $classroomId = $lesson->class_id;
        $lesson->delete();

        return $request->wantsJson()
            ? response()->json(['message' => 'Material deleted.'])
            : redirect()->route('teacher.classes.lessons.index', $classroomId)
                        ->with('success', 'Material deleted.');
    }

    public function downloadAttachment(Request $request, \App\Models\LessonAttachment $attachment)
    {
        abort_unless($attachment->lesson->classroom->isTaughtBy($request->user()), 403);
        abort_unless(Storage::exists($attachment->file_path), 404);

        return Storage::download($attachment->file_path, $attachment->file_name);
    }
}