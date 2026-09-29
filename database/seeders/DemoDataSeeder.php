<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Strand;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Term;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $year = SchoolYear::where('is_active', true)->firstOrFail();
        $term = Term::where('is_active', true)->firstOrFail();

        // ─── TEACHERS ─────────────────────────────────────
        $teacherData = [
            ['Maria', 'Rivera',    'Mathematics',      'Algebra and Geometry'],
            ['Jose',  'Santos',    'Science',          'Biology and Chemistry'],
            ['Luzviminda', 'Cruz', 'English',          'English Language and Literature'],
            ['Ramon', 'Gonzales',  'ICT',              'Computer Programming'],
            ['Teresita', 'Reyes',  'Business',         'Accountancy and Finance'],
            ['Eduardo', 'Mendoza', 'Mathematics',      'Statistics and Calculus'],
            ['Corazon', 'Dela Cruz', 'Social Studies', 'Philippine History'],
            ['Nestor', 'Fernandez', 'TVL',             'Cookery and Food Service'],
            ['Gloria', 'Lopez',    'Filipino',         'Wikang Filipino'],
            ['Rogelio', 'Garcia',  'PE and Health',    'Physical Education'],
            ['Elena', 'Villanueva', 'Science',         'Chemistry'],
            ['Fernando', 'Torres', 'Science',          'Physics'],
            ['Lourdes', 'Aquino',  'Business',         'Entrepreneurship'],
            ['Benjamin', 'Castillo', 'TVL',            'Computer Systems Servicing'],
            ['Victoria', 'Romero', 'Arts',             'Visual Arts'],
            ['Robert', 'Atanes',   'TVL',              'Computer Programming'],
        ];

        $teachers = collect();
        foreach ($teacherData as $i => [$first, $last, $dept, $spec]) {
            $empNo = 'T-2026-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
            $user = User::create([
                'name'                 => "{$first} {$last}",
                'email'                => strtolower($first . '.' . $last . '@deped.gov.ph'),
                'password'             => Hash::make('password'),
                'must_change_password' => false,
                'email_verified_at'    => now(),
            ]);
            $user->assignRole('teacher');

            $teachers->push(Teacher::create([
                'user_id'        => $user->id,
                'employee_no'    => $empNo,
                'sex'            => in_array($first, ['Maria', 'Luzviminda', 'Teresita', 'Corazon', 'Gloria', 'Elena', 'Lourdes', 'Victoria']) ? 'female' : 'male',
                'date_of_birth'  => now()->subYears(rand(30, 50))->subDays(rand(0, 365)),
                'contact_number' => '0917' . str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT),
                'date_hired'     => now()->subYears(rand(1, 10)),
                'department'     => $dept,
                'specialization' => $spec,
                'is_active'      => true,
            ]));
        }

        // ─── SECTIONS ─────────────────────────────────────
        $strands = Strand::whereIn('code', [
            'STEM', 'ICT', 'HOSPITALITY', 'BUSINESS-ENTREP', 'ARTS-SOC-HUM',
        ])->get();

        $sections = collect();
        foreach ($strands as $strand) {
            foreach (['11', '12'] as $grade) {
                $letter = 'A';
                $sections->push(Section::create([
                    'school_year_id' => $year->id,
                    'strand_id'      => $strand->id,
                    'grade_level'    => $grade,
                    'name'           => "Grade {$grade} - {$strand->code} {$letter}",
                    'adviser_id'     => $teachers->random()->id,
                    'max_capacity'   => 40,
                ]));
            }
        }

        // ─── STUDENT NAMES (Filipino) ─────────────────────
        $firstNames = [
            'male'   => ['Juan', 'Jose', 'Pedro', 'Mark', 'John Paul', 'Carlo', 'Angelo', 'Rafael', 'Miguel', 'Gabriel', 'Emilio', 'Andres', 'Rico', 'Rogelio', 'Antonio'],
            'female' => ['Maria', 'Ana', 'Rosa', 'Andrea', 'Sofia', 'Isabella', 'Camila', 'Bianca', 'Angelica', 'Jasmine', 'Kristine', 'Liza', 'Maricel', 'Rosario', 'Josefina'],
        ];
        $lastNames = ['Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Mendoza', 'Torres', 'Villanueva', 'Aquino', 'Castillo', 'Ramos', 'Domingo', 'Navarro', 'Salazar', 'Rivera', 'Pascual', 'Del Rosario', 'Lim', 'Tan'];

        // ─── STUDENTS ─────────────────────────────────────
        $students = collect();
        for ($i = 0; $i < 120; $i++) {
            $sex      = rand(0, 1) ? 'male' : 'female';
            $first    = $firstNames[$sex][array_rand($firstNames[$sex])];
            $last     = $lastNames[array_rand($lastNames)];

            $user = User::create([
                'name'                 => "{$first} {$last}",
                'email'                => strtolower($first . '.' . $last . $i . '@student.deped.gov.ph'),
                'password'             => Hash::make('password'),
                'must_change_password' => false,
                'email_verified_at'    => now(),
            ]);
            $user->assignRole('student');

            $students->push(Student::create([
                'user_id'        => $user->id,
                'lrn'            => '1234' . str_pad((string) ($i + 1000), 8, '0', STR_PAD_LEFT),
                'sex'            => $sex,
                'date_of_birth'  => now()->subYears(rand(15, 18))->subDays(rand(0, 365)),
                'contact_number' => '0918' . str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT),
                'house_street'   => 'Block ' . rand(1, 20) . ' Lot ' . rand(1, 30),
                'barangay'       => 'Barangay Salawag',
                'municipality'   => 'Dasmariñas',
                'province'       => 'Cavite',
                'zip_code'       => '4114',
                'status'         => 'active',
            ]));
        }

        // ─── CLASSES (Klass) ──────────────────────────────
        $coreSubjects   = Subject::where('is_core', true)->get();
        $strandSubjects = Subject::whereNotNull('strand_id')->get();

        $classrooms = collect();
        foreach ($sections as $section) {
            $subjectsForSection = $coreSubjects->merge(
                $strandSubjects->where('strand_id', $section->strand_id)
            )->take(5);   // limit for demo performance

            foreach ($subjectsForSection as $subject) {
                $classrooms->push(ClassRoom::create([
                    'subject_id'              => $subject->id,
                    'section_id'              => $section->id,
                    'teacher_id'              => $teachers->random()->id,
                    'term_id'                 => $term->id,
                    'weight_written_work'     => 25.00,
                    'weight_performance_task' => 50.00,
                    'weight_quarterly_exam'   => 25.00,
                    'is_published'            => true,
                ]));
            }
        }

        // ─── ENROLL STUDENTS ──────────────────────────────
        $sectionPool = $sections->all();
        foreach ($students as $student) {
            $section = $sectionPool[array_rand($sectionPool)];

            Enrollment::create([
                'student_id'     => $student->id,
                'school_year_id' => $year->id,
                'section_id'     => $section->id,
                'status'         => 'enrolled',
                'enrolled_at'    => now(),
                'enrolled_by'    => 1,
            ]);

            $sectionClasses = ClassRoom::where('section_id', $section->id)->get();
            foreach ($sectionClasses as $class) {
                \App\Models\ClassStudent::create([
                    'class_id'    => $class->id,
                    'student_id'  => $student->id,
                    'status'      => 'active',
                    'enrolled_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ Realistic demo data seeded:');
        $this->command->info('   ' . $teachers->count() . ' teachers');
        $this->command->info('   ' . $students->count() . ' students');
        $this->command->info('   ' . $sections->count() . ' sections');
        $this->command->info('   ' . $classrooms->count() . ' classes');
    }
}