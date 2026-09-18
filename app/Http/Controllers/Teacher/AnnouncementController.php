<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreAnnouncementRequest;
use App\Http\Requests\Teacher\UpdateAnnouncementRequest;
use App\Models\Announcement;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

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
                'id'           => $a->id,
                'title'        => $a->title,
                'body_preview' => \Str::limit(strip_tags($a->body), 120),
                'is_pinned'    => $a->is_pinned,
                'is_published' => $a->published_at !== null,
                'published_at' => $a->published_at?->toIso8601String(),
                'expires_at'   => $a->expires_at?->toIso8601String(),
                'created_at'   => $a->created_at?->toIso8601String(),
            ]);

        return response()->json([
            'classroom'     => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'section' => $classroom->section?->name,
            ],
            'announcements' => $announcements,
        ]);
    }

    public function store(StoreAnnouncementRequest $request, ClassRoom $classroom)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        $isPublished = $request->boolean('is_published', false);

        $announcement = $classroom->announcements()->create([
            'created_by'   => $request->user()->id,
            'title'        => $request->validated('title'),
            'body'         => $request->validated('body'),
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

        return response()->json([
            'announcement' => [
                'id'           => $announcement->id,
                'title'        => $announcement->title,
                'body'         => $announcement->body,
                'is_pinned'    => $announcement->is_pinned,
                'is_published' => $announcement->published_at !== null,
                'published_at' => $announcement->published_at?->toIso8601String(),
                'expires_at'   => $announcement->expires_at?->toIso8601String(),
                'created_at'   => $announcement->created_at?->toIso8601String(),
                'classroom'    => [
                    'id'      => $announcement->classroom->id,
                    'subject' => $announcement->classroom->subject?->name,
                    'section' => $announcement->classroom->section?->name,
                ],
            ],
        ]);
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
}