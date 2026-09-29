<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardWidgetsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // ═══════════════════════════════════════════════════
    // TEACHER DASHBOARD
    // ═══════════════════════════════════════════════════

    public function test_teacher_dashboard_returns_stats(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();

        $response = $this->actingAs($teacher)->getJson(route('teacher.dashboard'));

        $response->assertOk()
                 ->assertJsonStructure([
                     'stats' => ['classes', 'students', 'pending_grading'],
                     'needs_grading',
                     'today_schedule',
                 ]);
    }

    public function test_teacher_dashboard_shows_needs_grading_queue(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();
        $classroom = ClassRoom::where('teacher_id', $teacher->teacher->id)->first();

        // Create an assignment + submission
        $assignment = Assignment::create([
            'class_id'     => $classroom->id,
            'title'        => 'Needs Grading Test',
            'due_at'       => now()->addDays(2),
            'points'       => 100,
            'is_published' => true,
            'allow_late'   => true,
        ]);

        $student = $classroom->students()->first();
        $assignment->submissions()->create([
            'student_id'   => $student->id,
            'text_content' => 'My answer',
            'status'       => 'submitted',
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($teacher)->getJson(route('teacher.dashboard'));

        $response->assertOk();
        $this->assertGreaterThanOrEqual(1, count($response->json('needs_grading')));
        $this->assertSame('Needs Grading Test', $response->json('needs_grading.0.assignment'));
    }

    public function test_student_cannot_access_teacher_dashboard(): void
    {
        $student = User::where('email', 'student@test.com')->first();

        $this->actingAs($student)
            ->getJson(route('teacher.dashboard'))
            ->assertForbidden();
    }

    // ═══════════════════════════════════════════════════
    // STUDENT DASHBOARD
    // ═══════════════════════════════════════════════════

    public function test_student_dashboard_returns_stats(): void
    {
        $student = User::where('email', 'student@test.com')->first();

        $response = $this->actingAs($student)->getJson(route('student.dashboard'));

        $response->assertOk()
                 ->assertJsonStructure([
                     'stats' => ['classes', 'pending_assignments', 'grades_available'],
                     'upcoming_deadlines',
                     'recent_announcements',
                 ]);
    }

    public function test_student_dashboard_shows_upcoming_deadlines(): void
    {
        $student = User::where('email', 'student@test.com')->first();
        $classroom = $student->student->classroom()->first();

        Assignment::create([
            'class_id'     => $classroom->id,
            'title'        => 'Due Soon Test',
            'due_at'       => now()->addDays(3),
            'points'       => 50,
            'is_published' => true,
            'allow_late'   => true,
        ]);

        // This one is due beyond the 7-day window — should NOT appear
        Assignment::create([
            'class_id'     => $classroom->id,
            'title'        => 'Far Future Test',
            'due_at'       => now()->addDays(30),
            'points'       => 50,
            'is_published' => true,
            'allow_late'   => true,
        ]);

        $response = $this->actingAs($student)->getJson(route('student.dashboard'));

        $response->assertOk();
        $titles = collect($response->json('upcoming_deadlines'))->pluck('title')->all();
        $this->assertContains('Due Soon Test', $titles);
        $this->assertNotContains('Far Future Test', $titles);
    }

    public function test_student_dashboard_excludes_submitted_assignments_from_deadlines(): void
    {
        $student = User::where('email', 'student@test.com')->first();
        $classroom = $student->student->classroom()->first();

        $assignment = Assignment::create([
            'class_id'     => $classroom->id,
            'title'        => 'Already Submitted',
            'due_at'       => now()->addDays(3),
            'points'       => 50,
            'is_published' => true,
            'allow_late'   => true,
        ]);

        $assignment->submissions()->create([
            'student_id'   => $student->student->id,
            'text_content' => 'Done',
            'status'       => 'submitted',
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($student)->getJson(route('student.dashboard'));

        $titles = collect($response->json('upcoming_deadlines'))->pluck('title')->all();
        $this->assertNotContains('Already Submitted', $titles);
    }

    public function test_student_dashboard_shows_recent_announcements(): void
    {
        $student = User::where('email', 'student@test.com')->first();
        $classroom = $student->student->classroom()->first();

        Announcement::create([
            'created_by'   => $classroom->teacher->user_id,
            'class_id'     => $classroom->id,
            'title'        => 'Dashboard Notice',
            'body'         => 'Body',
            'published_at' => now(),
        ]);

        $response = $this->actingAs($student)->getJson(route('student.dashboard'));

        $titles = collect($response->json('recent_announcements'))->pluck('title')->all();
        $this->assertContains('Dashboard Notice', $titles);
    }

    public function test_teacher_cannot_access_student_dashboard(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();

        $this->actingAs($teacher)
            ->getJson(route('student.dashboard'))
            ->assertForbidden();
    }
}