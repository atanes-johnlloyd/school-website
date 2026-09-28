<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingFlowTest extends TestCase
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

    protected function student(): User
    {
        return User::where('email', 'student@test.com')->first();
    }

    public function test_user_can_list_conversations(): void
    {
        $this->actingAs($this->student())
            ->getJson(route('messages.index'))
            ->assertOk()
            ->assertJsonStructure(['conversations']);
    }

    public function test_teacher_recipient_list_only_contains_their_students_and_other_teachers(): void
    {
        $teacher = $this->teacher();

        $response = $this->actingAs($teacher)
            ->getJson(route('messages.create'));

        $response->assertOk();

        $ids = collect($response->json('recipients'))->pluck('id');
        $this->assertNotEmpty($ids);

        // Must not include self
        $this->assertNotContains($teacher->id, $ids->all());

        // Must include the test student (who is in their class)
        $studentUserId = $this->student()->id;
        $this->assertContains($studentUserId, $ids->all());
    }

    public function test_student_can_start_conversation_with_their_teacher(): void
    {
        $student = $this->student();
        $teacher = $this->teacher();

        $response = $this->actingAs($student)->postJson(
            route('messages.store'),
            [
                'recipient_id' => $teacher->id,
                'subject'      => 'Question about assignment',
                'body'         => 'Hello po, may question ako.',
            ]
        );

        $response->assertCreated();

        $conversation = Conversation::first();
        $this->assertNotNull($conversation);
        $this->assertEquals(2, $conversation->participants()->count());
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id'       => $student->id,
        ]);
    }

    public function test_user_cannot_start_conversation_with_unrelated_user(): void
    {
        // Create an unrelated student in a different section
        $outsider = User::factory()->create();
        $outsider->assignRole('student');
        \App\Models\Student::factory()->create(['user_id' => $outsider->id]);

        $this->actingAs($this->student())->postJson(
            route('messages.store'),
            [
                'recipient_id' => $outsider->id,
                'body'         => 'Hi',
            ]
        )->assertForbidden();
    }

    public function test_user_cannot_message_themselves(): void
    {
        $student = $this->student();

        $this->actingAs($student)->postJson(
            route('messages.store'),
            [
                'recipient_id' => $student->id,
                'body'         => 'Note to self',
            ]
        )->assertStatus(422);
    }

    public function test_user_can_view_their_own_conversation(): void
    {
        $student = $this->student();
        $teacher = $this->teacher();

        // Start a conversation first
        $this->actingAs($student)->postJson(route('messages.store'), [
            'recipient_id' => $teacher->id,
            'body'         => 'Hi',
        ]);

        $conv = Conversation::first();

        $response = $this->actingAs($teacher)
            ->getJson(route('messages.show', $conv->id));

        $response->assertOk()
                 ->assertJsonStructure([
                     'conversation' => ['id', 'participant'],
                     'messages'     => [['id', 'body', 'is_mine', 'sent_at']],
                 ]);
    }

    public function test_user_cannot_view_conversation_they_are_not_in(): void
    {
        $studentA = $this->student();
        $teacher  = $this->teacher();

        // Student A starts a conversation with teacher
        $this->actingAs($studentA)->postJson(route('messages.store'), [
            'recipient_id' => $teacher->id,
            'body'         => 'Hi',
        ]);

        $conv = Conversation::first();

        // Create another student who isn't in the conversation
        $outsider = User::factory()->create();
        $outsider->assignRole('student');
        \App\Models\Student::factory()->create(['user_id' => $outsider->id]);

        $this->actingAs($outsider)
            ->getJson(route('messages.show', $conv->id))
            ->assertForbidden();
    }

    public function test_user_can_reply_to_their_conversation(): void
    {
        $student = $this->student();
        $teacher = $this->teacher();

        $this->actingAs($student)->postJson(route('messages.store'), [
            'recipient_id' => $teacher->id,
            'body'         => 'Hello',
        ]);

        $conv = Conversation::first();

        $this->actingAs($teacher)->postJson(
            route('messages.reply', $conv->id),
            ['body' => 'Hi! How can I help?']
        )->assertCreated();

        $this->assertSame(2, $conv->messages()->count());
    }

    public function test_user_cannot_reply_to_conversation_they_are_not_in(): void
    {
        $student = $this->student();
        $teacher = $this->teacher();

        $this->actingAs($student)->postJson(route('messages.store'), [
            'recipient_id' => $teacher->id,
            'body'         => 'Hi',
        ]);

        $conv = Conversation::first();

        $outsider = User::factory()->create();
        $outsider->assignRole('student');

        $this->actingAs($outsider)->postJson(
            route('messages.reply', $conv->id),
            ['body' => 'Sneaking in']
        )->assertForbidden();
    }

    public function test_opening_conversation_marks_it_as_read(): void
    {
        $student = $this->student();
        $teacher = $this->teacher();

        $this->actingAs($student)->postJson(route('messages.store'), [
            'recipient_id' => $teacher->id,
            'body'         => 'Unread test',
        ]);

        $conv = Conversation::first();

        // Teacher opens
        $this->actingAs($teacher)->getJson(route('messages.show', $conv->id))->assertOk();

        // Then list — unread count should be 0
        $list = $this->actingAs($teacher)->getJson(route('messages.index'));
        $list->assertOk();

        $row = collect($list->json('conversations'))->firstWhere('id', $conv->id);
        $this->assertSame(0, $row['unread_count']);
    }

    public function test_message_body_is_required(): void
    {
        $student = $this->student();
        $teacher = $this->teacher();

        $this->actingAs($student)->postJson(route('messages.store'), [
            'recipient_id' => $teacher->id,
            'body'         => '',
        ])->assertStatus(422);
    }
}