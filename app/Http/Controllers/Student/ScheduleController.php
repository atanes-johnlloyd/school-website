<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        abort_unless($student, 403);

        $activeTerm = Term::where('is_active', true)->first();

        $classrooms = $student->classroom()
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
            ->with([
                'subject:id,code,name',
                'section:id,name,grade_level',
                'teacher.user:id,name',
            ])
            ->get();

        $classroomIds = $classrooms->pluck('id');

        $schedules = ClassSchedule::query()
            ->whereIn('class_id', $classroomIds)
            ->with([
                'room:id,code,name,building',
                'classroom:id,subject_id,section_id,teacher_id',
                'classroom.subject:id,code,name',
                'classroom.section:id,name',
                'classroom.teacher.user:id,name',
            ])
            ->orderBy('day_of_week')
            ->orderBy('time_start')
            ->get()
            ->map(fn (ClassSchedule $s) => [
                'id'          => $s->id,
                'day'         => $s->day_of_week,
                'time_start'  => substr($s->time_start, 0, 5),   // "07:30"
                'time_end'    => substr($s->time_end, 0, 5),
                'room'        => $s->room?->name,
                'room_code'   => $s->room?->code,
                'building'    => $s->room?->building,
                'subject'     => $s->classroom?->subject?->name,
                'subject_code'=> $s->classroom?->subject?->code,
                'section'     => $s->classroom?->section?->name,
                'instructor'  => $s->classroom?->teacher?->user?->name,
                'class_id'    => $s->class_id,
            ]);

        // Group by day for the weekly grid
        $byDay = collect(['monday','tuesday','wednesday','thursday','friday'])
            ->mapWithKeys(fn ($day) => [
                $day => $schedules->where('day', $day)->values(),
            ]);

        // Today's activity
        $todayName = strtolower(now()->format('l'));
        $todaysClasses = $byDay->get($todayName, collect())
            ->map(fn ($s) => array_merge($s, [
                'is_active' => $this->isNow($s['time_start'], $s['time_end']),
                'is_past'   => $this->isPast($s['time_end']),
                'is_next'   => false,
            ]))
            ->values()
            ->toArray();

        // Mark next upcoming
        $next = null;
        foreach ($todaysClasses as $idx => $c) {
            if (! $c['is_past'] && ! $c['is_active']) {
                $todaysClasses[$idx]['is_next'] = true;
                $next = $todaysClasses[$idx];
                break;
            }
        }

                // Group into time slots (unique start/end pair, sorted)
        $slots = $schedules
            ->groupBy(fn ($s) => $s['time_start'] . '|' . $s['time_end'])
            ->map(function ($group, $key) {
                [$start, $end] = explode('|', $key);
                return [
                    'slot_id'    => $key,
                    'time_start' => $start,
                    'time_end'   => $end,
                    'days'       => $group->keyBy('day')->toArray(),
                ];
            })
            ->sortBy('time_start')
            ->values();

        // Current live class (if any)
        $now = now()->format('H:i');
        $liveClass = $schedules
            ->where('day', $now < '12:00' ? 'monday' : 'monday') // placeholder, replaced below
            ->first();

        $today = strtolower(now()->format('l'));
        $liveClass = $schedules
            ->where('day', $today)
            ->first(fn ($s) => $now >= $s['time_start'] && $now <= $s['time_end']);

        $nextClass = $schedules
            ->where('day', $today)
            ->filter(fn ($s) => $s['time_start'] > $now)
            ->sortBy('time_start')
            ->first();

        $payload = [
            'slots'         => $slots,
            'schedules'     => $schedules,
            'today_name'    => $today,
            'live_class'    => $liveClass,
            'next_class'    => $nextClass,
            'active_term'   => $activeTerm?->name,
            'total_classes' => $classrooms->count(),
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Student/Schedule/Index', $payload);
    }

    protected function isNow(string $start, string $end): bool
    {
        $now = now()->format('H:i');
        return $now >= $start && $now <= $end;
    }

    protected function isPast(string $end): bool
    {
        return now()->format('H:i') > $end;
    }
}