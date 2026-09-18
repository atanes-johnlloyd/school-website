<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\ClassRoom;
use App\Models\Term;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;

        if (! $student) {
            abort(403, 'No student profile linked to this account.');
        }

        $activeTerm = Term::where('is_active', true)->first();

        // ─── Class scope ───
        $classroomIds = $student->classes()
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
            ->pluck('classes.id');

        // ─── Counts ───
        $stats = [
            'classes' => $classroomIds->count(),
            'pending_assignments' => Assignment::query()
                ->whereIn('class_id', $classroomIds)
                ->published()
                ->notSubmittedBy($student->id)
                ->where('due_at', '>', now())
                ->count(),
            'grades_available' => \App\Models\AssignmentSubmission::query()
                ->where('student_id', $student->id)
                ->whereNotNull('graded_at')
                ->count(),
        ];

        // ─── Upcoming deadlines (next 7 days, unpublished ones too — student should see what's coming) ───
        $upcomingDeadlines = Assignment::query()
            ->whereIn('class_id', $classroomIds)
            ->published()
            ->notSubmittedBy($student->id)
            ->whereBetween('due_at', [now(), now()->addDays(7)])
            ->with(['classroom.subject:id,name', 'classroom:id,subject_id,section_id'])
            ->orderBy('due_at')
            ->limit(10)
            ->get()
            ->map(fn (Assignment $a) => [
                'id'         => $a->id,
                'title'      => $a->title,
                'subject'    => $a->classroom?->subject?->name,
                'due_at'     => $a->due_at?->toIso8601String(),
                'due_human'  => $a->due_at?->diffForHumans(),
                'days_left'  => (int) now()->diffInDays($a->due_at, false),
                'points'     => $a->points,
            ]);

        // ─── Recent announcements (cross-class, last 5) ───
        $recentAnnouncements = Announcement::query()
            ->whereIn('class_id', $classroomIds)
            ->published()
            ->active()
            ->with([
                'author:id,name',
                'classroom.subject:id,name',
                'classroom:id,subject_id',
            ])
            ->ordered()
            ->limit(5)
            ->get()
            ->map(fn (Announcement $a) => [
                'id'           => $a->id,
                'title'        => $a->title,
                'body_preview' => \Str::limit(strip_tags($a->body), 120),
                'is_pinned'    => $a->is_pinned,
                'author'       => $a->author?->name,
                'subject'      => $a->classroom?->subject?->name,
                'published_at' => $a->published_at?->toIso8601String(),
                'published_human' => $a->published_at?->diffForHumans(),
            ]);

        $payload = [
            'stats'                => $stats,
            'upcoming_deadlines'   => $upcomingDeadlines,
            'recent_announcements' => $recentAnnouncements,
            'active_term'          => $activeTerm?->name,
        ];

        if ($request->wantsJson()) {
            return response()->json($payload);
        }

        return \Inertia\Inertia::render('Student/Dashboard', $payload);

        return response()->json([
            'stats'                => $stats,
            'upcoming_deadlines'   => $upcomingDeadlines,
            'recent_announcements' => $recentAnnouncements,
            'active_term'          => $activeTerm?->name,
        ]);
    }
}