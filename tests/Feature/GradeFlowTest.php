<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GradeFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_teacher_gradebook_loads(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();
        $classroom = ClassRoom::where('teacher_id', $teacher->teacher->id)->first();

        $response = $this->actingAs($teacher)->getJson(
            route('teacher.classes.gradebook.show', $classroom->id)
        );

        $response->assertOk()
                 ->assertJsonStructure([
                     'classroom'   => ['id', 'subject', 'section'],
                     'weights'     => ['written_work', 'performance_task', 'quarterly_exam'],
                     'assignments',
                     'students',
                     'summary'     => ['total_students', 'passing', 'failing', 'class_average'],
                 ]);
    }

    public function test_student_cannot_view_teacher_gradebook(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();
        $classroom = ClassRoom::where('teacher_id', $teacher->teacher->id)->first();
        $student = User::where('email', 'student@test.com')->first();

        $this->actingAs($student)
            ->getJson(route('teacher.classes.gradebook.show', $classroom->id))
            ->assertForbidden();
    }

    public function test_teacher_cannot_view_other_teachers_gradebook(): void
    {
        $teacherA = User::where('email', 'teacher@test.com')->first();
        $classroomOfOther = ClassRoom::where('teacher_id', '!=', $teacherA->teacher->id)->first();

        $this->actingAs($teacherA)
            ->getJson(route('teacher.classes.gradebook.show', $classroomOfOther->id))
            ->assertForbidden();
    }

    public function test_student_report_card_loads(): void
    {
        $student = User::where('email', 'student@test.com')->first();

        $response = $this->actingAs($student)
            ->getJson(route('student.grades.index'));

        $response->assertOk()
                 ->assertJsonStructure([
                     'active_term',
                     'classes',
                     'general_average',
                 ]);
    }

    public function test_computed_final_grade_matches_weights(): void
    {
        $classroom = ClassRoom::first();
        // Set uniform weights for a clean test
        $classroom->update([
            'weight_written_work'     => 25,
            'weight_performance_task' => 50,
            'weight_quarterly_exam'   => 25,
        ]);

        $student = $classroom->students()->first();

        // Delete existing assignments so we control the dataset
        Assignment::where('class_id', $classroom->id)->forceDelete();

        // One WW (50 points) — student scored 40 → 80%
        $ww = Assignment::create([
            'class_id' => $classroom->id, 'title' => 'Quiz',
            'category' => 'written_work', 'due_at' => now()->addDay(),
            'points' => 50, 'is_published' => true, 'allow_late' => true,
        ]);
        $ww->submissions()->create([
            'student_id' => $student->id, 'text_content' => 'x',
            'status' => 'graded', 'grade' => 40, 'graded_at' => now(),
        ]);

        // One PT (100 points) — student scored 90 → 90%
        $pt = Assignment::create([
            'class_id' => $classroom->id, 'title' => 'Project',
            'category' => 'performance_task', 'due_at' => now()->addDay(),
            'points' => 100, 'is_published' => true, 'allow_late' => true,
        ]);
        $pt->submissions()->create([
            'student_id' => $student->id, 'text_content' => 'x',
            'status' => 'graded', 'grade' => 90, 'graded_at' => now(),
        ]);

        // One QE (100 points) — student scored 80 → 80%
        $qe = Assignment::create([
            'class_id' => $classroom->id, 'title' => 'Exam',
            'category' => 'quarterly_exam', 'due_at' => now()->addDay(),
            'points' => 100, 'is_published' => true, 'allow_late' => true,
        ]);
        $qe->submissions()->create([
            'student_id' => $student->id, 'text_content' => 'x',
            'status' => 'graded', 'grade' => 80, 'graded_at' => now(),
        ]);

        // Expected: (80*25 + 90*50 + 80*25) / 100 = (2000 + 4500 + 2000)/100 = 85.00
        $teacher = User::where('email', 'teacher@test.com')->first();
        if ($classroom->teacher_id !== $teacher->teacher->id) {
            $classroom->update(['teacher_id' => $teacher->teacher->id]);
        }

        $response = $this->actingAs($teacher)
            ->getJson(route('teacher.classes.gradebook.show', $classroom->id));

        $response->assertOk();

        $row = collect($response->json('students'))->firstWhere('id', $student->id);
        $this->assertNotNull($row);
        $this->assertEquals(80,   $row['written_work']);
        $this->assertEquals(90,   $row['performance_task']);
        $this->assertEquals(80,   $row['quarterly_exam']);
        $this->assertEquals(85,   $row['final_grade']);
        $this->assertTrue($row['is_complete']);
    }

    public function test_partial_grading_returns_null_for_missing_categories(): void
    {
        $classroom = ClassRoom::first();
        $student = $classroom->students()->first();

        Assignment::where('class_id', $classroom->id)->forceDelete();

        $ww = Assignment::create([
            'class_id' => $classroom->id, 'title' => 'Quiz',
            'category' => 'written_work', 'due_at' => now()->addDay(),
            'points' => 100, 'is_published' => true, 'allow_late' => true,
        ]);
        $ww->submissions()->create([
            'student_id' => $student->id, 'text_content' => 'x',
            'status' => 'graded', 'grade' => 80, 'graded_at' => now(),
        ]);

        $teacher = User::where('email', 'teacher@test.com')->first();
        if ($classroom->teacher_id !== $teacher->teacher->id) {
            $classroom->update(['teacher_id' => $teacher->teacher->id]);
        }

        $response = $this->actingAs($teacher)
            ->getJson(route('teacher.classes.gradebook.show', $classroom->id));

        $row = collect($response->json('students'))->firstWhere('id', $student->id);
        $this->assertEquals(80, $row['written_work']);
        $this->assertNull($row['performance_task']);
        $this->assertNull($row['quarterly_exam']);
        $this->assertFalse($row['is_complete']);
        $this->assertNull($row['remarks']);
    }

    public function test_student_can_view_own_class_grades(): void
    {
        $student = User::where('email', 'student@test.com')->first();
        $classroom = $student->student->classroom()->first();

        $response = $this->actingAs($student)
            ->getJson(route('student.classes.grades.show', $classroom->id));

        $response->assertOk()
                 ->assertJsonStructure([
                     'classroom', 'grades', 'weights', 'assignments',
                 ]);
    }

    public function test_student_cannot_view_other_class_grades(): void
    {
        $student = User::where('email', 'student@test.com')->first();
        $studentClasses = $student->student->classroom()->pluck('classes.id');
        $otherClass = ClassRoom::whereNotIn('id', $studentClasses)->first();

        $this->actingAs($student)
            ->getJson(route('student.classes.grades.show', $otherClass->id))
            ->assertForbidden();
    }
}