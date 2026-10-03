<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreAnnouncementRequest;
use App\Http\Requests\Teacher\UpdateAnnouncementRequest;
use App\Models\Announcement;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    public function index(Request $request, ClassRoom $classroom)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        $announcements = $classroom->announcements()
            ->with('author:id,name')
            ->ordered()
            ->get()
            ->map(fn (Announcement $a) => [
                'id'            => $a->id,
                'title'         => $a->title,
                'body'          => $a->body,
                'body_preview'  => \Str::limit(strip_tags($a->body), 140),
                'is_pinned'     => (bool) $a->is_pinned,
                'is_published'  => $a->published_at !== null,
                'published_at'  => $a->published_at?->toIso8601String(),
                'published_human' => $a->published_at?->diffForHumans(),
                'expires_at'    => $a->expires_at?->toIso8601String(),
                'created_at'    => $a->created_at?->toIso8601String(),
                'image_url'     => $a->image_url,
                'author'        => $a->author?->name,
            ]);

        $payload = [
            'classroom' => [
                'id'           => $classroom->id,
                'subject'      => $classroom->subject?->name,
                'subject_code' => $classroom->subject?->code,
                'section'      => $classroom->section?->name,
            ],
            'announcements' => $announcements,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Teacher/Announcements/Show', $payload);
    }

    public function store(StoreAnnouncementRequest $request, ClassRoom $classroom)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        // Optional image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => ['image', 'max:5120', 'mimes:jpg,jpeg,png,webp'],
            ]);
            $imagePath = app(\App\Services\ImageUploadService::class)->store(
                $request->file('image'),
                'announcements/' . $classroom->id,
                1200
            );
        }

        $isPublished = $request->boolean('is_published', false);

        $announcement = $classroom->announcements()->create([
            'created_by'   => $request->user()->id,
            'title'        => $request->validated('title'),
            'body'         => $request->validated('body'),
            'image_path'   => $imagePath,
            'is_pinned'    => $request->boolean('is_pinned', false),
            'published_at' => $isPublished ? now() : null,
            'expires_at'   => $request->validated('expires_at'),
        ]);

        return response()->json([
            'message'      => $isPublished ? 'Announcement published.' : 'Draft saved.',
            'announcement' => $announcement,
        ], 201);
    }

    public function show(Request $request, Announcement $announcement)
    {
        abort_unless($announcement->classroom->isTaughtBy($request->user()), 403);

        $payload = [
            'announcement' => [
                'id'              => $announcement->id,
                'title'           => $announcement->title,
                'body'            => $announcement->body,           // full body, not preview
                'is_pinned'       => (bool) $announcement->is_pinned,
                'is_published'    => $announcement->published_at !== null,
                'published_at'    => $announcement->published_at?->toIso8601String(),
                'published_human' => $announcement->published_at?->diffForHumans(),
                'expires_at'      => $announcement->expires_at?->toIso8601String(),
                'created_at'      => $announcement->created_at?->toIso8601String(),
                'image_url'       => $announcement->image_url,
                'author'          => $announcement->author?->name,
            ],
            'classroom' => [
                'id'           => $announcement->classroom->id,
                'subject'      => $announcement->classroom->subject?->name,
                'subject_code' => $announcement->classroom->subject?->code,
                'section'      => $announcement->classroom->section?->name,
            ],
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Teacher/Announcements/Show', $payload);
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        abort_unless($announcement->classroom->isTaughtBy($request->user()), 403);

        $data = $request->validated();

        // Handle publish toggle explicitly
        if ($request->has('is_published')) {
            $isPublished = $request->boolean('is_published');
            $data['published_at'] = $isPublished
                ? ($announcement->published_at ?? now())
                : null;
            unset($data['is_published']);
        }

        $announcement->update($data);

        return response()->json([
            'message'      => 'Announcement updated.',
            'announcement' => $announcement->fresh(),
        ]);
    }

    public function togglePin(Request $request, Announcement $announcement)
    {
        abort_unless($announcement->classroom->isTaughtBy($request->user()), 403);

        $announcement->update(['is_pinned' => ! $announcement->is_pinned]);

        return response()->json([
            'message'   => $announcement->is_pinned ? 'Pinned.' : 'Unpinned.',
            'is_pinned' => $announcement->is_pinned,
        ]);
    }

    public function destroy(Request $request, Announcement $announcement)
    {
        abort_unless($announcement->classroom->isTaughtBy($request->user()), 403);

        $announcement->delete();

        return response()->json(['message' => 'Announcement deleted.']);
    }

    public function allAnnouncements(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        $activeTerm = \App\Models\Term::where('is_active', true)->first();

        $classroomIds = ClassRoom::where('teacher_id', $teacher->id)
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
            ->pluck('id');

        $announcements = Announcement::query()
            ->whereIn('class_id', $classroomIds)
            ->with(['classroom:id,subject_id,section_id',
                    'classroom.subject:id,code,name',
                    'classroom.section:id,name',
                    'author:id,name'])
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Announcement $a) => [
                'id'            => $a->id,
                'title'         => $a->title,
                'body_preview'  => \Str::limit(strip_tags($a->body), 140),
                'is_pinned'     => (bool) $a->is_pinned,
                'is_published'  => $a->published_at !== null,
                'published_at'  => $a->published_at?->toIso8601String(),
                'published_human' => $a->published_at?->diffForHumans(),
                'classroom_id'  => $a->class_id,
                'subject'       => $a->classroom?->subject?->name,
                'section'       => $a->classroom?->section?->name,
                'author'        => $a->author?->name,
                'image_url'     => $a->image_url,
            ]);

        $classrooms = ClassRoom::whereIn('id', $classroomIds)
            ->with(['subject:id,name,code','section:id,name'])
            ->withCount('announcements')
            ->get()
            ->map(fn ($c) => [
                'id'                => $c->id,
                'subject'           => $c->subject?->name,
                'subject_code'      => $c->subject?->code,
                'section'           => $c->section?->name,
                'announcements_count' => (int) $c->announcements_count,
            ]);

        return Inertia::render('Teacher/Announcements/Index', [
            'announcements' => $announcements,
            'classrooms'    => $classrooms,
            'stats' => [
                'total'     => $announcements->count(),
                'published' => $announcements->where('is_published', true)->count(),
                'draft'     => $announcements->where('is_published', false)->count(),
                'pinned'    => $announcements->where('is_pinned', true)->count(),
            ],
            'active_term' => $activeTerm?->name,
        ]);
    }
}