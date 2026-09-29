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
     * Matches the old convertToStudent() behavior.
     *
     * @return array{success: bool, message: string, student_id?: int, section_id?: int|null, status?: string, temp_password?: string}
     */
    public function convert(Applicant $applicant): array
    {
        if (! in_array($applicant->status, ['approved', 'enrolled'], true)) {
            return ['success' => false, 'message' => 'Applicant is not approved for enrollment.'];
        }

        if ($applicant->converted_student_id) {
            return [
                'success'    => false,
                'message'    => 'Applicant is already converted to a student.',
                'student_id' => $applicant->converted_student_id,
            ];
        }

        $activeYear = SchoolYear::where('is_active', true)->first();
        if (! $activeYear) {
            return ['success' => false, 'message' => 'No active school year.'];
        }

        return DB::transaction(function () use ($applicant, $activeYear) {
            // ─── 1. Create User account ────────────────
            $tempPassword = Str::random(12);

            $user = User::create([
                'name'                 => $applicant->full_name,
                'email'                => $applicant->email,
                'password'             => Hash::make($tempPassword),
                'must_change_password' => true,
                'email_verified_at'    => now(),
            ]);
            $user->assignRole('student');

            // ─── 2. Create Student profile ────────────
            $student = Student::create([
                'user_id'        => $user->id,
                'lrn'            => $applicant->lrn,
                'sex'            => strtolower($applicant->sex),
                'date_of_birth'  => $applicant->date_of_birth,
                'contact_number' => $applicant->contact_number,
                'house_street'   => $applicant->house_street,
                'barangay'       => $applicant->barangay,
                'municipality'   => $applicant->municipality,
                'province'       => $applicant->province,
                'zip_code'       => $applicant->zip_code,
                'status'         => 'active',
            ]);

            // ─── 3. Find an available section ──────────
            $sectionId = $this->findAvailableSection(
                $applicant->strand_id,
                $applicant->desired_grade_level,
                $activeYear->id
            );

            $enrollmentStatus = $sectionId ? 'enrolled' : 'pending';

            // ─── 4. Create Enrollment ──────────────────
            Enrollment::create([
                'student_id'     => $student->id,
                'school_year_id' => $activeYear->id,
                'section_id'     => $sectionId,
                'status'         => $enrollmentStatus,
                'enrolled_at'    => $sectionId ? now() : null,
                'enrolled_by'    => auth()->id(),
            ]);

            // ─── 5. Auto-attach to classes if section assigned ──
            if ($sectionId) {
                $classrooms = ClassRoom::where('section_id', $sectionId)->get();
                foreach ($classrooms as $classroom) {
                    $classroom->students()->syncWithoutDetaching([
                        $student->id => ['status' => 'active', 'enrolled_at' => now()],
                    ]);
                }
            }

            // ─── 6. Update applicant ───────────────────
            $applicant->update([
                'status'               => 'enrolled',
                'converted_student_id' => $student->id,
            ]);

            return [
                'success'       => true,
                'message'       => $sectionId
                    ? 'Applicant converted and enrolled successfully.'
                    : 'Applicant converted. Waiting for section assignment.',
                'student_id'    => $student->id,
                'section_id'    => $sectionId,
                'status'        => $enrollmentStatus,
                'temp_password' => $tempPassword,
            ];
        });
    }

    /**
     * Find the least-crowded section matching strand + grade level with capacity.
     */
    public function findAvailableSection(?int $strandId, string $gradeLevel, int $schoolYearId): ?int
    {
        if (! $strandId) return null;

        $section = Section::where('strand_id', $strandId)
            ->where('grade_level', $gradeLevel)
            ->where('school_year_id', $schoolYearId)
            ->withCount(['students' => function ($q) use ($schoolYearId) {
                $q->whereHas('enrollments', function ($eq) use ($schoolYearId) {
                    $eq->where('school_year_id', $schoolYearId)
                       ->where('status', 'enrolled');
                });
            }])
            ->get()
            ->filter(fn (Section $s) => $s->students_count < $s->max_capacity)
            ->sortBy('students_count')
            ->first();

        return $section?->id;
    }
}