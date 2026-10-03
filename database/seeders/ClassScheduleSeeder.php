<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Room;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassScheduleSeeder extends Seeder
{
    /**
     * Realistic Philippine SHS bell schedule.
     *
     * GRADE 11 — MORNING SHIFT
     *   07:30 – 08:30  Period 1
     *   08:30 – 09:30  Period 2
     *   09:30 – 09:45  Recess (15 min)
     *   09:45 – 10:45  Period 3
     *   10:45 – 11:45  Period 4
     *   11:45 – 12:00  Homeroom / Advisory
     *
     * GRADE 12 — AFTERNOON SHIFT
     *   13:00 – 14:00  Period 1
     *   14:00 – 15:00  Period 2
     *   15:00 – 15:15  Recess (15 min)
     *   15:15 – 16:15  Period 3
     *   16:15 – 17:15  Period 4
     *   17:15 – 17:30  Homeroom / Advisory
     *
     * Each section has 5 classes (one per core/strand subject). Across
     * 5 weekdays × 4 periods = 20 slots, each class meets exactly 4×
     * per week using a staggered Latin-square rotation — so no class
     * doubles up on the same day, and every subject appears in the
     * "Today's Schedule" widget at least once during the week.
     */
    public function run(): void
    {
        $rooms = Room::pluck('id')->all();
        if (empty($rooms)) {
            $this->command->warn('⚠ No rooms exist — skipping schedules.');
            return;
        }

        $morningPeriods = [
            ['07:30:00', '08:30:00'],
            ['08:30:00', '09:30:00'],
            ['09:45:00', '10:45:00'],   // after 15-min recess
            ['10:45:00', '11:45:00'],
        ];

        $afternoonPeriods = [
            ['13:00:00', '14:00:00'],
            ['14:00:00', '15:00:00'],
            ['15:15:00', '16:15:00'],   // after 15-min recess
            ['16:15:00', '17:15:00'],
        ];

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        $sections = Section::orderBy('id')->get();
        $totalSlots = 0;
        $morningSections = 0;
        $afternoonSections = 0;

        foreach ($sections as $sIdx => $section) {
            $classes = ClassRoom::where('section_id', $section->id)
                ->orderBy('id')
                ->get()
                ->values();

            if ($classes->isEmpty()) {
                continue;
            }

            // Grade 11 = morning shift, Grade 12 = afternoon shift.
            $isMorning = $section->grade_level === '11';
            $periods   = $isMorning ? $morningPeriods : $afternoonPeriods;

            if ($isMorning) {
                $morningSections++;
            } else {
                $afternoonSections++;
            }

            $rotation = $this->buildWeeklyRotation($classes->count());

            foreach ($days as $dayIdx => $day) {
                $daySlots = $rotation[$dayIdx] ?? [];

                foreach ($daySlots as $pIdx => $classIdx) {
                    $class = $classes[$classIdx] ?? null;
                    if (! $class || ! isset($periods[$pIdx])) {
                        continue;
                    }

                    [$start, $end] = $periods[$pIdx];

                    // Deterministic room rotation — avoids double-booking
                    // the same room in the same slot across sections.
                    $roomId = $rooms[($sIdx * 4 + $pIdx + $dayIdx) % count($rooms)];

                    DB::table('class_schedules')->updateOrInsert(
                        [
                            'class_id'    => $class->id,
                            'day_of_week' => $day,
                            'time_start'  => $start,
                        ],
                        [
                            'room_id'    => $roomId,
                            'time_end'   => $end,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                    $totalSlots++;
                }
            }
        }

        $this->command->info("✅ Class schedules seeded: {$totalSlots} slots");
        $this->command->info("   Grade 11 (morning):   {$morningSections} sections @ 07:30–12:00");
        $this->command->info("   Grade 12 (afternoon): {$afternoonSections} sections @ 13:00–17:30");
    }

    /**
     * Build a 5-day, 4-period rotation for N classes.
     *
     *   • Every class meets exactly 4× per week when N = 5.
     *   • No two periods in the same day share the same class.
     *   • Every period slot is used every day (no empty rows).
     *
     * Formula `(day*4 + p) % N` naturally produces a Latin-square
     * rotation for any N, and is exact for the 5-class common case.
     */
    private function buildWeeklyRotation(int $numClasses): array
    {
        if ($numClasses < 1) {
            return [];
        }

        $grid = [];
        for ($day = 0; $day < 5; $day++) {
            $dayRow = [];
            for ($p = 0; $p < 4; $p++) {
                $dayRow[] = ($day * 4 + $p) % $numClasses;
            }
            $grid[] = $dayRow;
        }

        return $grid;
    }
}