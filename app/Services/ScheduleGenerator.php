<?php

namespace App\Services;

use App\Exceptions\ScheduleException;
use App\Models\ClassRoom;
use App\Models\ClassSchedule;
use App\Models\Room;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Term;
use Illuminate\Support\Facades\DB;

class ScheduleGenerator
{
    protected array $curriculum;
    protected array $templates;

    public function __construct()
    {
        $this->curriculum = config('curriculum');
        $this->templates  = config('schedule_templates');
    }

    /* ═══════════════════════════════════════════════════════════════
     | PUBLIC ENTRY POINT — build a whole term's worth of classes
     |═══════════════════════════════════════════════════════════════ */

    /**
     * Generate ClassRoom + ClassSchedule rows for one section and one term.
     * Wrapped in a transaction. Throws ScheduleException on failure — no
     * partial writes.
     *
     * @return ClassRoom[]
     */
    public function generateForSectionTerm(Section $section, Term $term): array
    {
        $placement = $this->solvePlacement($section, $term);

        return DB::transaction(function () use ($section, $term, $placement) {
            return $this->persistPlacement($section, $term, $placement);
        });
    }

    /**
     * Look up a subject's curriculum definition by its DB code and grade.
     * Config keys are composite ("ICT-PROGDOTNET-12"); the DB code is
     * plain ("ICT-PROGDOTNET"). This bridges the two.
     */
    protected function subjectDef(string $subjectCode, string $grade): ?array
    {
        foreach ($this->curriculum['subjects'] as $key => $def) {
            if ((string) $def['grade'] !== (string) $grade) continue;

            $resolved = $def['subject_code'] ?? preg_replace('/-(11|12)$/', '', $key);
            if ($resolved === $subjectCode) return $def;
        }
        return null;
    }

    /* ═══════════════════════════════════════════════════════════════
     | CLUSTER-SWAP HELPERS
     |═══════════════════════════════════════════════════════════════ */

    /**
     * Resolve a teacher's ID from config.default_teachers by subject code.
     */
    public function resolveTeacherIdForCode(string $code): ?int
    {
        $email = config("curriculum.default_teachers.{$code}");
        if (! $email) return null;

        return Teacher::whereHas('user', fn ($q) => $q->where('email', $email))
            ->value('id');
    }

    /**
     * Change a class's room to a room of the given type, keeping its
     * existing day/time pattern. Throws if no free room is available.
     */
    public function reassignRoom(ClassRoom $classroom, string $newRoomType): void
    {
        $existing = ClassSchedule::where('class_id', $classroom->id)->get();
        if ($existing->isEmpty()) return;

        // Regular subjects use the section's home room.
        if ($newRoomType === 'regular') {
            $homeRoomId = $classroom->section?->home_room_id;
            if (! $homeRoomId) {
                throw new ScheduleException(
                    "Section {$classroom->section_id} has no home room assigned."
                );
            }
            foreach ($existing as $row) {
                $row->update(['room_id' => $homeRoomId]);
            }
            return;
        }

        $candidates = Room::where('type', $newRoomType)
            ->where('is_active', true)
            ->orderBy('id')
            ->pluck('id')
            ->all();

        if (empty($candidates)) {
            throw new ScheduleException("No rooms of type '{$newRoomType}' exist.");
        }

        foreach ($existing as $row) {
            $picked = null;

            foreach ($candidates as $roomId) {
                $conflict = ClassSchedule::where('room_id', $roomId)
                    ->where('day_of_week', $row->day_of_week)
                    ->where('time_start', $row->time_start)
                    ->where('id', '!=', $row->id)
                    ->whereHas('classroom', fn ($q) => $q->where('term_id', $classroom->term_id))
                    ->exists();

                if (! $conflict) { $picked = $roomId; break; }
            }

            if (! $picked) {
                throw new ScheduleException(
                    "No free '{$newRoomType}' room on {$row->day_of_week} at {$row->time_start}."
                );
            }

            $row->update(['room_id' => $picked]);
        }
    }

    /* ═══════════════════════════════════════════════════════════════
     | RESCHEDULE HELPERS
     |═══════════════════════════════════════════════════════════════ */

    /**
     * Return every viable (slot, days) placement for one class — options
     * currently blocked by other classes are marked unavailable with a reason.
     *
     * @return array<int,array{slot_index:int,start:string,end:string,days:array,available:bool,reason:?string}>
     */
    public function availableSlotsFor(ClassRoom $classroom): array
    {
        $section  = $classroom->section;
        $template = $this->templateForGrade($section->grade_level);
        $subject  = Subject::find($classroom->subject_id);

        $code     = $subject?->code;
        $def      = $code ? $this->subjectDef($code, $section->grade_level) : null;
        $needed   = $def['sessions_per_week'] ?? 5;
        $roomType = $def['room_type'] ?? 'regular';

        $teacherId = $classroom->teacher_id;

        $used = $this->snapshotUsed($section, $classroom->term_id, $classroom->id);

        $daySets = $this->dayCombinations($template['days'], $needed);
        $out     = [];

        foreach ($template['slots'] as $slot) {
            $anyAvailable = false;
            $reason       = null;

            foreach ($daySets as $days) {
                if ($this->sectionBusy($used, $section->id, $slot['index'], $days)) {
                    $reason = 'Section already has a class at this time.';
                    continue;
                }

                if ($teacherId && $this->teacherBusy($used, $teacherId, $slot['index'], $days, $classroom->term_id)) {
                    $reason = 'Assigned teacher is busy at this time.';
                    continue;
                }

                if ($this->resolveRoom($roomType, $section, $slot['index'], $days, $used, $classroom->term_id) === null) {
                    $reason = "No free {$roomType} room available.";
                    continue;
                }

                $anyAvailable = true;
                $out[] = [
                    'slot_index' => $slot['index'],
                    'start'      => $slot['start'],
                    'end'        => $slot['end'],
                    'days'       => $days,
                    'available'  => true,
                    'reason'     => null,
                ];
                break;
            }

            if (! $anyAvailable) {
                $out[] = [
                    'slot_index' => $slot['index'],
                    'start'      => $slot['start'],
                    'end'        => $slot['end'],
                    'days'       => [],
                    'available'  => false,
                    'reason'     => $reason,
                ];
            }
        }

        return $out;
    }

    /**
     * Apply a new (slot, days) placement to a class.
     * Removes old schedule rows, inserts new ones. Throws if anything conflicts.
     */
    public function applyPlacement(ClassRoom $classroom, int $slotIndex, array $days): void
    {
        $template = $this->templateForGrade($classroom->section->grade_level);
        $slot     = collect($template['slots'])->firstWhere('index', $slotIndex);
        if (! $slot) {
            throw new ScheduleException("Invalid slot index {$slotIndex}.");
        }

        $subject  = Subject::find($classroom->subject_id);
        $def      = $subject ? $this->subjectDef($subject->code, $classroom->section->grade_level) : null;
        $roomType = $def['room_type'] ?? 'regular';

        // Snapshot with the class itself excluded, so it doesn't self-conflict.
        $used = $this->snapshotUsed($classroom->section, $classroom->term_id, $classroom->id);

        if ($this->sectionBusy($used, $classroom->section_id, $slotIndex, $days)) {
            throw new ScheduleException('Section is busy at this time.');
        }

        if ($classroom->teacher_id && $this->teacherBusy($used, $classroom->teacher_id, $slotIndex, $days, $classroom->term_id)) {
            throw new ScheduleException('Teacher is busy at this time.');
        }

        $room = $this->resolveRoom($roomType, $classroom->section, $slotIndex, $days, $used, $classroom->term_id);
        if (! $room) {
            throw new ScheduleException("No free {$roomType} room for this time.");
        }

        ClassSchedule::where('class_id', $classroom->id)->delete();

        foreach ($days as $day) {
            ClassSchedule::create([
                'class_id'    => $classroom->id,
                'room_id'     => $room->id,
                'day_of_week' => $day,
                'time_start'  => $slot['start'],
                'time_end'    => $slot['end'],
            ]);
        }
    }

    /**
     * Build a fresh "used" snapshot for a term, optionally excluding one class.
     */
    protected function snapshotUsed(Section $section, int $termId, ?int $excludeClassId = null): array
    {
        $used = ['section' => [], 'teacher' => [], 'room' => []];

        $rows = ClassSchedule::whereHas('classroom', fn ($q) => $q->where('term_id', $termId))
            ->when($excludeClassId, fn ($q) => $q->where('class_id', '!=', $excludeClassId))
            ->with('classroom:id,section_id,teacher_id')
            ->get();

        foreach ($rows as $row) {
            $slotIndex = $this->slotIndexFor($row->time_start, $row->time_end, $section->grade_level);
            if ($slotIndex === null) continue;

            $key = "{$slotIndex}:{$row->day_of_week}";

            if ($row->classroom?->section_id) {
                $used['section'][$row->classroom->section_id][$key] = true;
            }
            if ($row->classroom?->teacher_id) {
                $used['teacher'][$row->classroom->teacher_id]["{$key}:{$termId}"] = true;
            }
            if ($row->room_id) {
                $used['room'][$row->room_id]["{$key}:{$termId}"] = true;
            }
        }

        return $used;
    }

    protected function slotIndexFor(string $start, string $end, string $grade): ?int
    {
        $template = $this->templateForGrade($grade);
        foreach ($template['slots'] as $slot) {
            if ($slot['start'] === $start && $slot['end'] === $end) {
                return $slot['index'];
            }
        }
        return null;
    }

    /* ═══════════════════════════════════════════════════════════════
     | SOLVER
     |═══════════════════════════════════════════════════════════════ */

    protected function solvePlacement(Section $section, Term $term): array
    {
        $template = $this->templateForGrade($section->grade_level);
        $subjects = $this->subjectsForSection($section);

        \Log::info('ScheduleGenerator: solving', [
            'section'    => $section->name,
            'grade'      => $section->grade_level,
            'strand'     => $section->strand?->code,
            'term'       => $term->name,
            'subject_count' => count($subjects),
            'subject_codes' => array_column($subjects, 'code'),
        ]);

        $totalUnits = array_sum(array_column($subjects, 'sessions_per_week'));
        if ($totalUnits > 30) {
            throw (new ScheduleException(
                "Curriculum for {$section->name} exceeds 30 weekly slots ({$totalUnits} declared)."
            ));
        }

        // Order: highest session-count first (they lock whole slot columns),
        // labs before regular within the same count.
        usort($subjects, function ($a, $b) {
            return [$b['sessions_per_week'], $this->roomPriority($b['room_type'])]
                <=> [$a['sessions_per_week'], $this->roomPriority($a['room_type'])];
        });

        $used = ['section' => [], 'teacher' => [], 'room' => []];

        // Preload conflicts from all already-scheduled sections in this term.
        $this->preloadTermConflicts($used, $term->id, $section->id);

        $solution = null;

        $this->backtrack(
            subjects:  $subjects,
            index:     0,
            template:  $template,
            section:   $section,
            term:      $term,
            used:      $used,
            placement: [],
            solution:  $solution,
        );

        if ($solution === null) {
            throw (new ScheduleException(
                "No valid schedule found for {$section->name} in {$term->name}. "
                . "Consider freeing a room, assigning another teacher, or adjusting the curriculum."
            ));
        }

        return $solution;
    }

    protected function preloadTermConflicts(array &$used, int $termId, int $excludeSectionId): void
    {
        $rows = ClassSchedule::whereHas('classroom', function ($q) use ($termId, $excludeSectionId) {
                $q->where('term_id', $termId)->where('section_id', '!=', $excludeSectionId);
            })
            ->with('classroom:id,section_id,teacher_id,term_id')
            ->get();

        foreach ($rows as $row) {
            $cr = $row->classroom;
            if (! $cr) continue;

            $slotIndex = $this->slotIndexFor($row->time_start, $row->time_end, '11')
                      ?? $this->slotIndexFor($row->time_start, $row->time_end, '12');
            if ($slotIndex === null) continue;

            $key = "{$slotIndex}:{$row->day_of_week}";

            $used['section'][$cr->section_id][$key] = true;
            if ($cr->teacher_id) {
                $used['teacher'][$cr->teacher_id]["{$key}:{$termId}"] = true;
            }
            if ($row->room_id) {
                $used['room'][$row->room_id]["{$key}:{$termId}"] = true;
            }
        }
    }

    protected function backtrack(
        array $subjects,
        int $index,
        array $template,
        Section $section,
        Term $term,
        array &$used,
        array $placement,
        ?array &$solution,
    ): void {
        if ($solution !== null) return;

        if ($index >= count($subjects)) {
            $solution = $placement;
            return;
        }

        $subject    = $subjects[$index];
        $candidates = $this->candidatePlacements($subject, $template, $section, $term, $used);

        foreach ($candidates as $candidate) {
            $this->commitPlacement($candidate, $used);
            $this->backtrack(
                $subjects, $index + 1, $template, $section, $term,
                $used, array_merge($placement, [$candidate]), $solution
            );
            if ($solution !== null) return;
            $this->rollbackPlacement($candidate, $used);
        }
    }

    protected function candidatePlacements(
        array $subject,
        array $template,
        Section $section,
        Term $term,
        array $used,
    ): array {
        $needed     = $subject['sessions_per_week'];
        $candidates = [];
        $daySets    = $this->dayCombinations($template['days'], $needed);

        $teacher = $this->resolveTeacher($subject);

        foreach ($template['slots'] as $slot) {
            foreach ($daySets as $days) {
                if ($this->sectionBusy($used, $section->id, $slot['index'], $days)) continue;

                if ($teacher && $this->teacherBusy($used, $teacher->id, $slot['index'], $days, $term->id)) continue;

                $room = $this->resolveRoom($subject['room_type'], $section, $slot['index'], $days, $used, $term->id);
                if ($room === null) continue;

                $candidates[] = [
                    'subject_id'      => $subject['id'],
                    'subject_code'    => $subject['code'],
                    'section_id'      => $section->id,
                    'term_id'         => $term->id,
                    'slot_index'      => $slot['index'],
                    'slot_start'      => $slot['start'],
                    'slot_end'        => $slot['end'],
                    'days'            => $days,
                    'session_minutes' => $subject['session_minutes'],
                    'room_id'         => $room->id,
                    'teacher_id'      => $teacher?->id,
                ];
            }
        }

        return $candidates;
    }

    /* ═══════════════════════════════════════════════════════════════
     | CONFLICT TRACKING
     |═══════════════════════════════════════════════════════════════ */

    protected function sectionBusy(array $used, int $sectionId, int $slotIndex, array $days): bool
    {
        foreach ($days as $day) {
            if (isset($used['section'][$sectionId]["{$slotIndex}:{$day}"])) return true;
        }
        return false;
    }

    protected function teacherBusy(array $used, int $teacherId, int $slotIndex, array $days, int $termId): bool
    {
        foreach ($days as $day) {
            if (isset($used['teacher'][$teacherId]["{$slotIndex}:{$day}:{$termId}"])) return true;
        }
        return false;
    }

    protected function roomBusy(array $used, int $roomId, int $slotIndex, array $days, int $termId): bool
    {
        foreach ($days as $day) {
            if (isset($used['room'][$roomId]["{$slotIndex}:{$day}:{$termId}"])) return true;
        }
        return false;
    }

    protected function commitPlacement(array $c, array &$used): void
    {
        $sectionId = $c['section_id'];
        foreach ($c['days'] as $day) {
            $used['section'][$sectionId]["{$c['slot_index']}:{$day}"] = true;

            if (! empty($c['teacher_id'])) {
                $used['teacher'][$c['teacher_id']]["{$c['slot_index']}:{$day}:{$c['term_id']}"] = true;
            }
            if (! empty($c['room_id'])) {
                $used['room'][$c['room_id']]["{$c['slot_index']}:{$day}:{$c['term_id']}"] = true;
            }
        }
    }

    protected function rollbackPlacement(array $c, array &$used): void
    {
        $sectionId = $c['section_id'];
        foreach ($c['days'] as $day) {
            unset($used['section'][$sectionId]["{$c['slot_index']}:{$day}"]);

            if (! empty($c['teacher_id'])) {
                unset($used['teacher'][$c['teacher_id']]["{$c['slot_index']}:{$day}:{$c['term_id']}"]);
            }
            if (! empty($c['room_id'])) {
                unset($used['room'][$c['room_id']]["{$c['slot_index']}:{$day}:{$c['term_id']}"]);
            }
        }
    }

    /* ═══════════════════════════════════════════════════════════════
     | RESOLUTION
     |═══════════════════════════════════════════════════════════════ */

    protected function templateForGrade(string $grade): array
    {
        $key = "grade_{$grade}";
        if (! isset($this->templates[$key])) {
            throw new ScheduleException("No schedule template for grade {$grade}.");
        }
        return $this->templates[$key];
    }

    protected function subjectsForSection(Section $section): array
    {
        $grade       = (string) $section->grade_level;
        $clusterCode = $section->strand?->code;
        $out         = [];

        foreach ($this->curriculum['subjects'] as $key => $def) {
            if ((string) $def['grade'] !== $grade) continue;

            $clusters = $def['clusters'];
            if ($clusters !== '*' && ! in_array($clusterCode, $clusters, true)) continue;

            // Prefer explicit subject_code. Fall back to stripping the
            // composite -11 / -12 suffix from the key.
            $subjectCode = $def['subject_code']
                ?? preg_replace('/-(11|12)$/', '', $key);

            $subject = Subject::where('code', $subjectCode)->first();
            if (! $subject) continue;

            $out[] = [
                'id'                => $subject->id,
                'code'              => $subjectCode,
                'room_type'         => $def['room_type'],
                'sessions_per_week' => $def['sessions_per_week'],
                'session_minutes'   => $def['session_minutes'] ?? 60,
                'teacher_email'     => $this->curriculum['default_teachers'][$subjectCode] ?? null,
            ];
        }

        return $out;
    }

    protected function resolveTeacher(array $subject): ?Teacher
    {
        if (empty($subject['teacher_email'])) return null;

        return Teacher::whereHas('user', fn ($q) => $q->where('email', $subject['teacher_email']))
            ->first();
    }

    protected function resolveRoom(
        string $roomType,
        Section $section,
        int $slotIndex,
        array $days,
        array $used,
        int $termId,
    ): ?Room {
        if ($roomType === 'regular') {
            if ($section->home_room_id) {
                $room = Room::find($section->home_room_id);
                if ($room && ! $this->roomBusy($used, $room->id, $slotIndex, $days, $termId)) {
                    return $room;
                }
                return null;
            }
            // No home room — fall back to any free regular room.
            foreach (Room::where('type', 'regular')->where('is_active', true)->get() as $room) {
                if (! $this->roomBusy($used, $room->id, $slotIndex, $days, $termId)) return $room;
            }
            return null;
        }

        $rooms = Room::where('type', $roomType)->where('is_active', true)->get();
        foreach ($rooms as $room) {
            if (! $this->roomBusy($used, $room->id, $slotIndex, $days, $termId)) {
                return $room;
            }
        }
        return null;
    }

    protected function dayCombinations(array $days, int $n): array
    {
        if ($n >= count($days)) return [$days];

        $out = [];
        $recurse = function ($start, $picked) use (&$recurse, &$out, $days, $n) {
            if (count($picked) === $n) { $out[] = array_values($picked); return; }
            for ($i = $start; $i < count($days); $i++) {
                $picked[] = $days[$i];
                $recurse($i + 1, $picked);
                array_pop($picked);
            }
        };
        $recurse(0, []);
        return $out;
    }

    protected function roomPriority(string $roomType): int
    {
        return match ($roomType) {
            'science_lab'  => 1,
            'computer_lab' => 2,
            'workshop'     => 3,
            'laboratory'   => 4,
            'lecture'      => 5,
            default        => 6,
        };
    }

    /* ═══════════════════════════════════════════════════════════════
     | PERSIST
     |═══════════════════════════════════════════════════════════════ */

    protected function persistPlacement(Section $section, Term $term, array $placement): array
    {
        $created = [];

        foreach ($placement as $p) {
            $classroom = ClassRoom::create([
                'subject_id'              => $p['subject_id'],
                'section_id'              => $section->id,
                'teacher_id'              => $p['teacher_id'],
                'term_id'                 => $term->id,
                'weight_written_work'     => 25.00,
                'weight_performance_task' => 50.00,
                'weight_quarterly_exam'   => 25.00,
                'is_published'            => true,
            ]);

            foreach ($p['days'] as $day) {
                ClassSchedule::create([
                    'class_id'    => $classroom->id,
                    'room_id'     => $p['room_id'],
                    'day_of_week' => $day,
                    'time_start'  => $p['slot_start'],
                    'time_end'    => $p['slot_end'],
                ]);
            }

            $created[] = $classroom;
        }

        return $created;
    }
}