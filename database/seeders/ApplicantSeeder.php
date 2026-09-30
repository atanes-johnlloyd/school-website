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
use Illuminate\Database\Seeder;

class ApplicantSeeder extends Seeder
{
    /**
     * Seeds the full admissions pipeline so every status, applicant type,
     * document state, and entrance-exam outcome is represented. This lets
     * the registrar/admin screens be exercised end-to-end without having
     * to hand-craft records.
     */
    public function run(): void
    {
        $year    = SchoolYear::where('is_active', true)->firstOrFail();
        $adminId = User::where('email', 'atanes.johnlloyd@ncst.edu.ph')->value('id') ?? 1;

        $strandId = Strand::pluck('id', 'code');
        $trackId  = Track::pluck('id', 'code');

        // ═══════════════════════════════════════════════════════════
        // ENTRANCE EXAMS — all three statuses
        // ═══════════════════════════════════════════════════════════
        $examData = [
            ['SHS Entrance Exam — Batch 1 (Academic)',   '2026-05-10', '08:00:00', 'Computer Laboratory 1', 40, '11',  'Completed', $trackId['ACAD']    ?? null],
            ['SHS Entrance Exam — Batch 2 (Tech-Pro)',   '2026-05-17', '08:00:00', 'Room 102',              40, '11',  'Completed', $trackId['TECHPRO'] ?? null],
            ['SHS Entrance Exam — Late Applicants',      '2026-06-07', '09:00:00', 'Room 101',              30, 'All', 'Upcoming',  null],
            ['Grade 12 Transferee Exam — 1st Semester',  '2026-07-19', '13:00:00', 'Room 102',              20, '12',  'Upcoming',  null],
            ['SHS Entrance Exam — Summer Special',       '2026-04-12', '08:00:00', 'Room 101',              25, '11',  'Cancelled', null],
        ];

        $exams = [];
        foreach ($examData as [$name, $date, $time, $venue, $cap, $gl, $status, $tid]) {
            $exams[] = EntranceExam::create([
                'school_year_id' => $year->id,
                'track_id'       => $tid,
                'exam_name'      => $name,
                'exam_date'      => $date,
                'exam_time'      => $time,
                'venue'          => $venue,
                'max_capacity'   => $cap,
                'grade_level'    => $gl,
                'status'         => $status,
            ]);
        }
        [$batch1, $batch2] = $exams;

        // ═══════════════════════════════════════════════════════════
        // APPLICANTS
        // ═══════════════════════════════════════════════════════════
        // [ref, first, middle, last, ext, sex, dob, type, desiredGrade, strand, status, extras]
        $rows = [
            // ── PENDING ────────────────────────────────────────────
            ['APP-2026-0001','Juan Miguel','Santos','Dela Cruz',null,'Male','2009-02-14','Grade11','11','STEM','pending',['submitted_at'=>'2026-06-01 09:15:00']],
            ['APP-2026-0002','Andrea','Reyes','Bautista',null,'Female','2009-05-22','Grade11','11','STEM','pending',['submitted_at'=>'2026-06-02 10:30:00']],
            ['APP-2026-0003','Mark Joseph','Cruz','Ocampo',null,'Male','2009-08-09','Grade11','11','ICT','pending',['submitted_at'=>'2026-06-03 14:05:00']],
            ['APP-2026-0004','Kristine Joy','Lim','Tan',null,'Female','2009-11-30','Grade11','11','HOSPITALITY','pending',['submitted_at'=>'2026-06-04 08:45:00']],
            ['APP-2026-0005','Paolo','Garcia','Mendoza',null,'Male','2009-07-18','Grade11','11','BUSINESS-ENTREP','pending',['submitted_at'=>'2026-06-05 16:20:00']],
            ['APP-2026-0006','Bea Angela','Torres','Villanueva',null,'Female','2009-04-02','Grade11','11','ARTS-SOC-HUM','pending',['submitted_at'=>'2026-06-06 11:00:00']],

            // ── UNDER REVIEW ───────────────────────────────────────
            ['APP-2026-0007','Rafael','Domingo','Navarro','Jr.','Male','2009-01-25','Grade11','11','STEM','under_review',['submitted_at'=>'2026-05-20 09:00:00','reviewed_at'=>'2026-05-25 10:00:00']],
            ['APP-2026-0008','Camila','Aquino','Ramos',null,'Female','2009-09-12','Grade11','11','BUSINESS-ENTREP','under_review',['submitted_at'=>'2026-05-21 13:30:00','reviewed_at'=>'2026-05-26 09:15:00']],
            ['APP-2026-0009','Gabriel','Salazar','Pascual',null,'Male','2009-06-04','Grade11','11','ICT','under_review',['submitted_at'=>'2026-05-22 08:20:00','reviewed_at'=>'2026-05-27 14:00:00']],
            ['APP-2026-0010','Sofia','Rivera','Castillo',null,'Female','2009-03-27','Grade11','11','HOSPITALITY','under_review',['submitted_at'=>'2026-05-23 15:10:00','reviewed_at'=>'2026-05-28 11:45:00']],
            ['APP-2026-0011','Diego','Mendoza','Santos',null,'Male','2008-12-19','Grade12','12','STEM','under_review',['submitted_at'=>'2026-05-24 10:00:00','reviewed_at'=>'2026-05-29 08:30:00']],

            // ── APPROVED ───────────────────────────────────────────
            ['APP-2026-0012','Liza Marie','Cruz','Reyes',null,'Female','2009-05-08','Grade11','11','STEM','approved',['submitted_at'=>'2026-05-10 09:00:00','reviewed_at'=>'2026-05-15 10:00:00']],
            ['APP-2026-0013','Marco','Bautista','Garcia',null,'Male','2009-10-21','Grade11','11','ICT','approved',['submitted_at'=>'2026-05-11 11:30:00','reviewed_at'=>'2026-05-16 13:00:00']],
            ['APP-2026-0014','Isabella','Ocampo','Torres',null,'Female','2009-02-11','Grade11','11','ARTS-SOC-HUM','approved',['submitted_at'=>'2026-05-12 14:45:00','reviewed_at'=>'2026-05-17 09:20:00']],
            ['APP-2026-0015','Lorenzo','Navarro','Domingo',null,'Male','2008-08-03','Grade12','12','HOSPITALITY','approved',['submitted_at'=>'2026-05-13 08:00:00','reviewed_at'=>'2026-05-18 15:30:00']],
            ['APP-2026-0016','Patricia','Ramos','Aquino',null,'Female','2009-12-25','Grade11','11','BUSINESS-ENTREP','approved',['submitted_at'=>'2026-05-14 16:10:00','reviewed_at'=>'2026-05-19 10:45:00']],

            // ── REJECTED ───────────────────────────────────────────
            ['APP-2026-0017','Eduardo','Pascual','Salazar',null,'Male','2009-07-07','Grade11','11','STEM','rejected',['submitted_at'=>'2026-05-05 09:00:00','reviewed_at'=>'2026-05-10 10:00:00','rejection_reason'=>'LRN could not be verified with the previous school records.']],
            ['APP-2026-0018','Rosa','Castillo','Rivera',null,'Female','2009-04-16','Grade11','11','HOSPITALITY','rejected',['submitted_at'=>'2026-05-06 13:00:00','reviewed_at'=>'2026-05-11 11:30:00','rejection_reason'=>'Incomplete requirements — Form 138 not submitted after two follow-ups.']],
            ['APP-2026-0019','Miguel','Santos','Mendoza',null,'Male','2008-11-09','Transferee','11','ICT','rejected',['submitted_at'=>'2026-05-07 10:30:00','reviewed_at'=>'2026-05-12 14:00:00','rejection_reason'=>'Did not meet the minimum grade requirement for the ICT strand.']],

            // ── ENROLLED (linked to existing students) ─────────────
            ['APP-2026-0020','Rosario','Cruz','Reyes',null,'Female','2008-04-18','Grade11','11','ARTS-SOC-HUM','enrolled',['submitted_at'=>'2026-04-01 08:00:00','reviewed_at'=>'2026-04-05 09:00:00','converted_student_id'=>1]],
            ['APP-2026-0021','John Paul','Castillo','Santos',null,'Male','2008-10-12','Grade11','11','ICT','enrolled',['submitted_at'=>'2026-04-02 09:00:00','reviewed_at'=>'2026-04-06 10:00:00','converted_student_id'=>2]],
            ['APP-2026-0022','Pedro','Mendoza','Garcia',null,'Male','2008-05-31','Grade11','11','STEM','enrolled',['submitted_at'=>'2026-04-03 10:00:00','reviewed_at'=>'2026-04-07 11:00:00','converted_student_id'=>3]],
            ['APP-2026-0023','Maria','Lim','Tan',null,'Female','2010-04-26','Grade11','11','BUSINESS-ENTREP','enrolled',['submitted_at'=>'2026-04-04 11:00:00','reviewed_at'=>'2026-04-08 12:00:00','converted_student_id'=>4]],
            ['APP-2026-0024','Emilio','Garcia','Navarro',null,'Male','2010-09-01','Grade11','11','HOSPITALITY','enrolled',['submitted_at'=>'2026-04-05 12:00:00','reviewed_at'=>'2026-04-09 13:00:00','converted_student_id'=>5]],

            // ── NEEDS RESUBMISSION ─────────────────────────────────
            ['APP-2026-0025','Antonio','Reyes','Cruz',null,'Male','2009-06-13','Grade11','11','STEM','needs_resubmission',['submitted_at'=>'2026-05-25 09:00:00','reviewed_at'=>'2026-05-30 10:00:00','rejection_reason'=>'Birth certificate scan is unreadable — please re-upload.']],
            ['APP-2026-0026','Bianca','Villanueva','Ocampo',null,'Female','2009-08-30','Grade11','11','ARTS-SOC-HUM','needs_resubmission',['submitted_at'=>'2026-05-26 10:30:00','reviewed_at'=>'2026-05-31 11:00:00','rejection_reason'=>'Good moral certificate missing.']],
            ['APP-2026-0027','Rogelio','Navarro','Bautista',null,'Male','2008-03-05','Returning','12','ICT','needs_resubmission',['submitted_at'=>'2026-05-27 13:15:00','reviewed_at'=>'2026-06-01 09:45:00','rejection_reason'=>'Previous SHS records from another division still pending.']],
            ['APP-2026-0028','Maricel','Torres','Domingo',null,'Female','2009-10-17','Grade11','11','HOSPITALITY','needs_resubmission',['submitted_at'=>'2026-05-28 14:00:00','reviewed_at'=>'2026-06-02 10:30:00','rejection_reason'=>'2x2 photo background must be plain white.']],
        ];

        $allDocs = [
            'Form 138 (Report Card)',
            'PSA Birth Certificate',
            'Good Moral Certificate',
            '2x2 ID Picture',
            'Certificate of Residency',
            'NCAE Result',
        ];

        $adminFirstName = 'Maria';
        $adminLastName  = 'Rivera';

        $created = [];

        foreach ($rows as $idx => $row) {
            [$ref, $first, $middle, $last, $ext, $sex, $dob, $type, $grade, $strandCode, $status, $extra] = $row;

            $applicant = Applicant::create([
                'reference_number'    => $ref,
                'school_year_id'      => $year->id,
                'strand_id'           => $strandId[$strandCode] ?? null,
                'applicant_type'      => $type,
                'first_name'          => $first,
                'middle_name'         => $middle,
                'last_name'           => $last,
                'extension_name'      => $ext,
                'lrn'                 => '1234' . str_pad((string) (2000 + $idx), 8, '0', STR_PAD_LEFT),
                'date_of_birth'       => $dob,
                'sex'                 => $sex,
                'religion'            => 'Roman Catholic',
                'contact_number'      => '0917' . str_pad((string) random_int(0, 9999999), 7, '0', STR_PAD_LEFT),
                'email'               => strtolower(str_replace([' ', '.'], ['.', ''], $first)) . '.' . strtolower($last) . $idx . '@example.com',
                'house_street'        => 'Blk ' . random_int(1, 20) . ' Lot ' . random_int(1, 30),
                'barangay'            => 'Barangay Salawag',
                'municipality'        => 'Dasmariñas',
                'province'            => 'Cavite',
                'zip_code'            => '4114',
                'prev_school_name'    => 'Salawag National High School',
                'prev_school_address' => 'Dasmariñas, Cavite',
                'prev_school_type'    => 'Public',
                'last_school_year'    => '2025-2026',
                'desired_grade_level' => $grade,
                'status'              => $status,
                'rejection_reason'    => $extra['rejection_reason'] ?? null,
                'reviewed_by'         => isset($extra['reviewed_at']) ? $adminId : null,
                'reviewed_at'         => $extra['reviewed_at'] ?? null,
                'converted_student_id'=> $extra['converted_student_id'] ?? null,
                'submitted_at'        => $extra['submitted_at'] ?? now(),
            ]);

            $created[] = $applicant;

            // ─── Contacts ────────────────────────────────────────
            ApplicantContact::create([
                'applicant_id'   => $applicant->id,
                'role'           => 'father',
                'full_name'      => "Ramon {$last}",
                'relationship'   => 'Father',
                'occupation'     => ['Tricycle Driver','Farmer','Construction Worker','Security Guard'][$idx % 4],
                'contact_number' => '0918' . str_pad((string) random_int(0, 9999999), 7, '0', STR_PAD_LEFT),
                'email'          => strtolower("ramon.{$last}{$idx}@example.com"),
            ]);
            ApplicantContact::create([
                'applicant_id'   => $applicant->id,
                'role'           => 'mother',
                'full_name'      => "Teresita {$last}",
                'relationship'   => 'Mother',
                'occupation'     => ['Housewife','Vendor','Laundrywoman','Sari-sari Store Owner'][$idx % 4],
                'contact_number' => '0919' . str_pad((string) random_int(0, 9999999), 7, '0', STR_PAD_LEFT),
                'email'          => strtolower("teresita.{$last}{$idx}@example.com"),
            ]);
            if ($idx % 3 === 0) {
                ApplicantContact::create([
                    'applicant_id'   => $applicant->id,
                    'role'           => 'guardian',
                    'full_name'      => 'Lola ' . $last,
                    'relationship'   => 'Grandmother',
                    'occupation'     => 'Retired',
                    'contact_number' => '0920' . str_pad((string) random_int(0, 9999999), 7, '0', STR_PAD_LEFT),
                    'email'          => strtolower("lola.{$last}{$idx}@example.com"),
                ]);
            }
            ApplicantContact::create([
                'applicant_id'   => $applicant->id,
                'role'           => 'emergency',
                'full_name'      => 'Barangay Salawag Health Center',
                'relationship'   => 'Emergency Contact',
                'occupation'     => null,
                'contact_number' => '046-555-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT),
                'email'          => null,   // emergency contact is an org, no personal email
            ]);

            // ─── Documents (status reflects applicant status) ────
            $docsToCreate = $status === 'pending'
                ? array_slice($allDocs, 0, 3)
                : $allDocs;

            foreach ($docsToCreate as $dIdx => $docType) {
                $docStatus = match (true) {
                    $status === 'pending'                                   => $dIdx < 2 ? 'received' : 'pending',
                    $status === 'under_review'                              => $dIdx < 3 ? 'verified' : 'received',
                    in_array($status, ['approved','enrolled'])              => 'verified',
                    $status === 'rejected'                                  => $dIdx === 0 ? 'incomplete' : 'verified',
                    $status === 'needs_resubmission'                        => $dIdx === 0 ? 'rejected' : 'received',
                    default                                                 => 'pending',
                };

                ApplicantDocument::create([
                    'applicant_id' => $applicant->id,
                    'document_type'=> $docType,
                    'file_path'    => "applicants/{$ref}/" . strtolower(str_replace([' ', '(', ')', '/'], ['_','','','_'], $docType)) . '.pdf',
                    'file_name'    => $docType . '.pdf',
                    'file_size'    => random_int(120_000, 2_400_000),
                    'mime_type'    => 'application/pdf',
                    'status'       => $docStatus,
                    'remarks'      => $docStatus === 'rejected'
                        ? 'Unreadable scan — please re-upload a clearer copy.'
                        : ($docStatus === 'incomplete' ? 'Missing page 2.' : null),
                    'verified_by'  => in_array($docStatus, ['verified','incomplete','rejected']) ? $adminId : null,
                    'verified_at'  => in_array($docStatus, ['verified','incomplete','rejected'])
                        ? $extra['reviewed_at'] ?? now()
                        : null,
                ]);
            }
        }

        // ═══════════════════════════════════════════════════════════
        // ENTRANCE EXAM RESULTS
        // ═══════════════════════════════════════════════════════════
        // Assign reviewed applicants to Batch 1 / Batch 2 with a mix
        // of outcomes. Only one result per (exam, applicant) pair.
        $reviewedApplicants = collect($created)->filter(
            fn ($a) => ! in_array($a->status, ['pending', 'needs_resubmission'])
        )->values();

        foreach ($reviewedApplicants as $i => $applicant) {
            $exam = $i % 2 === 0 ? $batch1 : $batch2;

            // Skew outcomes so we get a realistic spread.
            $result = match ($i % 6) {
                0       => 'Passed',
                1       => 'Passed',
                2       => 'For Interview',
                3       => 'Failed',
                4       => 'Absent',
                default => 'Pending',
            };

            EntranceExamResult::create([
                'entrance_exam_id' => $exam->id,
                'applicant_id'     => $applicant->id,
                'score'            => in_array($result, ['Passed','Failed','For Interview'])
                    ? random_int(45, 98)
                    : null,
                'result'           => $result,
                'remarks'          => match ($result) {
                    'For Interview' => 'Schedule interview with the strand coordinator.',
                    'Failed'        => 'Score below the strand cut-off.',
                    'Absent'        => 'Did not show up on the scheduled exam date.',
                    default         => null,
                },
                'recorded_by'      => $adminId,
                'recorded_at'      => $exam->exam_date->copy()->addDays(3)->setTime(9, 0),
            ]);
        }

        $this->command->info('✅ Applicants seeded:');
        $this->command->info('   ' . count($rows) . ' applicants across all statuses');
        $this->command->info('   ' . count($exams) . ' entrance exams');
        $this->command->info('   ' . $reviewedApplicants->count() . ' entrance exam results');
    }
}