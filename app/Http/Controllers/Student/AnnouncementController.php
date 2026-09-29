<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request, ClassRoom $classroom)
    {
        abort_unless($classroom->hasStudent($request->user()), 403);

        $announcements = $classroom->announcements()
            ->published()
            ->active()
            ->with('author:id,name')
            ->ordered()
            ->get()
            ->map(fn (Announcement $a) => [
                'id'           => $a->id,
                'title'        => $a->title,
                'body_preview' => \Str::limit(strip_tags($a->body), 120),
                'is_pinned'    => $a->is_pinned,
                'author'       => $a->author?->name,
                'published_at' => $a->published_at?->toIso8601String(),
            ]);

        return response()->json([
            'classroom'     => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'section' => $classroom->section?->name,
                'teacher' => $classroom->teacher?->user?->name,
            ],
            'announcements' => $announcements,
        ]);
    }

    public function show(Request $request, Announcement $announcement)
    {
        abort_unless($announcement->classroom->hasStudent($request->user()), 403);
        abort_unless($announcement->published_at !== null, 404);

        // Auto-expired announcements are treated as gone
        if ($announcement->expires_at && $announcement->expires_at->isPast()) {
            abort(404);
        }

        return response()->json([
            'announcement' => [
                'id'           => $announcement->id,
                'title'        => $announcement->title,
                'body'         => $announcement->body,
                'is_pinned'    => $announcement->is_pinned,
                'author'       => $announcement->author?->name,
                'published_at' => $announcement->published_at?->toIso8601String(),
                'classroom'    => [
                    'id'      => $announcement->classroom->id,
                    'subject' => $announcement->classroom->subject?->name,
                    'section' => $announcement->classroom->section?->name,
                ],
            ],
        ]);
    }

    /**
     * Cross-class feed for dashboard widget.
     * Returns the latest N published announcements across all of the student's classes.
     */
    public function feed(Request $request)
    {
        $student = $request->user()->student;
        abort_unless($student, 403);

        $limit = min((int) $request->input('limit', 10), 50);

        $classroomIds = $student->classroom()->pluck('classes.id');

        $announcements = Announcement::query()
            ->whereIn('class_id', $classroomIds)
            ->published()
            ->active()
            ->with(['author:id,name', 'classroom.subject:id,name', 'classroom.section:id,name'])
            ->ordered()
            ->limit($limit)
            ->get()
            ->map(fn (Announcement $a) => [
                'id'           => $a->id,
                'title'        => $a->title,
                'body_preview' => \Str::limit(strip_tags($a->body), 120),
                'is_pinned'    => $a->is_pinned,
                'author'       => $a->author?->name,
                'subject'      => $a->classroom?->subject?->name,
                'section'      => $a->classroom?->section?->name,
                'published_at' => $a->published_at?->toIso8601String(),
            ]);

        return response()->json(['announcements' => $announcements]);
    }
}
