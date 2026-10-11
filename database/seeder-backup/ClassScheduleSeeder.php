<?php

namespace database\seeder;

use App\Models\ClassRoom;
use App\Models\Room;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassScheduleSeeder extends Seeder
{
    /**
     * Follows the DepEd Strengthened SHS Curriculum sample class programs
     * (DO No. 17, s. 2026; OM-LS-2026-247).
     *
     *   • Core subjects meet 1 hour per session, 4 days per week (Mon–Thu),
     *     at a FIXED time slot — the same hour every meeting.
     *   • Friday is reserved for Homeroom Guidance Program (HGP), electives,
     *     and co-curricular activities — no core classes.
     *   • A 15-minute Health Break sits between the 2nd and 3rd slots.
     *   • The three-term calendar and per-term instructional blocks are
     *     handled by the Term model, not by this seeder.
     *
     * No rotation. A class that meets Mon 07:00 meets Tue/Wed/Thu 07:00 too.
     *
     *   GRADE 11 — MORNING SHIFT
     *     07:00–08:00  Slot 1
     *     08:00–09:00  Slot 2
     *     09:00–09:15  Health Break
     *     09:15–10:15  Slot 3
     *     10:15–11:15  Slot 4
     *     11:15–12:15  Slot 5
     *
     *   GRADE 12 — AFTERNOON SHIFT
     *     13:00–14:00  Slot 1
     *     14:00–15:00  Slot 2
     *     15:00–15:15  Health Break
     *     15:15–16:15  Slot 3
     *     16:15–17:15  Slot 4
     *     17:15–18:15  Slot 5
     */
    public function run(): void
    {
        $rooms = Room::pluck('id')->all();
        if (empty($rooms)) {
            $this->command->warn('⚠ No rooms exist — skipping schedules.');
            return;
        }

        $morningSlots = [
            ['07:00:00', '08:00:00'],
            ['08:00:00', '09:00:00'],
            ['09:15:00', '10:15:00'],   // after 09:00–09:15 Health Break
            ['10:15:00', '11:15:00'],
            ['11:15:00', '12:15:00'],
        ];

        $afternoonSlots = [
            ['13:00:00', '14:00:00'],
            ['14:00:00', '15:00:00'],
            ['15:15:00', '16:15:00'],   // after 15:00–15:15 Health Break
            ['16:15:00', '17:15:00'],
            ['17:15:00', '18:15:00'],
        ];

        // Core subjects meet Mon–Thu. Friday is HGP / electives only.
        $coreMeetingDays    = ['monday', 'tuesday', 'wednesday', 'thursday'];
        $electiveMeetingDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        $sections = Section::orderBy('id')->get();

        $coreSlots     = 0;
        $electiveSlots = 0;

        foreach ($sections as $sIdx => $section) {
            $classes = ClassRoom::with('subject')
                ->where('section_id', $section->id)
                ->orderBy('id')
                ->get()
                ->values();

            if ($classes->isEmpty()) {
                continue;
            }

            $isMorning = $section->grade_level === '11';
            $slots     = $isMorning ? $morningSlots : $afternoonSlots;

            // One home room per section — no rotating rooms across days.
            $homeRoomId = $rooms[$sIdx % count($rooms)];

            $coreIdx     = 0;
            $electiveIdx = 0;

            foreach ($classes as $class) {
                $isCore = (bool) $class->subject?->is_core;

                if ($isCore) {
                    // Core subjects get a fixed slot Mon–Thu.
                    $slotIdx = $coreIdx % count($slots);
                    [$start, $end] = $slots[$slotIdx];
                    $days = $coreMeetingDays;
                    $coreIdx++;
                } else {
                    // Electives get a fixed slot. If the section has more
                    // electives than remaining slots, wrap around — schools
                    // normally cap electives so this is an edge case.
                    $slotIdx = ($coreIdx + $electiveIdx) % count($slots);
                    [$start, $end] = $slots[$slotIdx];
                    $days = $electiveMeetingDays;
                    $electiveIdx++;
                }

                foreach ($days as $day) {
                    DB::table('class_schedules')->updateOrInsert(
                        [
                            'class_id'    => $class->id,
                            'day_of_week' => $day,
                            'time_start'  => $start,
                        ],
                        [
                            'room_id'    => $homeRoomId,
                            'time_end'   => $end,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                    $isCore ? $coreSlots++ : $electiveSlots++;
                }
            }
        }

        $this->command->info('✅ Class schedules seeded:');
        $this->command->info("   {$coreSlots} core-subject slots (Mon–Thu, fixed time per subject)");
        $this->command->info("   {$electiveSlots} elective slots (Mon–Fri)");
        $this->command->info('   Sample pattern:');
        $this->command->info('     G11 AM  07:00–12:15  (5 one-hour slots + 15-min break)');
        $this->command->info('     G12 PM  13:00–18:15  (5 one-hour slots + 15-min break)');
    }
}