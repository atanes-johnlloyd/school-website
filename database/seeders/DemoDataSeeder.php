<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\Term;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $activeYear = SchoolYear::where('is_active', true)->firstOrFail();

        // ---------- TEACHERS ----------
        $teachers = Teacher::factory()->count(8)->create();
        foreach ($teachers as $teacher) {
            $teacher->user->assignRole('teacher');
        }
        $this->command->info("✅ Created " . $teachers->count() . " teachers");

        // ---------- STUDENTS ----------
        $students = Student::factory()->count(60)->create();
        foreach ($students as $student) {
            $student->user->assignRole('student');
        }
        $this->command->info("✅ Created " . $students->count() . " students");

        // ---------- SECTIONS ----------
        // Create 2 sections per grade level per active strand (limit for demo)
        $strandsToUse = \App\Models\Strand::whereIn('code', ['STEM', 'ICT', 'HOSPITALITY', 'BUSINESS-ENTREP'])
                                          ->get();

        $sections = collect();
        foreach ($strandsToUse as $strand) {
            foreach (['11', '12'] as $grade) {
                $sections->push(Section::create([
                    'school_year_id' => $activeYear->id,
                    'strand_id'      => $strand->id,
                    'grade_level'    => $grade,
                    'name'           => "Grade {$grade} - {$strand->code}",
                    'adviser_id'     => $teachers->random()->id,
                    'max_capacity'   => 40,
                ]));
            }
        }
        $this->command->info("✅ Created " . $sections->count() . " sections");

        // ---------- CLASSES (ClassRoom) ----------
        $activeTerm  = Term::where('is_active', true)->firstOrFail();
        $coreSubjects = \App\Models\Subject::where('is_core', true)->get();
        $strandSubjects = \App\Models\Subject::whereNotNull('strand_id')->get();

        $classCount = 0;
        foreach ($sections as $section) {
            // Every section gets all core subjects
            $subjectsForSection = $coreSubjects->merge(
                $strandSubjects->where('strand_id', $section->strand_id)
            );

            foreach ($subjectsForSection as $subject) {
                ClassRoom::create([
                    'subject_id'              => $subject->id,
                    'section_id'              => $section->id,
                    'teacher_id'              => $teachers->random()->id,
                    'term_id'                 => $activeTerm->id,
                    'weight_written_work'     => 25.00,
                    'weight_performance_task' => 50.00,
                    'weight_quarterly_exam'   => 25.00,
                    'is_published'            => true,
                ]);
                $classCount++;
            }
        }
        $this->command->info("✅ Created {$classCount} classes");

        // ---------- ENROLL STUDENTS ----------
        // Distribute 60 students across the sections
        $enrolled = 0;
        foreach ($students as $student) {
            $section = $sections->random();

            // Enrollment record for the school year
            \App\Models\Enrollment::create([
                'student_id'     => $student->id,
                'school_year_id' => $activeYear->id,
                'section_id'     => $section->id,
                'status'         => 'enrolled',
                'enrolled_at'    => now(),
                'enrolled_by'    => User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->first()?->id,
            ]);

            // Attach student to the section's classes
            $sectionClasses = ClassRoom::where('section_id', $section->id)->get();
            foreach ($sectionClasses as $class) {
                \App\Models\ClassStudent::create([
                    'class_id'    => $class->id,
                    'student_id'  => $student->id,
                    'status'      => 'active',
                    'enrolled_at' => now(),
                ]);
            }

            $enrolled++;
        }
        $this->command->info("✅ Enrolled {$enrolled} students into sections and classes");
    }
}