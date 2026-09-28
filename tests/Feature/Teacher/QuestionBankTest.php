<?php

namespace Tests\Feature\Teacher;

use App\Models\Question;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class QuestionBankTest extends TestCase
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

    public function test_teacher_can_list_own_questions(): void
    {
        $this->actingAs($this->teacher())
            ->getJson(route('teacher.questions.index'))
            ->assertOk()
            ->assertJsonStructure(['questions', 'categories']);
    }

    public function test_teacher_can_create_multiple_choice_question(): void
    {
        $subject = Subject::first();

        $response = $this->actingAs($this->teacher())->postJson(
            route('teacher.questions.store'),
            [
                'subject_id'    => $subject->id,
                'category'      => 'Week 1',
                'type'          => 'multiple_choice',
                'question_text' => 'What is 2+2?',
                'points'        => 1,
                'explanation'   => 'Basic arithmetic.',
                'options'       => [
                    ['option_text' => '3', 'is_correct' => false],
                    ['option_text' => '4', 'is_correct' => true],
                    ['option_text' => '5', 'is_correct' => false],
                ],
            ]
        );

        $response->assertCreated();
        $this->assertDatabaseHas('questions', ['question_text' => 'What is 2+2?']);
        $this->assertDatabaseHas('question_options', ['option_text' => '4', 'is_correct' => true]);
    }

    public function test_teacher_can_create_true_false_question(): void
    {
        $subject = Subject::first();

        $response = $this->actingAs($this->teacher())->postJson(
            route('teacher.questions.store'),
            [
                'subject_id'    => $subject->id,
                'type'          => 'true_false',
                'question_text' => 'The sky is blue.',
                'options'       => [
                    ['option_text' => 'True',  'is_correct' => true],
                    ['option_text' => 'False', 'is_correct' => false],
                ],
            ]
        );

        $response->assertCreated();
    }

    public function test_multiple_choice_requires_exactly_one_correct(): void
    {
        $subject = Subject::first();

        $this->actingAs($this->teacher())->postJson(
            route('teacher.questions.store'),
            [
                'subject_id'    => $subject->id,
                'type'          => 'multiple_choice',
                'question_text' => 'Pick the correct one.',
                'options'       => [
                    ['option_text' => 'A', 'is_correct' => true],
                    ['option_text' => 'B', 'is_correct' => true],   // both correct → fails
                ],
            ]
        )->assertStatus(422);
    }

    public function test_essay_question_has_no_options(): void
    {
        $subject = Subject::first();

        $response = $this->actingAs($this->teacher())->postJson(
            route('teacher.questions.store'),
            [
                'subject_id'    => $subject->id,
                'type'          => 'essay',
                'question_text' => 'Explain photosynthesis.',
                'explanation'   => 'Rubric: mention light, CO2, water.',
                'points'        => 10,
            ]
        );

        $response->assertCreated();
        $this->assertSame(0, Question::latest('id')->first()->options()->count());
    }

    public function test_teacher_can_update_question_and_replace_options(): void
    {
        $subject = Subject::first();

        $this->actingAs($this->teacher())->postJson(
            route('teacher.questions.store'),
            [
                'subject_id'    => $subject->id,
                'type'          => 'multiple_choice',
                'question_text' => 'Original',
                'options'       => [
                    ['option_text' => 'A', 'is_correct' => true],
                    ['option_text' => 'B', 'is_correct' => false],
                ],
            ]
        );

        $question = Question::latest('id')->first();

        $this->actingAs($this->teacher())->putJson(
            route('teacher.questions.update', $question->id),
            [
                'question_text' => 'Updated text',
                'options'       => [
                    ['option_text' => 'Yes', 'is_correct' => true],
                    ['option_text' => 'No',  'is_correct' => false],
                ],
            ]
        )->assertOk();

        $this->assertSame('Updated text', $question->fresh()->question_text);
        $this->assertSame(2, $question->options()->count());
        $this->assertSame('Yes', $question->options()->first()->option_text);
    }

    public function test_teacher_can_delete_unused_question(): void
    {
        $subject = Subject::first();

        $this->actingAs($this->teacher())->postJson(
            route('teacher.questions.store'),
            [
                'subject_id'    => $subject->id,
                'type'          => 'essay',
                'question_text' => 'Delete me.',
            ]
        );

        $question = Question::latest('id')->first();

        $this->actingAs($this->teacher())
            ->deleteJson(route('teacher.questions.destroy', $question->id))
            ->assertOk();

        $this->assertSoftDeleted('questions', ['id' => $question->id]);
    }

    public function test_teacher_can_filter_by_subject_and_category(): void
    {
        $subject = Subject::first();

        foreach (['Week 1', 'Week 1', 'Week 2'] as $cat) {
            $this->actingAs($this->teacher())->postJson(
                route('teacher.questions.store'),
                [
                    'subject_id'    => $subject->id,
                    'category'      => $cat,
                    'type'          => 'essay',
                    'question_text' => "Q for {$cat}",
                ]
            );
        }

        $response = $this->actingAs($this->teacher())
            ->getJson(route('teacher.questions.index', [
                'subject_id' => $subject->id,
                'category'   => 'Week 1',
            ]));

        $response->assertOk();
        $this->assertSame(2, $response->json('questions.total'));
    }

    public function test_teacher_can_import_csv_questions(): void
    {
        $subject = Subject::first();

        $csv = "type,question_text,option_a,option_b,option_c,option_d,correct,explanation\n";
        $csv .= "multiple_choice,What is 2+2?,3,4,5,6,B,Basic arithmetic\n";
        $csv .= "true_false,The sky is blue,True,False,,,A,\n";
        $csv .= "essay,Explain photosynthesis,,,,,,Be specific\n";

        $file = UploadedFile::fake()->createWithContent('questions.csv', $csv);

        $response = $this->actingAs($this->teacher())->post(
            route('teacher.questions.import-csv'),
            [
                'file'       => $file,
                'subject_id' => $subject->id,
                'category'   => 'Week 1',
            ]
        );

        $response->assertCreated();
        $this->assertSame(3, $response->json('created'));
        $this->assertSame(3, Question::where('category', 'Week 1')->count());
    }

    public function test_student_cannot_access_question_bank(): void
    {
        $student = User::where('email', 'student@test.com')->first();

        $this->actingAs($student)
            ->getJson(route('teacher.questions.index'))
            ->assertForbidden();
    }
}