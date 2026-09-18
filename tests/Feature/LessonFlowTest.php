<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_teacher_can_create_lesson(): void
    {
        $classroom = ClassRoom::first();
        $teacher = User::find($classroom->teacher->user_id);

        $response = $this->actingAs($teacher)->postJson(
            route('teacher.classes.lessons.store', $classroom->id),
            [
                'title'        => 'Intro to Cookery',
                'body'         => 'Watch this: https://youtube.com/watch?v=abc',
                'is_published' => true,
            ]
        );

        $response->assertCreated();
        $this->assertDatabaseHas('lessons', ['title' => 'Intro to Cookery']);
    }

    public function test_student_sees_published_lessons(): void
    {
        $classroom = ClassRoom::first();
        $studentUser = User::where('id', $classroom->students->first()->user_id)->first();

        Lesson::create([
            'class_id' => $classroom->id, 'title' => 'Visible Material',
            'body' => 'Content here', 'is_published' => true,
        ]);
        Lesson::create([
            'class_id' => $classroom->id, 'title' => 'Draft Material',
            'body' => 'Hidden', 'is_published' => false,
        ]);

        $response = $this->actingAs($studentUser)
            ->getJson(route('student.classes.lessons.index', $classroom->id));

        $response->assertOk();
        $response->assertJsonFragment(['title' => 'Visible Material']);
        $response->assertJsonMissing(['title' => 'Draft Material']);
    }

    public function test_student_cannot_view_other_class_lesson(): void
    {
        $classroomA = ClassRoom::first();
        $classroomB = ClassRoom::where('section_id', '!=', $classroomA->section_id)->first();

        $lesson = Lesson::create([
            'class_id' => $classroomB->id, 'title' => 'Hidden',
            'body' => 'Not yours', 'is_published' => true,
        ]);

        $studentUser = User::where('id', $classroomA->students->first()->user_id)->first();

        $this->actingAs($studentUser)
            ->getJson(route('student.lessons.show', $lesson->id))
            ->assertForbidden();
    }

    public function test_teacher_can_update_lesson(): void
    {
        $classroom = ClassRoom::first();
        $teacher = User::find($classroom->teacher->user_id);

        $lesson = Lesson::create([
            'class_id' => $classroom->id, 'title' => 'Old Title',
            'body' => 'Old body', 'is_published' => false,
        ]);

        $this->actingAs($teacher)
            ->putJson(route('teacher.lessons.update', $lesson->id), ['title' => 'New Title'])
            ->assertOk();

        $this->assertDatabaseHas('lessons', ['id' => $lesson->id, 'title' => 'New Title']);
    }

    public function test_teacher_can_delete_lesson(): void
    {
        $classroom = ClassRoom::first();
        $teacher = User::find($classroom->teacher->user_id);

        $lesson = Lesson::create([
            'class_id' => $classroom->id, 'title' => 'Delete Me', 'body' => 'Body',
        ]);

        $this->actingAs($teacher)
            ->deleteJson(route('teacher.lessons.destroy', $lesson->id))
            ->assertOk();

        $this->assertDatabaseMissing('lessons', ['id' => $lesson->id]);
    }
}