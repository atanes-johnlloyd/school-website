<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        $sex = fake()->randomElement(['male', 'female']);
        $firstName = $sex === 'male' ? fake()->firstNameMale() : fake()->firstNameFemale();
        $lastName  = fake()->lastName();

        return [
            'user_id' => User::factory()->create([
                'name'  => "$firstName $lastName",
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'must_change_password' => false,
            ])->id,
            'employee_no'    => 'EMP-' . fake()->unique()->numerify('#####'),
            'sex'            => $sex,
            'date_of_birth'  => fake()->dateTimeBetween('-55 years', '-25 years'),
            'contact_number' => '09' . fake()->numerify('#########'),
            'date_hired'     => fake()->dateTimeBetween('-10 years', 'now'),
            'department'     => fake()->randomElement([
                'Languages', 'Mathematics', 'Sciences', 'Social Studies',
                'ICT', 'PE and Health', 'Humanities',
            ]),
            'specialization' => fake()->randomElement([
                'English', 'Filipino', 'Math', 'Physics', 'Chemistry',
                'Biology', 'History', 'Programming', 'Cookery',
            ]),
            'is_active' => true,
        ];
    }
}