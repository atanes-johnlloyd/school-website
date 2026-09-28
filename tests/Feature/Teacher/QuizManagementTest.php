<?php

namespace Tests\Feature\Teacher;

use App\Models\ClassRoom;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function teacher(): User
    {
        return User::where('email', 'teacher@test.com')->first();
    }

    protected function classroom(): ClassRoom
    {
        return ClassRoom::where('teacher_id', $this->teacher()->teacher->id)->first();
    }

    protected function makeQuestion(array $overrides = []): Question
    {
        $subject = Subject::first();

        return Question::create(array_merge([
            'teacher_id'    => $this->teacher()->teacher->id,
            'subject_id'    => $subject->id,
            'category'      => 'Week 1',
            'type'          => 'multiple_choice',
            'question_text' => 'What is 2+2?',
            'points'        => 1,
        ], $overrides));
    }

    // ─── Quiz CRUD ─────────────────────────────────

    public function test_teacher_can_list_quizzes_in_class(): void
    {
        $this->actingAs($this->teacher())
            ->getJson(route('teacher.classes.quizzes.index', $this->classroom()->id))
            ->assertOk()
            ->assertJsonStructure(['classroom', 'quizzes']);
    }

    public function test_teacher_can_create_quiz(): void
    {
        $response = $this->actingAs($this->teacher())->postJson(
            route('teacher.classes.quizzes.store', $this->classroom()->id),
            [
                'title'              => 'Quiz 1',
                'time_limit_minutes' => 30,
                'passing_score'      => 75,
            ]
        );

        $response->assertCreated();
        $this->assertDatabaseHas('quizzes', ['title' => 'Quiz 1']);
    }

    public function test_quiz_defaults_all_show_flags_to_true(): void
    {
        $this->actingAs($this->teacher())->postJson(
            route('teacher.classes.quizzes.store', $this->classroom()->id),
            ['title' => 'Default Flags Quiz']
        );

        $quiz = Quiz::where('title', 'Default Flags Quiz')->first();
        $this->assertTrue($quiz->show_score_immediately);
        $this->assertTrue($quiz->show_correct_answers);
        $this->assertTrue($quiz->show_explanations);
    }

    public function test_quiz_starts_unpublished(): void
    {
        $this->actingAs($this->teacher())->postJson(
            route('teacher.classes.quizzes.store', $this->classroom()->id),
            ['title' => 'Draft Quiz']
        );

        $this->assertFalse(Quiz::where('title', 'Draft Quiz')->first()->is_published);
    }

    public function test_teacher_can_update_quiz(): void
    {
        $quiz = $this->classroom()->quizzes()->create([
            'title' => 'Old Title',
        ]);

        $this->actingAs($this->teacher())->putJson(
            route('teacher.quizzes.update', $quiz->id),
            ['title' => 'New Title', 'time_limit_minutes' => 45]
        )->assertOk();

        $this->assertSame('New Title', $quiz->fresh()->title);
        $this->assertSame(45, $quiz->fresh()->time_limit_minutes);
    }

    // ─── Attach questions ──────────────────────────

    public function test_teacher_can_attach_questions_to_quiz(): void
    {
        $quiz = $this->classroom()->quizzes()->create(['title' => 'Quiz']);
        $q1 = $this->makeQuestion(['question_text' => 'Q1']);
        $q2 = $this->makeQuestion(['question_text' => 'Q2']);

        $this->actingAs($this->teacher())->postJson(
            route('teacher.quizzes.attach-questions', $quiz->id),
            ['question_ids' => [$q1->id, $q2->id]]
        )->assertOk();

        $this->assertSame(2, $quiz->questions()->count());
    }

    public function test_teacher_cannot_attach_other_teachers_questions(): void
    {
        $quiz = $this->classroom()->quizzes()->create(['title' => 'Quiz']);

        // Create another teacher's question
        $otherTeacherUser = User::factory()->create();
        $otherTeacherUser->assignRole('teacher');
        $otherTeacher = \App\Models\Teacher::factory()->create(['user_id' => $otherTeacherUser->id]);
        $otherQuestion = Question::create([
            'teacher_id'    => $otherTeacher->id,
            'subject_id'    => Subject::first()->id,
            'type'          => 'essay',
            'question_text' => 'Not yours',
        ]);

        $this->actingAs($this->teacher())->postJson(
            route('teacher.quizzes.attach-questions', $quiz->id),
            ['question_ids' => [$otherQuestion->id]]
        )->assertForbidden();
    }

    public function test_teacher_can_detach_question_from_quiz(): void
    {
        $quiz = $this->classroom()->quizzes()->create(['title' => 'Quiz']);
        $q = $this->makeQuestion();
        $quiz->questions()->attach($q->id, ['position' => 0]);

        $this->actingAs($this->teacher())->deleteJson(
            route('teacher.quizzes.detach-question', [$quiz->id, $q->id])
        )->assertOk();

        $this->assertSame(0, $quiz->questions()->count());
        $this->assertDatabaseHas('questions', ['id' => $q->id]); // not deleted from bank
    }

    // ─── Publish ───────────────────────────────────

    public function test_quiz_with_no_questions_cannot_publish(): void
    {
        $quiz = $this->classroom()->quizzes()->create(['title' => 'Empty']);

        $this->actingAs($this->teacher())->putJson(
            route('teacher.quizzes.toggle-publish', $quiz->id)
        )->assertStatus(422);
    }

    public function test_quiz_with_questions_can_publish(): void
    {
        $quiz = $this->classroom()->quizzes()->create(['title' => 'Ready']);
        $q = $this->makeQuestion();
        $quiz->questions()->attach($q->id, ['position' => 0]);

        $this->actingAs($this->teacher())->putJson(
            route('teacher.quizzes.toggle-publish', $quiz->id)
        )->assertOk();

        $this->assertTrue($quiz->fresh()->is_published);
    }

    public function test_teacher_cannot_manage_another_teachers_quiz(): void
    {
        $otherTeacherUser = User::factory()->create();
        $otherTeacherUser->assignRole('teacher');
        $otherTeacher = \App\Models\Teacher::factory()->create(['user_id' => $otherTeacherUser->id]);
        $otherClass = \App\Models\ClassRoom::factory()->create(['teacher_id' => $otherTeacher->id]);
        $otherQuiz = $otherClass->quizzes()->create(['title' => 'Theirs']);

        $this->actingAs($this->teacher())
            ->putJson(route('teacher.quizzes.update', $otherQuiz->id), ['title' => 'Hacked'])
            ->assertForbidden();
    }

    // ─── Submissions ───────────────────────────────

    public function test_teacher_can_view_submissions_list(): void
    {
        $quiz = $this->classroom()->quizzes()->create(['title' => 'Quiz']);

        $this->actingAs($this->teacher())
            ->getJson(route('teacher.quizzes.submissions.index', $quiz->id))
            ->assertOk()
            ->assertJsonStructure(['quiz', 'attempts', 'stats']);
    }

    public function test_student_cannot_view_quiz_submissions(): void
    {
        $quiz = $this->classroom()->quizzes()->create(['title' => 'Quiz']);
        $student = User::where('email', 'student@test.com')->first();

        $this->actingAs($student)
            ->getJson(route('teacher.quizzes.submissions.index', $quiz->id))
            ->assertForbidden();
    }
}