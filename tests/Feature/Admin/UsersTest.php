<?php

namespace Tests\Feature\Admin;

use App\Mail\TemplateMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UsersTest extends TestCase
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

    // ─── Listing ─────────────────────────────────────

    public function test_admin_can_list_admin_users(): void
    {
        $this->actingAs($this->admin())
            ->getJson(route('admin.users.index'))
            ->assertOk()
            ->assertJsonStructure([
                'users',
                'stats'     => ['total', 'active', 'disabled'],
                'positions',
            ]);
    }

    public function test_teacher_cannot_list_admin_users(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();
        $this->actingAs($teacher)
            ->getJson(route('admin.users.index'))
            ->assertForbidden();
    }

    // ─── Creating ────────────────────────────────────

    public function test_admin_can_create_admin_user_and_send_email(): void
    {
        Mail::fake();

        $this->actingAs($this->admin())->postJson(route('admin.users.store'), [
            'name'  => 'New Admin',
            'email' => 'newadmin@test.com',
        ])->assertCreated();

        $created = User::where('email', 'newadmin@test.com')->first();
        $this->assertNotNull($created);
        $this->assertTrue($created->hasRole('admin'));
        $this->assertTrue($created->must_change_password);
        $this->assertNotNull($created->reset_token);
        $this->assertNotNull($created->reset_expiry);

        Mail::assertSent(TemplateMail::class, function (TemplateMail $mail) {
            return $mail->templateKey === 'account-created'
                && isset($mail->placeholders['default_password']);
        });
    }

    public function test_creating_duplicate_email_is_rejected(): void
    {
        $existing = $this->admin();

        $this->actingAs($this->admin())->postJson(route('admin.users.store'), [
            'name'  => 'Dup',
            'email' => $existing->email,
        ])->assertStatus(422)->assertJsonValidationErrors('email');
    }

    // ─── Toggle status ───────────────────────────────

    public function test_disabling_user_sends_email(): void
    {
        Mail::fake();

        $victim = User::factory()->create();
        $victim->assignRole('admin');

        $this->actingAs($this->admin())->putJson(
            route('admin.users.toggle-status', $victim->id),
            ['reason' => 'Policy violation.']
        )->assertOk();

        $this->assertSame('disabled', $victim->fresh()->status);
        $this->assertSame('Policy violation.', $victim->fresh()->disabled_reason);

        Mail::assertSent(TemplateMail::class, fn (TemplateMail $mail) =>
            $mail->templateKey === 'account-disabled'
        );
    }

    public function test_reactivating_user_sends_email(): void
    {
        Mail::fake();

        $victim = User::factory()->create(['status' => 'disabled', 'disabled_reason' => 'X']);
        $victim->assignRole('admin');

        $this->actingAs($this->admin())->putJson(
            route('admin.users.toggle-status', $victim->id),
            []
        )->assertOk();

        $this->assertSame('active', $victim->fresh()->status);
        $this->assertNull($victim->fresh()->disabled_reason);

        Mail::assertSent(TemplateMail::class, fn (TemplateMail $mail) =>
            $mail->templateKey === 'account-reactivated'
        );
    }

    public function test_admin_cannot_disable_themselves(): void
    {
        $this->actingAs($this->admin())->putJson(
            route('admin.users.toggle-status', $this->admin()->id),
            ['reason' => 'test']
        )->assertStatus(422);
    }

    public function test_admin_cannot_disable_primary_admin(): void
    {
        $primary = User::find(1);
        $otherAdmin = User::factory()->create();
        $otherAdmin->assignRole('admin');

        $this->actingAs($otherAdmin)->putJson(
            route('admin.users.toggle-status', $primary->id),
            ['reason' => 'test']
        )->assertStatus(422);
    }

    // ─── Update + Show ───────────────────────────────

    public function test_admin_can_update_admin_user(): void
    {
        $victim = User::factory()->create();
        $victim->assignRole('admin');

        $this->actingAs($this->admin())
            ->putJson(route('admin.users.update', $victim->id), ['name' => 'Updated Name'])
            ->assertOk();

        $this->assertSame('Updated Name', $victim->fresh()->name);
    }

    public function test_admin_can_show_admin_user(): void
    {
        $victim = User::factory()->create();
        $victim->assignRole('admin');

        $this->actingAs($this->admin())
            ->getJson(route('admin.users.show', $victim->id))
            ->assertOk()
            ->assertJsonStructure(['user' => ['id', 'name', 'email', 'roles', 'permissions']]);
    }

    // ─── Revoke ──────────────────────────────────────

    public function test_admin_can_revoke_admin_role(): void
    {
        $victim = User::factory()->create();
        $victim->assignRole('admin');

        $this->actingAs($this->admin())
            ->deleteJson(route('admin.users.destroy', $victim->id))
            ->assertOk();

        $this->assertFalse($victim->fresh()->hasRole('admin'));
        $this->assertSame('disabled', $victim->fresh()->status);
    }

    public function test_cannot_revoke_own_admin_access(): void
    {
        $this->actingAs($this->admin())
            ->deleteJson(route('admin.users.destroy', $this->admin()->id))
            ->assertStatus(422);
    }
}