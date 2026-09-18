<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class StudentFactory extends Factory
{
    protected $model = Student::class;

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
            'lrn'            => fake()->unique()->numerify('############'),
            'sex'            => $sex,
            'date_of_birth'  => fake()->dateTimeBetween('-18 years', '-15 years'),
            'contact_number' => '09' . fake()->numerify('#########'),
            'house_street'   => fake()->streetAddress(),
            'barangay'       => fake()->randomElement([
                'Barangay Salawag', 'Barangay Paliparan I', 'Barangay Paliparan II',
                'Barangay Paliparan III', 'Barangay San Agustin',
            ]),
            'municipality'   => 'Dasmariñas',
            'province'       => 'Cavite',
            'zip_code'       => '4114',
            'status'         => 'active',
        ];
    }
}