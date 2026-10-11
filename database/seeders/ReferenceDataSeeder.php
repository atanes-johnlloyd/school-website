<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * ReferenceDataSeeder
 * -------------------
 * Foundational reference data for Salawag Senior High School.
 *
 * Aligned to the NEW STRENGTHENED SHS CURRICULUM
 * (DepEd Memorandum No. 012, s. 2026) effective SY 2026-2027:
 *
 *   • 2 tracks only: Academic (ACAD) and Technical-Professional (TECHPRO)
 *   • 5 core subjects (160 hours each, delivered entirely in Grade 11)
 *   • Strands replaced with "clusters" of electives — learners may mix
 *     and match electives across clusters.
 *   • Academic clusters: 5 | TechPro clusters: 10
 *
 * Historical school years 2023-2024, 2024-2025, 2025-2026 use the OLD
 * 4-track curriculum (Academic, TVL, Arts & Design, Sports) and are
 * seeded as ARCHIVED records so the app can display historical data.
 *
 * Seeding order:
 *   1. school_years   – 4 years (3 historical, 1 active: 2026-2027)
 *   2. terms          – 2 semesters per year (8 total)
 *   3. tracks         – ACAD, TECHPRO
 *   4. clusters       – 15 elective clusters (5 Academic + 10 TechPro)
 *   5. subjects       – 5 core + comprehensive electives per cluster
 *   6. rooms          – campus rooms for a mid-sized public SHS
 *   7. admin_positions – 5 roles matching the working app pattern
 *   8. roles          – admin (portal marker, ZERO perms), teacher, student
 *   9. system_settings – school profile, branding, academic, payment
 *
 * Uses DB::table() for reference data; Spatie models for roles/permissions
 * so the permission cache invalidates cleanly.
 */
class ReferenceDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSchoolYears();
        $this->seedTerms();
        $this->seedTracksAndClusters();
        $this->seedSubjects();
        $this->seedRooms();
        $this->seedAdminPositions();
        $this->seedRolesAndPermissions();
        $this->seedSystemSettings();

        $this->command->info('✔ Reference data seeded.');
        $this->command->info('   Tracks: 2 | Clusters: 15 | Subjects: ~80');
        $this->command->info('   School years: 2023-2024 → 2026-2027 (active)');
        $this->command->info('   Roles: admin (portal marker) | teacher | student');
    }

    /* =================================================================
     | 1. SCHOOL YEARS
     |-----------------------------------------------------------------
     | Four consecutive years. 2026-2027 is ACTIVE and uses the new
     | Strengthened Curriculum. The three earlier years are historical
     | and use the old 4-track structure.
     * ================================================================= */
    protected function seedSchoolYears(): void
    {
        $now = Carbon::now();

        $years = [
            // DepEd PH opening/closing dates
            ['label' => '2023-2024', 'start_date' => '2023-08-29', 'end_date' => '2024-06-14', 'is_active' => false],
            ['label' => '2024-2025', 'start_date' => '2024-07-29', 'end_date' => '2025-04-15', 'is_active' => false],
            ['label' => '2025-2026', 'start_date' => '2025-06-16', 'end_date' => '2026-04-10', 'is_active' => false],
            ['label' => '2026-2027', 'start_date' => '2026-08-03', 'end_date' => '2027-04-10', 'is_active' => true],
        ];

        foreach ($years as $y) {
            DB::table('school_years')->updateOrInsert(
                ['label' => $y['label']],
                array_merge($y, ['created_at' => $now, 'updated_at' => $now])
            );
        }
    }

        /* =================================================================
     | 2. TERMS
     |-----------------------------------------------------------------
     | Historical years (2023-2024 through 2025-2026) use the OLD
     | semestral calendar (2 semesters per year).
     |
     | SY 2026-2027 uses the NEW THREE-TERM calendar mandated by the
     | Strengthened SHS Curriculum (DepEd Memo No. 012, s. 2026).
     * ================================================================= */
    protected function seedTerms(): void
    {
        $now = Carbon::now();

        $terms = [
            // ── Historical — 2 semesters per year (old calendar) ────
            '2023-2024' => [
                ['name' => '1st Semester', 'start_date' => '2023-08-29', 'end_date' => '2024-01-26', 'is_active' => false],
                ['name' => '2nd Semester', 'start_date' => '2024-01-29', 'end_date' => '2024-06-14', 'is_active' => false],
            ],
            '2024-2025' => [
                ['name' => '1st Semester', 'start_date' => '2024-07-29', 'end_date' => '2024-12-20', 'is_active' => false],
                ['name' => '2nd Semester', 'start_date' => '2025-01-06', 'end_date' => '2025-04-15', 'is_active' => false],
            ],
            '2025-2026' => [
                ['name' => '1st Semester', 'start_date' => '2025-06-16', 'end_date' => '2025-12-19', 'is_active' => false],
                ['name' => '2nd Semester', 'start_date' => '2026-01-05', 'end_date' => '2026-04-10', 'is_active' => false],
            ],

            // ── Active — 3 Terms per year (Strengthened Curriculum) ──
            '2026-2027' => [
                ['name' => '1st Term', 'start_date' => '2026-08-03', 'end_date' => '2026-10-30', 'is_active' => true],
                ['name' => '2nd Term', 'start_date' => '2026-11-02', 'end_date' => '2027-01-29', 'is_active' => false],
                ['name' => '3rd Term', 'start_date' => '2027-02-01', 'end_date' => '2027-04-10', 'is_active' => false],
            ],
        ];

        foreach ($terms as $label => $rows) {
            $yearId = DB::table('school_years')->where('label', $label)->value('id');
            if (! $yearId) {
                continue;
            }

            foreach ($rows as $row) {
                DB::table('terms')->updateOrInsert(
                    ['school_year_id' => $yearId, 'name' => $row['name']],
                    array_merge($row, [
                        'school_year_id' => $yearId,
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ])
                );
            }
        }
    }

    /* =================================================================
     | 3. TRACKS & CLUSTERS
     |-----------------------------------------------------------------
     | New Strengthened SHS Curriculum: 2 tracks, 15 clusters.
     | Clusters replace the old "strands".
     *
     | Academic: 5 clusters
     |   ARTS-SOC-HUM | STEM | SPORTS-WELLNESS | BUSINESS-ENTREP | FIELD-EXP
     |
     | TechPro: 10 clusters
     |   AESTHETIC-WELLNESS | AGRI-FISHERY | ARTISANRY | AUTOMOTIVE |
     |   CONSTRUCTION | CREATIVE-ARTS | HOSPITALITY | INDUSTRIAL |
     |   ICT | MARITIME
     * ================================================================= */
    protected function seedTracksAndClusters(): void
    {
        $now = Carbon::now();

        // --- Tracks ----------------------------------------------------
        $tracks = [
            [
                'code' => 'ACAD', 'name' => 'Academic Track',
                'description' => 'Prepares learners for college/university education. Includes electives from Arts & Design and Sports.',
                'icon' => 'academic-cap',
                'image_path' => '/images/tracks/academic-track.jpg',
                'color' => '#1E40AF',
            ],
            [
                'code' => 'TECHPRO', 'name' => 'Technical-Professional Track',
                'description' => 'Provides practical, industry-relevant skills leading to TESDA certifications and employment.',
                'icon' => 'wrench-screwdriver',
                'image_path' => '/images/tracks/techpro-track.jpg',
                'color' => '#B45309',
            ],
        ];

        $trackIds = [];
        foreach ($tracks as $t) {
            DB::table('tracks')->updateOrInsert(
                ['code' => $t['code']],
                array_merge($t, ['is_active' => true, 'created_at' => $now, 'updated_at' => $now])
            );
            $trackIds[$t['code']] = DB::table('tracks')->where('code', $t['code'])->value('id');
        }

        // --- Clusters (replacing strands) -----------------------------
        $clusters = [
            // ═══ Academic Clusters ═══════════════════════════════════
            ['track' => 'ACAD', 'code' => 'ARTS-SOC-HUM',
             'name' => 'Arts, Social Sciences, and Humanities',
             'description' => 'Includes the former HUMSS strand and Arts & Design subjects. For learners pursuing law, education, journalism, public service, and creative fields.',
             'icon' => 'globe-alt', 'image_path' => '/images/clusters/arts-soc-hum.jpg', 'color' => '#7C3AED'],

            ['track' => 'ACAD', 'code' => 'STEM',
             'name' => 'Science, Technology, Engineering, and Mathematics',
             'description' => 'For learners pursuing engineering, medicine, pure sciences, and technology.',
             'icon' => 'beaker', 'image_path' => '/images/clusters/stem.jpg', 'color' => '#0369A1'],

            ['track' => 'ACAD', 'code' => 'SPORTS-WELLNESS',
             'name' => 'Sports, Health, and Wellness',
             'description' => 'Includes the former Sports Track. For learners pursuing sports science, coaching, fitness, and allied health.',
             'icon' => 'trophy', 'image_path' => '/images/clusters/sports-wellness.jpg', 'color' => '#047857'],

            ['track' => 'ACAD', 'code' => 'BUSINESS-ENTREP',
             'name' => 'Business and Entrepreneurship',
             'description' => 'Replaces ABM specialized subjects. For learners pursuing business, accountancy, and entrepreneurship.',
             'icon' => 'briefcase', 'image_path' => '/images/clusters/business-entrep.jpg', 'color' => '#15803D'],

            ['track' => 'ACAD', 'code' => 'FIELD-EXP',
             'name' => 'Field Experience',
             'description' => 'Work immersion, research, and culminating activities for Academic Track learners.',
             'icon' => 'clipboard-document-list', 'image_path' => '/images/clusters/field-experience.jpg', 'color' => '#4F46E5'],

            // ═══ TechPro Clusters ════════════════════════════════════
            ['track' => 'TECHPRO', 'code' => 'AESTHETIC-WELLNESS',
             'name' => 'Aesthetic, Wellness, and Human Care',
             'description' => 'Beauty care, barbering, caregiving (adult and child care), and wellness services.',
             'icon' => 'sparkles', 'image_path' => '/images/clusters/aesthetic-wellness.jpg', 'color' => '#DB2777'],

            ['track' => 'TECHPRO', 'code' => 'AGRI-FISHERY',
             'name' => 'Agri-Fishery Business and Food Innovation',
             'description' => 'Agricultural crops production, aquaculture, agro-entrepreneurship, and food processing.',
             'icon' => 'sprout', 'image_path' => '/images/clusters/agri-fishery.jpg', 'color' => '#4D7C0F'],

            ['track' => 'TECHPRO', 'code' => 'ARTISANRY',
             'name' => 'Artisanry and Creative Enterprise',
             'description' => 'Garments artisanry, fashion accessories, and creative enterprise.',
             'icon' => 'scissors', 'image_path' => '/images/clusters/artisanry.jpg', 'color' => '#92400E'],

            ['track' => 'TECHPRO', 'code' => 'AUTOMOTIVE',
             'name' => 'Automotive and Small Engine Technologies',
             'description' => 'Automotive servicing, small engine repair, and maintenance.',
             'icon' => 'cog-6-tooth', 'image_path' => '/images/clusters/automotive.jpg', 'color' => '#6B7280'],

            ['track' => 'TECHPRO', 'code' => 'CONSTRUCTION',
             'name' => 'Construction and Building Technologies',
             'description' => 'Carpentry, masonry, plumbing, and building construction.',
             'icon' => 'building-office', 'image_path' => '/images/clusters/construction.jpg', 'color' => '#78716C'],

            ['track' => 'TECHPRO', 'code' => 'CREATIVE-ARTS',
             'name' => 'Creative Arts and Design Technologies',
             'description' => 'Visual graphic design, animation, music production, and multimedia arts.',
             'icon' => 'paint-brush', 'image_path' => '/images/clusters/creative-arts.jpg', 'color' => '#BE185D'],

            ['track' => 'TECHPRO', 'code' => 'HOSPITALITY',
             'name' => 'Hospitality and Tourism',
             'description' => 'Food and beverage operations, bakery, kitchen operations, housekeeping, and tourism services.',
             'icon' => 'cake', 'image_path' => '/images/clusters/hospitality.jpg', 'color' => '#C2410C'],

            ['track' => 'TECHPRO', 'code' => 'INDUSTRIAL',
             'name' => 'Industrial Technologies',
             'description' => 'Air-conditioning, refrigeration, electrical installation, and photovoltaic systems.',
             'icon' => 'bolt', 'image_path' => '/images/clusters/industrial.jpg', 'color' => '#0F766E'],

            ['track' => 'TECHPRO', 'code' => 'ICT',
             'name' => 'ICT Support and Computer Programming Technologies',
             'description' => 'Computer programming (Java, .NET), computer systems servicing, broadband installation, animation, and contact center services.',
             'icon' => 'code-bracket', 'image_path' => '/images/clusters/ict.jpg', 'color' => '#0891B2'],

            ['track' => 'TECHPRO', 'code' => 'MARITIME',
             'name' => 'Maritime Transport',
             'description' => 'Marine engineering at the support level and maritime field exposure.',
             'icon' => 'globe-americas', 'image_path' => '/images/clusters/maritime.jpg', 'color' => '#1E3A8A'],
        ];

        foreach ($clusters as $c) {
            $trackId = $trackIds[$c['track']] ?? null;
            if (! $trackId) {
                continue;
            }

            DB::table('strands')->updateOrInsert(
                ['code' => $c['code']],
                [
                    'track_id'     => $trackId,
                    'code'         => $c['code'],
                    'name'         => $c['name'],
                    'description'  => $c['description'],
                    'icon'         => $c['icon'],
                    'image_path'   => $c['image_path'],
                    'color'        => $c['color'],
                    'is_active'    => true,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]
            );
        }
    }

    /* =================================================================
     | 4. SUBJECTS
     |-----------------------------------------------------------------
     | 5 Core Subjects (all Grade 11, 160 hours each):
     |   CORE-COMM   Effective Communication / Mabisang Komunikasyon
     |   CORE-MATH   General Mathematics
     |   CORE-SCI    General Science
     |   CORE-LCS    Life and Career Skills
     |   CORE-KASP   Pag-aaral ng Kasaysayan at Lipunang Pilipino
     |
     | Electives are scoped to their cluster (stored as strand_id).
     | Academic electives: 80 or 160 hours.
     | TechPro electives: 320 hours.
     *
     | Prerequisites are applied in a second pass at the end.
     * ================================================================= */
    protected function seedSubjects(): void
    {
        $now = Carbon::now();

        $clusterIds = DB::table('strands')->pluck('id', 'code')->all();

        // --- 5 Core Subjects (Grade 11, 160 hours) --------------------
        $core = [
            ['CORE-COMM',  'Effective Communication / Mabisang Komunikasyon',
             'Integrates oral communication, reading and writing, and 21st century literature. Aligned to CHED GE subjects.', 160, 'chat-bubble-left-right', '#0EA5E9'],
            ['CORE-MATH',  'General Mathematics',
             'Deepens understanding of numbers, algebra, geometry, measurement, and statistics. Strengthens problem-solving and critical thinking.', 160, 'calculator', '#2563EB'],
            ['CORE-SCI',   'General Science',
             'Equips learners with essential scientific knowledge, skills, and capabilities for a rapidly changing society.', 160, 'beaker', '#059669'],
            ['CORE-LCS',   'Life and Career Skills',
             'Develops personal, social, and career-related competencies for lifelong learning and employability.', 160, 'user-circle', '#9333EA'],
            ['CORE-KASP',  'Pag-aaral ng Kasaysayan at Lipunang Pilipino',
             'Examines Philippine history, society, and citizenship through an interdisciplinary lens.', 160, 'building-library', '#B45309'],
        ];

        foreach ($core as [$code, $name, $desc, $hrs, $icon, $color]) {
            DB::table('subjects')->updateOrInsert(
                ['code' => $code],
                [
                    'code'                   => $code,
                    'name'                   => $name,
                    'description'            => $desc,
                    'strand_id'              => null,
                    'grade_level'            => '11',
                    'is_core'                => true,
                    'hours'                  => $hrs,
                    'prerequisite_subject_id'=> null,
                    'is_active'              => true,
                    'image_path'             => '/images/subjects/core-default.jpg',
                    'icon'                   => $icon,
                    'color'                  => $color,
                    'created_at'             => $now,
                    'updated_at'             => $now,
                ]
            );
        }

        // --- Academic Electives ---------------------------------------
        $academic = [

            // ─── ARTS, SOCIAL SCIENCES, AND HUMANITIES ────────────────
            ['ARTS-SOC-HUM', 'ASH-CREATW1',   'Creative Writing',                                     '11', 80,  'pencil-square', '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-CREATNF',   'Creative Nonfiction',                                  '11', 80,  'document-text', '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-WORLDREL',  'Introduction to World Religions and Belief Systems',    '11', 80,  'globe-alt',     '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-TRENDS',    'Trends, Networks, and Critical Thinking in the 21st Century', '11', 80, 'globe-americas', '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-PHILPOL',   'Philippine Politics and Governance',                    '11', 80,  'building-library', '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-COMMENG',   'Community Engagement, Solidarity, and Citizenship',     '11', 80,  'users',         '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-DISCSOC',   'Disciplines and Ideas in the Social Sciences',          '11', 80,  'users',         '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-DISCAPP',   'Disciplines and Ideas in the Applied Social Sciences',  '12', 80,  'users',         '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-PHILHUM',   'Introduction to the Philosophy of the Human Person',    '11', 80,  'light-bulb',    '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-CREATIND',  'Creative Industries (Music, Dance, Theater)',           '11', 80,  'musical-note',  '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-VISARTS',   'Visual Arts',                                          '11', 160, 'paint-brush',   '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-MEDARTS',   'Media Arts',                                           '12', 160, 'film',          '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-CITIZEN',   'Citizenship and Civic Engagement',                      '12', 80,  'users',         '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-CULTID',    'Cultivating Filipino Identity Through the Arts',         '12', 80,  'sparkles',      '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-LEADART',   'Leadership and Management in the Arts',                 '12', 80,  'trophy',        '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-ARTSAPP',   'Arts Apprenticeship (Theater, Literary, Dance, Music)', '12', 160, 'paint-brush',   '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-FILARTS',   'Filipino in Arts & Design, and Technical-Professional Contexts', '12', 80, 'language', '#7C3AED'],
            ['ARTS-SOC-HUM', 'ASH-FILSPORT',  'Filipino sa Isports',                                   '12', 80,  'bolt',          '#7C3AED'],

            // ─── STEM ──────────────────────────────────────────────────
            ['STEM', 'STEM-PCALC',   'Pre-Calculus',                          '11', 80,  'function',       '#0369A1'],
            ['STEM', 'STEM-BCALC',   'Basic Calculus',                        '12', 80,  'function',       '#0369A1'],
            ['STEM', 'STEM-TRIG',    'Trigonometry 1-2',                      '11', 80,  'function',       '#0369A1'],
            ['STEM', 'STEM-ADVMATH', 'Advanced Mathematics 1-2',              '12', 80,  'function',       '#0369A1'],
            ['STEM', 'STEM-FINMATH', 'Finite Mathematics',                    '12', 80,  'calculator',     '#0369A1'],
            ['STEM', 'STEM-BIO12',   'Biology 1-2',                           '11', 160, 'beaker',         '#059669'],
            ['STEM', 'STEM-BIO34',   'Biology 3-4',                           '12', 160, 'beaker',         '#059669'],
            ['STEM', 'STEM-CHEM12',  'Chemistry 1-2',                         '11', 160, 'beaker',         '#7C3AED'],
            ['STEM', 'STEM-CHEM34',  'Chemistry 3-4',                         '12', 160, 'beaker',         '#7C3AED'],
            ['STEM', 'STEM-PHYS12',  'Physics 1-2',                           '11', 160, 'atom',           '#DC2626'],
            ['STEM', 'STEM-PHYS34',  'Physics 3-4',                           '12', 160, 'atom',           '#DC2626'],
            ['STEM', 'STEM-EARTH',   'Earth and Space Science 1-2',           '11', 80,  'globe-americas', '#0891B2'],
            ['STEM', 'STEM-EARTH34', 'Earth and Space Science 3-4',           '12', 80,  'globe-americas', '#0891B2'],
            ['STEM', 'STEM-RESMETH', 'Research Methods',                      '11', 80,  'magnifying-glass', '#0F766E'],
            ['STEM', 'STEM-DATA',    'Fundamentals of Data Analytics and Management', '12', 80, 'chart-bar', '#0F766E'],
            ['STEM', 'STEM-EMPowTECH','Empowerment Technologies',             '11', 80,  'computer-desktop', '#0891B2'],
            ['STEM', 'STEM-DBMGMT',  'Database Management',                   '12', 80,  'circle-stack',   '#0891B2'],

            // ─── BUSINESS AND ENTREPRENEURSHIP ───────────────────────
            ['BUSINESS-ENTREP', 'BE-BASACC',   'Basic Accounting',                              '11', 80,  'calculator', '#15803D'],
            ['BUSINESS-ENTREP', 'BE-BUSFIN',   'Business Finance and Income Taxation',          '12', 80,  'banknotes',  '#15803D'],
            ['BUSINESS-ENTREP', 'BE-CONTMKT',  'Contemporary Marketing and Business Economics', '12', 80,  'megaphone',  '#15803D'],
            ['BUSINESS-ENTREP', 'BE-ENTREP',   'Entrepreneurship',                              '11', 80,  'briefcase',  '#15803D'],
            ['BUSINESS-ENTREP', 'BE-ORG-MGMT', 'Introduction to Organization and Management',   '11', 80,  'briefcase',  '#15803D'],

            // ─── SPORTS, HEALTH, AND WELLNESS ────────────────────────
            ['SPORTS-WELLNESS', 'SW-EXSPORT',  'Exercise and Sports Programming',           '11', 80,  'bolt',  '#047857'],
            ['SPORTS-WELLNESS', 'SW-HUMANMOV', 'Introduction to Human Movement',             '11', 80,  'bolt',  '#047857'],
            ['SPORTS-WELLNESS', 'SW-HUMANMOV1','Human Movement 1 (Basic Anatomy in Sports)', '11', 80,  'bolt',  '#047857'],
            ['SPORTS-WELLNESS', 'SW-SPORTOFF', 'Sports Officiating',                         '11', 80,  'trophy','#047857'],
            ['SPORTS-WELLNESS', 'SW-SAFEFIRST','Safety and First Aid',                       '12', 80,  'heart', '#047857'],
            ['SPORTS-WELLNESS', 'SW-SPORTCOACH','Sports Coaching',                           '12', 80,  'trophy','#047857'],
            ['SPORTS-WELLNESS', 'SW-SPORTMGMT','Sports Activity Management',                 '12', 80,  'clipboard-document-list', '#047857'],
            ['SPORTS-WELLNESS', 'SW-PEFIT',    'Physical Education (Fitness and Recreation)', '12', 80,  'bolt',  '#047857'],
            ['SPORTS-WELLNESS', 'SW-PESPORT',  'Physical Education (Sports and Dance)',      '12', 80,  'musical-note', '#047857'],

            // ─── FIELD EXPERIENCE ─────────────────────────────────────
            ['FIELD-EXP', 'FE-WORKIMM',   'Work Immersion (320-640 hours)',              '12', 320, 'briefcase',  '#4F46E5'],
            ['FIELD-EXP', 'FE-RESMETH',   'Research Methods',                             '12', 80,  'magnifying-glass', '#4F46E5'],
            ['FIELD-EXP', 'FE-DESIGN',    'Design and Innovation (160 hours)',           '12', 160, 'light-bulb', '#4F46E5'],
            ['FIELD-EXP', 'FE-CREATPROD', 'Creative Production and Presentation (160 hours)', '12', 160, 'sparkles', '#4F46E5'],
        ];

        // --- TechPro Electives (320 hours each) -----------------------
        $techpro = [

            // ─── AESTHETIC, WELLNESS, AND HUMAN CARE ─────────────────
            ['AESTHETIC-WELLNESS', 'AW-BEAUTY',   'Aesthetic Services (Beauty Care) NC II',    '11', 320, 'sparkles', '#DB2777'],
            ['AESTHETIC-WELLNESS', 'AW-BARBER',   'Barbering Services NC II',                  '11', 320, 'scissors', '#DB2777'],
            ['AESTHETIC-WELLNESS', 'AW-CAREADULT','Caregiving (Adult Care) NC II',             '11', 320, 'heart',    '#DB2777'],
            ['AESTHETIC-WELLNESS', 'AW-CARECHILD','Caregiving (Child Care) NC II',             '12', 320, 'users',    '#DB2777'],

            // ─── AGRI-FISHERY BUSINESS AND FOOD INNOVATION ───────────
            ['AGRI-FISHERY', 'AF-AGROENT',  'Agro-Entrepreneurship',                     '11', 320, 'sprout', '#4D7C0F'],
            ['AGRI-FISHERY', 'AF-CROP',     'Agricultural Crops Production NC II',       '11', 320, 'sprout', '#4D7C0F'],
            ['AGRI-FISHERY', 'AF-AQUA',     'Aquaculture NC II',                         '12', 320, 'sprout', '#4D7C0F'],
            ['AGRI-FISHERY', 'AF-FOODINNO', 'Food Innovation and Processing',            '12', 320, 'fire',   '#4D7C0F'],

            // ─── ARTISANRY AND CREATIVE ENTERPRISE ───────────────────
            ['ARTISANRY', 'AR-GARMENTS', 'Garments Artisanry',                 '11', 320, 'scissors', '#92400E'],
            ['ARTISANRY', 'AR-FASHACC',  'Fashion Accessories and Craft',      '12', 320, 'sparkles', '#92400E'],

            // ─── AUTOMOTIVE AND SMALL ENGINE TECHNOLOGIES ────────────
            ['AUTOMOTIVE', 'AU-AUTO',    'Automotive Servicing NC II',          '11', 320, 'wrench', '#6B7280'],
            ['AUTOMOTIVE', 'AU-SMALLENG','Small Engine Repair and Maintenance', '12', 320, 'cog-6-tooth', '#6B7280'],

            // ─── CONSTRUCTION AND BUILDING TECHNOLOGIES ──────────────
            ['CONSTRUCTION', 'CO-CARP',   'Carpentry NC II',                     '11', 320, 'building-office', '#78716C'],
            ['CONSTRUCTION', 'CO-MASON',  'Masonry NC II',                       '11', 320, 'building-office', '#78716C'],
            ['CONSTRUCTION', 'CO-PLUMB',  'Plumbing NC II',                      '12', 320, 'wrench',          '#78716C'],

            // ─── CREATIVE ARTS AND DESIGN TECHNOLOGIES ───────────────
            ['CREATIVE-ARTS', 'CA-VISGRAPH', 'Visual Graphic Design NC III',      '11', 320, 'paint-brush', '#BE185D'],
            ['CREATIVE-ARTS', 'CA-ANIM',     'Animation NC II',                   '11', 320, 'film',        '#BE185D'],
            ['CREATIVE-ARTS', 'CA-MUSIC',    'Music Production',                  '12', 320, 'musical-note', '#BE185D'],

            // ─── HOSPITALITY AND TOURISM ─────────────────────────────
            ['HOSPITALITY', 'HO-FBO',      'Food and Beverage Operations NC II',   '11', 320, 'fire',  '#C2410C'],
            ['HOSPITALITY', 'HO-BAKERY',   'Bakery Operations NC II',              '11', 320, 'cake',  '#C2410C'],
            ['HOSPITALITY', 'HO-KITCHEN',  'Kitchen Operations NC II',             '11', 320, 'fire',  '#C2410C'],
            ['HOSPITALITY', 'HO-HOUSEKEEP','Housekeeping Operations NC II',        '12', 320, 'building-storefront', '#C2410C'],
            ['HOSPITALITY', 'HO-BARTEND',  'Bartending NC II',                     '12', 320, 'beaker','#C2410C'],
            ['HOSPITALITY', 'HO-EVENTS',   'Events Management Services NC II',     '12', 320, 'clipboard-document-list', '#C2410C'],

            // ─── INDUSTRIAL TECHNOLOGIES ─────────────────────────────
            ['INDUSTRIAL', 'IN-AIRCON',  'Commercial Air-Conditioning Installation and Servicing NC III', '11', 320, 'snowflake', '#0F766E'],
            ['INDUSTRIAL', 'IN-REFRIG',  'Domestic Refrigeration and Air-Conditioning Servicing NC II', '11', 320, 'snowflake', '#0F766E'],
            ['INDUSTRIAL', 'IN-ELEC',    'Electrical System Installation',       '12', 320, 'bolt', '#0F766E'],
            ['INDUSTRIAL', 'IN-SOLAR',   'Photovoltaic System Installation',     '12', 320, 'sun',  '#0F766E'],

            // ─── ICT SUPPORT AND COMPUTER PROGRAMMING TECHNOLOGIES ───
            ['ICT', 'ICT-CSS',       'Computer Systems Servicing NC II',           '11', 320, 'computer-desktop', '#0891B2'],
            ['ICT', 'ICT-PROGJAVA',  'Computer Programming (Java) NC III',         '11', 320, 'code-bracket',     '#0891B2'],
            ['ICT', 'ICT-PROGDOTNET','Computer Programming (.NET Technology) NC III', '12', 320, 'code-bracket',  '#0891B2'],
            ['ICT', 'ICT-BROADBAND', 'Broadband Installation NC II',               '12', 320, 'signal',           '#0891B2'],
            ['ICT', 'ICT-CONTACT',   'Contact Center Services NC II',              '12', 320, 'phone',            '#0891B2'],

            // ─── MARITIME TRANSPORT ──────────────────────────────────
            ['MARITIME', 'MA-MARENG',   'Marine Engineering at the Support Level', '11', 320, 'globe-americas', '#1E3A8A'],
            ['MARITIME', 'MA-FIELDEXP', 'Maritime Field Exposure',                   '12', 320, 'anchor',         '#1E3A8A'],
        ];

        // Insert all electives.
        foreach (array_merge($academic, $techpro) as [$clusterCode, $code, $name, $gl, $hrs, $icon, $color]) {
            $strandId = $clusterIds[$clusterCode] ?? null;
            if (! $strandId) {
                continue;
            }

            DB::table('subjects')->updateOrInsert(
                ['code' => $code],
                [
                    'code'                    => $code,
                    'name'                    => $name,
                    'description'             => null,
                    'strand_id'               => $strandId,
                    'grade_level'             => $gl,
                    'is_core'                 => false,
                    'hours'                   => $hrs,
                    'prerequisite_subject_id' => null,
                    'is_active'               => true,
                    'image_path'              => '/images/subjects/' . strtolower($clusterCode) . '-default.jpg',
                    'icon'                    => $icon,
                    'color'                   => $color,
                    'created_at'              => $now,
                    'updated_at'              => $now,
                ]
            );
        }

        // --- Second pass: attach prerequisites ------------------------
        $prerequisites = [
            'STEM-BCALC'   => 'STEM-PCALC',
            'STEM-BIO34'   => 'STEM-BIO12',
            'STEM-CHEM34'  => 'STEM-CHEM12',
            'STEM-PHYS34'  => 'STEM-PHYS12',
            'STEM-EARTH34' => 'STEM-EARTH',
            'STEM-DATA'    => 'STEM-RESMETH',
            'STEM-DBMGMT'  => 'STEM-EMPowTECH',
            'BE-BUSFIN'    => 'BE-BASACC',
            'BE-CONTMKT'   => 'BE-ENTREP',
            'ASH-DISCAPP'  => 'ASH-DISCSOC',
            'ASH-MEDARTS'  => 'ASH-VISARTS',
            'ASH-ARTSAPP'  => 'ASH-CREATIND',
            'SW-SPORTCOACH'=> 'SW-EXSPORT',
            'SW-SPORTMGMT' => 'SW-SPORTOFF',
            'HO-HOUSEKEEP' => 'HO-KITCHEN',
            'HO-BARTEND'   => 'HO-FBO',
            'HO-EVENTS'    => 'HO-FBO',
            'IN-ELEC'      => 'IN-AIRCON',
            'IN-SOLAR'     => 'IN-ELEC',
            'ICT-PROGDOTNET' => 'ICT-PROGJAVA',
            'ICT-BROADBAND'  => 'ICT-CSS',
            'ICT-CONTACT'    => 'ICT-CSS',
            'MA-FIELDEXP'    => 'MA-MARENG',
            'AF-AQUA'        => 'AF-CROP',
            'AF-FOODINNO'    => 'AF-AGROENT',
            'AR-FASHACC'     => 'AR-GARMENTS',
            'CO-PLUMB'       => 'CO-MASON',
            'CA-MUSIC'       => 'CA-VISGRAPH',
            'AW-CARECHILD'   => 'AW-CAREADULT',
        ];

        foreach ($prerequisites as $subjectCode => $prereqCode) {
            $subjectId = DB::table('subjects')->where('code', $subjectCode)->value('id');
            $prereqId  = DB::table('subjects')->where('code', $prereqCode)->value('id');

            if ($subjectId && $prereqId) {
                DB::table('subjects')
                    ->where('id', $subjectId)
                    ->update(['prerequisite_subject_id' => $prereqId, 'updated_at' => $now]);
            }
        }
    }

    /* =================================================================
     | 5. ROOMS
     |-----------------------------------------------------------------
     | Realistic room inventory for a mid-sized public SHS.
     * ================================================================= */
    protected function seedRooms(): void
    {
        $now = Carbon::now();

        $rooms = [
            // Grade 11 regular classrooms
            ['code' => 'R11-A', 'name' => 'Grade 11 – Room A', 'building' => 'Rizal Building',   'floor' => '1st Floor', 'capacity' => 45, 'type' => 'regular'],
            ['code' => 'R11-B', 'name' => 'Grade 11 – Room B', 'building' => 'Rizal Building',   'floor' => '1st Floor', 'capacity' => 45, 'type' => 'regular'],
            ['code' => 'R11-C', 'name' => 'Grade 11 – Room C', 'building' => 'Rizal Building',   'floor' => '2nd Floor', 'capacity' => 45, 'type' => 'regular'],
            ['code' => 'R11-D', 'name' => 'Grade 11 – Room D', 'building' => 'Rizal Building',   'floor' => '2nd Floor', 'capacity' => 45, 'type' => 'regular'],

            // Grade 12 regular classrooms
            ['code' => 'R12-A', 'name' => 'Grade 12 – Room A', 'building' => 'Bonifacio Building', 'floor' => '1st Floor', 'capacity' => 45, 'type' => 'regular'],
            ['code' => 'R12-B', 'name' => 'Grade 12 – Room B', 'building' => 'Bonifacio Building', 'floor' => '1st Floor', 'capacity' => 45, 'type' => 'regular'],
            ['code' => 'R12-C', 'name' => 'Grade 12 – Room C', 'building' => 'Bonifacio Building', 'floor' => '2nd Floor', 'capacity' => 45, 'type' => 'regular'],
            ['code' => 'R12-D', 'name' => 'Grade 12 – Room D', 'building' => 'Bonifacio Building', 'floor' => '2nd Floor', 'capacity' => 45, 'type' => 'regular'],

            // Laboratories & workshops
            ['code' => 'SCI-LAB-1',  'name' => 'Science Laboratory 1',         'building' => 'Science Building', 'floor' => '1st Floor', 'capacity' => 40, 'type' => 'science_lab'],
            ['code' => 'SCI-LAB-2',  'name' => 'Science Laboratory 2',         'building' => 'Science Building', 'floor' => '2nd Floor', 'capacity' => 40, 'type' => 'science_lab'],
            ['code' => 'COMP-LAB-1', 'name' => 'Computer Laboratory 1',        'building' => 'ICT Building',     'floor' => '1st Floor', 'capacity' => 40, 'type' => 'computer_lab'],
            ['code' => 'COMP-LAB-2', 'name' => 'Computer Laboratory 2',        'building' => 'ICT Building',     'floor' => '2nd Floor', 'capacity' => 40, 'type' => 'computer_lab'],
            ['code' => 'HE-LAB',     'name' => 'Home Economics Laboratory',    'building' => 'TechPro Building', 'floor' => '1st Floor', 'capacity' => 30, 'type' => 'laboratory'],
            ['code' => 'IA-SHOP',    'name' => 'Industrial Arts Workshop',     'building' => 'TechPro Building', 'floor' => '1st Floor', 'capacity' => 30, 'type' => 'workshop'],
            ['code' => 'AF-FARM',    'name' => 'Agri-Fisheries Demo Farm',     'building' => 'TechPro Annex',    'floor' => 'Ground',    'capacity' => 35, 'type' => 'workshop'],
            ['code' => 'ARTS-STUDIO','name' => 'Arts and Design Studio',       'building' => 'Rizal Building',   'floor' => '3rd Floor', 'capacity' => 30, 'type' => 'workshop'],
            ['code' => 'AUTO-SHOP',  'name' => 'Automotive Workshop',          'building' => 'TechPro Building', 'floor' => 'Ground',    'capacity' => 25, 'type' => 'workshop'],
            ['code' => 'MARITIME-LAB','name' => 'Maritime Simulation Room',    'building' => 'TechPro Annex',    'floor' => '1st Floor', 'capacity' => 25, 'type' => 'laboratory'],

            // Shared spaces
            ['code' => 'GYM', 'name' => 'Covered Court / Gymnasium',  'building' => 'Sports Complex', 'floor' => 'Ground', 'capacity' => 300, 'type' => 'lecture'],
            ['code' => 'AVR', 'name' => 'Audio-Visual Room',          'building' => 'Admin Building', 'floor' => '1st Floor', 'capacity' => 80, 'type' => 'lecture'],
            ['code' => 'LIB', 'name' => 'Learning Resource Center',   'building' => 'Admin Building', 'floor' => '2nd Floor', 'capacity' => 60, 'type' => 'lecture'],
        ];

        foreach ($rooms as $r) {
            DB::table('rooms')->updateOrInsert(
                ['code' => $r['code']],
                array_merge($r, ['is_active' => true, 'created_at' => $now, 'updated_at' => $now])
            );
        }
    }

    /* =================================================================
     | 6. ADMIN POSITIONS
     |-----------------------------------------------------------------
     | Matches the working app pattern. "default_permissions" is copied
     | onto a user at account-creation time as DIRECT user permissions.
     | The "admin" role itself carries ZERO permissions — it is a
     | portal marker only.
     * ================================================================= */
    protected function seedAdminPositions(): void
    {
        $now = Carbon::now();

        $positions = [
            [
                'name' => 'System Admin',
                'description' => 'Full technical administrator — users, settings, backups, audit log.',
                'default_permissions' => [
                    'manage-users', 'manage-students', 'manage-teachers',
                    'manage-enrollment', 'manage-school-years', 'manage-tracks',
                    'manage-strands', 'manage-subjects', 'manage-sections',
                    'manage-rooms', 'manage-classes', 'assign-teachers',
                    'manage-class-schedules', 'view-reports', 'view-audit-log',
                    'manage-settings', 'manage-announcements', 'manage-backups',
                    'manage-contributions', 'manage-payments',
                ],
            ],
            [
                'name' => 'Registrar',
                'description' => 'Manages student records, enrollment, and section rosters.',
                'default_permissions' => [
                    'manage-students', 'manage-enrollment', 'manage-sections',
                    'view-reports', 'manage-contributions',
                ],
            ],
            [
                'name' => 'Curriculum Coordinator',
                'description' => 'Oversees curriculum, subjects, class offerings, and teacher assignment.',
                'default_permissions' => [
                    'manage-tracks', 'manage-strands', 'manage-subjects',
                    'manage-classes', 'assign-teachers', 'manage-class-schedules',
                    'manage-teachers', 'view-reports',
                ],
            ],
            [
                'name' => 'School Head',
                'description' => 'Principal/OIC — high-level oversight and school-wide communication.',
                'default_permissions' => [
                    'view-reports', 'view-audit-log', 'manage-announcements',
                ],
            ],
            [
                'name' => 'Staff',
                'description' => 'Read-only access to reports.',
                'default_permissions' => [
                    'view-reports',
                ],
            ],
        ];

        foreach ($positions as $p) {
            DB::table('admin_positions')->updateOrInsert(
                ['name' => $p['name']],
                [
                    'name'                 => $p['name'],
                    'description'          => $p['description'],
                    'default_permissions'  => json_encode($p['default_permissions']),
                    'is_active'            => true,
                    'created_at'           => $now,
                    'updated_at'           => $now,
                ]
            );
        }
    }

    /* =================================================================
     | 7. ROLES & PERMISSIONS (Spatie)
     |-----------------------------------------------------------------
     | Matches the working app pattern exactly:
     |   • admin   → portal marker. ZERO permissions. Actual perms come
     |               from the user's admin_position preset.
     |   • teacher → carries teaching-related permissions.
     |   • student → carries learner-related permissions.
     * ================================================================= */
    protected function seedRolesAndPermissions(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionsByGroup = [
            'admin' => [
                'manage-users', 'manage-students', 'manage-teachers',
                'manage-enrollment', 'manage-school-years', 'manage-tracks',
                'manage-strands', 'manage-subjects', 'manage-sections',
                'manage-rooms', 'manage-classes', 'assign-teachers',
                'manage-class-schedules', 'view-reports', 'view-audit-log',
                'manage-settings', 'manage-announcements', 'manage-backups',
                'manage-contributions', 'manage-payments',
            ],
            'teacher' => [
                'view-own-classes', 'manage-own-content', 'manage-own-quizzes',
                'manage-own-question-bank', 'manage-own-assignments',
                'grade-submissions', 'manage-own-gradebook',
                'manage-own-announcements', 'view-own-schedule', 'send-messages',
                'manage-own-contributions',
            ],
            'student' => [
                'view-own-courses', 'take-quizzes', 'submit-assignments',
                'view-own-grades', 'view-own-schedule', 'view-own-report-card',
                'view-announcements', 'send-messages', 'pay-contributions',
            ],
            'shared' => [
                'view-own-profile', 'update-own-profile',
            ],
        ];

        $allPermissions = array_merge(...array_values($permissionsByGroup));

        foreach ($allPermissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // --- Roles -----------------------------------------------------
        $admin   = Role::firstOrCreate(['name' => 'admin',   'guard_name' => 'web']);
        $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // ⚠️ admin role is a PORTAL MARKER ONLY.
        // Actual permissions are attached per-user from their
        // admin_position preset (see TestAdminUsersSeeder).
        $admin->syncPermissions([]);

        $teacher->syncPermissions([
            ...$permissionsByGroup['teacher'],
            ...$permissionsByGroup['shared'],
        ]);

        $student->syncPermissions([
            ...$permissionsByGroup['student'],
            ...$permissionsByGroup['shared'],
        ]);

        // Remove legacy parent role if it exists.
        if ($parent = Role::where('name', 'parent')->where('guard_name', 'web')->first()) {
            $parent->users()->each(fn ($user) => $user->removeRole($parent));
            $parent->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /* =================================================================
     | 8. SYSTEM SETTINGS
     |-----------------------------------------------------------------
     | key/value store. Keys match the working app's naming convention
     | (snake_case, grouped by module).
     * ================================================================= */
    protected function seedSystemSettings(): void
    {
        $now = Carbon::now();

        $settings = [
            // ---------------- School profile ----------------
            ['school', 'school_name',           'Salawag Senior High School'],
            ['school', 'school_short_name',     'Salawag SHS'],
            ['school', 'school_address',        'Barangay Salawag, Dasmariñas City, Cavite 4114'],
            ['school', 'school_email',          'info@salawagshs.edu.ph'],
            ['school', 'school_phone',          '(046) 471-2289'],
            ['school', 'school_mobile',         '+63 917 845 2233'],
            ['school', 'school_id_number',      '305412'],
            ['school', 'school_region',         'Region IV-A (CALABARZON)'],
            ['school', 'school_division',       'Division of Dasmariñas City'],
            ['school', 'school_district',       'Dasmariñas East'],
            ['school', 'school_motto',          'Serbisyong Tapat, Karunungang Wagas'],
            ['school', 'principal_name',        'Dr. Marilou T. Santiago'],
            ['school', 'principal_title',       'Principal IV'],
            ['school', 'shs_coordinator',       'Mr. Fernando L. Bautista'],
            ['school', 'registrar_name',        'Ms. Cristina A. Delos Reyes'],
            ['school', 'school_vision',         'We dream of Filipinos who passionately love their country and whose values and competencies enable them to realize their full potential and contribute meaningfully to building the nation.'],
            ['school', 'school_mission',        'To protect and promote the right of every Filipino to quality, equitable, culture-based, and complete basic education.'],
            ['school', 'school_about',          'Salawag Senior High School offers the Academic and Technical-Professional tracks under the Strengthened SHS Curriculum. Since 2016 it has served learners from Barangay Salawag and neighboring barangays of Dasmariñas City, Cavite.'],

            // ---------------- Branding / images ----------------
            ['branding', 'school_logo',              '/images/branding/salawag-shs-logo.png'],
            ['branding', 'school_favicon',           '/images/branding/favicon.ico'],
            ['branding', 'hero_banner',              '/images/banners/hero-campus-salawag-sunrise.jpg'],
            ['branding', 'hero_banner_alt',          '/images/banners/hero-students-classroom.jpg'],
            ['branding', 'campus_photo',             '/images/campus/salawag-front-facade.jpg'],
            ['branding', 'campus_gym',               '/images/campus/salawag-covered-court.jpg'],
            ['branding', 'campus_library',           '/images/campus/salawag-library.jpg'],
            ['branding', 'campus_science_lab',       '/images/campus/salawag-science-laboratory.jpg'],
            ['branding', 'campus_computer_lab',      '/images/campus/salawag-computer-laboratory.jpg'],
            ['branding', 'admissions_banner',        '/images/banners/admissions-open-2026-2027.jpg'],
            ['branding', 'default_student_avatar',   '/images/avatars/default-student.png'],
            ['branding', 'default_teacher_avatar',   '/images/avatars/default-teacher.png'],
            ['branding', 'default_announcement_cover','/images/announcements/default-cover.jpg'],

            // ---------------- Academic ----------------
            ['academic', 'current_school_year',        '2026-2027'],
            ['academic', 'current_semester',           '1st Semester'],
            ['academic', 'passing_grade',              '75'],
            ['academic', 'max_class_size',             '45'],
            ['academic', 'weight_written_work',        '25'],
            ['academic', 'weight_performance_task',    '50'],
            ['academic', 'weight_quarterly_exam',      '25'],
            ['academic', 'attendance_late_grace_min',  '15'],
            ['academic', 'grading_system',             'DepEd K–12 (DO 8, s. 2015)'],
            ['academic', 'honor_threshold_with_honors','90'],
            ['academic', 'honor_threshold_high_honors','95'],
            ['academic', 'honor_threshold_highest',    '98'],

            // ---------------- Admissions ----------------
            ['admissions', 'admissions_open',           '1'],
            ['admissions', 'admissions_school_year',    '2026-2027'],
            ['admissions', 'admissions_open_message',   'Online admission for Grade 11 and Grade 12 transferees for S.Y. 2026–2027 is now open. Please prepare your Form 138 (Report Card), PSA Birth Certificate, and Good Moral Certificate.'],
            ['admissions', 'admissions_contact_person', 'Ms. Cristina A. Delos Reyes (Registrar)'],
            ['admissions', 'admissions_contact_number', '+63 917 845 2233'],
            ['admissions', 'admissions_requirements',   json_encode([
                'Form 138 / Report Card',
                'PSA Birth Certificate',
                'Certificate of Good Moral Character',
                '2x2 ID Picture (2 pcs)',
                'ESC Certificate (for ESC graduates)',
            ])],

            // ---------------- System ----------------
            ['system', 'timezone',                'Asia/Manila'],
            ['system', 'locale',                  'en_PH'],
            ['system', 'maintenance_mode',        '0'],
            ['system', 'student_portal_enabled',  '1'],
            ['system', 'teacher_portal_enabled',  '1'],
            ['system', 'parent_portal_enabled',   '0'],
            ['system', 'announcement_days',       '30'],
            ['system', 'max_file_upload_mb',      '10'],
            ['system', 'contact_email_visible',   '1'],
            ['system', 'login_attempts_limit',    '5'],
            ['system', 'session_timeout_minutes', '120'],
            ['system', 'backup_retention_days',   '30'],
            ['system', 'support_email',           'support@salawagshs.edu.ph'],
            ['system', 'support_phone',           '+63 46 555 1234'],

            // ---------------- Payments (PayMongo) ----------------
            ['payment', 'paymongo_enabled',       '1'],
            ['payment', 'paymongo_mode',          'test'],
            ['payment', 'currency',               'PHP'],
            ['payment', 'cash_payment_enabled',   '1'],
            ['payment', 'online_payment_enabled', '1'],
            ['payment', 'consent_link_expiry_hours', '72'],
        ];

        foreach ($settings as [$group, $key, $value]) {
            DB::table('system_settings')->updateOrInsert(
                ['key' => $key],
                [
                    'key'        => $key,
                    'value'      => (string) $value,
                    'group'      => $group,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}