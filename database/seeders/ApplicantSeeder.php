<?php

namespace Database\Seeders;

use App\Models\Applicant;
use App\Models\ApplicantContact;
use App\Models\ApplicantDocument;
use App\Models\EntranceExam;
use App\Models\EntranceExamResult;
use App\Models\SchoolYear;
use App\Models\Strand;
use App\Models\Track;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * ApplicantSeeder
 * ---------------
 * Seeds the S.Y. 2026-2027 admissions pipeline for Salawag SHS.
 *
 * Depends on: ReferenceDataSeeder (school years, tracks, clusters),
 *             UserAndStaffSeeder (registrar / sysadmin for reviewed_by).
 *
 * Produces:
 *   • 32 applicants — every status represented:
 *       pending (6) | under_review (5) | approved (6) |
 *       rejected (3) | enrolled (7) | needs_resubmission (5)
 *   • ~110 applicant contacts (father + mother + emergency per applicant,
 *     plus a guardian for ~1/3)
 *   • ~170 applicant documents with status matching the applicant state
 *   • 6 entrance exams — all three lifecycle states
 *   • ~20 entrance-exam results for reviewed applicants
 *
 * The 7 "enrolled" applicants intentionally leave `converted_student_id`
 * NULL here; StudentConversionSeeder will create the students and
 * back-fill those links so the applicant→student flow is real.
 */
class ApplicantSeeder extends Seeder
{
    public function run(): void
    {
        $year = SchoolYear::where('is_active', true)->firstOrFail();
        $this->command->info("  · Seeding applicants for SY {$year->label}");

        // Users who act as reviewers (Registrar + System Admin).
        $registrar = User::where('email', 'registrar@salawagshs.deped.gov.ph')->first()
                  ?? User::where('email', 'sysadmin@salawagshs.deped.gov.ph')->first()
                  ?? User::first();

        $clusterIds = Strand::pluck('id', 'code')->all();
        $trackIds   = Track::pluck('id', 'code')->all();

        // ─────────────────────────────────────────────────────────
        // ENTRANCE EXAMS — all three lifecycle states
        // ─────────────────────────────────────────────────────────
        $exams = $this->seedEntranceExams($year, $trackIds);

        // ─────────────────────────────────────────────────────────
        // APPLICANTS — handcrafted core
        // ─────────────────────────────────────────────────────────
        $applicants = $this->seedApplicants($year, $clusterIds, $registrar);

        // ─────────────────────────────────────────────────────────
        // ENTRANCE EXAM RESULTS — only for reviewed applicants
        // ─────────────────────────────────────────────────────────
        $this->seedEntranceExamResults($applicants, $exams, $registrar);

        $this->command->info('✔ Applicants seeded: '
            . $applicants->count() . ' applicants, '
            . count($exams) . ' entrance exams, '
            . EntranceExamResult::count() . ' exam results.');
    }

    /* =================================================================
     | ENTRANCE EXAMS
     * ================================================================= */
    protected function seedEntranceExams(SchoolYear $year, array $trackIds): array
    {
        $now = Carbon::now();

        $examData = [
            [
                'exam_name'    => 'SHS Entrance Exam — Academic Track (Batch 1)',
                'exam_date'    => '2026-05-16',
                'exam_time'    => '08:00:00',
                'venue'        => 'Computer Laboratory 1',
                'max_capacity' => 60,
                'grade_level'  => '11',
                'status'       => 'Completed',
                'track_id'     => $trackIds['ACAD'] ?? null,
            ],
            [
                'exam_name'    => 'SHS Entrance Exam — Technical-Professional Track (Batch 2)',
                'exam_date'    => '2026-05-23',
                'exam_time'    => '08:00:00',
                'venue'        => 'Room 102',
                'max_capacity' => 60,
                'grade_level'  => '11',
                'status'       => 'Completed',
                'track_id'     => $trackIds['TECHPRO'] ?? null,
            ],
            [
                'exam_name'    => 'SHS Entrance Exam — Open Batch (All Clusters)',
                'exam_date'    => '2026-06-06',
                'exam_time'    => '08:00:00',
                'venue'        => 'Audio-Visual Room',
                'max_capacity' => 80,
                'grade_level'  => '11',
                'status'       => 'Completed',
                'track_id'     => null,
            ],
            [
                'exam_name'    => 'Grade 12 Transferee Entrance Exam',
                'exam_date'    => '2026-06-20',
                'exam_time'    => '13:00:00',
                'venue'        => 'Room 102',
                'max_capacity' => 30,
                'grade_level'  => '12',
                'status'       => 'Completed',
                'track_id'     => null,
            ],
            [
                'exam_name'    => 'SHS Entrance Exam — Late Applicants',
                'exam_date'    => '2026-07-11',
                'exam_time'    => '09:00:00',
                'venue'        => 'Audio-Visual Room',
                'max_capacity' => 40,
                'grade_level'  => 'All',
                'status'       => 'Upcoming',
                'track_id'     => null,
            ],
            [
                'exam_name'    => 'SHS Entrance Exam — Final Batch',
                'exam_date'    => '2026-07-25',
                'exam_time'    => '08:00:00',
                'venue'        => 'Room 101',
                'max_capacity' => 40,
                'grade_level'  => 'All',
                'status'       => 'Upcoming',
                'track_id'     => null,
            ],
        ];

        $exams = [];
        foreach ($examData as $row) {
            $exams[] = EntranceExam::updateOrCreate(
                ['school_year_id' => $year->id, 'exam_name' => $row['exam_name']],
                array_merge($row, [
                    'school_year_id' => $year->id,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ])
            );
        }

        return $exams;
    }

    /* =================================================================
     | APPLICANTS
     |-----------------------------------------------------------------
     | 32 handcrafted applicants so every cluster has representation
     | and every status is populated.
     * ================================================================= */
    protected function seedApplicants(SchoolYear $year, array $clusterIds, ?User $registrar): \Illuminate\Support\Collection
    {
        // [ref_seq, first, middle, last, ext, sex, dob, type, desired_gl,
        //  cluster_code, status, extra]
        $rows = [
            // ═══ PENDING (6) — submitted recently, no review yet ═══
            [1, 'Juan Miguel', 'Santos',   'Dela Cruz',  null,  'Male',   '2010-02-14', 'Grade11',    '11', 'STEM',             'pending', ['submitted_at' => '2026-07-02 09:15:00']],
            [2, 'Andrea',      'Reyes',    'Bautista',   null,  'Female', '2010-05-22', 'Grade11',    '11', 'STEM',             'pending', ['submitted_at' => '2026-07-02 10:30:00']],
            [3, 'Mark Joseph', 'Cruz',     'Ocampo',     null,  'Male',   '2010-08-09', 'Grade11',    '11', 'ICT',              'pending', ['submitted_at' => '2026-07-03 14:05:00']],
            [4, 'Kristine Joy','Lim',      'Tan',        null,  'Female', '2010-11-30', 'Grade11',    '11', 'HOSPITALITY',      'pending', ['submitted_at' => '2026-07-04 08:45:00']],
            [5, 'Paolo',       'Garcia',   'Mendoza',    null,  'Male',   '2010-07-18', 'Grade11',    '11', 'BUSINESS-ENTREP',  'pending', ['submitted_at' => '2026-07-05 16:20:00']],
            [6, 'Bea Angela',  'Torres',   'Villanueva', null,  'Female', '2010-04-02', 'Grade11',    '11', 'ARTS-SOC-HUM',     'pending', ['submitted_at' => '2026-07-06 11:00:00']],

            // ═══ UNDER REVIEW (5) — being evaluated right now ═══
            [7,  'Rafael',    'Domingo',  'Navarro',   'Jr.', 'Male',   '2010-01-25', 'Grade11',    '11', 'STEM',            'under_review', ['submitted_at' => '2026-06-28 09:00:00']],
            [8,  'Camila',    'Aquino',   'Ramos',     null,  'Female', '2010-09-12', 'Grade11',    '11', 'BUSINESS-ENTREP', 'under_review', ['submitted_at' => '2026-06-28 13:30:00']],
            [9,  'Lorenzo',   'Navarro',  'Domingo',   null,  'Male',   '2009-08-03', 'Transferee', '12', 'HOSPITALITY',     'under_review', ['submitted_at' => '2026-06-29 08:00:00']],
            [10, 'Patricia',  'Ramos',    'Aquino',    null,  'Female', '2010-12-25', 'Grade11',    '11', 'ICT',             'under_review', ['submitted_at' => '2026-06-29 16:10:00']],
            [11, 'Gabriel',   'Salazar',  'Pascual',   null,  'Male',   '2010-06-04', 'Grade11',    '11', 'SPORTS-WELLNESS', 'under_review', ['submitted_at' => '2026-06-30 10:20:00']],

            // ═══ APPROVED (6) — passed review, waiting to enroll ═══
            [12, 'Liza Marie', 'Cruz',    'Reyes',     null,  'Female', '2010-05-08', 'Grade11',    '11', 'STEM',             'approved', ['submitted_at' => '2026-06-01 09:00:00', 'reviewed_at' => '2026-06-05 10:00:00']],
            [13, 'Marco',      'Bautista','Garcia',    null,  'Male',   '2010-10-21', 'Grade11',    '11', 'ICT',              'approved', ['submitted_at' => '2026-06-02 11:30:00', 'reviewed_at' => '2026-06-06 13:00:00']],
            [14, 'Isabella',   'Ocampo',  'Torres',    null,  'Female', '2010-02-11', 'Grade11',    '11', 'ARTS-SOC-HUM',     'approved', ['submitted_at' => '2026-06-03 14:45:00', 'reviewed_at' => '2026-06-07 09:20:00']],
            [15, 'Rogelio',    'Villamor','Padilla',   null,  'Male',   '2010-09-09', 'Grade11',    '11', 'INDUSTRIAL',       'approved', ['submitted_at' => '2026-06-04 08:30:00', 'reviewed_at' => '2026-06-08 11:00:00']],
            [16, 'Janine',     'Reyes',   'Soriano',   null,  'Female', '2010-07-27', 'Grade11',    '11', 'HOSPITALITY',      'approved', ['submitted_at' => '2026-06-05 10:15:00', 'reviewed_at' => '2026-06-09 14:30:00']],
            [17, 'Miguel',     'Angeles', 'Diaz',      null,  'Male',   '2009-11-19', 'Transferee', '12', 'ICT',              'approved', ['submitted_at' => '2026-06-06 15:20:00', 'reviewed_at' => '2026-06-10 09:45:00']],

            // ═══ REJECTED (3) ═══
            [18, 'Eduardo', 'Pascual',  'Salazar',   null, 'Male',   '2010-07-07', 'Grade11', '11', 'STEM',        'rejected', ['submitted_at' => '2026-05-05 09:00:00', 'reviewed_at' => '2026-05-10 10:00:00', 'rejection_reason' => 'LRN could not be verified with the previous school records.']],
            [19, 'Rosa',    'Castillo', 'Rivera',    null, 'Female', '2010-04-16', 'Grade11', '11', 'HOSPITALITY', 'rejected', ['submitted_at' => '2026-05-06 13:00:00', 'reviewed_at' => '2026-05-11 11:30:00', 'rejection_reason' => 'Incomplete requirements — Form 138 not submitted after two follow-ups.']],
            [20, 'Miguel',  'Santos',   'Mendoza',   null, 'Male',   '2010-11-09', 'Transferee', '12', 'ICT',      'rejected', ['submitted_at' => '2026-05-07 10:30:00', 'reviewed_at' => '2026-05-12 14:00:00', 'rejection_reason' => 'Did not meet the minimum grade requirement for the ICT cluster.']],

            // ═══ ENROLLED (7) — approved AND converted to students.
            //     converted_student_id left NULL here; StudentConversionSeeder
            //     will create the students and back-fill the link.
            [21, 'Rosario',   'Cruz',    'Reyes',    null, 'Female', '2009-04-18', 'Grade11', '11', 'ARTS-SOC-HUM',    'enrolled', ['submitted_at' => '2026-04-01 08:00:00', 'reviewed_at' => '2026-04-05 09:00:00', 'enroll_slot' => 1]],
            [22, 'John Paul', 'Castillo','Santos',   null, 'Male',   '2009-10-12', 'Grade11', '11', 'ICT',             'enrolled', ['submitted_at' => '2026-04-02 09:00:00', 'reviewed_at' => '2026-04-06 10:00:00', 'enroll_slot' => 2]],
            [23, 'Pedro',     'Mendoza', 'Garcia',   null, 'Male',   '2009-05-31', 'Grade11', '11', 'STEM',            'enrolled', ['submitted_at' => '2026-04-03 10:00:00', 'reviewed_at' => '2026-04-07 11:00:00', 'enroll_slot' => 3]],
            [24, 'Maria',     'Lim',     'Tan',      null, 'Female', '2009-04-26', 'Grade11', '11', 'BUSINESS-ENTREP', 'enrolled', ['submitted_at' => '2026-04-04 11:00:00', 'reviewed_at' => '2026-04-08 12:00:00', 'enroll_slot' => 4]],
            [25, 'Emilio',    'Garcia',  'Navarro',  null, 'Male',   '2009-09-01', 'Grade11', '11', 'HOSPITALITY',     'enrolled', ['submitted_at' => '2026-04-05 12:00:00', 'reviewed_at' => '2026-04-09 13:00:00', 'enroll_slot' => 5]],
            [26, 'Beatriz',   'Uy',      'Ong',      null, 'Female', '2009-03-14', 'Grade11', '11', 'SPORTS-WELLNESS', 'enrolled', ['submitted_at' => '2026-04-06 13:00:00', 'reviewed_at' => '2026-04-10 14:00:00', 'enroll_slot' => 6]],
            [27, 'Fernando',  'Reyes',   'Bautista', null, 'Male',   '2008-12-08', 'Transferee', '12', 'ICT',          'enrolled', ['submitted_at' => '2026-04-07 14:00:00', 'reviewed_at' => '2026-04-11 15:00:00', 'enroll_slot' => 7]],

            // ═══ NEEDS RESUBMISSION (5) ═══
            [28, 'Diego',   'Mendoza',  'Santos',   null, 'Male',   '2009-12-19', 'Grade12',    '12', 'STEM',         'needs_resubmission', ['submitted_at' => '2026-05-24 10:00:00', 'reviewed_at' => '2026-05-29 08:30:00', 'rejection_reason' => 'Form 137 from previous school is missing — please submit a certified true copy.']],
            [29, 'Antonio', 'Reyes',    'Cruz',     null, 'Male',   '2010-06-13', 'Grade11',    '11', 'STEM',         'needs_resubmission', ['submitted_at' => '2026-05-25 09:00:00', 'reviewed_at' => '2026-05-30 10:00:00', 'rejection_reason' => 'Birth certificate scan is unreadable — please re-upload a clearer copy.']],
            [30, 'Bianca',  'Villanueva','Ocampo',  null, 'Female', '2010-08-30', 'Grade11',    '11', 'ARTS-SOC-HUM', 'needs_resubmission', ['submitted_at' => '2026-05-26 10:30:00', 'reviewed_at' => '2026-05-31 11:00:00', 'rejection_reason' => 'Good moral certificate missing.']],
            [31, 'Rogelio', 'Navarro',  'Bautista', null, 'Male',   '2009-03-05', 'Returning',  '12', 'ICT',          'needs_resubmission', ['submitted_at' => '2026-05-27 13:15:00', 'reviewed_at' => '2026-06-01 09:45:00', 'rejection_reason' => 'Previous SHS records from another division still pending.']],
            [32, 'Maricel', 'Torres',   'Domingo',  null, 'Female', '2010-10-17', 'Grade11',    '11', 'HOSPITALITY',  'needs_resubmission', ['submitted_at' => '2026-05-28 14:00:00', 'reviewed_at' => '2026-06-02 10:30:00', 'rejection_reason' => '2x2 photo background must be plain white.']],
        ];

        $created = collect();

        foreach ($rows as $idx => $row) {
            [$seq, $first, $middle, $last, $ext, $sex, $dob, $type, $gl, $clusterCode, $status, $extra] = $row;

            $ref   = sprintf('APP-2026-%04d', $seq);
            $lrn   = $this->generateLrn($seq);
            $email = strtolower(str_replace([' ', '.', "'"], ['.', '', ''], $first))
                   . '.' . strtolower(str_replace(' ', '', $last)) . '@gmail.com';

            $applicant = Applicant::updateOrCreate(
                ['reference_number' => $ref],
                [
                    'school_year_id'       => $year->id,
                    'strand_id'            => $clusterIds[$clusterCode] ?? null,
                    'applicant_type'       => $type,
                    'first_name'           => $first,
                    'middle_name'          => $middle,
                    'last_name'            => $last,
                    'extension_name'       => $ext,
                    'lrn'                  => $lrn,
                    'date_of_birth'        => $dob,
                    'sex'                  => $sex,
                    'religion'             => 'Roman Catholic',
                    'contact_number'       => '0917' . str_pad((string) (1000000 + $seq), 7, '0', STR_PAD_LEFT),
                    'email'                => $email,
                    'house_street'         => 'Blk ' . (($seq % 20) + 1) . ' Lot ' . (($seq % 15) + 1) . ' Phase ' . (($seq % 4) + 1),
                    'barangay'             => $this->pickBarangay($seq),
                    'municipality'         => 'Dasmariñas',
                    'province'             => 'Cavite',
                    'zip_code'             => '4114',
                    'prev_school_name'     => $this->pickPrevSchool($seq),
                    'prev_school_address'  => 'Dasmariñas City, Cavite',
                    'prev_school_type'     => 'Public',
                    'last_school_year'     => '2025-2026',
                    'desired_grade_level'  => $gl,
                    'status'               => $status,
                    'rejection_reason'     => $extra['rejection_reason'] ?? null,
                    'reviewed_by'          => isset($extra['reviewed_at']) ? $registrar?->id : null,
                    'reviewed_at'          => $extra['reviewed_at'] ?? null,
                    'converted_student_id' => null, // set later by StudentConversionSeeder
                    'submitted_at'         => $extra['submitted_at'] ?? now(),
                ]
            );

            $this->seedContacts($applicant, $last, $seq);
            $this->seedDocuments($applicant, $status, $registrar, $extra['reviewed_at'] ?? null);

            $created->push($applicant);
        }

        return $created;
    }

    /* =================================================================
     | CONTACTS
     * ================================================================= */
    protected function seedContacts(Applicant $applicant, string $lastName, int $seq): void
    {
        // Father
        ApplicantContact::updateOrCreate(
            ['applicant_id' => $applicant->id, 'role' => 'father'],
            [
                'full_name'      => $this->pickFatherName($seq) . ' ' . $lastName,
                'relationship'   => 'Father',
                'occupation'     => ['Tricycle Driver', 'Construction Worker', 'Security Guard', 'Farmer', 'Welder', 'Electrician'][$seq % 6],
                'contact_number' => '0918' . str_pad((string) (1000000 + $seq), 7, '0', STR_PAD_LEFT),
                'email'          => null,
            ]
        );

        // Mother
        ApplicantContact::updateOrCreate(
            ['applicant_id' => $applicant->id, 'role' => 'mother'],
            [
                'full_name'      => $this->pickMotherName($seq) . ' ' . $lastName,
                'relationship'   => 'Mother',
                'occupation'     => ['Housewife', 'Sari-sari Store Owner', 'Market Vendor', 'Laundrywoman', 'Barangay Health Worker', 'Public School Teacher'][$seq % 6],
                'contact_number' => '0919' . str_pad((string) (1000000 + $seq), 7, '0', STR_PAD_LEFT),
                'email'          => null,
            ]
        );

        // Guardian (every third applicant — grandmother or aunt/uncle)
        if ($seq % 3 === 0) {
            $guardianFirst = $seq % 2 === 0 ? 'Lola' : 'Tita';
            $relation      = $seq % 2 === 0 ? 'Grandmother' : 'Aunt';

            ApplicantContact::updateOrCreate(
                ['applicant_id' => $applicant->id, 'role' => 'guardian'],
                [
                    'full_name'      => $guardianFirst . ' ' . $lastName,
                    'relationship'   => $relation,
                    'occupation'     => 'Retired',
                    'contact_number' => '0920' . str_pad((string) (1000000 + $seq), 7, '0', STR_PAD_LEFT),
                    'email'          => null,
                ]
            );
        }

        // Emergency — Barangay Health Center (fixed local contact).
        ApplicantContact::updateOrCreate(
            ['applicant_id' => $applicant->id, 'role' => 'emergency'],
            [
                'full_name'      => 'Barangay Salawag Health Center',
                'relationship'   => 'Emergency Contact',
                'occupation'     => null,
                'contact_number' => '(046) 555-0145',
                'email'          => null,
            ]
        );
    }

    /* =================================================================
     | DOCUMENTS
     |-----------------------------------------------------------------
     | Document set + status mirrors the applicant's workflow state.
     * ================================================================= */
    protected function seedDocuments(Applicant $applicant, string $status, ?User $reviewer, ?string $reviewedAt): void
    {
        // [document_type, required?]
        $docSet = [
            ['Form 138 (Report Card)',           true],
            ['PSA Birth Certificate',            true],
            ['Good Moral Certificate',           true],
            ['2x2 ID Picture',                   true],
            ['Certificate of Residency',         false],
            ['NCAE Result',                      false],
        ];

        foreach ($docSet as $i => [$docType, $required]) {
            // Skip optional docs for pending applicants.
            if (! $required && $status === 'pending') {
                continue;
            }

            $docStatus = match (true) {
                $status === 'pending'                  => $i < 2 ? 'received' : 'pending',
                $status === 'under_review'             => $i < 3 ? 'received' : 'pending',
                in_array($status, ['approved', 'enrolled']) => 'verified',
                $status === 'rejected'                 => $i === 0 ? 'incomplete' : 'received',
                $status === 'needs_resubmission'       => $i === 0 ? 'rejected' : 'received',
                default                                => 'pending',
            };

            $verifiedBy = null;
            $verifiedAt = null;
            if (in_array($docStatus, ['verified', 'incomplete', 'rejected'])) {
                $verifiedBy = $reviewer?->id;
                $verifiedAt = $reviewedAt ? Carbon::parse($reviewedAt) : now();
            }

            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '_', $docType));

            ApplicantDocument::updateOrCreate(
                ['applicant_id' => $applicant->id, 'document_type' => $docType],
                [
                    'file_path'   => "applicants/{$applicant->reference_number}/{$slug}.pdf",
                    'file_name'   => "{$docType}.pdf",
                    'file_size'   => 200_000 + (($applicant->id * 37 + $i * 91) % 1_800_000),
                    'mime_type'   => 'application/pdf',
                    'status'      => $docStatus,
                    'remarks'     => match ($docStatus) {
                        'rejected'   => 'Unreadable scan — please re-upload a clearer copy.',
                        'incomplete' => 'Missing page 2.',
                        default      => null,
                    },
                    'verified_by' => $verifiedBy,
                    'verified_at' => $verifiedAt,
                ]
            );
        }
    }

    /* =================================================================
     | ENTRANCE EXAM RESULTS
     |-----------------------------------------------------------------
     | Only reviewed applicants receive a result. Determined by a
     | deterministic formula on the applicant ID so re-runs are stable.
     * ================================================================= */
    protected function seedEntranceExamResults(\Illuminate\Support\Collection $applicants, array $exams, ?User $recorder): void
    {
        // Completed exams are the only ones we can record results against.
        $completed = array_values(array_filter(
            $exams,
            fn ($e) => in_array($e->status, ['Completed'], true)
        ));

        if (empty($completed)) {
            return;
        }

        $reviewed = $applicants->filter(
            fn ($a) => ! in_array($a->status, ['pending', 'under_review'], true)
        )->values();

        foreach ($reviewed as $i => $applicant) {
            // Deterministic result based on position.
            $result = match ($i % 6) {
                0, 1 => 'Passed',
                2    => 'Passed',
                3    => 'For Interview',
                4    => 'Failed',
                default => 'Absent',
            };

            // Only "Passed" applicants should be in the approved/enrolled
            // buckets — flip any mismatches so data stays coherent.
            if ($applicant->status === 'rejected' && $result === 'Passed') {
                $result = 'Failed';
            }
            if (in_array($applicant->status, ['approved', 'enrolled'], true)
                && in_array($result, ['Failed', 'Absent'], true)) {
                $result = 'Passed';
            }

            $exam = $completed[$i % count($completed)];

            EntranceExamResult::updateOrCreate(
                ['entrance_exam_id' => $exam->id, 'applicant_id' => $applicant->id],
                [
                    'score'       => in_array($result, ['Passed', 'Failed', 'For Interview'], true)
                        ? 45 + (($applicant->id * 7) % 54)   // 45–98
                        : null,
                    'result'      => $result,
                    'remarks'     => match ($result) {
                        'For Interview' => 'Schedule interview with the cluster coordinator.',
                        'Failed'        => 'Score below the cluster cut-off.',
                        'Absent'        => 'Did not show up on the scheduled exam date.',
                        default         => null,
                    },
                    'recorded_by' => $recorder?->id,
                    'recorded_at' => Carbon::parse($exam->exam_date)->addDays(3)->setTime(9, 0),
                ]
            );
        }
    }

    /* =================================================================
     | HELPERS
     * ================================================================= */

    /**
     * Produce a plausible 12-digit DepEd LRN unique within this school year.
     * Prefix "136" mimics a Region IV-A school.
     */
    protected function generateLrn(int $seq): string
    {
        return '136' . str_pad((string) (200000000 + $seq * 137), 9, '0', STR_PAD_LEFT);
    }

    protected function pickBarangay(int $seq): string
    {
        $barangays = [
            'Barangay Salawag',
            'Barangay Salawag',
            'Barangay Salawag',
            'Barangay San Agustin',
            'Barangay Paliparan III',
            'Barangay Burol II',
            'Barangay Zone IV',
        ];

        return $barangays[$seq % count($barangays)];
    }

    protected function pickPrevSchool(int $seq): string
    {
        $schools = [
            'Salawag National High School',
            'Dasmariñas East Integrated High School',
            'Paliparan National High School',
            'Burol National High School',
            'Dasmariñas National High School',
            'San Agustin Integrated National High School',
        ];

        return $schools[$seq % count($schools)];
    }

    protected function pickFatherName(int $seq): string
    {
        $names = ['Ramon', 'Ernesto', 'Rogelio', 'Antonio', 'Fidel', 'Renato', 'Mario', 'Ricardo', 'Rodel', 'Melchor'];
        return $names[$seq % count($names)];
    }

    protected function pickMotherName(int $seq): string
    {
        $names = ['Teresita', 'Luzviminda', 'Rosario', 'Gloria', 'Marilou', 'Josefina', 'Aurora', 'Editha', 'Elena', 'Corazon'];
        return $names[$seq % count($names)];
    }
}