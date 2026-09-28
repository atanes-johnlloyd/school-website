<?php

namespace Tests\Feature\Admin;

use App\Models\Enrollment;
use App\Models\Klass;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SectionEnrollmentTest extends TestCase
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

    public function test_admin_can_create_section(): void
    {
        $year = \App\Models\SchoolYear::first();
        $strand = \App\Models\Strand::where('code', 'STEM')->first();

        $this->actingAs($this->admin())->postJson(route('admin.sections.store'), [
            'school_year_id' => $year->id,
            'strand_id'      => $strand->id,
            'grade_level'    => '11',
            'name'           => 'Grade 11 - STEM Z',
            'max_capacity'   => 40,
        ])->assertCreated();

        $this->assertDatabaseHas('sections', ['name' => 'Grade 11 - STEM Z']);
    }

    public function test_admin_can_enroll_student_in_section(): void
    {
        $section = Section::first();
        $student = Student::whereDoesntHave('enrollments', function ($q) use ($section) {
            $q->where('school_year_id', $section->school_year_id);
        })->first() ?? Student::factory()->create();

        $this->actingAs($this->admin())->postJson(
            route('admin.sections.enroll', $section->id),
            ['student_id' => $student->id]
        )->assertCreated();

        $this->assertDatabaseHas('enrollments', [
            'student_id'     => $student->id,
            'section_id'     => $section->id,
            'status'         => 'enrolled',
        ]);
    }

    public function test_duplicate_enrollment_is_rejected(): void
    {
        $existing = Enrollment::where('status', 'enrolled')->first();

        $this->actingAs($this->admin())->postJson(
            route('admin.sections.enroll', $existing->section_id),
            ['student_id' => $existing->student_id]
        )->assertStatus(422);
    }

    public function test_admin_can_remove_student_from_section(): void
    {
        $enrollment = Enrollment::where('status', 'enrolled')->first();

        $this->actingAs($this->admin())->deleteJson(
            route('admin.sections.students.remove', [
                'section' => $enrollment->section_id,
                'student' => $enrollment->student_id,
            ])
        )->assertOk();
    }

    public function test_admin_can_approve_pending_enrollment(): void
    {
        $section = Section::first();
        $student = Student::factory()->create();

        $enrollment = Enrollment::create([
            'student_id'     => $student->id,
            'school_year_id' => $section->school_year_id,
            'section_id'     => $section->id,
            'status'         => 'pending',
        ]);

        $this->actingAs($this->admin())
            ->putJson(route('admin.enrollments.approve', $enrollment->id))
            ->assertOk();

        $this->assertSame('enrolled', $enrollment->fresh()->status);
    }
}