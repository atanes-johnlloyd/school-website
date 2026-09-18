<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // ═══════════════════════════════════════════════════════════
        // ADMIN
        // ═══════════════════════════════════════════════════════════
        $admin = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name'                 => 'Test Admin',
                'password'             => Hash::make('password'),
                'email_verified_at'    => now(),
                'must_change_password' => false,
                'admin_position_id'    => \App\Models\AdminPosition::where('name', 'System Admin')->value('id'),
            ]
        );
        $admin->syncRoles(['admin']);

        // ═══════════════════════════════════════════════════════════
        // TEACHER
        // ═══════════════════════════════════════════════════════════
        $teacherUser = User::updateOrCreate(
            ['email' => 'teacher@test.com'],
            [
                'name'                 => 'Test Teacher',
                'password'             => Hash::make('password'),
                'email_verified_at'    => now(),
                'must_change_password' => false,
            ]
        );
        $teacherUser->syncRoles(['teacher']);

        // Create the teacher profile if missing
        $teacher = Teacher::updateOrCreate(
            ['user_id' => $teacherUser->id],
            [
                'employee_no'    => 'EMP-TEST-001',
                'sex'            => 'female',
                'date_of_birth'  => '1990-05-15',
                'contact_number' => '09171234567',
                'date_hired'     => '2020-06-01',
                'department'     => 'Sciences',
                'specialization' => 'General Science',
                'is_active'      => true,
            ]
        );

        // Assign this teacher to a couple of classes so they have something to see
        $classes = \App\Models\ClassRoom::inRandomOrder()->take(3)->get();
        foreach ($classes as $classroom) {
            $classroom->update(['teacher_id' => $teacher->id]);
        }

        // ═══════════════════════════════════════════════════════════
        // STUDENT
        // ═══════════════════════════════════════════════════════════
        $studentUser = User::updateOrCreate(
            ['email' => 'student@test.com'],
            [
                'name'                 => 'Test Student',
                'password'             => Hash::make('password'),
                'email_verified_at'    => now(),
                'must_change_password' => false,
            ]
        );
        $studentUser->syncRoles(['student']);

        // Create the student profile if missing
        $student = Student::updateOrCreate(
            ['user_id' => $studentUser->id],
            [
                'lrn'            => '123456789012',
                'sex'            => 'male',
                'date_of_birth'  => '2009-03-20',
                'contact_number' => '09181234567',
                'house_street'   => 'Blk 5 Lot 12',
                'barangay'       => 'Barangay Salawag',
                'municipality'   => 'Dasmariñas',
                'province'       => 'Cavite',
                'zip_code'       => '4114',
                'status'         => 'active',
            ]
        );

        // Enroll this student in the same section as one of the teacher's classes
        $targetClass = $classes->first();
        if ($targetClass) {
            $section = Section::find($targetClass->section_id);

            if ($section) {
                // Enrollment record for the year
                \App\Models\Enrollment::updateOrCreate(
                    [
                        'student_id'     => $student->id,
                        'school_year_id' => $section->school_year_id,
                    ],
                    [
                        'section_id'  => $section->id,
                        'status'      => 'enrolled',
                        'enrolled_at' => now(),
                    ]
                );

                // Attach to every class in that section
                $sectionClasses = \App\Models\ClassRoom::where('section_id', $section->id)->get();
                foreach ($sectionClasses as $classroom) {
                    \App\Models\ClassStudent::updateOrCreate(
                        ['class_id' => $classroom->id, 'student_id' => $student->id],
                        ['status' => 'active', 'enrolled_at' => now()]
                    );
                }
            }
        }

        $this->command->info('✅ Test users ready:');
        $this->command->info('   admin@test.com   / password');
        $this->command->info('   teacher@test.com / password');
        $this->command->info('   student@test.com / password');
    }
}