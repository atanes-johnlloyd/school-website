<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function index(Request $request, ClassRoom $classroom)
    {
        abort_unless($classroom->hasStudent($request->user()), 403);

        $lessons = $classroom->lessons()
            ->published()
            ->orderBy('position')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Lesson $l) => [
                'id'           => $l->id,
                'title'        => $l->title,
                'body_preview' => \Str::limit(strip_tags($l->body), 120),
                'created_at'   => $l->created_at?->toIso8601String(),
            ]);

        $payload = [
            'classroom' => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'section' => $classroom->section?->name,
                'teacher' => $classroom->teacher?->user?->name,
            ],
            'lessons' => $lessons,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Student/Lessons/Index', $payload);
    }

    public function show(Request $request, Lesson $lesson)
    {
        abort_unless($lesson->classroom->hasStudent($request->user()), 403);
        abort_unless($lesson->is_published, 404);

        $payload = [
            'classroom' => [
                'id'      => $lesson->classroom->id,
                'subject' => $lesson->classroom->subject?->name,
                'section' => $lesson->classroom->section?->name,
                'teacher' => $lesson->classroom->teacher?->user?->name,
            ],
            'lesson' => [
                'id'         => $lesson->id,
                'title'      => $lesson->title,
                'body'       => $lesson->body,
                'created_at' => $lesson->created_at?->toIso8601String(),
            ],
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Student/Lessons/Show', $payload);
    }

    public function downloadAttachment(Request $request, \App\Models\LessonAttachment $attachment)
    {
        abort_unless($attachment->lesson->classroom->isTaughtBy($request->user()), 403);
        abort_unless(Storage::exists($attachment->file_path), 404);

        return Storage::download($attachment->file_path, $attachment->file_name);
    }
}