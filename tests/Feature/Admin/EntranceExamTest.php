<?php

namespace Tests\Feature\Admin;

use App\Models\Applicant;
use App\Models\EntranceExam;
use App\Models\EntranceExamResult;
use App\Models\SchoolYear;
use App\Models\Strand;
use App\Models\Track;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntranceExamTest extends TestCase
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

    protected function makeApprovedApplicant(array $overrides = []): Applicant
    {
        $strand = Strand::first();

        return Applicant::create(array_merge([
            'reference_number'    => '2026-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'school_year_id'      => SchoolYear::where('is_active', true)->value('id'),
            'strand_id'           => $strand->id,
            'applicant_type'      => 'Grade11',
            'first_name'          => 'Juan',
            'last_name'           => 'Dela Cruz',
            'lrn'                 => '123456789' . rand(100, 999),
            'date_of_birth'       => '2009-05-15',
            'sex'                 => 'Male',
            'contact_number'      => '09171234567',
            'email'               => 'applicant' . rand(1000, 9999) . '@test.com',
            'prev_school_name'    => 'Test School',
            'prev_school_type'    => 'Public',
            'desired_grade_level' => '11',
            'status'              => 'approved',
            'submitted_at'        => now(),
        ], $overrides));
    }

    public function test_admin_can_list_exams(): void
    {
        $this->actingAs($this->admin())
            ->getJson(route('admin.entrance-exams.index'))
            ->assertOk()
            ->assertJsonStructure(['exams', 'stats' => ['total', 'upcoming', 'ongoing', 'completed', 'cancelled']]);
    }

    public function test_teacher_cannot_list_exams(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();
        $this->actingAs($teacher)->getJson(route('admin.entrance-exams.index'))->assertForbidden();
    }

    public function test_admin_can_create_exam(): void
    {
        $response = $this->actingAs($this->admin())->postJson(route('admin.entrance-exams.store'), [
            'school_year_id' => SchoolYear::where('is_active', true)->value('id'),
            'exam_name'      => 'Entrance Exam 1',
            'exam_date'      => now()->addDays(10)->toDateString(),
            'exam_time'      => '09:00',
            'venue'          => 'Gymnasium',
            'max_capacity'   => 30,
            'grade_level'    => 'All',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('entrance_exams', ['exam_name' => 'Entrance Exam 1']);
    }

    public function test_exam_auto_assigns_approved_applicants_when_7_days_away(): void
    {
        $this->makeApprovedApplicant();
        $this->makeApprovedApplicant();

        $this->actingAs($this->admin())->postJson(route('admin.entrance-exams.store'), [
            'school_year_id' => SchoolYear::where('is_active', true)->value('id'),
            'exam_name'      => 'Auto-Assign Exam',
            'exam_date'      => now()->addDays(10)->toDateString(),
            'exam_time'      => '09:00',
            'grade_level'    => 'All',
        ])->assertCreated();

        $exam = EntranceExam::where('exam_name', 'Auto-Assign Exam')->first();
        $this->assertSame(2, $exam->results()->count());
    }

    public function test_exam_does_not_auto_assign_when_less_than_7_days(): void
    {
        $this->makeApprovedApplicant();

        $this->actingAs($this->admin())->postJson(route('admin.entrance-exams.store'), [
            'school_year_id' => SchoolYear::where('is_active', true)->value('id'),
            'exam_name'      => 'Short Notice Exam',
            'exam_date'      => now()->addDays(3)->toDateString(),
            'exam_time'      => '09:00',
            'grade_level'    => 'All',
        ])->assertCreated();

        $exam = EntranceExam::where('exam_name', 'Short Notice Exam')->first();
        $this->assertSame(0, $exam->results()->count());
    }

    public function test_admin_can_manually_assign_an_applicant(): void
    {
        $applicant = $this->makeApprovedApplicant();
        $exam = EntranceExam::create([
            'school_year_id' => SchoolYear::where('is_active', true)->value('id'),
            'exam_name'      => 'Manual Exam',
            'exam_date'      => now()->addDays(20),
            'exam_time'      => '09:00',
            'max_capacity'   => 10,
            'grade_level'    => 'All',
            'status'         => 'Upcoming',
        ]);

        $this->actingAs($this->admin())->postJson(
            route('admin.entrance-exams.assign', $exam->id),
            ['applicant_ids' => [$applicant->id]]
        )->assertOk();

        $this->assertDatabaseHas('entrance_exam_results', [
            'entrance_exam_id' => $exam->id,
            'applicant_id'     => $applicant->id,
            'result'           => 'Pending',
        ]);
    }

    public function test_assigning_beyond_capacity_is_rejected(): void
    {
        $exam = EntranceExam::create([
            'school_year_id' => SchoolYear::where('is_active', true)->value('id'),
            'exam_name'      => 'Small Exam',
            'exam_date'      => now()->addDays(20),
            'exam_time'      => '09:00',
            'max_capacity'   => 1,
            'grade_level'    => 'All',
            'status'         => 'Upcoming',
        ]);

        $a1 = $this->makeApprovedApplicant();
        $a2 = $this->makeApprovedApplicant();

        $this->actingAs($this->admin())->postJson(
            route('admin.entrance-exams.assign', $exam->id),
            ['applicant_ids' => [$a1->id, $a2->id]]
        )->assertOk();

        // Only 1 should be assigned
        $this->assertSame(1, $exam->fresh()->results()->count());
    }

    public function test_admin_can_cancel_exam(): void
    {
        $exam = EntranceExam::create([
            'school_year_id' => SchoolYear::where('is_active', true)->value('id'),
            'exam_name'      => 'To Cancel',
            'exam_date'      => now()->addDays(5),
            'exam_time'      => '09:00',
            'max_capacity'   => 30,
            'grade_level'    => 'All',
            'status'         => 'Upcoming',
        ]);

        $this->actingAs($this->admin())
            ->putJson(route('admin.entrance-exams.cancel', $exam->id))
            ->assertOk();

        $this->assertSame('Cancelled', $exam->fresh()->status);
    }

    public function test_removing_applicant_from_exam(): void
    {
        $applicant = $this->makeApprovedApplicant();
        $exam = EntranceExam::create([
            'school_year_id' => SchoolYear::where('is_active', true)->value('id'),
            'exam_name'      => 'Remove Test',
            'exam_date'      => now()->addDays(10),
            'exam_time'      => '09:00',
            'max_capacity'   => 30,
            'grade_level'    => 'All',
            'status'         => 'Upcoming',
        ]);
        EntranceExamResult::create([
            'entrance_exam_id' => $exam->id,
            'applicant_id'     => $applicant->id,
        ]);

        $this->actingAs($this->admin())->postJson(
            route('admin.entrance-exams.remove-applicant', $exam->id),
            ['applicant_id' => $applicant->id]
        )->assertOk();

        $this->assertSame(0, $exam->results()->count());
    }
}