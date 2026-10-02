<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

        /**
     * Cross-class feed for the student — main announcements page.
     */
    public function feed(Request $request)
    {
        $student = $request->user()->student;
        abort_unless($student, 403);

        $limit = min((int) $request->input('limit', 30), 100);
        $classroomIds = $student->classroom()->pluck('classes.id');

        $announcements = Announcement::query()
            ->where(function ($q) use ($classroomIds) {
                $q->whereIn('class_id', $classroomIds)
                  ->orWhere('is_school_wide', true);
            })
            ->published()
            ->active()
            ->with([
                'author:id,name',
                'classroom.subject:id,name',
                'classroom.section:id,name',
            ])
            ->orderedByImportance()
            ->limit($limit)
            ->get()
            ->map(fn (Announcement $a) => [
                'id'             => $a->id,
                'title'          => $a->title,
                'body'           => $a->body,
                'body_preview'   => \Str::limit(strip_tags($a->body), 160),
                'is_pinned'      => (bool) $a->is_pinned,
                'is_school_wide' => (bool) $a->is_school_wide,
                'priority'       => $a->priority ?? 'normal',
                'image_url'      => $a->image_url,
                'author'         => $a->author?->name,
                'subject'        => $a->classroom?->subject?->name,
                'section'        => $a->classroom?->section?->name,
                'published_at'   => $a->published_at?->toIso8601String(),
                'published_human'=> $a->published_at?->diffForHumans(),
                'expires_at'     => $a->expires_at?->toIso8601String(),
                'show_url'       => route('student.announcements.show', $a->id),
            ]);

        $counts = [
            'all'         => $announcements->count(),
            'school_wide' => $announcements->where('is_school_wide', true)->count(),
            'class'       => $announcements->where('is_school_wide', false)->count(),
            'pinned'      => $announcements->where('is_pinned', true)->count(),
            'urgent'      => $announcements->where('priority', 'urgent')->count(),
        ];

        return Inertia::render('Student/Announcements/Index', [
            'announcements' => $announcements,
            'counts'        => $counts,
            'active_term'   => \App\Models\Term::where('is_active', true)->value('name'),
        ]);
    }

    /**
     * Single announcement view.
     */
    public function show(Request $request, Announcement $announcement)
    {
        $student = $request->user()->student;
        abort_unless($student, 403);

        abort_unless($announcement->published_at !== null, 404);
        if ($announcement->expires_at && $announcement->expires_at->isPast()) {
            abort(404);
        }

        if (! $announcement->is_school_wide && $announcement->class_id) {
            abort_unless($announcement->classroom?->hasStudent($request->user()), 403);
        }

        $announcement->load(['author:id,name', 'classroom.subject:id,name', 'classroom.section:id,name']);

        return Inertia::render('Student/Announcements/Show', [
            'announcement' => [
                'id'             => $announcement->id,
                'title'          => $announcement->title,
                'body'           => $announcement->body,
                'is_pinned'      => (bool) $announcement->is_pinned,
                'is_school_wide' => (bool) $announcement->is_school_wide,
                'priority'       => $announcement->priority ?? 'normal',
                'image_url'      => $announcement->image_url,
                'author'         => $announcement->author?->name,
                'subject'        => $announcement->classroom?->subject?->name,
                'section'        => $announcement->classroom?->section?->name,
                'classroom_id'   => $announcement->class_id,
                'published_at'   => $announcement->published_at?->toIso8601String(),
                'expires_at'     => $announcement->expires_at?->toIso8601String(),
            ],
        ]);
    }
}
