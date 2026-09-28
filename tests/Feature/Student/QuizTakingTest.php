<?php

namespace Tests\Feature\Student;

use App\Models\ClassRoom;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizTakingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function student(): User
    {
        return User::where('email', 'student@test.com')->first();
    }

    protected function teacher(): User
    {
        return User::where('email', 'teacher@test.com')->first();
    }

    protected function classroom(): ClassRoom
    {
        // Class the student is enrolled in
        return $this->student()->student->classroom()->first();
    }

    /**
     * Build a published quiz with one MC question (correct = option B).
     */
    protected function makePublishedQuiz(array $overrides = []): array
    {
        $classroom = $this->classroom();
        $subject = $classroom->subject;

        $quiz = $classroom->quizzes()->create(array_merge([
            'title'              => 'Sample Quiz',
            'time_limit_minutes' => 30,
            'passing_score'      => 75,
            'is_published'       => true,
            'show_score_immediately' => true,
            'show_correct_answers'   => true,
            'show_explanations'      => true,
        ], $overrides));

        $q = Question::create([
            'teacher_id'    => $classroom->teacher_id,
            'subject_id'    => $subject->id,
            'type'          => 'multiple_choice',
            'question_text' => 'What is 2+2?',
            'points'        => 10,
            'explanation'   => 'Basic math.',
        ]);

        $optA = $q->options()->create(['option_text' => '3', 'is_correct' => false, 'position' => 0]);
        $optB = $q->options()->create(['option_text' => '4', 'is_correct' => true,  'position' => 1]);
        $optC = $q->options()->create(['option_text' => '5', 'is_correct' => false, 'position' => 2]);

        $quiz->questions()->attach($q->id, ['position' => 0]);

        return [$quiz, $q, ['a' => $optA, 'b' => $optB, 'c' => $optC]];
    }

    // ─── Listing ─────────────────────────────

    public function test_student_can_list_available_quizzes_in_class(): void
    {
        [$quiz] = $this->makePublishedQuiz();

        $response = $this->actingAs($this->student())
            ->getJson(route('student.classroom.quizzes.index', $this->classroom()->id));

        $response->assertOk()
                 ->assertJsonStructure([
                     'classroom', 'quizzes',
                 ]);
    }

    public function test_student_does_not_see_unpublished_quizzes(): void
    {
        [$pubQuiz] = $this->makePublishedQuiz(['title' => 'Published']);

        // Create unpublished one
        $this->classroom()->quizzes()->create([
            'title' => 'Hidden', 'is_published' => false,
        ]);

        $response = $this->actingAs($this->student())
            ->getJson(route('student.classroom.quizzes.index', $this->classroom()->id));

        $titles = collect($response->json('quizzes'))->pluck('title')->all();
        $this->assertContains('Published', $titles);
        $this->assertNotContains('Hidden', $titles);
    }

    public function test_student_cannot_view_quiz_from_other_class(): void
    {
        [$quiz] = $this->makePublishedQuiz();
        $otherClass = ClassRoom::whereDoesntHave('students', function ($q) {
            $q->where('students.id', $this->student()->student->id);
        })->first();

        // Move the quiz
        $quiz->update(['class_id' => $otherClass->id]);

        $this->actingAs($this->student())
            ->getJson(route('student.quizzes.show', $quiz->id))
            ->assertForbidden();
    }

    // ─── Start ───────────────────────────────

    public function test_student_can_start_quiz(): void
    {
        [$quiz] = $this->makePublishedQuiz();

        $response = $this->actingAs($this->student())
            ->postJson(route('student.quizzes.start', $quiz->id));

        $response->assertOk()
                 ->assertJsonStructure([
                     'attempt' => ['id', 'started_at', 'expires_at', 'warning_count', 'max_warnings'],
                     'quiz'    => ['id', 'title'],
                     'questions' => [['id', 'type', 'question_text', 'options', 'my_answer']],
                 ]);

        // Questions must NOT include is_correct
        $firstQuestion = $response->json('questions.0');
        foreach ($firstQuestion['options'] as $opt) {
            $this->assertArrayNotHasKey('is_correct', $opt);
        }
    }

    public function test_second_start_returns_same_attempt(): void
    {
        [$quiz] = $this->makePublishedQuiz();

        $first = $this->actingAs($this->student())->postJson(route('student.quizzes.start', $quiz->id));
        $first->assertOk();

        $second = $this->actingAs($this->student())->postJson(route('student.quizzes.start', $quiz->id));
        $second->assertOk();

        $this->assertSame(
            $first->json('attempt.id'),
            $second->json('attempt.id')
        );

        $this->assertSame(1, QuizAttempt::where('quiz_id', $quiz->id)->count());
    }

    // ─── Autosave ────────────────────────────

    public function test_student_can_autosave_answer(): void
    {
        [$quiz, $question, $opts] = $this->makePublishedQuiz();

        $start = $this->actingAs($this->student())->postJson(route('student.quizzes.start', $quiz->id));
        $attemptId = $start->json('attempt.id');

        $this->actingAs($this->student())->postJson(
            route('student.quiz-attempts.answer', $attemptId),
            ['question_id' => $question->id, 'question_option_id' => $opts['b']->id]
        )->assertOk();

        $answer = QuizAttempt::find($attemptId)->answers()->where('question_id', $question->id)->first();
        $this->assertSame($opts['b']->id, $answer->question_option_id);
    }

    // ─── Warnings ────────────────────────────

    public function test_warnings_increment_and_third_auto_submits(): void
    {
        [$quiz] = $this->makePublishedQuiz();

        $start = $this->actingAs($this->student())->postJson(route('student.quizzes.start', $quiz->id));
        $attemptId = $start->json('attempt.id');

        // Warning 1
        $r1 = $this->actingAs($this->student())
            ->postJson(route('student.quiz-attempts.warning', $attemptId));
        $r1->assertOk();
        $this->assertSame(1, $r1->json('warning_count'));
        $this->assertFalse($r1->json('auto_submitted'));

        // Warning 2
        $r2 = $this->actingAs($this->student())
            ->postJson(route('student.quiz-attempts.warning', $attemptId));
        $r2->assertOk();
        $this->assertSame(2, $r2->json('warning_count'));
        $this->assertFalse($r2->json('auto_submitted'));

        // Warning 3 → auto-submit
        $r3 = $this->actingAs($this->student())
            ->postJson(route('student.quiz-attempts.warning', $attemptId));
        $r3->assertOk();
        $this->assertSame(3, $r3->json('warning_count'));
        $this->assertTrue($r3->json('auto_submitted'));

        $attempt = QuizAttempt::find($attemptId);
        $this->assertNotNull($attempt->submitted_at);
    }

    // ─── Submit + auto-grade ─────────────────

    public function test_submit_auto_grades_correct_answer(): void
    {
        [$quiz, $question, $opts] = $this->makePublishedQuiz();

        $start = $this->actingAs($this->student())->postJson(route('student.quizzes.start', $quiz->id));
        $attemptId = $start->json('attempt.id');

        // Correct answer = B
        $this->actingAs($this->student())->postJson(
            route('student.quiz-attempts.answer', $attemptId),
            ['question_id' => $question->id, 'question_option_id' => $opts['b']->id]
        );

        $this->actingAs($this->student())
            ->postJson(route('student.quiz-attempts.submit', $attemptId))
            ->assertCreated();

        $attempt = QuizAttempt::find($attemptId);
        $this->assertSame('graded', $attempt->status);
        $this->assertEquals(10, $attempt->score);
    }

    public function test_submit_auto_grades_wrong_answer_as_zero(): void
    {
        [$quiz, $question, $opts] = $this->makePublishedQuiz();

        $start = $this->actingAs($this->student())->postJson(route('student.quizzes.start', $quiz->id));
        $attemptId = $start->json('attempt.id');

        // Wrong answer = A
        $this->actingAs($this->student())->postJson(
            route('student.quiz-attempts.answer', $attemptId),
            ['question_id' => $question->id, 'question_option_id' => $opts['a']->id]
        );

        $this->actingAs($this->student())
            ->postJson(route('student.quiz-attempts.submit', $attemptId))
            ->assertCreated();

        $attempt = QuizAttempt::find($attemptId);
        $this->assertEquals(0, $attempt->score);
    }

    public function test_essay_question_leaves_attempt_as_submitted_not_graded(): void
    {
        $classroom = $this->classroom();
        $subject = $classroom->subject;

        $quiz = $classroom->quizzes()->create([
            'title' => 'Essay Quiz', 'is_published' => true,
        ]);

        $q = Question::create([
            'teacher_id'    => $classroom->teacher_id,
            'subject_id'    => $subject->id,
            'type'          => 'essay',
            'question_text' => 'Explain.',
            'points'        => 20,
        ]);
        $quiz->questions()->attach($q->id, ['position' => 0]);

        $start = $this->actingAs($this->student())->postJson(route('student.quizzes.start', $quiz->id));
        $attemptId = $start->json('attempt.id');

        $this->actingAs($this->student())->postJson(
            route('student.quiz-attempts.answer', $attemptId),
            ['question_id' => $q->id, 'answer_text' => 'My essay...']
        );

        $this->actingAs($this->student())
            ->postJson(route('student.quiz-attempts.submit', $attemptId))
            ->assertCreated();

        $this->assertSame('submitted', QuizAttempt::find($attemptId)->status);
    }

    // ─── Result ──────────────────────────────

    public function test_result_shows_score_when_enabled(): void
    {
        [$quiz, $question, $opts] = $this->makePublishedQuiz();

        $start = $this->actingAs($this->student())->postJson(route('student.quizzes.start', $quiz->id));
        $attemptId = $start->json('attempt.id');

        $this->actingAs($this->student())->postJson(
            route('student.quiz-attempts.answer', $attemptId),
            ['question_id' => $question->id, 'question_option_id' => $opts['b']->id]
        );
        $this->actingAs($this->student())->postJson(route('student.quiz-attempts.submit', $attemptId));

        $result = $this->actingAs($this->student())
            ->getJson(route('student.quiz-attempts.result', $attemptId));

        $result->assertOk();
        $this->assertEquals(10, $result->json('attempt.score'));
        $this->assertTrue($result->json('attempt.passed'));
    }

    public function test_result_hides_score_when_disabled(): void
    {
        [$quiz, $question, $opts] = $this->makePublishedQuiz([
            'show_score_immediately' => false,
        ]);

        $start = $this->actingAs($this->student())->postJson(route('student.quizzes.start', $quiz->id));
        $attemptId = $start->json('attempt.id');

        $this->actingAs($this->student())->postJson(
            route('student.quiz-attempts.answer', $attemptId),
            ['question_id' => $question->id, 'question_option_id' => $opts['b']->id]
        );
        $this->actingAs($this->student())->postJson(route('student.quiz-attempts.submit', $attemptId));

        $result = $this->actingAs($this->student())
            ->getJson(route('student.quiz-attempts.result', $attemptId));

        $result->assertOk();
        $this->assertNull($result->json('attempt.score'));
        $this->assertTrue($result->json('attempt.score_hidden'));
    }

    public function test_result_hides_correct_answers_when_disabled(): void
    {
        [$quiz, $question, $opts] = $this->makePublishedQuiz([
            'show_correct_answers' => false,
        ]);

        $start = $this->actingAs($this->student())->postJson(route('student.quizzes.start', $quiz->id));
        $attemptId = $start->json('attempt.id');

        $this->actingAs($this->student())->postJson(
            route('student.quiz-attempts.answer', $attemptId),
            ['question_id' => $question->id, 'question_option_id' => $opts['b']->id]
        );
        $this->actingAs($this->student())->postJson(route('student.quiz-attempts.submit', $attemptId));

        $result = $this->actingAs($this->student())
            ->getJson(route('student.quiz-attempts.result', $attemptId));

        $firstAnswer = $result->json('answers.0');
        $this->assertNull($firstAnswer['is_correct']);
        $this->assertNull($firstAnswer['correct_option_id']);
    }

    // ─── Security ────────────────────────────

    public function test_student_cannot_view_another_students_attempt(): void
    {
        [$quiz] = $this->makePublishedQuiz();

        // Create an attempt for student A
        $start = $this->actingAs($this->student())->postJson(route('student.quizzes.start', $quiz->id));
        $attemptId = $start->json('attempt.id');

        // Student B
        $outsider = User::factory()->create();
        $outsider->assignRole('student');
        \App\Models\Student::factory()->create(['user_id' => $outsider->id]);

        $this->actingAs($outsider)
            ->getJson(route('student.quiz-attempts.active', $attemptId))
            ->assertForbidden();
    }
}