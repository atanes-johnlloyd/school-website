<?php

namespace Database\Seeders;

use App\Models\Applicant;
use App\Models\ApplicantContact;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentGuardian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * StudentConversionSeeder
 * -----------------------
 *   1. Converts "enrolled" applicants into full students
 *   2. Seeds historical + current student cohorts
 *
 * Students are seeded with must_change_password = false so seeded
 * accounts can log in immediately during testing.
 */
class StudentConversionSeeder extends Seeder
{
    private const DEMO_PASSWORD = 'password';

    public function run(): void
    {
        $now = Carbon::now();

        $this->convertEnrolledApplicants($now);
        $this->seedHistoricalAndCurrentStudents($now);

        $total = Student::count();
        $this->command->info("✔ Students seeded: {$total} total.");
        $this->command->info("   Converted from applicants: 7");
        $this->command->info("   Historical + current (direct): " . ($total - 7));
        $this->command->warn('  Demo password for all student accounts: password');
    }

    /* =================================================================
     | PART 1 — CONVERT ENROLLED APPLICANTS → STUDENTS
     * ================================================================= */
    protected function convertEnrolledApplicants(Carbon $now): void
    {
        $enrolledApplicants = Applicant::where('status', 'enrolled')
            ->whereNull('converted_student_id')
            ->orderBy('id')
            ->get();

        if ($enrolledApplicants->isEmpty()) {
            $this->command->warn('  ! No enrolled applicants found — skipping conversion.');
            return;
        }

        foreach ($enrolledApplicants as $applicant) {
            // --- 1. Create the user account (login-ready) ---
            $user = User::updateOrCreate(
                ['email' => $applicant->email],
                [
                    'name'                 => trim("{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name} {$applicant->extension_name}"),
                    'password'             => Hash::make(self::DEMO_PASSWORD),
                    'must_change_password' => false,   // ← was true
                    'status'               => 'active',
                    'email_verified_at'    => $now,
                ]
            );
            $user->syncRoles(['student']);

            // --- 2. Create the student record ---
            $student = Student::updateOrCreate(
                ['lrn' => $applicant->lrn],
                [
                    'user_id'         => $user->id,
                    'sex'             => strtolower($applicant->sex),
                    'date_of_birth'   => $applicant->date_of_birth,
                    'contact_number'  => $applicant->contact_number,
                    'house_street'    => $applicant->house_street,
                    'barangay'        => $applicant->barangay,
                    'municipality'    => $applicant->municipality,
                    'province'        => $applicant->province,
                    'zip_code'        => $applicant->zip_code,
                    'status'          => 'active',
                    'guardian_name'    => null,
                    'guardian_contact' => null,
                    'guardian_email'   => null,
                ]
            );

            // --- 3. Copy applicant_contacts → student_guardians ---
            $contacts = ApplicantContact::where('applicant_id', $applicant->id)->get();

            $primaryGuardianName    = null;
            $primaryGuardianContact = null;
            $primaryGuardianEmail   = null;

            foreach ($contacts as $contact) {
                if ($contact->role === 'emergency') {
                    continue;
                }

                $isPrimary = in_array($contact->role, ['mother', 'father'], true);

                StudentGuardian::updateOrCreate(
                    [
                        'student_id'  => $student->id,
                        'full_name'   => $contact->full_name,
                    ],
                    [
                        'relationship'       => $contact->relationship ?? ucfirst($contact->role),
                        'contact_number'     => $contact->contact_number,
                        'email'              => $contact->email,
                        'is_primary'         => $isPrimary && $primaryGuardianName === null,
                        'source'             => 'applicant',
                        'source_contact_id'  => $contact->id,
                    ]
                );

                if ($isPrimary && $primaryGuardianName === null) {
                    $primaryGuardianName    = $contact->full_name;
                    $primaryGuardianContact = $contact->contact_number;
                    $primaryGuardianEmail   = $contact->email;
                }
            }

            $student->update([
                'guardian_name'    => $primaryGuardianName,
                'guardian_contact' => $primaryGuardianContact,
                'guardian_email'   => $primaryGuardianEmail,
            ]);

            // --- 4. Back-fill the applicant→student link ---
            $applicant->update(['converted_student_id' => $student->id]);
        }
    }

    /* =================================================================
     | PART 2 — HISTORICAL + CURRENT STUDENTS
     * ================================================================= */
    protected function seedHistoricalAndCurrentStudents(Carbon $now): void
    {
        $seq = 1000;

        $this->createStudentBatch(18, $seq, 'graduated', 2006, $now); $seq += 18;
        $this->createStudentBatch(20, $seq, 'graduated', 2007, $now); $seq += 20;
        $this->createStudentBatch(28, $seq, 'active',    2008, $now); $seq += 28;
        $this->createStudentBatch(23, $seq, 'active',    2009, $now); $seq += 23;
        $this->createStudentBatch(4,  $seq, 'dropped_out', 2008, $now); $seq += 4;
        $this->createStudentBatch(3,  $seq, 'transferred_out', 2008, $now);
    }

    protected function createStudentBatch(
        int $count,
        int $seqStart,
        string $status,
        int $yearOfBirth,
        Carbon $now,
    ): void {
        for ($i = 0; $i < $count; $i++) {
            $seq = $seqStart + $i;

            $sex    = $seq % 2 === 0 ? 'male' : 'female';
            $first  = $sex === 'male'
                ? self::MALE_NAMES[$seq % count(self::MALE_NAMES)]
                : self::FEMALE_NAMES[$seq % count(self::FEMALE_NAMES)];
            $middle = self::MIDDLE_NAMES[$seq % count(self::MIDDLE_NAMES)];
            $last   = self::LAST_NAMES[$seq % count(self::LAST_NAMES)];

            $fullName = "{$first} {$middle} {$last}";
            $email    = strtolower($first . '.' . $last . $seq) . '@student.salawagshs.edu.ph';

            $lrn  = '136' . str_pad((string) (300000000 + $seq * 41), 9, '0', STR_PAD_LEFT);
            $dob  = sprintf('%d-%02d-%02d', $yearOfBirth, ($seq % 12) + 1, ($seq % 27) + 1);

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'                 => $fullName,
                    'password'             => Hash::make(self::DEMO_PASSWORD),
                    'must_change_password' => false,   // ← was true
                    'status'               => $status === 'dropped_out' ? 'disabled' : 'active',
                    'email_verified_at'    => $now,
                ]
            );
            $user->syncRoles(['student']);

            $fatherName    = self::FATHER_NAMES[$seq % count(self::FATHER_NAMES)] . ' ' . $last;
            $motherName    = self::MOTHER_NAMES[$seq % count(self::MOTHER_NAMES)] . ' ' . $last;
            $primaryName    = $seq % 2 === 0 ? $motherName : $fatherName;
            $primaryContact = '0918' . str_pad((string) (2000000 + $seq), 7, '0', STR_PAD_LEFT);
            $primaryEmail   = strtolower(str_replace(' ', '.', $primaryName)) . '@gmail.com';

            $student = Student::updateOrCreate(
                ['lrn' => $lrn],
                [
                    'user_id'          => $user->id,
                    'sex'              => $sex,
                    'date_of_birth'    => $dob,
                    'contact_number'   => '0917' . str_pad((string) (2000000 + $seq), 7, '0', STR_PAD_LEFT),
                    'house_street'     => 'Blk ' . (($seq % 25) + 1) . ' Lot ' . (($seq % 20) + 1),
                    'barangay'         => self::BARANGAYS[$seq % count(self::BARANGAYS)],
                    'municipality'     => 'Dasmariñas',
                    'province'         => 'Cavite',
                    'zip_code'         => '4114',
                    'status'           => $status,
                    'guardian_name'    => $primaryName,
                    'guardian_contact' => $primaryContact,
                    'guardian_email'   => $primaryEmail,
                ]
            );

            StudentGuardian::updateOrCreate(
                ['student_id' => $student->id, 'full_name' => $fatherName],
                [
                    'relationship'   => 'Father',
                    'contact_number' => '0918' . str_pad((string) (3000000 + $seq), 7, '0', STR_PAD_LEFT),
                    'email'          => null,
                    'is_primary'     => $primaryName === $fatherName,
                    'source'         => 'manual',
                    'source_contact_id' => null,
                ]
            );

            StudentGuardian::updateOrCreate(
                ['student_id' => $student->id, 'full_name' => $motherName],
                [
                    'relationship'   => 'Mother',
                    'contact_number' => '0919' . str_pad((string) (3000000 + $seq), 7, '0', STR_PAD_LEFT),
                    'email'          => null,
                    'is_primary'     => $primaryName === $motherName,
                    'source'         => 'manual',
                    'source_contact_id' => null,
                ]
            );
        }
    }

    /* =================================================================
     | NAME POOLS
     * ================================================================= */
    private const MALE_NAMES = [
        'Juan', 'Jose', 'Mark', 'Carlo', 'Angelo', 'Rafael', 'Miguel', 'Gabriel',
        'Emilio', 'Andres', 'Rico', 'Rogelio', 'Antonio', 'Lorenzo', 'Diego',
        'Marco', 'Paolo', 'Eduardo', 'Fernando', 'Enrique', 'Ramon', 'Ernesto',
        'Benigno', 'Melchor', 'Fidel', 'Roberto', 'Alfredo', 'Sergio', 'Vicente',
        'Gregorio',
    ];

    private const FEMALE_NAMES = [
        'Maria', 'Ana', 'Rosa', 'Andrea', 'Sofia', 'Isabella', 'Camila', 'Bianca',
        'Angelica', 'Jasmine', 'Kristine', 'Liza', 'Maricel', 'Rosario',
        'Josefina', 'Patricia', 'Beatrice', 'Lourdes', 'Teresita', 'Gloria',
        'Cristina', 'Luzviminda', 'Elena', 'Aurora', 'Editha', 'Corazon',
        'Cecilia', 'Margarita', 'Precious', 'Hazel',
    ];

    private const MIDDLE_NAMES = [
        'Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Mendoza',
        'Torres', 'Villanueva', 'Aquino', 'Castillo', 'Ramos', 'Domingo',
        'Navarro', 'Salazar', 'Rivera', 'Pascual', 'Del Rosario', 'Lim', 'Tan',
        'Angeles', 'Diaz', 'Uy', 'Ong', 'Santiago', 'Lopez', 'Villamor',
        'Padilla', 'Soriano', 'Soriano',
    ];

    private const LAST_NAMES = [
        'Dela Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Mendoza', 'Torres',
        'Villanueva', 'Aquino', 'Castillo', 'Ramos', 'Domingo', 'Navarro',
        'Salazar', 'Rivera', 'Pascual', 'Del Rosario', 'Lim', 'Tan', 'Angeles',
        'Diaz', 'Uy', 'Ong', 'Santiago', 'Lopez', 'Villamor', 'Padilla',
        'Soriano', 'Reyes', 'Cruz', 'Santos',
    ];

    private const FATHER_NAMES = [
        'Ramon', 'Ernesto', 'Rogelio', 'Antonio', 'Fidel', 'Renato', 'Mario',
        'Ricardo', 'Rodel', 'Melchor', 'Vicente', 'Alfredo',
    ];

    private const MOTHER_NAMES = [
        'Teresita', 'Luzviminda', 'Rosario', 'Gloria', 'Marilou', 'Josefina',
        'Aurora', 'Editha', 'Elena', 'Corazon', 'Cecilia', 'Margarita',
    ];

    private const BARANGAYS = [
        'Barangay Salawag',
        'Barangay Salawag',
        'Barangay Salawag',
        'Barangay San Agustin',
        'Barangay Paliparan III',
        'Barangay Burol II',
        'Barangay Zone IV',
        'Barangay Paliparan II',
        'Barangay Burol Main',
        'Barangay San Jose',
    ];
}