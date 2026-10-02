<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\ClassStudent;
use App\Models\Enrollment;
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

        // ─── Assign test teacher to ALL classes in a fixed section ───
        // Picks the first section that already has classes (created by
        // DemoDataSeeder). This guarantees teacher@test.com has a full
        // roster to work with.
        $targetSection = Section::whereHas('classroom')
            ->orderBy('id')
            ->first();

        $testClasses = collect();

        if ($targetSection) {
            $testClasses = ClassRoom::where('section_id', $targetSection->id)->get();
            foreach ($testClasses as $classroom) {
                $classroom->update(['teacher_id' => $teacher->id]);
            }
        } else {
            $this->command->warn('⚠ No section with classes found. Run DemoDataSeeder first.');
        }

        // ═══════════════════════════════════════════════════════════
        // STUDENT — enrolled in the SAME section as test teacher's classes
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

        if ($targetSection) {
            // Enrollment record
            Enrollment::updateOrCreate(
                [
                    'student_id'     => $student->id,
                    'school_year_id' => $targetSection->school_year_id,
                ],
                [
                    'section_id'  => $targetSection->id,
                    'status'      => 'enrolled',
                    'enrolled_at' => now(),
                    'enrolled_by' => $admin->id,
                ]
            );

            // Attach to every class in the section (including test teacher's)
            $sectionClasses = ClassRoom::where('section_id', $targetSection->id)->get();
            foreach ($sectionClasses as $classroom) {
                ClassStudent::updateOrCreate(
                    ['class_id' => $classroom->id, 'student_id' => $student->id],
                    ['status' => 'active', 'enrolled_at' => now()]
                );
            }
        }

        $this->command->info('✅ Test users ready:');
        $this->command->info('   admin@test.com   / password');
        $this->command->info('   teacher@test.com / password');
        $this->command->info('   student@test.com / password');
        $this->command->info('   → Test teacher assigned to ' . $testClasses->count()
            . ' class(es) in section "' . ($targetSection?->name ?? 'N/A') . '"');
        $this->command->info('   → Test student enrolled in same section');
    }
}