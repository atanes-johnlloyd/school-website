<?php

namespace Tests\Feature;

use App\Mail\TemplateMail;
use App\Models\Applicant;
use App\Models\EntranceExam;
use App\Models\SchoolYear;
use App\Models\Strand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailFlowTest extends TestCase
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

    protected function makeApplicant(array $overrides = []): Applicant
    {
        $year = SchoolYear::where('is_active', true)->first();

        return Applicant::create(array_merge([
            'reference_number'    => '2026-' . rand(1000, 9999),
            'school_year_id'      => $year->id,
            'strand_id'           => Strand::first()->id,
            'applicant_type'      => 'Grade11',
            'first_name'          => 'Test',
            'last_name'           => 'Applicant',
            'lrn'                 => '123456789' . rand(100, 999),
            'date_of_birth'       => '2009-05-15',
            'sex'                 => 'Male',
            'contact_number'      => '09171234567',
            'email'               => 'applicant' . rand(1000, 9999) . '@test.com',
            'prev_school_name'    => 'Test School',
            'prev_school_type'    => 'Public',
            'desired_grade_level' => '11',
            'status'              => 'pending',
            'submitted_at'        => now(),
        ], $overrides));
    }

    public function test_approving_applicant_sends_email_with_exam_when_available(): void
    {
        Mail::fake();

        $exam = EntranceExam::create([
            'school_year_id' => SchoolYear::where('is_active', true)->value('id'),
            'exam_name'      => 'Test Exam',
            'exam_date'      => now()->addDays(10),
            'exam_time'      => '09:00:00',
            'max_capacity'   => 30,
            'grade_level'    => 'All',
            'status'         => 'Upcoming',
        ]);

        $applicant = $this->makeApplicant();

        $this->actingAs($this->admin())
            ->putJson(route('admin.applicants.approve', $applicant->id))
            ->assertOk();

        Mail::assertSent(TemplateMail::class, function (TemplateMail $mail) use ($applicant) {
            return $mail->hasTo($applicant->email)
                && $mail->templateKey === 'accepted_with_exam'
                && isset($mail->placeholders['schedule']);
        });
    }

    public function test_approving_applicant_sends_no_exam_email_when_none_available(): void
    {
        Mail::fake();

        $applicant = $this->makeApplicant();

        $this->actingAs($this->admin())
            ->putJson(route('admin.applicants.approve', $applicant->id))
            ->assertOk();

        Mail::assertSent(TemplateMail::class, fn (TemplateMail $mail) => $mail->templateKey === 'accepted_no_exam');
    }

    public function test_rejecting_applicant_sends_rejection_email(): void
    {
        Mail::fake();

        $applicant = $this->makeApplicant();

        $this->actingAs($this->admin())
            ->putJson(route('admin.applicants.reject', $applicant->id), [
                'reason' => 'Incomplete documents.',
            ])
            ->assertOk();

        Mail::assertSent(TemplateMail::class, function (TemplateMail $mail) use ($applicant) {
            return $mail->hasTo($applicant->email)
                && $mail->templateKey === 'reject'
                && $mail->placeholders['reason'] === 'Incomplete documents.';
        });
    }

    public function test_requesting_resubmission_sends_email_with_link(): void
    {
        Mail::fake();

        $applicant = $this->makeApplicant();

        $this->actingAs($this->admin())
            ->putJson(route('admin.applicants.request-resubmission', $applicant->id), [
                'reason' => 'Blurry document.',
            ])
            ->assertOk();

        Mail::assertSent(TemplateMail::class, function (TemplateMail $mail) {
            return $mail->templateKey === 'resubmission_request'
                && str_contains($mail->placeholders['resubmit_link'] ?? '', '/site/admission/resubmit');
        });
    }

    public function test_recording_passed_exam_sends_passed_email(): void
    {
        Mail::fake();

        // Build approved applicant + exam result Pending
        $applicant = $this->makeApplicant(['status' => 'approved']);
        $exam = EntranceExam::create([
            'school_year_id' => SchoolYear::where('is_active', true)->value('id'),
            'exam_name'      => 'Past Exam',
            'exam_date'      => now()->subDays(2),
            'exam_time'      => '09:00:00',
            'max_capacity'   => 30,
            'grade_level'    => 'All',
            'status'         => 'Upcoming',
        ]);
        $result = \App\Models\EntranceExamResult::create([
            'entrance_exam_id' => $exam->id,
            'applicant_id'     => $applicant->id,
            'result'           => 'Pending',
        ]);

        $this->actingAs($this->admin())
            ->putJson(route('admin.exam-results.update', $result->id), [
                'score' => 85, 'result' => 'Passed',
            ])
            ->assertOk();

        Mail::assertSent(TemplateMail::class, fn (TemplateMail $mail) => $mail->templateKey === 'passed-exam');
    }

    public function test_recording_failed_exam_sends_failed_email(): void
    {
        Mail::fake();

        $applicant = $this->makeApplicant(['status' => 'approved']);
        $exam = EntranceExam::create([
            'school_year_id' => SchoolYear::where('is_active', true)->value('id'),
            'exam_name'      => 'Past Exam',
            'exam_date'      => now()->subDays(2),
            'exam_time'      => '09:00:00',
            'max_capacity'   => 30,
            'grade_level'    => 'All',
            'status'         => 'Upcoming',
        ]);
        $result = \App\Models\EntranceExamResult::create([
            'entrance_exam_id' => $exam->id,
            'applicant_id'     => $applicant->id,
            'result'           => 'Pending',
        ]);

        $this->actingAs($this->admin())
            ->putJson(route('admin.exam-results.update', $result->id), [
                'score' => 40, 'result' => 'Failed',
            ])
            ->assertOk();

        Mail::assertSent(TemplateMail::class, fn (TemplateMail $mail) => $mail->templateKey === 'not-pass-exam');
    }

    public function test_recording_absent_sends_absent_email(): void
    {
        Mail::fake();

        $applicant = $this->makeApplicant(['status' => 'approved']);
        $exam = EntranceExam::create([
            'school_year_id' => SchoolYear::where('is_active', true)->value('id'),
            'exam_name'      => 'Past Exam',
            'exam_date'      => now()->subDays(2),
            'exam_time'      => '09:00:00',
            'max_capacity'   => 30,
            'grade_level'    => 'All',
            'status'         => 'Upcoming',
        ]);
        $result = \App\Models\EntranceExamResult::create([
            'entrance_exam_id' => $exam->id,
            'applicant_id'     => $applicant->id,
            'result'           => 'Pending',
        ]);

        $this->actingAs($this->admin())
            ->putJson(route('admin.exam-results.update', $result->id), [
                'result' => 'Absent',
            ])
            ->assertOk();

        Mail::assertSent(TemplateMail::class, fn (TemplateMail $mail) => $mail->templateKey === 'absent-notice');
    }
}