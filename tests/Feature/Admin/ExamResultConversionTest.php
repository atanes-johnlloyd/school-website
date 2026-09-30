<?php

namespace Tests\Feature\Admin;

use App\Models\Applicant;
use App\Models\EntranceExam;
use App\Models\EntranceExamResult;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Strand;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamResultConversionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function admin(): User
    {
        return User::where('email', 'admin@test.com')->first();
    }

    protected function buildCompletedExamWithApprovedApplicant(): array
    {
        $activeYear = SchoolYear::where('is_active', true)->first();
        $strand = Strand::first();

        $applicant = Applicant::create([
            'reference_number'    => '2026-' . rand(1000, 9999),
            'school_year_id'      => $activeYear->id,
            'strand_id'           => $strand->id,
            'applicant_type'      => 'Grade11',
            'first_name'          => 'Maria',
            'last_name'           => 'Santos',
            'lrn'                 => '999888777' . rand(100, 999),
            'date_of_birth'       => '2009-05-15',
            'sex'                 => 'Female',
            'contact_number'      => '09171234567',
            'email'               => 'maria' . rand(1000, 9999) . '@test.com',
            'prev_school_name'    => 'Test School',
            'prev_school_type'    => 'Public',
            'desired_grade_level' => '11',
            'status'              => 'approved',
            'submitted_at'        => now(),
        ]);

        // Create a section for strand + grade 11 with capacity
        $section = Section::create([
            'school_year_id' => $activeYear->id,
            'strand_id'      => $strand->id,
            'grade_level'    => '11',
            'name'           => 'Grade 11 - Test',
            'max_capacity'   => 40,
        ]);

        $exam = EntranceExam::create([
            'school_year_id' => $activeYear->id,
            'exam_name'      => 'Past Exam',
            'exam_date'      => now()->subDays(2),
            'exam_time'      => '09:00',
            'max_capacity'   => 30,
            'grade_level'    => 'All',
            'status'         => 'Upcoming',
        ]);

        $result = EntranceExamResult::create([
            'entrance_exam_id' => $exam->id,
            'applicant_id'     => $applicant->id,
            'result'           => 'Pending',
        ]);

        return [$exam, $result, $applicant, $section];
    }

    public function test_admin_can_record_result_and_auto_convert_on_pass(): void
    {
        [$exam, $result, $applicant, $section] = $this->buildCompletedExamWithApprovedApplicant();

        $this->actingAs($this->admin())->putJson(
            route('admin.exam-results.update', $result->id),
            ['score' => 85, 'result' => 'Passed']
        )->assertOk();

        // Result saved
        $this->assertSame('Passed', $result->fresh()->result);

        // Applicant converted
        $applicant->refresh();
        $this->assertSame('enrolled', $applicant->status);
        $this->assertNotNull($applicant->converted_student_id);

        // User created
        $student = Student::find($applicant->converted_student_id);
        $this->assertNotNull($student);
        $this->assertNotNull($student->user);

        // Enrolled in the section
        $enrollment = Enrollment::where('student_id', $student->id)->first();
        $this->assertNotNull($enrollment);
        $this->assertSame('enrolled', $enrollment->status);
        $this->assertSame($section->id, $enrollment->section_id);
    }

    public function test_result_can_be_recorded_when_no_section_available(): void
    {
        [$exam, $result, $applicant, $section] = $this->buildCompletedExamWithApprovedApplicant();

        // Remove ALL sections that could match this applicant
        Section::where('school_year_id', $section->school_year_id)
            ->where('strand_id', $section->strand_id)
            ->where('grade_level', '11')
            ->delete();

        $this->actingAs($this->admin())->putJson(
            route('admin.exam-results.update', $result->id),
            ['score' => 85, 'result' => 'Passed']
        )->assertOk();

        $applicant->refresh();
        $student = Student::find($applicant->converted_student_id);
        $enrollment = Enrollment::where('student_id', $student->id)->first();

        $this->assertSame('pending', $enrollment->status);
        $this->assertNull($enrollment->section_id);
    }

    public function test_failed_result_does_not_convert(): void
    {
        [$exam, $result, $applicant, $section] = $this->buildCompletedExamWithApprovedApplicant();

        $this->actingAs($this->admin())->putJson(
            route('admin.exam-results.update', $result->id),
            ['score' => 50, 'result' => 'Failed']
        )->assertOk();

        $applicant->refresh();
        $this->assertSame('approved', $applicant->status);
        $this->assertNull($applicant->converted_student_id);
    }

    public function test_cannot_convert_already_converted_applicant(): void
    {
        [$exam, $result, $applicant, $section] = $this->buildCompletedExamWithApprovedApplicant();

        // First conversion
        $this->actingAs($this->admin())->putJson(
            route('admin.exam-results.update', $result->id),
            ['score' => 85, 'result' => 'Passed']
        )->assertOk();

        $this->assertNotNull($applicant->fresh()->converted_student_id);

        // Cannot re-convert — result stays but no double Student
        $countBefore = Student::count();

        // Simulate re-approval
        $applicant->update(['status' => 'approved']);
        $this->actingAs($this->admin())->putJson(
            route('admin.exam-results.update', $result->id),
            ['score' => 85, 'result' => 'Passed']
        )->assertOk();

        $this->assertSame($countBefore, Student::count());
    }

    public function test_cannot_record_result_on_future_exam(): void
    {
        [$exam, $result, $applicant, $section] = $this->buildCompletedExamWithApprovedApplicant();

        $exam->update(['exam_date' => now()->addDays(5), 'exam_time' => '09:00']);

        $this->actingAs($this->admin())->putJson(
            route('admin.exam-results.update', $result->id),
            ['score' => 85, 'result' => 'Passed']
        )->assertStatus(422);
    }
}