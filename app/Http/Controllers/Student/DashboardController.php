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
            'pending_assignments' =>
                Assignment::query()
                    ->whereIn('class_id', $classroomIds)
                    ->published()
                    ->notSubmittedBy($student->id)
                    ->where('due_at', '>', now())
                    ->count()
                +
                \App\Models\Quiz::query()
                    ->whereIn('class_id', $classroomIds)
                    ->where('is_published', true)
                    ->whereDoesntHave('attempts', fn ($q) => $q
                        ->where('student_id', $student->id)
                        ->whereIn('status', ['submitted', 'graded']))
                    ->where(fn ($q) => $q->whereNull('available_until')
                                    ->orWhere('available_until', '>', now()))
                    ->count(),
            'grades_available' => \App\Models\AssignmentSubmission::query()
                ->where('student_id', $student->id)
                ->whereNotNull('graded_at')
                ->count(),
        ];

        // ─── Upcoming deadlines: assignments + quizzes ──────────
        $assignmentDeadlines = Assignment::query()
            ->whereIn('class_id', $classroomIds)
            ->published()
            ->notSubmittedBy($student->id)
            ->whereBetween('due_at', [now(), now()->addDays(7)])
            ->with(['classroom.subject:id,name', 'classroom:id,subject_id,section_id'])
            ->get()
            ->map(fn (Assignment $a) => [
                'id'         => 'a-' . $a->id,
                'raw_id'     => $a->id,
                'type'       => 'assignment',
                'title'      => $a->title,
                'subject'    => $a->classroom?->subject?->name,
                'due_at'     => $a->due_at?->toIso8601String(),
                'due_human'  => $a->due_at?->diffForHumans(),
                'days_left'  => (int) now()->diffInDays($a->due_at, false),
                'points'     => $a->points,
                'show_url'   => route('student.assignments.show', $a->id),
            ]);

        $quizDeadlines = \App\Models\Quiz::query()
            ->whereIn('class_id', $classroomIds)
            ->where('is_published', true)
            ->whereDoesntHave('attempts', fn ($q) => $q
                ->where('student_id', $student->id)
                ->whereIn('status', ['submitted', 'graded']))
            ->whereBetween('available_until', [now(), now()->addDays(7)])
            ->with(['classroom.subject:id,name', 'classroom:id,subject_id,section_id'])
            ->get()
            ->map(fn (\App\Models\Quiz $q) => [
                'id'         => 'q-' . $q->id,
                'raw_id'     => $q->id,
                'type'       => 'quiz',
                'title'      => $q->title,
                'subject'    => $q->classroom?->subject?->name,
                'due_at'     => $q->available_until?->toIso8601String(),
                'due_human'  => $q->available_until?->diffForHumans(),
                'days_left'  => (int) now()->diffInDays($q->available_until, false),
                'points'     => (float) $q->questions()->sum('points'),
                'show_url'   => route('student.quizzes.show', $q->id),
            ]);

        $upcomingDeadlines = $assignmentDeadlines
            ->concat($quizDeadlines)
            ->sortBy('due_at')
            ->take(10)
            ->values();

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

        $currentEnrollment = $student->enrollments()
            ->with(['section.strand', 'schoolYear'])
            ->where('status', 'enrolled')
            ->latest('enrolled_at')
            ->first();

        $payload = [
            'student' => [
                'name'        => $request->user()->name,
                'lrn'         => $student->lrn,
                'avatar_url'  => $request->user()->avatar_url,
                'grade_level' => $currentEnrollment?->section?->grade_level,
                'section'     => $currentEnrollment?->section?->name,
                'strand'      => $currentEnrollment?->section?->strand?->name,
                'school_year' => $currentEnrollment?->schoolYear?->label,
            ],
            'classes'              => $classesPaginator,
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