<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Room;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Strand;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Term;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * ClassAndScheduleSeeder
 * ----------------------
 * Creates ClassRoom records (section × subject × term) and their weekly
 * schedules, aligned to the Strengthened SHS Curriculum.
 *
 * ─── CURRICULUM RULES ─────────────────────────────────────────────
 *   • 5 core subjects are taught ENTIRELY in Grade 11 (160 hours each).
 *   • Grade 12 has NO core subjects — only cluster electives.
 *   • Core subjects run 1 hour/day, 5 days/week (fixed slot per subject).
 *   • Grade 11 (morning shift) and Grade 12 (afternoon shift) use
 *     separate 6-period days.
 *
 * ─── SCHEDULE PATTERNS ────────────────────────────────────────────
 *
 *   GRADE 11 — Morning shift  06:00-12:15 (after 15-min Health Break)
 *
 *     Slot 0  06:00-07:00  CORE-COMM   (every day)
 *     Slot 1  07:00-08:00  CORE-MATH   (every day)
 *     Slot 2  08:15-09:15  CORE-SCI    (every day)
 *     Slot 3  09:15-10:15  CORE-LCS    (every day)
 *     Slot 4  10:15-11:15  CORE-KASP   (every day)
 *     Slot 5  11:15-12:15  Elective A (Mon/Wed/Fri)  or
 *                          Elective B (Tue/Thu)
 *
 *   GRADE 12 — Afternoon shift  12:30-18:45
 *
 *     Slot 0  12:30-13:30  Elective 1 (every day)
 *     Slot 1  13:30-14:30  Elective 2 (every day)
 *     Slot 2  14:45-15:45  Elective 3 (every day)
 *     Slot 3  15:45-16:45  Elective 4 (every day)
 *     Slot 4  16:45-17:45  Elective 5 (every day)
 *     Slot 5  17:45-18:45  Elective 6 (every day)
 *
 * This yields:
 *   • Each core subject: 5 hours/week ≈ 160 hours/year  ✓
 *   • Each Grade 12 elective: 5 hours/week
 *   • Every day (Mon-Fri) fully filled for every section
 */
class ClassAndScheduleSeeder extends Seeder
{
    /* ---------------------------------------------------------------
     | Time slots
     * --------------------------------------------------------------- */
    private const MORNING_SLOTS = [
        ['06:00:00', '07:00:00'],   // Slot 0
        ['07:00:00', '08:00:00'],   // Slot 1
        ['08:15:00', '09:15:00'],   // Slot 2 (after 15-min Health Break)
        ['09:15:00', '10:15:00'],   // Slot 3
        ['10:15:00', '11:15:00'],   // Slot 4
        ['11:15:00', '12:15:00'],   // Slot 5
    ];

    private const AFTERNOON_SLOTS = [
        ['12:30:00', '13:30:00'],   // Slot 0
        ['13:30:00', '14:30:00'],   // Slot 1
        ['14:45:00', '15:45:00'],   // Slot 2 (after 15-min Health Break)
        ['15:45:00', '16:45:00'],   // Slot 3
        ['16:45:00', '17:45:00'],   // Slot 4
        ['17:45:00', '18:45:00'],   // Slot 5
    ];

    private const DAYS = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

    /* ---------------------------------------------------------------
     | Core subjects & teachers
     * --------------------------------------------------------------- */
    private const CORE_SUBJECTS = [
        'CORE-COMM',   // Slot 0
        'CORE-MATH',   // Slot 1
        'CORE-SCI',    // Slot 2
        'CORE-LCS',    // Slot 3
        'CORE-KASP',   // Slot 4
    ];

    private const CORE_TEACHERS = [
        'CORE-COMM' => 'jocelyn.fernandez@salawagshs.deped.gov.ph',
        'CORE-MATH' => 'antonio.cruz@salawagshs.deped.gov.ph',
        'CORE-SCI'  => 'rebecca.luna@salawagshs.deped.gov.ph',
        'CORE-LCS'  => 'benjamin.ocampo@salawagshs.deped.gov.ph',
        'CORE-KASP' => 'lourdes.santos@salawagshs.deped.gov.ph',
    ];

    /* ---------------------------------------------------------------
     | Cluster elective plans
     |-----------------------------------------------------------------
     | Each cluster defines:
     |   • g11    — 2 intro-level electives for Grade 11
     |   • g12    — 6 advanced electives for Grade 12
     |              (cross-cluster padding where the cluster alone
     |              cannot supply 6 distinct advanced subjects)
     * --------------------------------------------------------------- */
    private const CLUSTER_PLANS = [
        'STEM' => [
            'g11' => ['STEM-PCALC', 'STEM-BIO12'],
            'g12' => ['STEM-BCALC', 'STEM-CHEM12', 'STEM-PHYS12', 'STEM-RESMETH', 'STEM-DBMGMT', 'STEM-DATA'],
        ],
        'ICT' => [
            'g11' => ['ICT-CSS', 'ICT-PROGJAVA'],
            'g12' => ['ICT-PROGDOTNET', 'ICT-BROADBAND', 'ICT-CONTACT', 'STEM-EMPowTECH', 'STEM-DBMGMT', 'BE-ENTREP'],
        ],
        'HOSPITALITY' => [
            'g11' => ['HO-FBO', 'HO-BAKERY'],
            'g12' => ['HO-KITCHEN', 'HO-HOUSEKEEP', 'HO-BARTEND', 'HO-EVENTS', 'BE-ENTREP', 'BE-ORG-MGMT'],
        ],
        'ARTS-SOC-HUM' => [
            'g11' => ['ASH-CREATW1', 'ASH-WORLDREL'],
            'g12' => ['ASH-DISCSOC', 'ASH-DISCAPP', 'ASH-PHILHUM', 'ASH-TRENDS', 'ASH-COMMENG', 'ASH-CREATIND'],
        ],
        'BUSINESS-ENTREP' => [
            'g11' => ['BE-BASACC', 'BE-ORG-MGMT'],
            'g12' => ['BE-BUSFIN', 'BE-CONTMKT', 'BE-ENTREP', 'STEM-EMPowTECH', 'STEM-DBMGMT', 'ASH-COMMENG'],
        ],
        'SPORTS-WELLNESS' => [
            'g11' => ['SW-EXSPORT', 'SW-HUMANMOV'],
            'g12' => ['SW-SPORTOFF', 'SW-SAFEFIRST', 'SW-SPORTCOACH', 'SW-SPORTMGMT', 'SW-PEFIT', 'SW-PESPORT'],
        ],
        'AGRI-FISHERY' => [
            'g11' => ['AF-CROP', 'AF-AGROENT'],
            'g12' => ['AF-AQUA', 'AF-FOODINNO', 'AF-CROP', 'AF-AGROENT', 'BE-ENTREP', 'STEM-RESMETH'],
        ],
        'INDUSTRIAL' => [
            'g11' => ['IN-REFRIG', 'IN-AIRCON'],
            'g12' => ['IN-ELEC', 'IN-SOLAR', 'IN-REFRIG', 'IN-AIRCON', 'BE-ENTREP', 'STEM-RESMETH'],
        ],
        'AUTOMOTIVE' => [
            'g11' => ['AU-AUTO', 'AU-SMALLENG'],
            'g12' => ['AU-AUTO', 'AU-SMALLENG', 'IN-REFRIG', 'IN-ELEC', 'BE-ENTREP', 'STEM-RESMETH'],
        ],
        'CONSTRUCTION' => [
            'g11' => ['CO-CARP', 'CO-MASON'],
            'g12' => ['CO-PLUMB', 'CO-CARP', 'CO-MASON', 'IN-ELEC', 'BE-ENTREP', 'STEM-RESMETH'],
        ],
        'CREATIVE-ARTS' => [
            'g11' => ['CA-VISGRAPH', 'CA-ANIM'],
            'g12' => ['CA-MUSIC', 'CA-VISGRAPH', 'CA-ANIM', 'ASH-MEDARTS', 'ASH-ARTSAPP', 'ASH-CREATIND'],
        ],
        'ARTISANRY' => [
            'g11' => ['AR-GARMENTS', 'AR-FASHACC'],
            'g12' => ['AR-GARMENTS', 'AR-FASHACC', 'BE-ENTREP', 'STEM-RESMETH', 'BE-ORG-MGMT', 'ASH-CREATIND'],
        ],
        'AESTHETIC-WELLNESS' => [
            'g11' => ['AW-BEAUTY', 'AW-CAREADULT'],
            'g12' => ['AW-BARBER', 'AW-CARECHILD', 'AW-BEAUTY', 'AW-CAREADULT', 'BE-ENTREP', 'STEM-RESMETH'],
        ],
        'MARITIME' => [
            'g11' => ['MA-MARENG', 'MA-FIELDEXP'],
            'g12' => ['MA-MARENG', 'MA-FIELDEXP', 'IN-REFRIG', 'IN-ELEC', 'BE-ENTREP', 'STEM-RESMETH'],
        ],
        'FIELD-EXP' => [
            'g11' => ['FE-RESMETH', 'FE-DESIGN'],
            'g12' => ['FE-WORKIMM', 'FE-DESIGN', 'FE-CREATPROD', 'ASH-COMMENG', 'BE-ENTREP', 'STEM-RESMETH'],
        ],
    ];

    /* ---------------------------------------------------------------
     | Teacher for each elective subject code
     * --------------------------------------------------------------- */
    private const ELECTIVE_TEACHERS = [
        'STEM-PCALC'     => 'ramon.villareal@salawagshs.deped.gov.ph',
        'STEM-BCALC'     => 'ramon.villareal@salawagshs.deped.gov.ph',
        'STEM-TRIG'      => 'ramon.villareal@salawagshs.deped.gov.ph',
        'STEM-ADVMATH'   => 'ramon.villareal@salawagshs.deped.gov.ph',
        'STEM-FINMATH'   => 'ramon.villareal@salawagshs.deped.gov.ph',
        'STEM-BIO12'     => 'marilou.aquino@salawagshs.deped.gov.ph',
        'STEM-BIO34'     => 'marilou.aquino@salawagshs.deped.gov.ph',
        'STEM-CHEM12'    => 'marilou.aquino@salawagshs.deped.gov.ph',
        'STEM-CHEM34'    => 'marilou.aquino@salawagshs.deped.gov.ph',
        'STEM-PHYS12'    => 'marilou.aquino@salawagshs.deped.gov.ph',
        'STEM-PHYS34'    => 'marilou.aquino@salawagshs.deped.gov.ph',
        'STEM-EARTH'     => 'marilou.aquino@salawagshs.deped.gov.ph',
        'STEM-EARTH34'   => 'marilou.aquino@salawagshs.deped.gov.ph',
        'STEM-RESMETH'   => 'marilou.aquino@salawagshs.deped.gov.ph',
        'STEM-DATA'      => 'mark.reyes@salawagshs.deped.gov.ph',
        'STEM-EMPowTECH' => 'mark.reyes@salawagshs.deped.gov.ph',
        'STEM-DBMGMT'    => 'mark.reyes@salawagshs.deped.gov.ph',
        'ICT-CSS'        => 'mark.reyes@salawagshs.deped.gov.ph',
        'ICT-PROGJAVA'   => 'mark.reyes@salawagshs.deped.gov.ph',
        'ICT-PROGDOTNET' => 'christian.bautista@salawagshs.deped.gov.ph',
        'ICT-BROADBAND'  => 'christian.bautista@salawagshs.deped.gov.ph',
        'ICT-CONTACT'    => 'christian.bautista@salawagshs.deped.gov.ph',
        'HO-FBO'         => 'marilou.delacruz@salawagshs.deped.gov.ph',
        'HO-BAKERY'      => 'ernesto.padilla@salawagshs.deped.gov.ph',
        'HO-KITCHEN'     => 'marilou.delacruz@salawagshs.deped.gov.ph',
        'HO-HOUSEKEEP'   => 'ernesto.padilla@salawagshs.deped.gov.ph',
        'HO-BARTEND'     => 'ernesto.padilla@salawagshs.deped.gov.ph',
        'HO-EVENTS'      => 'marilou.delacruz@salawagshs.deped.gov.ph',
        'ASH-CREATW1'    => 'ricardo.aquino@salawagshs.deped.gov.ph',
        'ASH-WORLDREL'   => 'ricardo.aquino@salawagshs.deped.gov.ph',
        'ASH-DISCSOC'    => 'ricardo.aquino@salawagshs.deped.gov.ph',
        'ASH-DISCAPP'    => 'ricardo.aquino@salawagshs.deped.gov.ph',
        'ASH-PHILHUM'    => 'ricardo.aquino@salawagshs.deped.gov.ph',
        'ASH-TRENDS'     => 'ricardo.aquino@salawagshs.deped.gov.ph',
        'ASH-COMMENG'    => 'ricardo.aquino@salawagshs.deped.gov.ph',
        'ASH-CREATIND'   => 'hannah.bautista@salawagshs.deped.gov.ph',
        'ASH-MEDARTS'    => 'hannah.bautista@salawagshs.deped.gov.ph',
        'ASH-ARTSAPP'    => 'hannah.bautista@salawagshs.deped.gov.ph',
        'BE-BASACC'      => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'BE-BUSFIN'      => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'BE-CONTMKT'     => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'BE-ENTREP'      => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'BE-ORG-MGMT'    => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'SW-EXSPORT'     => 'daniel.lim@salawagshs.deped.gov.ph',
        'SW-HUMANMOV'    => 'daniel.lim@salawagshs.deped.gov.ph',
        'SW-SPORTOFF'    => 'daniel.lim@salawagshs.deped.gov.ph',
        'SW-SAFEFIRST'   => 'daniel.lim@salawagshs.deped.gov.ph',
        'SW-SPORTCOACH'  => 'daniel.lim@salawagshs.deped.gov.ph',
        'SW-SPORTMGMT'   => 'daniel.lim@salawagshs.deped.gov.ph',
        'SW-PEFIT'       => 'daniel.lim@salawagshs.deped.gov.ph',
        'SW-PESPORT'     => 'daniel.lim@salawagshs.deped.gov.ph',
        'AF-CROP'        => 'rogelio.fernandez@salawagshs.deped.gov.ph',
        'AF-AQUA'        => 'rogelio.fernandez@salawagshs.deped.gov.ph',
        'AF-AGROENT'     => 'rogelio.fernandez@salawagshs.deped.gov.ph',
        'AF-FOODINNO'    => 'rogelio.fernandez@salawagshs.deped.gov.ph',
        'IN-REFRIG'      => 'rodolfo.villamor@salawagshs.deped.gov.ph',
        'IN-ELEC'        => 'rodolfo.villamor@salawagshs.deped.gov.ph',
        'IN-AIRCON'      => 'rodolfo.villamor@salawagshs.deped.gov.ph',
        'IN-SOLAR'       => 'rodolfo.villamor@salawagshs.deped.gov.ph',
        'AU-AUTO'        => 'mario.villanueva@salawagshs.deped.gov.ph',
        'AU-SMALLENG'    => 'mario.villanueva@salawagshs.deped.gov.ph',
        'CO-CARP'        => 'mario.villanueva@salawagshs.deped.gov.ph',
        'CO-MASON'       => 'mario.villanueva@salawagshs.deped.gov.ph',
        'CO-PLUMB'       => 'mario.villanueva@salawagshs.deped.gov.ph',
        'CA-VISGRAPH'    => 'hannah.bautista@salawagshs.deped.gov.ph',
        'CA-ANIM'        => 'hannah.bautista@salawagshs.deped.gov.ph',
        'CA-MUSIC'       => 'hannah.bautista@salawagshs.deped.gov.ph',
        'AR-GARMENTS'    => 'marilou.delacruz@salawagshs.deped.gov.ph',
        'AR-FASHACC'     => 'marilou.delacruz@salawagshs.deped.gov.ph',
        'AW-BEAUTY'      => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'AW-BARBER'      => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'AW-CAREADULT'   => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'AW-CARECHILD'   => 'elizabeth.reyes@salawagshs.deped.gov.ph',
        'MA-MARENG'      => 'rodolfo.villamor@salawagshs.deped.gov.ph',
        'MA-FIELDEXP'    => 'rodolfo.villamor@salawagshs.deped.gov.ph',
        'FE-RESMETH'     => 'marilou.aquino@salawagshs.deped.gov.ph',
        'FE-DESIGN'      => 'hannah.bautista@salawagshs.deped.gov.ph',
        'FE-CREATPROD'   => 'hannah.bautista@salawagshs.deped.gov.ph',
        'FE-WORKIMM'     => 'marilou.aquino@salawagshs.deped.gov.ph',
    ];

    /* =================================================================
     | RUN
     *================================================================= */
    public function run(): void
    {
        $teacherByEmail = Teacher::with('user')->get()
            ->keyBy(fn ($t) => $t->user?->email);

        $roomIds    = Room::pluck('id', 'code')->all();
        $subjectIds = Subject::pluck('id', 'code')->all();

        $totalClasses   = 0;
        $totalSchedules = 0;

        foreach (SchoolYear::orderBy('label')->get() as $year) {

            // Pick the term to build classes for.
            // New calendar years have "1st Term"; old ones have "1st Semester".
            $term = Term::where('school_year_id', $year->id)
                ->where('is_active', true)
                ->first()
                ?? Term::where('school_year_id', $year->id)
                    ->orderBy('start_date')
                    ->first();

            if (! $term) {
                continue;
            }

            $sections = Section::where('school_year_id', $year->id)->get();

            foreach ($sections as $section) {
                $clusterCode = $this->resolveClusterCode($section);
                $isG11       = $section->grade_level === '11';

                // ── Create ClassRoom rows ────────────────────────
                if ($isG11) {
                    $classes = $this->createGrade11Classes(
                        $section, $term, $clusterCode,
                        $subjectIds, $teacherByEmail
                    );
                } else {
                    $classes = $this->createGrade12Classes(
                        $section, $term, $clusterCode,
                        $subjectIds, $teacherByEmail
                    );
                }

                $totalClasses += $classes->count();

                if ($classes->isEmpty()) {
                    continue;
                }

                // ── Build and persist schedule ───────────────────
                $totalSchedules += $this->scheduleClasses($section, $classes, $roomIds);
            }
        }

        $this->command->info("✔ Classes seeded: {$totalClasses}");
        $this->command->info("✔ Schedule entries seeded: {$totalSchedules}");
        $this->command->info('   Cores: Grade 11 only, 1h/day × 5 days/week');
        $this->command->info('   Electives: G11 alternating; G12 six electives daily');
    }

    /* =================================================================
     | CLASS CREATION — GRADE 11 (5 cores + 2 electives)
     * ================================================================= */
    protected function createGrade11Classes(
        Section $section,
        Term $term,
        string $clusterCode,
        array $subjectIds,
        \Illuminate\Support\Collection $teacherByEmail,
    ): \Illuminate\Support\Collection {
        $classes = collect();

        // 5 cores — fixed slots 0-4.
        foreach (self::CORE_SUBJECTS as $code) {
            $class = $this->upsertClass(
                $subjectIds[$code] ?? null,
                $section->id,
                $term->id,
                $teacherByEmail->get(self::CORE_TEACHERS[$code] ?? '')?->id,
            );

            if ($class) {
                $classes->push(['class' => $class, 'code' => $code, 'is_core' => true]);
            }
        }

        // 2 cluster electives.
        $plan = self::CLUSTER_PLANS[$clusterCode] ?? null;
        if ($plan) {
            foreach ($plan['g11'] as $code) {
                $teacherEmail = self::ELECTIVE_TEACHERS[$code] ?? null;
                $class = $this->upsertClass(
                    $subjectIds[$code] ?? null,
                    $section->id,
                    $term->id,
                    $teacherByEmail->get($teacherEmail)?->id,
                );

                if ($class) {
                    $classes->push(['class' => $class, 'code' => $code, 'is_core' => false]);
                }
            }
        }

        return $classes;
    }

    /* =================================================================
     | CLASS CREATION — GRADE 12 (6 electives, no cores)
     * ================================================================= */
    protected function createGrade12Classes(
        Section $section,
        Term $term,
        string $clusterCode,
        array $subjectIds,
        \Illuminate\Support\Collection $teacherByEmail,
    ): \Illuminate\Support\Collection {
        $classes = collect();

        $plan = self::CLUSTER_PLANS[$clusterCode] ?? null;
        if (! $plan) {
            return $classes;
        }

        foreach ($plan['g12'] as $code) {
            $teacherEmail = self::ELECTIVE_TEACHERS[$code] ?? null;
            $class = $this->upsertClass(
                $subjectIds[$code] ?? null,
                $section->id,
                $term->id,
                $teacherByEmail->get($teacherEmail)?->id,
            );

            if ($class) {
                $classes->push(['class' => $class, 'code' => $code, 'is_core' => false]);
            }
        }

        return $classes;
    }

    /* =================================================================
     | UPSERT CLASSROOM ROW
     * ================================================================= */
    protected function upsertClass(?int $subjectId, int $sectionId, int $termId, ?int $teacherId): ?ClassRoom
    {
        if (! $subjectId) {
            return null;
        }

        return ClassRoom::updateOrCreate(
            [
                'subject_id' => $subjectId,
                'section_id' => $sectionId,
                'term_id'    => $termId,
            ],
            [
                'teacher_id'              => $teacherId,
                'weight_written_work'     => 25.00,
                'weight_performance_task' => 50.00,
                'weight_quarterly_exam'   => 25.00,
                'is_published'            => true,
            ]
        );
    }

    /* =================================================================
     | SCHEDULE
     *================================================================= */
    protected function scheduleClasses(
        Section $section,
        \Illuminate\Support\Collection $classes,
        array $roomIds,
    ): int {
        $isG11  = $section->grade_level === '11';
        $slots  = $isG11 ? self::MORNING_SLOTS : self::AFTERNOON_SLOTS;
        $homeRoom = $this->homeRoomForSection($section, $roomIds);
        $count  = 0;

        if ($isG11) {
            $count += $this->scheduleGrade11($section, $classes, $slots, $homeRoom, $roomIds);
        } else {
            $count += $this->scheduleGrade12($section, $classes, $slots, $homeRoom, $roomIds);
        }

        return $count;
    }

    /**
     * Grade 11 grid:
     *   - 5 cores at slots 0-4, every weekday.
     *   - 2 electives alternating on slot 5 (E0: M/W/F, E1: T/Th).
     */
    protected function scheduleGrade11(
        Section $section,
        \Illuminate\Support\Collection $classes,
        array $slots,
        ?int $homeRoom,
        array $roomIds,
    ): int {
        $cores     = $classes->where('is_core', true)->values();
        $electives = $classes->where('is_core', false)->values();

        $count = 0;

        // Cores: fixed slot per subject index, every day.
        foreach ($cores as $idx => $row) {
            $slotIdx = $idx;   // 0,1,2,3,4
            [$start, $end] = $slots[$slotIdx];

            foreach (self::DAYS as $day) {
                $count += $this->persistSchedule($row['class'], $day, $start, $end, $homeRoom, $roomIds);
            }
        }

        // Electives: slot 5, alternating days.
        $e0 = $electives->get(0);
        $e1 = $electives->get(1);

        [$start, $end] = $slots[5];

        if ($e0) {
            foreach (['monday', 'wednesday', 'friday'] as $day) {
                $count += $this->persistSchedule($e0['class'], $day, $start, $end, $homeRoom, $roomIds);
            }
        }
        if ($e1) {
            foreach (['tuesday', 'thursday'] as $day) {
                $count += $this->persistSchedule($e1['class'], $day, $start, $end, $homeRoom, $roomIds);
            }
        }

        return $count;
    }

    /**
     * Grade 12 grid:
     *   - 6 electives at slots 0-5, each meeting every weekday.
     */
    protected function scheduleGrade12(
        Section $section,
        \Illuminate\Support\Collection $classes,
        array $slots,
        ?int $homeRoom,
        array $roomIds,
    ): int {
        $electives = $classes->values();

        $count = 0;

        foreach ($electives as $idx => $row) {
            if ($idx >= count($slots)) {
                break;
            }
            [$start, $end] = $slots[$idx];

            foreach (self::DAYS as $day) {
                $count += $this->persistSchedule($row['class'], $day, $start, $end, $homeRoom, $roomIds);
            }
        }

        return $count;
    }

    /**
     * Insert/update a single class_schedules row.
     */
    protected function persistSchedule(
        ClassRoom $class,
        string $day,
        string $start,
        string $end,
        ?int $homeRoom,
        array $roomIds,
    ): int {
        $subject = Subject::find($class->subject_id);
        $roomId  = $this->pickRoomForSubject($subject, $homeRoom, $roomIds);
        $now     = Carbon::now();

        DB::table('class_schedules')->updateOrInsert(
            [
                'class_id'    => $class->id,
                'day_of_week' => $day,
                'time_start'  => $start,
            ],
            [
                'room_id'    => $roomId,
                'time_end'   => $end,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        return 1;
    }

    /* =================================================================
     | ROOM RESOLUTION
     *================================================================= */
    protected function homeRoomForSection(Section $section, array $roomIds): ?int
    {
        $regular = Room::where('type', 'regular')->pluck('id')->values()->all();
        if (empty($regular)) {
            return array_values($roomIds)[0] ?? null;
        }
        return $regular[$section->id % count($regular)];
    }

    protected function pickRoomForSubject(?Subject $subject, ?int $homeRoomId, array $roomIds): ?int
    {
        if (! $subject) {
            return $homeRoomId;
        }

        $code = $subject->code;

        $specialized = match (true) {
            str_starts_with($code, 'ICT-')       => $roomIds['COMP-LAB-1'] ?? null,
            str_starts_with($code, 'STEM-BIO'),
            str_starts_with($code, 'STEM-CHEM'),
            str_starts_with($code, 'STEM-PHYS'),
            str_starts_with($code, 'STEM-EARTH') => $roomIds['SCI-LAB-1'] ?? null,
            str_starts_with($code, 'HO-')        => $roomIds['HE-LAB'] ?? null,
            str_starts_with($code, 'AF-')        => $roomIds['AF-FARM'] ?? null,
            str_starts_with($code, 'AU-')        => $roomIds['AUTO-SHOP'] ?? null,
            str_starts_with($code, 'IN-')        => $roomIds['IA-SHOP'] ?? null,
            str_starts_with($code, 'CO-')        => $roomIds['IA-SHOP'] ?? null,
            str_starts_with($code, 'CA-')        => $roomIds['ARTS-STUDIO'] ?? null,
            str_starts_with($code, 'AR-')        => $roomIds['ARTS-STUDIO'] ?? null,
            str_starts_with($code, 'AW-')        => $roomIds['HE-LAB'] ?? null,
            str_starts_with($code, 'MA-')        => $roomIds['MARITIME-LAB'] ?? null,
            str_starts_with($code, 'SW-')        => $roomIds['GYM'] ?? null,
            default                              => $homeRoomId,
        };

        return $specialized ?? $homeRoomId;
    }

    /* =================================================================
     | CLUSTER RESOLUTION FOR HISTORICAL SECTIONS
     *================================================================= */
    protected function resolveClusterCode(Section $section): string
    {
        if ($section->strand_id) {
            return Strand::where('id', $section->strand_id)->value('code') ?? 'STEM';
        }

        // Historical sections: parse the leading token from the name.
        // "STEM 11-A" → "STEM", "HUMSS 12-B" → "HUMSS", etc.
        $name = strtoupper($section->name);
        $base = preg_replace('/\s+\d{1,2}-[A-Z]$/', '', $name);

        $map = [
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

        return $map[$base] ?? 'STEM';
    }
}