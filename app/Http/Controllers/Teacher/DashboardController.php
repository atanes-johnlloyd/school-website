<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use App\Models\ClassSchedule;
use App\Models\ClassRoom;
use App\Models\Term;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;

        if (! $teacher) {
            abort(403, 'No teacher profile linked to this account.');
        }

        $activeTerm = Term::where('is_active', true)->first();

        // ─── Class scope (teacher's classes for the active term) ───
        $classQuery = ClassRoom::query()
            ->where('teacher_id', $teacher->id)
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id));

        $classroomIds = $classQuery->pluck('id');

        // ─── Counts ───
        $stats = [
            'classes'  => $classroomIds->count(),
            'students' => \App\Models\Student::whereHas('classes', function ($q) use ($classroomIds) {
                $q->whereIn('classes.id', $classroomIds);
            })->count(),
            'pending_grading' => AssignmentSubmission::query()
                ->whereHas('assignment', fn ($q) => $q->whereIn('class_id', $classroomIds))
                ->whereIn('status', ['submitted', 'late'])
                ->count(),
        ];

        // ─── Needs grading queue (top 10, oldest submissions first) ───
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
                'is_late'       => $s->status === 'late',
            ]);

        // ─── Today's schedule ───
        $today = strtolower(now()->format('l')); // 'monday', 'tuesday', etc.

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
                'id'         => $s->id,
                'subject'    => $s->classroom?->subject?->name,
                'subject_code' => $s->classroom?->subject?->code,
                'section'    => $s->classroom?->section?->name,
                'room'       => $s->room?->name,
                'time_start' => $s->time_start,
                'time_end'   => $s->time_end,
            ]);

        $payload = [
            'stats'          => $stats,
            'needs_grading'  => $needsGrading,
            'today_schedule' => $todaySchedule,
            'active_term'    => $activeTerm?->name,
            'today'          => now()->format('l, F j, Y'),
        ];

        if ($request->wantsJson()) {
            return response()->json($payload);
        }

        return \Inertia\Inertia::render('Teacher/Dashboard', $payload);

        return response()->json([
            'stats'          => $stats,
            'needs_grading'  => $needsGrading,
            'today_schedule' => $todaySchedule,
            'active_term'    => $activeTerm?->name,
            'today'          => now()->format('l, F j, Y'),
        ]);
    }
}