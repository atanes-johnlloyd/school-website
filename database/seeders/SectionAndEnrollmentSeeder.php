<?php

namespace Database\Seeders;

use App\Models\Applicant;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Strand;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * SectionAndEnrollmentSeeder
 * --------------------------
 * Creates sections and enrollments for all four school years.
 *
 * ─── SECTION NAMING ────────────────────────────────────────────────
 * Format:  {CLUSTER} {GRADE}-{SECTION_LETTER}
 * Examples:
 *   • STEM 11-A, STEM 11-B, STEM 12-A, STEM 12-B
 *   • ICT 11-A, ICT 12-A
 *   • HOSPITALITY 11-A, HOSPITALITY 12-A
 *
 * The grade level is baked into the name, so a section is
 * self-identifying in the UI without needing to inspect the
 * `grade_level` column. Historical sections follow the same
 * pattern but use the OLD strand names (HUMSS, ABM, ICT-CP).
 *
 * ─── CLUSTER-AWARE PLACEMENT ───────────────────────────────────────
 *   • Students who came from an applicant with a cluster preference
 *     are placed in a section matching that cluster.
 *   • Direct-seeded students fall back to least-loaded round-robin.
 *   • Historical years use name-based cluster matching (their
 *     sections carry strand_id = null).
 */
class SectionAndEnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'sysadmin@salawagshs.deped.gov.ph')->first()
              ?? User::where('email', 'registrar@salawagshs.deped.gov.ph')->first()
              ?? User::first();

        if (! $admin) {
            $this->command->error('  ! No admin user found — skipping section seeder.');
            return;
        }

        $teachers = Teacher::with('user')->get()->keyBy(fn ($t) => $t->user?->email);

        $this->seedHistoricalSections($teachers);
        $this->seedActiveYearSections($teachers);
        $this->enrollAllCohorts($admin);

        $this->command->info('✔ Sections and enrollments seeded.');
        $this->command->info('   Sections: ' . Section::count());
        $this->command->info('   Enrollments: ' . Enrollment::count());
    }

    /* =================================================================
     | 1. HISTORICAL SECTIONS
     *-----------------------------------------------------------------
     | Uses the OLD strand names (HUMSS, ABM, ICT-CP, HE) because these
     | years predate the Strengthened Curriculum. Grade level is
     | embedded in the name: "STEM 11-A", "HUMSS 12-A", etc.
     * ================================================================= */
    protected function seedHistoricalSections(Collection $teachers): void
    {
        $now = Carbon::now();

        $config = [
            '2023-2024' => [
                ['11', 'STEM 11-A',    'ramon.villareal@salawagshs.deped.gov.ph'],
                ['11', 'STEM 11-B',    'marilou.aquino@salawagshs.deped.gov.ph'],
                ['11', 'HUMSS 11-A',   'ricardo.aquino@salawagshs.deped.gov.ph'],
                ['11', 'ABM 11-A',     'elizabeth.reyes@salawagshs.deped.gov.ph'],
                ['11', 'ICT-CP 11-A',  'mark.reyes@salawagshs.deped.gov.ph'],
            ],
            '2024-2025' => [
                ['11', 'STEM 11-A',    'ramon.villareal@salawagshs.deped.gov.ph'],
                ['11', 'STEM 11-B',    'marilou.aquino@salawagshs.deped.gov.ph'],
                ['11', 'HUMSS 11-A',   'ricardo.aquino@salawagshs.deped.gov.ph'],
                ['11', 'ABM 11-A',     'elizabeth.reyes@salawagshs.deped.gov.ph'],
                ['11', 'ICT-CP 11-A',  'mark.reyes@salawagshs.deped.gov.ph'],
                ['12', 'STEM 12-A',    'antonio.cruz@salawagshs.deped.gov.ph'],
                ['12', 'STEM 12-B',    'rebecca.luna@salawagshs.deped.gov.ph'],
                ['12', 'HUMSS 12-A',   'lourdes.santos@salawagshs.deped.gov.ph'],
                ['12', 'ABM 12-A',     'benjamin.ocampo@salawagshs.deped.gov.ph'],
                ['12', 'ICT-CP 12-A',  'christian.bautista@salawagshs.deped.gov.ph'],
            ],
            '2025-2026' => [
                ['11', 'STEM 11-A',    'ramon.villareal@salawagshs.deped.gov.ph'],
                ['11', 'STEM 11-B',    'marilou.aquino@salawagshs.deped.gov.ph'],
                ['11', 'HUMSS 11-A',   'ricardo.aquino@salawagshs.deped.gov.ph'],
                ['11', 'ABM 11-A',     'elizabeth.reyes@salawagshs.deped.gov.ph'],
                ['11', 'ICT-CP 11-A',  'mark.reyes@salawagshs.deped.gov.ph'],
                ['11', 'HE 11-A',      'marilou.delacruz@salawagshs.deped.gov.ph'],
                ['12', 'STEM 12-A',    'antonio.cruz@salawagshs.deped.gov.ph'],
                ['12', 'STEM 12-B',    'rebecca.luna@salawagshs.deped.gov.ph'],
                ['12', 'HUMSS 12-A',   'lourdes.santos@salawagshs.deped.gov.ph'],
                ['12', 'ABM 12-A',     'benjamin.ocampo@salawagshs.deped.gov.ph'],
                ['12', 'ICT-CP 12-A',  'christian.bautista@salawagshs.deped.gov.ph'],
            ],
        ];

        foreach ($config as $yearLabel => $rows) {
            $year = SchoolYear::where('label', $yearLabel)->first();
            if (! $year) {
                continue;
            }

            foreach ($rows as [$gl, $name, $adviserEmail]) {
                Section::updateOrCreate(
                    ['school_year_id' => $year->id, 'grade_level' => $gl, 'name' => $name],
                    [
                        'strand_id'    => null,
                        'adviser_id'   => $teachers->get($adviserEmail)?->id,
                        'max_capacity' => 45,
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ]
                );
            }
        }
    }

    /* =================================================================
     | 2. ACTIVE YEAR SECTIONS
     *-----------------------------------------------------------------
     | Uses the NEW Strengthened Curriculum clusters and embeds grade
     | level into the section name: "STEM 11-A", "STEM 12-A", etc.
     * ================================================================= */
    protected function seedActiveYearSections(Collection $teachers): void
    {
        $now = Carbon::now();

        $year = SchoolYear::where('label', '2026-2027')->first();
        if (! $year) {
            return;
        }

        $clusterIds = Strand::pluck('id', 'code')->all();

        $sections = [
            // Grade 11
            ['11', 'STEM',             'STEM 11-A',             'ramon.villareal@salawagshs.deped.gov.ph'],
            ['11', 'STEM',             'STEM 11-B',             'marilou.aquino@salawagshs.deped.gov.ph'],
            ['11', 'ARTS-SOC-HUM',     'ARTS-SOC-HUM 11-A',     'hannah.bautista@salawagshs.deped.gov.ph'],
            ['11', 'ICT',              'ICT 11-A',              'mark.reyes@salawagshs.deped.gov.ph'],
            ['11', 'BUSINESS-ENTREP',  'BUSINESS-ENTREP 11-A',  'elizabeth.reyes@salawagshs.deped.gov.ph'],
            ['11', 'HOSPITALITY',      'HOSPITALITY 11-A',      'marilou.delacruz@salawagshs.deped.gov.ph'],
            ['11', 'SPORTS-WELLNESS',  'SPORTS-WELLNESS 11-A',  'daniel.lim@salawagshs.deped.gov.ph'],

            // Grade 12
            ['12', 'STEM',             'STEM 12-A',             'antonio.cruz@salawagshs.deped.gov.ph'],
            ['12', 'STEM',             'STEM 12-B',             'rebecca.luna@salawagshs.deped.gov.ph'],
            ['12', 'ARTS-SOC-HUM',     'ARTS-SOC-HUM 12-A',     'ricardo.aquino@salawagshs.deped.gov.ph'],
            ['12', 'BUSINESS-ENTREP',  'BUSINESS-ENTREP 12-A',  'benjamin.ocampo@salawagshs.deped.gov.ph'],
            ['12', 'ICT',              'ICT 12-A',              'christian.bautista@salawagshs.deped.gov.ph'],
            ['12', 'HOSPITALITY',      'HOSPITALITY 12-A',      'ernesto.padilla@salawagshs.deped.gov.ph'],
        ];

        foreach ($sections as [$gl, $clusterCode, $name, $adviserEmail]) {
            Section::updateOrCreate(
                ['school_year_id' => $year->id, 'grade_level' => $gl, 'name' => $name],
                [
                    'strand_id'    => $clusterIds[$clusterCode] ?? null,
                    'adviser_id'   => $teachers->get($adviserEmail)?->id,
                    'max_capacity' => 45,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]
            );
        }
    }

    /* =================================================================
     | 3. ENROLL ALL COHORTS
     *================================================================= */
    protected function enrollAllCohorts(User $admin): void
    {
        $grad2025   = Student::where('status', 'graduated')->whereYear('date_of_birth', 2006)->get();
        $grad2026   = Student::where('status', 'graduated')->whereYear('date_of_birth', 2007)->get();
        $g12Active  = Student::whereIn('status', ['active', 'dropped_out', 'transferred_out'])
                        ->whereYear('date_of_birth', 2008)
                        ->get();
        $g11Active  = Student::where('status', 'active')
                        ->whereYear('date_of_birth', '>=', 2009)
                        ->get();

        // 2023-2024 — grad2025 as G11
        $this->enrollCohort($grad2025, '2023-2024', '11',
            ['STEM 11-A', 'STEM 11-B', 'HUMSS 11-A', 'ABM 11-A', 'ICT-CP 11-A'],
            'completed', $admin);

        // 2024-2025 — grad2025 as G12; grad2026 as G11
        $this->enrollCohort($grad2025, '2024-2025', '12',
            ['STEM 12-A', 'STEM 12-B', 'HUMSS 12-A', 'ABM 12-A', 'ICT-CP 12-A'],
            'completed', $admin);
        $this->enrollCohort($grad2026, '2024-2025', '11',
            ['STEM 11-A', 'STEM 11-B', 'HUMSS 11-A', 'ABM 11-A', 'ICT-CP 11-A'],
            'completed', $admin);

        // 2025-2026 — grad2026 as G12; g12Active as G11
        $this->enrollCohort($grad2026, '2025-2026', '12',
            ['STEM 12-A', 'STEM 12-B', 'HUMSS 12-A', 'ABM 12-A', 'ICT-CP 12-A'],
            'completed', $admin);
        $this->enrollCohort($g12Active, '2025-2026', '11',
            ['STEM 11-A', 'STEM 11-B', 'HUMSS 11-A', 'ABM 11-A', 'ICT-CP 11-A', 'HE 11-A'],
            'enrolled', $admin);

        // 2026-2027 — g12Active as G12; g11Active as G11
        $this->enrollCohort($g12Active, '2026-2027', '12',
            ['STEM 12-A', 'STEM 12-B', 'ARTS-SOC-HUM 12-A', 'BUSINESS-ENTREP 12-A', 'ICT 12-A', 'HOSPITALITY 12-A'],
            'enrolled', $admin);
        $this->enrollCohort($g11Active, '2026-2027', '11',
            ['STEM 11-A', 'STEM 11-B', 'ARTS-SOC-HUM 11-A', 'ICT 11-A', 'BUSINESS-ENTREP 11-A', 'HOSPITALITY 11-A', 'SPORTS-WELLNESS 11-A'],
            'enrolled', $admin);
    }

    /* =================================================================
     | 4. CLUSTER-AWARE ENROLLMENT
     *================================================================= */
    protected function enrollCohort(
        Collection $students,
        string $yearLabel,
        string $gradeLevel,
        array $sectionNames,
        string $status,
        User $admin,
    ): void {
        if ($students->isEmpty()) {
            return;
        }

        $year = SchoolYear::where('label', $yearLabel)->first();
        if (! $year) {
            return;
        }

        $sections = Section::where('school_year_id', $year->id)
            ->where('grade_level', $gradeLevel)
            ->whereIn('name', $sectionNames)
            ->orderBy('id')
            ->get();

        if ($sections->isEmpty()) {
            return;
        }

        $enrolledAt = Carbon::parse($year->start_date)->setTime(8, 30);

        // Partition students by cluster preference.
        $byStrand     = [];
        $noPreference = collect();

        foreach ($students as $student) {
            $preferredStrandId = $this->preferredStrandIdFor($student);

            if ($preferredStrandId) {
                $byStrand[$preferredStrandId][] = $student;
            } else {
                $noPreference->push($student);
            }
        }

        $sectionCounts = [];
        foreach ($sections as $s) {
            $sectionCounts[$s->id] = 0;
        }

        // Place cluster-matched students first.
        foreach ($byStrand as $strandId => $clusterStudents) {
            $matched = $this->sectionsMatchingCluster($sections, $strandId);
            if ($matched->isEmpty()) {
                $matched = $sections;
            }

            foreach ($clusterStudents as $student) {
                $picked = $this->leastLoadedSection($matched, $sectionCounts);
                $this->createEnrollment($student, $year, $picked, $status, $enrolledAt, $admin);
                $sectionCounts[$picked->id]++;
            }
        }

        // Place no-preference students round-robin.
        foreach ($noPreference as $student) {
            $picked = $this->leastLoadedSection($sections, $sectionCounts);
            $this->createEnrollment($student, $year, $picked, $status, $enrolledAt, $admin);
            $sectionCounts[$picked->id]++;
        }
    }

    /* =================================================================
     | 5. HELPER METHODS
     *================================================================= */

    protected function preferredStrandIdFor(Student $student): ?int
    {
        $applicant = Applicant::where('converted_student_id', $student->id)->first();
        return $applicant?->strand_id;
    }

    protected function sectionsMatchingCluster(Collection $sections, int $strandId): Collection
    {
        $direct = $sections->where('strand_id', $strandId)->values();
        if ($direct->isNotEmpty()) {
            return $direct;
        }

        $clusterCode = Strand::where('id', $strandId)->value('code');
        if (! $clusterCode) {
            return collect();
        }

        return $sections->filter(fn ($s) => $this->sectionNameMatchesCluster($s->name, $clusterCode))->values();
    }

    /**
     * Name-based cluster match. Section names follow the format
     * "{CLUSTER} {GRADE}-{LETTER}", e.g. "STEM 11-A", "HUMSS 12-B".
     *
     * Step 1: strip the " {GRADE}-{LETTER}" suffix → "STEM"
     * Step 2: compare to the cluster code (or old-strand alias).
     */
    protected function sectionNameMatchesCluster(string $sectionName, string $clusterCode): bool
    {
        // Strip grade+letter: "STEM 11-A" → "STEM", "ICT-CP 12-B" → "ICT-CP"
        $base = strtoupper(trim(preg_replace('/\s+\d{1,2}-[A-Z]$/', '', $sectionName)));

        // Direct match against the current cluster code.
        if ($base === strtoupper($clusterCode)) {
            return true;
        }

        // Old strand name → new cluster alias (for historical sections).
        $oldToNew = [
            'STEM'   => 'STEM',
            'HUMSS'  => 'ARTS-SOC-HUM',
            'ABM'    => 'BUSINESS-ENTREP',
            'GAS'    => 'ARTS-SOC-HUM',
            'ICT-CP' => 'ICT',
            'ICT'    => 'ICT',
            'HE'     => 'HOSPITALITY',
            'AF'     => 'AGRI-FISHERY',
            'IA'     => 'INDUSTRIAL',
            'AD-VMA' => 'CREATIVE-ARTS',
            'SPTS'   => 'SPORTS-WELLNESS',
        ];

        return isset($oldToNew[$base]) && $oldToNew[$base] === strtoupper($clusterCode);
    }

    protected function leastLoadedSection(Collection $sections, array $counts): Section
    {
        return $sections
            ->sort(function ($a, $b) use ($counts) {
                return [$counts[$a->id] ?? 0, $a->id] <=> [$counts[$b->id] ?? 0, $b->id];
            })
            ->first();
    }

    protected function createEnrollment(
        Student $student,
        SchoolYear $year,
        Section $section,
        string $status,
        Carbon $enrolledAt,
        User $admin,
    ): void {
        Enrollment::updateOrCreate(
            ['student_id' => $student->id, 'school_year_id' => $year->id],
            [
                'section_id'  => $section->id,
                'status'      => $status,
                'enrolled_at' => $enrolledAt,
                'enrolled_by' => $admin->id,
            ]
        );
    }
}