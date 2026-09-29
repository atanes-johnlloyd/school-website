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

        // ─── Query Student Enrolled Classes ───
        $classesQuery = $student->classroom()
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
            ->with([
                'subject:id,code,name',
                'section:id,name',
                'teacher.user:id,name',
            ]);

        $classroomIds = (clone $classesQuery)->pluck('classes.id');

        // ─── Format Classes/Subjects to match Index page structure ───
        $classesPaginator = $classesQuery->paginate(10);
        $classesPaginator->through(fn ($c) => [
            'id'           => $c->id,
            'subject'      => $c->subject?->name ?? 'Untitled Subject',
            'subject_code' => $c->subject?->code ?? 'N/A',
            'section'      => $c->section?->name ?? 'N/A',
            'teacher'      => $c->teacher?->user?->name ?? 'TBA',
        ]);

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

        // ─── Upcoming deadlines ───
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

        // ─── Recent announcements ───
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
            'classes'              => $classesPaginator, // ← Pass enrolled classes here
            'stats'                => $stats,
            'upcoming_deadlines'   => $upcomingDeadlines,
            'recent_announcements' => $recentAnnouncements,
            'active_term'          => $activeTerm?->name,
        ];

        if ($request->wantsJson()) {
            return response()->json($payload);
        }

        return \Inertia\Inertia::render('Student/Dashboard', $payload);
    }
}