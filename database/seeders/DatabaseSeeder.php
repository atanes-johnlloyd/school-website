<?php

namespace Database\Seeders;

use App\Models\AdminPosition;
use App\Models\Room;
use App\Models\SchoolYear;
use App\Models\Strand;
use App\Models\Subject;
use App\Models\Term;
use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(AdminPositionSeeder::class);

        // ------------------------------------------------------------
        // School Year & Terms
        // ------------------------------------------------------------
        $sy = SchoolYear::firstOrCreate(
            ['label' => '2026-2027'],
            ['start_date' => '2026-08-03', 'end_date' => '2027-04-10', 'is_active' => true]
        );

        Term::firstOrCreate(
            ['school_year_id' => $sy->id, 'name' => '1st Semester'],
            ['start_date' => '2026-08-03', 'end_date' => '2026-12-19', 'is_active' => true]
        );

        Term::firstOrCreate(
            ['school_year_id' => $sy->id, 'name' => '2nd Semester'],
            ['start_date' => '2027-01-05', 'end_date' => '2027-04-10', 'is_active' => false]
        );

        // ------------------------------------------------------------
        // Tracks — only two under the Strengthened SHS Curriculum
        // (DepEd Memorandum No. 012, s. 2026)
        // ------------------------------------------------------------
        $academic = Track::firstOrCreate(
            ['code' => 'ACAD'],
            [
                'name' => 'Academic Track',
                'description' => 'Prepares learners for college/university education.',
                'is_active' => true,
            ]
        );

        $techpro = Track::firstOrCreate(
            ['code' => 'TECHPRO'],
            [
                'name' => 'Technical-Professional Track',
                'description' => 'Merges the former TVL, Arts & Design, and Sports tracks.',
                'is_active' => true,
            ]
        );

        $academicClusters = [
            ['ARTS-SOC-HUM', 'Arts, Social Sciences, and Humanities'],
            ['STEM', 'Science, Technology, Engineering, and Mathematics'],
            ['SPORTS-WELLNESS', 'Sports, Health, and Wellness'],
            ['BUSINESS-ENTREP', 'Business and Entrepreneurship'],
            ['FIELD-EXP', 'Field Experience'],
        ];
        foreach ($academicClusters as [$code, $name]) {
            Strand::firstOrCreate(
                ['code' => $code],
                ['track_id' => $academic->id, 'name' => $name, 'is_active' => true]
            );
        }

        $techproClusters = [
            ['AESTHETIC-WELLNESS', 'Aesthetic, Wellness, and Human Care'],
            ['AGRI-FISHERY', 'Agri-Fishery Business and Food Innovation'],
            ['ARTISANRY', 'Artisanry and Creative Enterprise'],
            ['AUTOMOTIVE', 'Automotive and Small Engine Technologies'],
            ['CONSTRUCTION', 'Construction and Building Technologies'],
            ['CREATIVE-ARTS', 'Creative Arts and Design Technologies'],
            ['HOSPITALITY', 'Hospitality and Tourism'],
            ['INDUSTRIAL', 'Industrial Technologies'],
            ['ICT', 'ICT Support and Computer Programming Technologies'],
            ['MARITIME', 'Maritime Transport'],
        ];
        foreach ($techproClusters as [$code, $name]) {
            Strand::firstOrCreate(
                ['code' => $code],
                ['track_id' => $techpro->id, 'name' => $name, 'is_active' => true]
            );
        }

        // ------------------------------------------------------------
        // The 5 Grade 11 core subjects
        // ------------------------------------------------------------
        $core = [
            ['CORE-COMM', 'Effective Communication / Mabisang Komunikasyon'],
            ['CORE-MATH', 'General Mathematics'],
            ['CORE-SCI', 'General Science'],
            ['CORE-LCS', 'Life and Career Skills'],
            ['CORE-KASP', 'Pag-aaral ng Kasaysayan at Lipunang Pilipino'],
        ];
        foreach ($core as [$code, $name]) {
            Subject::firstOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'strand_id' => null,
                    'grade_level' => '11',
                    'is_core' => true,
                    'hours' => 160,
                    'is_active' => true,
                ]
            );
        }

        // ------------------------------------------------------------
        // Starter electives (illustrative — complete via Subject Management)
        // ------------------------------------------------------------
        $electiveSeed = [
            'STEM' => [['STEM-BIO', 'General Biology'], ['STEM-PCALC', 'Pre-Calculus']],
            'ARTS-SOC-HUM' => [['ASH-CREATW1', 'Creative Composition 1'], ['ASH-CITZ', 'Citizenship and Civic Engagement']],
            'BUSINESS-ENTREP' => [['BE-FIN', 'Business Finance'], ['BE-ENTREP', 'Entrepreneurship']],
            'ICT' => [['ICT-PROG', 'Computer Programming'], ['ICT-CSS', 'Computer Systems Servicing']],
            'HOSPITALITY' => [['HOSP-COOK', 'Cookery'], ['HOSP-FBS', 'Food and Beverage Services']],
        ];
        foreach ($electiveSeed as $clusterCode => $subjects) {
            $strand = Strand::where('code', $clusterCode)->first();
            foreach ($subjects as [$code, $name]) {
                Subject::firstOrCreate(
                    ['code' => $code],
                    [
                        'name' => $name,
                        'strand_id' => $strand?->id,
                        'grade_level' => 'both',
                        'is_core' => false,
                        'hours' => 80,
                        'is_active' => true,
                    ]
                );
            }
        }

        // ------------------------------------------------------------
        // Rooms
        // ------------------------------------------------------------
        $rooms = [
            ['R-101', 'Room 101', 'regular'],
            ['R-102', 'Room 102', 'regular'],
            ['LAB-SCI1', 'Science Laboratory 1', 'science_lab'],
            ['LAB-COMP1', 'Computer Laboratory 1', 'computer_lab'],
        ];
        foreach ($rooms as [$code, $name, $type]) {
            Room::firstOrCreate(
                ['code' => $code],
                ['name' => $name, 'type' => $type, 'capacity' => 40, 'is_active' => true]
            );
        }

        // ------------------------------------------------------------
        // Initial admin account — role "admin" + "System Admin" position,
        // which grants the full permission preset. This is the pattern
        // every future admin account creation should follow:
        //   1. assignRole('admin')
        //   2. attach an admin_position_id
        //   3. givePermissionTo($position->default_permissions)
        // ------------------------------------------------------------
        $systemAdminPosition = AdminPosition::where('name', 'System Admin')->first();

        $admin = User::firstOrCreate(
            ['email' => 'atanes.johnlloyd@ncst.edu.ph'],
            [
                'name' => 'John Lloyd Atanes',
                'password' => Hash::make('ChangeMe!2026'),
                'must_change_password' => true,
                'email_verified_at' => now(),
                'admin_position_id' => $systemAdminPosition?->id,
            ]
        );

        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        if ($systemAdminPosition) {
            $admin->syncPermissions($systemAdminPosition->default_permissions);
        }

        $this->call(DemoDataSeeder::class);
        $this->call(TestUsersSeeder::class);
    }
}
