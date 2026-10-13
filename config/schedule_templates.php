<?php

/*
|--------------------------------------------------------------------------
| Grade-level schedule templates
|--------------------------------------------------------------------------
|
| A slot is a 60-minute unit. Subjects that need longer sessions claim
| adjacent slots. The recess window is a hard break — no block may cross
| it.
|
| recess_after_slot points at the slot index the recess sits after.
| Slot 0 → recess → Slot 2 means recess_after_slot = 1.
|
*/

return [

    'grade_11' => [
        'shift'    => 'morning',
        'earliest' => '06:00',
        'latest'   => '12:15',
        'recess'   => ['08:00', '08:15'],
        'days'     => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],

        'slots' => [
            ['index' => 0, 'start' => '06:00', 'end' => '07:00'],
            ['index' => 1, 'start' => '07:00', 'end' => '08:00'],
            // ── recess ──
            ['index' => 2, 'start' => '08:15', 'end' => '09:15'],
            ['index' => 3, 'start' => '09:15', 'end' => '10:15'],
            ['index' => 4, 'start' => '10:15', 'end' => '11:15'],
            ['index' => 5, 'start' => '11:15', 'end' => '12:15'],
        ],

        'recess_after_slot' => 1,
        'total_slots'       => 6,
    ],

    'grade_12' => [
        'shift'    => 'afternoon',
        'earliest' => '12:30',
        'latest'   => '18:45',
        'recess'   => ['14:30', '14:45'],
        'days'     => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],

        'slots' => [
            ['index' => 0, 'start' => '12:30', 'end' => '13:30'],
            ['index' => 1, 'start' => '13:30', 'end' => '14:30'],
            // ── recess ──
            ['index' => 2, 'start' => '14:45', 'end' => '15:45'],
            ['index' => 3, 'start' => '15:45', 'end' => '16:45'],
            ['index' => 4, 'start' => '16:45', 'end' => '17:45'],
            ['index' => 5, 'start' => '17:45', 'end' => '18:45'],
        ],

        'recess_after_slot' => 1,
        'total_slots'       => 6,
    ],

];