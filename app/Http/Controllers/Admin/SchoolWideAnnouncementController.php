<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSchoolWideAnnouncementRequest;
use App\Models\Announcement;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolWideAnnouncementController extends Controller
{
    public function __construct(protected ImageUploadService $uploader) {}

    /**
     * List school-wide announcements.
     */
    public function index(Request $request)
    {
        $announcements = Announcement::query()
            ->schoolWide()
            ->with('author:id,name')
            ->ordered()
            ->paginate(20);

        return response()->json([
            'announcements' => $announcements,
        ]);
    }

    /**
     * Create school-wide announcement.
     */
    public function store(StoreSchoolWideAnnouncementRequest $request)
    {
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $this->uploader->store(
                $request->file('image'),
                'school-news',
                1200
            );
        }

        $isPublished = $request->boolean('is_published', false);

        $announcement = Announcement::create([
            'created_by'    => $request->user()->id,
            'class_id'      => null,
            'title'         => $request->validated('title'),
            'body'          => $request->validated('body'),
            'image_path'    => $imagePath,
            'is_pinned'     => $request->boolean('is_pinned', false),
            'is_school_wide' => true,
            'published_at'  => $isPublished ? now() : null,
            'expires_at'    => $request->validated('expires_at'),
        ]);

        return response()->json([
            'message'      => $isPublished ? 'School-wide announcement published.' : 'Draft saved.',
            'announcement' => $announcement,
        ], 201);
    }

    public function show(Request $request, Announcement $announcement)
    {
        abort_unless($announcement->is_school_wide, 404);

        return response()->json([
            'announcement' => $announcement->load('author:id,name'),
        ]);
    }

    public function update(Request $request, Announcement $announcement)
    {
        abort_unless($announcement->is_school_wide, 404);

        $validated = $request->validate([
            'title'        => ['sometimes', 'string', 'max:255'],
            'body'         => ['sometimes', 'string', 'max:20000'],
            'is_pinned'    => ['boolean'],
            'is_published' => ['boolean'],
            'expires_at'   => ['nullable', 'date'],
        ]);

        if ($request->has('is_published')) {
            $isPublished = $request->boolean('is_published');
            $validated['published_at'] = $isPublished
                ? ($announcement->published_at ?? now())
                : null;
            unset($validated['is_published']);
        }

        $announcement->update($validated);

        return response()->json([
            'message'      => 'Announcement updated.',
            'announcement' => $announcement->fresh(),
        ]);
    }

    public function destroy(Request $request, Announcement $announcement)
    {
        abort_unless($announcement->is_school_wide, 404);

        $this->uploader->delete($announcement->image_path);
        $announcement->delete();

        return response()->json(['message' => 'Announcement deleted.']);
    }
}