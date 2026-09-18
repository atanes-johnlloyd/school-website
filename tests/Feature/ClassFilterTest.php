<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClassFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_teacher_class_list_loads(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();

        $this->actingAs($teacher)
            ->getJson(route('teacher.classes.index'))
            ->assertOk()
            ->assertJsonStructure([
                'classes' => ['data', 'current_page', 'last_page', 'total'],
                'filters',
                'filterOptions' => ['strands', 'terms'],
            ]);
    }

    public function test_teacher_can_search_classes(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();

        // Get a real subject name from one of the teacher's classes
        $classroom = ClassRoom::where('teacher_id', $teacher->teacher->id)->first();
        $searchTerm = substr($classroom->subject->name, 0, 4);

        $response = $this->actingAs($teacher)
            ->getJson(route('teacher.classes.index', ['search' => $searchTerm]));

        $response->assertOk();
        $this->assertGreaterThan(0, count($response->json('classes.data')));
    }

    public function test_teacher_can_filter_by_grade_level(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();

        $response = $this->actingAs($teacher)
            ->getJson(route('teacher.classes.index', ['grade_level' => '11']));

        $response->assertOk();
        foreach ($response->json('classes.data') as $class) {
            $this->assertSame('11', $class['grade_level']);
        }
    }

    public function test_teacher_can_sort_classes(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();

        $response = $this->actingAs($teacher)
            ->getJson(route('teacher.classes.index', [
                'sort' => 'subject', 'direction' => 'asc',
            ]));

        $response->assertOk();
    }

    public function test_invalid_sort_is_rejected(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();

        $this->actingAs($teacher)
            ->getJson(route('teacher.classes.index', ['sort' => 'malicious; DROP TABLE']))
            ->assertStatus(422);
    }

    public function test_student_class_list_loads(): void
    {
        $student = User::where('email', 'student@test.com')->first();

        $this->actingAs($student)
            ->getJson(route('student.classes.index'))
            ->assertOk()
            ->assertJsonStructure([
                'classes' => ['data', 'current_page', 'total'],
                'filters',
            ]);
    }

    public function test_student_can_search_classes(): void
    {
        $student = User::where('email', 'student@test.com')->first();

        $response = $this->actingAs($student)
            ->getJson(route('student.classes.index', ['search' => 'a']));

        $response->assertOk();
    }
}