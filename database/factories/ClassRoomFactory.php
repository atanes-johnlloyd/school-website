<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassRoomFactory extends Factory
{
    protected $model = ClassRoom::class;

    public function definition(): array
    {
        return [
            'subject_id' => Subject::inRandomOrder()->first()?->id
                            ?? Subject::factory()->create()->id,
            'section_id' => Section::inRandomOrder()->first()?->id
                            ?? Section::factory()->create()->id,
            'teacher_id' => Teacher::inRandomOrder()->first()?->id
                            ?? Teacher::factory()->create()->id,
            'term_id'    => Term::where('is_active', true)->first()?->id
                            ?? Term::factory()->create()->id,
            'weight_written_work'     => 25.00,
            'weight_performance_task' => 50.00,
            'weight_quarterly_exam'   => 25.00,
            'is_published'            => true,
        ];
    }
}