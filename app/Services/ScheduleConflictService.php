<?php

namespace App\Services;

use App\Models\ClassRoom;
use App\Models\ClassSchedule;
use App\Models\Student;
use App\Models\Teacher;

class ScheduleConflictService
{
    /**
     * Returns the conflicting class info if adding $target would overlap any
     * of the student's existing classes, or null if safe.
     */
    public function studentHasConflict(Student $student, ClassRoom $target): ?array
    {
        $target->loadMissing('schedules');
        if ($target->schedules->isEmpty()) return null;

        $existing = ClassSchedule::where('class_id', '!=', $target->id)
            ->whereHas('classroom.students', fn ($q) => $q->where('students.id', $student->id))
            ->whereHas('classroom', fn ($q) => $q->where('term_id', $target->term_id))
            ->with(['classroom.subject:id,name', 'classroom.section:id,name'])
            ->get();

        foreach ($existing as $s) {
            foreach ($target->schedules as $t) {
                if ($s->day_of_week === $t->day_of_week
                    && $s->time_start < $t->time_end
                    && $s->time_end > $t->time_start) {
                    return [
                        'class_id'   => $s->class_id,
                        'subject'    => $s->classroom?->subject?->name,
                        'section'    => $s->classroom?->section?->name,
                        'day'        => $s->day_of_week,
                        'time_start' => $s->time_start,
                        'time_end'   => $s->time_end,
                    ];
                }
            }
        }

        return null;
    }
}