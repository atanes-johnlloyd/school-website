<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = Room::pluck('id')->all();
        if (empty($rooms)) {
            $this->command->warn('⚠ No rooms exist — skipping schedules.');
            return;
        }

        // 4 standard period slots per day.
        $slots = [
            ['07:30:00', '08:30:00'],
            ['08:30:00', '09:30:00'],
            ['10:00:00', '11:00:00'],
            ['13:00:00', '14:00:00'],
            ['14:00:00', '15:00:00'],
        ];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        $classes = ClassRoom::orderBy('id')->get();

        // Group by section so we don't double-book the same section at the same time.
        $sectionBooked = [];
        $classCount = 0;

        foreach ($classes as $class) {
            $sid = $class->section_id;

            // pick a slot not yet used by this section
            $slotIndex = null;
            for ($i = 0; $i < count($slots); $i++) {
                if (! in_array($i, $sectionBooked[$sid] ?? [])) {
                    $slotIndex = $i;
                    break;
                }
            }
            if ($slotIndex === null) continue; // section is full for the day

            // pick a day that section hasn't used for that slot
            $day = $days[($class->id + $slotIndex) % count($days)];

            DB::table('class_schedules')->updateOrInsert(
                [
                    'class_id'    => $class->id,
                    'day_of_week' => $day,
                    'time_start'  => $slots[$slotIndex][0],
                ],
                [
                    'room_id'    => $rooms[($class->id + $slotIndex) % count($rooms)],
                    'time_end'   => $slots[$slotIndex][1],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $sectionBooked[$sid][] = $slotIndex;
            $classCount++;
        }

        $this->command->info("✅ Class schedules seeded: {$classCount} slots across all sections");
    }
}