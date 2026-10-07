<?php

namespace App\Services;

use App\Models\Applicant;
use App\Models\ClassRoom;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApplicantConversionService
{
    /**
     * Convert an approved applicant into a Student + User + Enrollment.
     *
     * ── IMPORTANT ──
     * If no section with capacity is available, nothing is created. The
     * applicant stays `approved` and the caller surfaces a warning so the
     * registrar can place them manually. A student with no section gets no
     * LMS account — otherwise they could log in and see an empty dashboard.
     *
     * @return array{
     *   success: bool,
     *   reason?: string,
     *   message: string,
     *   student_id?: int,
     *   section_id?: int|null,
     *   section_name?: string|null,
     *   status?: string,
     *   temp_password?: string,
     *   login_url?: string
     * }
     */
    public function convert(Applicant $applicant): array
    {
        if (! in_array($applicant->status, ['approved', 'enrolled'], true)) {
            return [
                'success' => false,
                'reason'  => 'not_approved',
                'message' => 'Applicant is not approved for enrollment.',
            ];
        }

        if ($applicant->converted_student_id) {
            return [
                'success'    => false,
                'reason'     => 'already_converted',
                'message'    => 'Applicant is already converted to a student.',
                'student_id' => $applicant->converted_student_id,
            ];
        }

        $activeYear = SchoolYear::where('is_active', true)->first();
        if (! $activeYear) {
            return [
                'success' => false,
                'reason'  => 'no_active_year',
                'message' => 'No active school year — cannot convert.',
            ];
        }

        // ─── Find a section FIRST. Nothing is created until we know. ───
        $activeYear = SchoolYear::where('is_active', true)->first();
        if (! $activeYear) {
            return [
                'success' => false,
                'reason'  => 'no_active_year',
                'message' => 'No active school year — cannot convert.',
            ];
        }

        // ✅ FIX: Wrap EVERYTHING (lookup + creation) in a single transaction.
        // The `lockForUpdate()` in findAvailableSection() will now be held until
        // this outer transaction commits, closing the TOCTOU window.
        return DB::transaction(function () use ($applicant, $activeYear) {

            // Look up the section INSIDE the transaction so the lock is held throughout
            $sectionId = $this->findAvailableSection(
                $applicant->strand_id,
                (string) $applicant->desired_grade_level,
                $activeYear->id
            );

            if (! $sectionId) {
                return [
                    'success'    => false,
                    'reason'     => 'no_section',
                    'message'    => 'No section with capacity for this strand/grade. Applicant remains approved pending manual placement.',
                    'section_id' => null,
                ];
            }

            $section = Section::find($sectionId);

            // 1. User account
            $tempPassword = Str::random(12);

            $user = User::create([
                'name'                 => $applicant->full_name,
                'email'                => $applicant->email,
                'password'             => Hash::make($tempPassword),
                'must_change_password' => true,
                'email_verified_at'    => now(),
                'role'                 => 'student',
                'status'               => 'active',
            ]);
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('student');
            }

            $applicant->loadMissing('contacts');

            $guardian = $applicant->contacts
                ->sortBy(fn ($c) => match ($c->role) {
                    'guardian' => 0,
                    'father'   => 1,
                    'mother'   => 2,
                    default    => 9,
                })
                ->first(fn ($c) => filled($c->full_name));

            // 2. Student profile
            $student = Student::create([
                'user_id'          => $user->id,
                'lrn'              => $applicant->lrn,
                'first_name'       => $applicant->first_name,
                'middle_name'      => $applicant->middle_name,
                'last_name'        => $applicant->last_name,
                'extension_name'   => $applicant->extension_name,
                'sex'              => strtolower((string) $applicant->sex),
                'date_of_birth'    => $applicant->date_of_birth,
                'contact_number'   => $applicant->contact_number,
                'house_street'     => $applicant->house_street,
                'barangay'         => $applicant->barangay,
                'municipality'     => $applicant->municipality,
                'province'         => $applicant->province,
                'zip_code'         => $applicant->zip_code,
                'strand_id'        => $applicant->strand_id,
                'guardian_name'    => $guardian?->full_name,
                'guardian_contact' => $guardian?->contact_number,
                'guardian_email'   => $guardian?->email,
                'status'           => 'active',
            ]);

            foreach ($applicant->contacts as $contact) {
                if (! in_array($contact->role, ['father', 'mother', 'guardian'], true)) {
                    continue;
                }

                \App\Models\StudentGuardian::create([
                    'student_id'        => $student->id,
                    'full_name'         => $contact->full_name,
                    'relationship'      => $contact->relationship ?: $contact->role,
                    'contact_number'    => $contact->contact_number,
                    'email'             => $contact->email,
                    'is_primary'        => $contact->role === 'guardian',
                    'source'            => 'applicant',
                    'source_contact_id' => $contact->id,
                ]);
            }

            // 3. Enrollment
            Enrollment::create([
                'student_id'     => $student->id,
                'school_year_id' => $activeYear->id,
                'section_id'     => $section->id,
                'status'         => 'enrolled',
                'enrolled_at'    => now(),
                'enrolled_by'    => auth()->id(),
            ]);

            // 4. Attach to every classroom under the section
            $classrooms = ClassRoom::where('section_id', $section->id)->get();
            foreach ($classrooms as $classroom) {
                $classroom->students()->syncWithoutDetaching([
                    $student->id => ['status' => 'active', 'enrolled_at' => now()],
                ]);
            }

            // 5. Mark applicant enrolled
            $applicant->update([
                'status'               => 'enrolled',
                'converted_student_id' => $student->id,
            ]);

            return [
                'success'       => true,
                'message'       => 'Applicant converted and enrolled successfully.',
                'student_id'    => $student->id,
                'section_id'    => $section->id,
                'section_name'  => $section->name,
                'status'        => 'enrolled',
                'temp_password' => $tempPassword,
                'login_url'     => url('/login'),
            ];
        });
    }

    /**
     * Least-crowded section matching strand + grade, with capacity.
     *
     * Uses `lockForUpdate()` to prevent two simultaneous conversions from
     * both claiming the last slot (TOCTOU).
     */
    public function findAvailableSection(?int $strandId, string $gradeLevel, int $schoolYearId): ?int
    {
        if (! $strandId) return null;

        // ✅ FIX: Removed the nested DB::transaction here. We now rely on the
        // caller's transaction to keep the lock held across the capacity check
        // AND the subsequent insert.
        $candidates = Section::where('strand_id', $strandId)
            ->where('grade_level', $gradeLevel)
            ->where('school_year_id', $schoolYearId)
            ->lockForUpdate()
            ->withCount(['enrollments as enrolled_count' => function ($q) use ($schoolYearId) {
                $q->where('school_year_id', $schoolYearId)
                ->where('status', 'enrolled');
            }])
            ->get();

        // max_capacity === null means unlimited
        $available = $candidates
            ->filter(fn (Section $s) =>
                is_null($s->max_capacity)
                || $s->enrolled_count < $s->max_capacity
            )
            ->sortBy('enrolled_count')
            ->first();

        return $available?->id;
    }
}