<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\ContactMessage;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Sprint12Test extends TestCase
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

    // ═══════════════════════════════════════════════
    // CONTACT FORM
    // ═══════════════════════════════════════════════

    public function test_public_user_can_submit_contact_message(): void
    {
        $response = $this->postJson(route('contact.store'), [
            'name'    => 'Juan Dela Cruz',
            'email'   => 'juan@example.com',
            'subject' => 'Inquiry',
            'message' => 'Hello, I have a question.',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('contact_messages', [
            'email'   => 'juan@example.com',
            'is_read' => false,
        ]);
    }

    public function test_contact_message_requires_valid_email(): void
    {
        $this->postJson(route('contact.store'), [
            'name'    => 'Juan',
            'email'   => 'not-an-email',
            'message' => 'Hi',
        ])->assertStatus(422);
    }

    public function test_admin_can_list_contact_messages(): void
    {
        ContactMessage::create([
            'name' => 'A', 'email' => 'a@x.com',
            'subject' => 'S', 'message' => 'M',
        ]);

        $response = $this->actingAs($this->admin())
            ->getJson(route('admin.contact-messages.index'));

        $response->assertOk()
                 ->assertJsonStructure(['messages', 'unread_count', 'filters']);
    }

    public function test_opening_message_marks_it_read(): void
    {
        $msg = ContactMessage::create([
            'name' => 'A', 'email' => 'a@x.com',
            'message' => 'M', 'is_read' => false,
        ]);

        $this->actingAs($this->admin())
            ->getJson(route('admin.contact-messages.show', $msg->id))
            ->assertOk();

        $this->assertTrue($msg->fresh()->is_read);
    }

    public function test_admin_can_toggle_read_status(): void
    {
        $msg = ContactMessage::create([
            'name' => 'A', 'email' => 'a@x.com',
            'message' => 'M', 'is_read' => false,
        ]);

        $this->actingAs($this->admin())
            ->putJson(route('admin.contact-messages.toggle-read', $msg->id))
            ->assertOk();

        $this->assertTrue($msg->fresh()->is_read);
    }

    public function test_admin_can_delete_contact_message(): void
    {
        $msg = ContactMessage::create([
            'name' => 'A', 'email' => 'a@x.com', 'message' => 'M',
        ]);

        $this->actingAs($this->admin())
            ->deleteJson(route('admin.contact-messages.destroy', $msg->id))
            ->assertOk();

        $this->assertDatabaseMissing('contact_messages', ['id' => $msg->id]);
    }

    public function test_teacher_cannot_access_contact_inbox(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();

        $this->actingAs($teacher)
            ->getJson(route('admin.contact-messages.index'))
            ->assertForbidden();
    }

    // ═══════════════════════════════════════════════
    // SYSTEM SETTINGS
    // ═══════════════════════════════════════════════

    public function test_admin_can_view_settings(): void
    {
        $response = $this->actingAs($this->admin())
            ->getJson(route('admin.settings.index'));

        $response->assertOk()
                 ->assertJsonStructure(['schema', 'values']);
    }

    public function test_admin_can_update_settings(): void
    {
        $response = $this->actingAs($this->admin())
            ->putJson(route('admin.settings.update'), [
                'settings' => [
                    'school_name'    => 'New School Name',
                    'passing_grade'  => '80',
                ],
            ]);

        $response->assertOk();

        SystemSetting::forget('school_name');
        $this->assertSame('New School Name', SystemSetting::get('school_name'));
        $this->assertSame('80', SystemSetting::get('passing_grade'));
    }

    public function test_unknown_settings_are_ignored(): void
    {
        $this->actingAs($this->admin())
            ->putJson(route('admin.settings.update'), [
                'settings' => [
                    'hacker_key' => 'malicious',
                ],
            ])
            ->assertOk();

        $this->assertDatabaseMissing('system_settings', ['key' => 'hacker_key']);
    }

    public function test_admin_can_reset_group(): void
    {
        SystemSetting::set('school_name', 'Changed', 'school');

        $this->actingAs($this->admin())
            ->postJson(route('admin.settings.reset'), ['group' => 'school'])
            ->assertOk();

        $this->assertNull(SystemSetting::where('key', 'school_name')->first());
    }

    public function test_teacher_cannot_update_settings(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();

        $this->actingAs($teacher)
            ->putJson(route('admin.settings.update'), [
                'settings' => ['school_name' => 'Hacked'],
            ])
            ->assertForbidden();
    }

    // ═══════════════════════════════════════════════
    // AUDIT LOGS
    // ═══════════════════════════════════════════════

    public function test_admin_can_list_audit_logs(): void
    {
        $response = $this->actingAs($this->admin())
            ->getJson(route('admin.audit-logs.index'));

        $response->assertOk()
                 ->assertJsonStructure(['logs', 'users', 'model_types', 'filters']);
    }

    public function test_audit_log_records_a_creation(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();

        // Trigger an auditable event as teacher
        $this->actingAs($teacher)->postJson(
            route('teacher.classes.assignments.store', $teacher->teacher->classroom()->first()->id),
            [
                'title'        => 'Audited Assignment',
                'category'     => 'written_work',
                'due_at'       => now()->addDays(3)->toIso8601String(),
                'points'       => 100,
                'is_published' => true,
            ]
        );

        $this->assertDatabaseHas('audit_logs', [
            'user_id'        => $teacher->id,
            'action'         => 'created',
            'auditable_type' => \App\Models\Assignment::class,
        ]);
    }

    public function test_admin_can_filter_audit_logs_by_action(): void
    {
        // Create a couple of logs manually
        AuditLog::create([
            'user_id' => $this->admin()->id,
            'action'  => 'created',
            'auditable_type' => \App\Models\Assignment::class,
            'auditable_id' => 1,
        ]);
        AuditLog::create([
            'user_id' => $this->admin()->id,
            'action'  => 'deleted',
            'auditable_type' => \App\Models\Assignment::class,
            'auditable_id' => 2,
        ]);

        $response = $this->actingAs($this->admin())
            ->getJson(route('admin.audit-logs.index', ['action' => 'created']));

        $response->assertOk();
        foreach ($response->json('logs.data') as $log) {
            $this->assertSame('created', $log['action']);
        }
    }

    public function test_teacher_cannot_access_audit_logs(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();

        $this->actingAs($teacher)
            ->getJson(route('admin.audit-logs.index'))
            ->assertForbidden();
    }
}