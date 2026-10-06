<?php

namespace Database\Seeders;

use App\Models\AdminPosition;
use App\Models\Room;
use App\Models\SchoolYear;
use App\Models\Strand;
use App\Models\Subject;
use App\Models\Term;
use App\Models\Track;
use Illuminate\Database\Seeder;

class ReferenceDataSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- SCHOOL YEAR ----------
        $sy = SchoolYear::updateOrCreate(
            ['label' => '2026-2027'],
            [
                'start_date' => '2026-08-03',
                'end_date'   => '2027-04-10',
                'is_active'  => true,
            ]
        );

        // ---------- TERMS ----------
        Term::updateOrCreate(
            ['school_year_id' => $sy->id, 'name' => '1st Semester'],
            [
                'start_date' => '2026-08-03',
                'end_date'   => '2026-12-19',
                'is_active'  => true,
            ]
        );
        Term::updateOrCreate(
            ['school_year_id' => $sy->id, 'name' => '2nd Semester'],
            [
                'start_date' => '2027-01-05',
                'end_date'   => '2027-04-10',
                'is_active'  => false,
            ]
        );

        // ---------- TRACKS ----------
        $acad = Track::updateOrCreate(
            ['code' => 'ACAD'],
            [
                'name'        => 'Academic Track',
                'description' => 'Prepares learners for college/university education.',
                'is_active'   => true,
            ]
        );
        $techPro = Track::updateOrCreate(
            ['code' => 'TECHPRO'],
            [
                'name'        => 'Technical-Professional Track',
                'description' => 'Merges the former TVL, Arts & Design, and Sports tracks.',
                'is_active'   => true,
            ]
        );

        // ---------- STRANDS (Academic) ----------
        $strandData = [
            [$acad->id,    'ARTS-SOC-HUM',   'Arts, Social Sciences, and Humanities'],
            [$acad->id,    'STEM',           'Science, Technology, Engineering, and Mathematics'],
            [$acad->id,    'SPORTS-WELLNESS','Sports, Health, and Wellness'],
            [$acad->id,    'BUSINESS-ENTREP','Business and Entrepreneurship'],
            [$acad->id,    'FIELD-EXP',      'Field Experience'],
            // Technical-Professional
            [$techPro->id, 'AESTHETIC-WELLNESS', 'Aesthetic, Wellness, and Human Care'],
            [$techPro->id, 'AGRI-FISHERY',       'Agri-Fishery Business and Food Innovation'],
            [$techPro->id, 'ARTISANRY',          'Artisanry and Creative Enterprise'],
            [$techPro->id, 'AUTOMOTIVE',         'Automotive and Small Engine Technologies'],
            [$techPro->id, 'CONSTRUCTION',       'Construction and Building Technologies'],
            [$techPro->id, 'CREATIVE-ARTS',      'Creative Arts and Design Technologies'],
            [$techPro->id, 'HOSPITALITY',        'Hospitality and Tourism'],
            [$techPro->id, 'INDUSTRIAL',         'Industrial Technologies'],
            [$techPro->id, 'ICT',                'ICT Support and Computer Programming Technologies'],
            [$techPro->id, 'MARITIME',           'Maritime Transport'],
        ];

        $strands = [];
        foreach ($strandData as [$trackId, $code, $name]) {
            $strands[$code] = Strand::updateOrCreate(
                ['code' => $code],
                ['track_id' => $trackId, 'name' => $name, 'is_active' => true]
            );
        }

        // ---------- SUBJECTS ----------
        $subjectData = [
            // Core subjects
            ['CORE-COMM',  'Effective Communication / Mabisang Komunikasyon', null,             '11', true],
            ['CORE-MATH',  'General Mathematics',                              null,             '11', true],
            ['CORE-SCI',   'General Science',                                  null,             '11', true],
            ['CORE-LCS',   'Life and Career Skills',                           null,             '11', true],
            ['CORE-KASP',  'Pag-aaral ng Kasaysayan at Lipunang Pilipino',     null,             '11', true],
            // Strand subjects
            ['STEM-BIO',    'General Biology',            'STEM',            'both', false],
            ['STEM-PCALC',  'Pre-Calculus',               'STEM',            'both', false],
            ['ASH-CREATW1', 'Creative Composition 1',     'ARTS-SOC-HUM',    'both', false],
            ['ASH-CITZ',    'Citizenship and Civic Engagement', 'ARTS-SOC-HUM', 'both', false],
            ['BE-FIN',      'Business Finance',           'BUSINESS-ENTREP', 'both', false],
            ['BE-ENTREP',   'Entrepreneurship',           'BUSINESS-ENTREP', 'both', false],
            ['ICT-PROG',    'Computer Programming',       'ICT',             'both', false],
            ['ICT-CSS',     'Computer Systems Servicing', 'ICT',             'both', false],
            ['HOSP-COOK',   'Cookery',                    'HOSPITALITY',     'both', false],
            ['HOSP-FBS',    'Food and Beverage Services', 'HOSPITALITY',     'both', false],
        ];

        foreach ($subjectData as [$code, $name, $strandCode, $grade, $isCore]) {
            Subject::updateOrCreate(
                ['code' => $code],
                [
                    'name'        => $name,
                    'strand_id'   => $strandCode ? $strands[$strandCode]->id : null,
                    'grade_level' => $grade,
                    'is_core'     => $isCore,
                    'hours'       => $isCore ? 160 : 80,
                    'is_active'   => true,
                ]
            );
        }

        // ---------- ROOMS ----------
        $rooms = [
            ['R-101',    'Room 101',              'regular',      40],
            ['R-102',    'Room 102',              'regular',      40],
            ['LAB-SCI1', 'Science Laboratory 1',  'science_lab',  40],
            ['LAB-COMP1','Computer Laboratory 1', 'computer_lab', 40],
        ];

        foreach ($rooms as [$code, $name, $type, $capacity]) {
            Room::updateOrCreate(
                ['code' => $code],
                ['name' => $name, 'type' => $type, 'capacity' => $capacity, 'is_active' => true]
            );
        }

        // ---------- ADMIN POSITIONS ----------
        $positions = [
            [
                'name' => 'System Admin',
                'description' => 'Full technical administrator — users, settings, backups, audit log.',
                'default_permissions' => [
                    'manage-users','manage-students','manage-teachers','manage-enrollment',
                    'manage-school-years','manage-tracks','manage-strands','manage-subjects',
                    'manage-sections','manage-rooms','manage-classes','assign-teachers',
                    'manage-class-schedules','view-reports','view-audit-log','manage-settings',
                    'manage-announcements','manage-backups', 'manage-class-schedules','view-reports','view-audit-log','manage-settings',
                    'manage-announcements','manage-backups','manage-contributions',   // ← NEW
                ],
            ],
            [
                'name' => 'Registrar',
                'description' => 'Manages student records, enrollment, and section rosters.',
                'default_permissions' => [
                    'manage-students','manage-enrollment','manage-sections','view-reports',
                ],
            ],
            [
                'name' => 'Curriculum Coordinator',
                'description' => 'Oversees curriculum, subjects, class offerings, and teacher assignment.',
                'default_permissions' => [
                    'manage-tracks','manage-strands','manage-subjects','manage-classes',
                    'assign-teachers','manage-class-schedules','manage-teachers','view-reports',
                ],
            ],
            [
                'name' => 'School Head',
                'description' => 'Principal/OIC — high-level oversight and school-wide communication.',
                'default_permissions' => [
                    'view-reports','view-audit-log','manage-announcements',
                ],
            ],
            [
                'name' => 'Staff',
                'description' => 'Read-only access to reports.',
                'default_permissions' => ['view-reports'],
            ],
        ];

        foreach ($positions as $pos) {
            AdminPosition::updateOrCreate(['name' => $pos['name']], $pos);
        }
    }
}