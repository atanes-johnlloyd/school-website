<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\ClassRoom;
use App\Models\ClassSchedule;
use App\Models\Quiz;
use App\Models\Term;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403, 'No teacher profile linked to this account.');

        $activeTerm = Term::where('is_active', true)->first();

        // ─── Base class scope ────────────────────────────
        $classQuery = ClassRoom::query()
            ->where('teacher_id', $teacher->id)
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id));

        $classroomIds = $classQuery->pluck('id');

        // ─── Stats ───────────────────────────────────────
        $studentCount = \App\Models\Student::whereHas('classroom', function ($q) use ($classroomIds) {
            $q->whereIn('classes.id', $classroomIds);
        })->count();

        $pendingGrading = AssignmentSubmission::query()
            ->whereHas('assignment', fn ($q) => $q->whereIn('class_id', $classroomIds))
            ->whereIn('status', ['submitted', 'late'])
            ->count();

        $activeTasks = Assignment::query()
            ->whereIn('class_id', $classroomIds)
            ->where('is_published', true)
            ->where(fn ($q) => $q->whereNull('due_at')->orWhere('due_at', '>', now()))
            ->count()
            +
            Quiz::query()
            ->whereIn('class_id', $classroomIds)
            ->where('is_published', true)
            ->where(fn ($q) => $q->whereNull('available_until')->orWhere('available_until', '>', now()))
            ->count();

        $stats = [
            'classes'         => $classroomIds->count(),
            'students'        => $studentCount,
            'pending_grading' => $pendingGrading,
            'active_tasks'    => $activeTasks,
        ];

        // ─── Class cards (for "Assigned Classes" section) ────
        $classes = ClassRoom::query()
            ->whereIn('id', $classroomIds)
            ->with(['subject:id,code,name', 'section:id,name,grade_level'])
            ->withCount('students')
            ->get()
            ->map(fn (ClassRoom $c) => [
                'id'                 => $c->id,
                'subject'            => $c->subject?->name,
                'subject_code'       => $c->subject?->code,
                'section'            => $c->section?->name,
                'grade_level'        => $c->section?->grade_level,
                'students_count'     => $c->students_count,
                'weight_written_work'     => (float) $c->weight_written_work,
                'weight_performance_task' => (float) $c->weight_performance_task,
                'weight_quarterly_exam'   => (float) $c->weight_quarterly_exam,
            ]);

        // ─── Needs grading queue (top 10, oldest first) ──────
        $needsGrading = AssignmentSubmission::query()
            ->with([
                'student.user:id,name',
                'assignment:id,title,class_id,points,due_at',
                'assignment.classroom:id,subject_id,section_id',
                'assignment.classroom.subject:id,name',
                'assignment.classroom.section:id,name',
            ])
            ->whereHas('assignment', fn ($q) => $q->whereIn('class_id', $classroomIds))
            ->whereIn('status', ['submitted', 'late'])
            ->orderBy('submitted_at')
            ->limit(10)
            ->get()
            ->map(fn (AssignmentSubmission $s) => [
                'id'            => $s->id,
                'student_name'  => $s->student?->user?->name,
                'assignment_id' => $s->assignment_id,
                'assignment'    => $s->assignment?->title,
                'subject'       => $s->assignment?->classroom?->subject?->name,
                'section'       => $s->assignment?->classroom?->section?->name,
                'submitted_at'  => $s->submitted_at?->toIso8601String(),
                'submitted_human' => $s->submitted_at?->diffForHumans(),
                'is_late'       => $s->status === 'late',
            ]);

        // ─── Today's schedule ────────────────────────────────
        $today = strtolower(now()->format('l'));

        $todaySchedule = ClassSchedule::query()
            ->with([
                'classroom:id,subject_id,section_id,teacher_id',
                'classroom.subject:id,name,code',
                'classroom.section:id,name,grade_level',
                'room:id,code,name',
            ])
            ->whereHas('classroom', fn ($q) => $q->whereIn('id', $classroomIds))
            ->where('day_of_week', $today)
            ->orderBy('time_start')
            ->get()
            ->map(fn (ClassSchedule $s) => [
                'id'           => $s->id,
                'subject'      => $s->classroom?->subject?->name,
                'subject_code' => $s->classroom?->subject?->code,
                'section'      => $s->classroom?->section?->name,
                'room'         => $s->room?->name,
                'time_start'   => substr($s->time_start, 0, 5),
                'time_end'     => substr($s->time_end, 0, 5),
            ]);

        // ─── Recent announcements from my classes ────────────
        $recentAnnouncements = Announcement::query()
            ->whereIn('class_id', $classroomIds)
            ->published()
            ->active()
            ->with(['classroom.subject:id,name', 'classroom.section:id,name'])
            ->ordered()
            ->limit(5)
            ->get()
            ->map(fn (Announcement $a) => [
                'id'              => $a->id,
                'title'           => $a->title,
                'body_preview'    => \Str::limit(strip_tags($a->body), 140),
                'is_pinned'       => (bool) $a->is_pinned,
                'subject'         => $a->classroom?->subject?->name,
                'section'         => $a->classroom?->section?->name,
                'published_human' => $a->published_at?->diffForHumans(),
                'published_at'    => $a->published_at?->toIso8601String(),
            ]);

        $payload = [
            'stats'                => $stats,
            'classes'              => $classes,
            'needs_grading'        => $needsGrading,
            'today_schedule'       => $todaySchedule,
            'recent_announcements' => $recentAnnouncements,
            'active_term'          => $activeTerm?->name,
            'today'                => now()->format('l, F j, Y'),
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : \Inertia\Inertia::render('Teacher/Dashboard', $payload);
    }
}