<?php

namespace Tests\Feature\Admin;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportExportTest extends TestCase
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

    protected function teacher(): User
    {
        return User::where('email', 'teacher@test.com')->first();
    }

    // ─── Reports ─────────────────────────────────────────

    public function test_admin_can_view_reports(): void
    {
        $this->actingAs($this->admin())
            ->getJson(route('admin.reports.index'))
            ->assertOk()
            ->assertJsonStructure([
                'school_year_id',
                'tracks',
                'school_years',
                'data' => [
                    'applicant_stats',
                    'student_stats',
                    'teacher_stats',
                    'section_stats',
                    'exam_stats',
                    'exam_summary',
                    'monthly_trend',
                    'strand_distribution',
                    'section_occupancy',
                    'grade_summary',
                    'attendance_summary',
                    'recent_applications',
                    'recent_enrollments',
                    'upcoming_exams',
                ],
            ]);
    }

    public function test_teacher_cannot_view_reports(): void
    {
        $this->actingAs($this->teacher())
            ->getJson(route('admin.reports.index'))
            ->assertForbidden();
    }

    public function test_reports_support_school_year_filter(): void
    {
        $year = \App\Models\SchoolYear::first();

        $this->actingAs($this->admin())
            ->getJson(route('admin.reports.index', ['school_year_id' => $year->id]))
            ->assertOk()
            ->assertJson(['school_year_id' => $year->id]);
    }

    // ─── Exports ─────────────────────────────────────────

    public function test_admin_can_export_students_csv(): void
    {
        $response = $this->actingAs($this->admin())
            ->get(route('admin.exports.students'));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }

    public function test_admin_can_export_teachers_csv(): void
    {
        $response = $this->actingAs($this->admin())
            ->get(route('admin.exports.teachers'));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }

    public function test_admin_can_export_enrollments_csv(): void
    {
        $response = $this->actingAs($this->admin())
            ->get(route('admin.exports.enrollments'));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }

    public function test_admin_can_export_applicants_csv(): void
    {
        $response = $this->actingAs($this->admin())
            ->get(route('admin.exports.applicants'));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }

    public function test_teacher_can_export_class_list(): void
    {
        $teacher = $this->teacher();
        $classroom = ClassRoom::where('teacher_id', $teacher->teacher->id)->first();

        $response = $this->actingAs($teacher)
            ->get(route('exports.class-list', $classroom->id));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }

    public function test_teacher_cannot_export_other_teachers_class(): void
    {
        $teacher = $this->teacher();
        $otherClass = ClassRoom::where('teacher_id', '!=', $teacher->teacher->id)->first();

        $this->actingAs($teacher)
            ->get(route('exports.class-list', $otherClass->id))
            ->assertForbidden();
    }

    public function test_teacher_can_export_gradebook(): void
    {
        $teacher = $this->teacher();
        $classroom = ClassRoom::where('teacher_id', $teacher->teacher->id)->first();

        $response = $this->actingAs($teacher)
            ->get(route('exports.gradebook', $classroom->id));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }
}