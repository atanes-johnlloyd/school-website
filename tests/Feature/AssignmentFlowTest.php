<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssignmentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();   // runs DatabaseSeeder — gives us teachers, students, classes
    }

    protected function teacherUser(ClassRoom $classroom): User
    {
        return User::where('id', $classroom->teacher->user_id)->first();
    }

    public function test_teacher_can_create_assignment(): void
    {
        $classroom = ClassRoom::first();
        $teacher = $this->teacherUser($classroom);

        $response = $this->actingAs($teacher)->postJson(
            route('teacher.classes.assignments.store', $classroom->id),
            [
                'title'        => 'Essay on Rizal',
                'instructions' => 'Write 500 words.',
                'due_at'       => now()->addDays(3)->toIso8601String(),
                'points'       => 100,
                'allow_late'   => true,
                'is_published' => true,
            ]
        );

        $response->assertCreated();
        $this->assertDatabaseHas('assignments', ['title' => 'Essay on Rizal']);
    }

    public function test_student_can_view_published_assignments(): void
    {
        $classroom   = ClassRoom::first();
        $teacher = $this->teacherUser($classroom);

        $assignment = Assignment::create([
            'class_id'      => $classroom->id,
            'title'         => 'Quiz 1',
            'due_at'        => now()->addDays(2),
            'points'        => 50,
            'is_published'  => true,
            'allow_late'    => true,
        ]);

        $student = User::where('id', $classroom->students->first()->user_id)->first();

        $response = $this->actingAs($student)
            ->getJson(route('student.classes.assignments.index', $classroom->id));

        $response->assertOk()
                 ->assertJsonFragment(['title' => 'Quiz 1']);
    }

    public function test_student_can_submit_assignment(): void
    {
        $classroom = ClassRoom::first();
        $student = User::where('id', $classroom->students->first()->user_id)->first();

        $assignment = Assignment::create([
            'class_id'      => $classroom->id,
            'title'         => 'Homework 1',
            'due_at'        => now()->addDays(2),
            'points'        => 50,
            'is_published'  => true,
            'allow_late'    => true,
        ]);

        $response = $this->actingAs($student)->postJson(
            route('student.assignments.submit', $assignment->id),
            ['text_content' => 'Here is my answer.']
        );

        $response->assertCreated();
        $this->assertDatabaseHas('assignment_submissions', [
            'assignment_id' => $assignment->id,
            'status'        => 'submitted',
        ]);
    }

    public function test_teacher_can_grade_submission(): void
    {
        $classroom = ClassRoom::first();
        $teacher = $this->teacherUser($classroom);
        $studentUser = User::where('id', $classroom->students->first()->user_id)->first();

        $assignment = Assignment::create([
            'class_id' => $classroom->id, 'title' => 'Quiz', 'due_at' => now()->addDay(),
            'points' => 100, 'is_published' => true, 'allow_late' => true,
        ]);

        $this->actingAs($studentUser)->postJson(
            route('student.assignments.submit', $assignment->id),
            ['text_content' => 'Answer']
        );

        $submission = $assignment->submissions()->first();

        $response = $this->actingAs($teacher)->putJson(
            route('teacher.submissions.grade', $submission->id),
            ['grade' => 95, 'feedback' => 'Great work!']
        );

        $response->assertOk();
        $this->assertDatabaseHas('assignment_submissions', [
            'id'     => $submission->id,
            'grade'  => 95,
            'status' => 'graded',
        ]);
    }

    public function test_student_cannot_see_other_class_assignment(): void
    {
        $classroomA = \App\Models\ClassRoom::first();

        // ← Force different sections: pick a class from a DIFFERENT section
        $classroomB = \App\Models\ClassRoom::where('section_id', '!=', $classroomA->section_id)
            ->first();

        $this->assertNotNull($classroomB, 'Test needs 2 classes from different sections.');

        $assignment = \App\Models\Assignment::create([
            'class_id'      => $classroomB->id,
            'title'         => 'Hidden',
            'due_at'        => now()->addDay(),
            'points'        => 10,
            'is_published'  => true,
            'allow_late'    => true,
        ]);

        $student = \App\Models\User::where('id', $classroomA->students->first()->user_id)->first();

        $this->actingAs($student)
            ->getJson(route('student.assignments.show', $assignment->id))
            ->assertForbidden();
    }
}