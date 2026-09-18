<?php

namespace Database\Factories;

use App\Models\Section;
use App\Models\Strand;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class SectionFactory extends Factory
{
    protected $model = Section::class;

    public function definition(): array
    {
        // Prefer 11 or 12
        $gradeLevel = fake()->randomElement(['11', '12']);
        $letter     = fake()->randomElement(['A', 'B', 'C']);

        return [
            'school_year_id' => SchoolYear::where('is_active', true)->first()?->id
                                ?? SchoolYear::factory()->create()->id,
            'strand_id'      => Strand::inRandomOrder()->first()?->id,
            'grade_level'    => $gradeLevel,
            'name'           => "Grade {$gradeLevel} - {$letter}",
            'adviser_id'     => Teacher::inRandomOrder()->first()?->id,
            'max_capacity'   => 40,
        ];
    }
}