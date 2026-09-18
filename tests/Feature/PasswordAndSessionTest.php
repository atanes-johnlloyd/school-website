<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordAndSessionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_weak_password_is_rejected_on_password_change(): void
    {
        $user = User::where('email', 'teacher@test.com')->first();

        $this->actingAs($user)->putJson(route('password.change.update'), [
            'current_password' => 'password',
            'password'         => 'short',
            'password_confirmation' => 'short',
        ])->assertStatus(422);
    }

    public function test_strong_password_is_accepted(): void
    {
        $user = User::where('email', 'teacher@test.com')->first();

        $this->actingAs($user)->putJson(route('password.change.update'), [
            'current_password'      => 'password',
            'password'              => 'C0mpl3x!P@ssword2026',
            'password_confirmation' => 'C0mpl3x!P@ssword2026',
        ])->assertRedirect();

        $user->refresh();
        $this->assertFalse($user->must_change_password);
        $this->assertTrue(Hash::check('C0mpl3x!P@ssword2026', $user->password));
    }

    public function test_user_with_must_change_password_is_redirected(): void
    {
        $user = User::where('email', 'teacher@test.com')->first();
        $user->update(['must_change_password' => true]);

        $this->actingAs($user)
            ->get(route('teacher.classes.index'))
            ->assertRedirect(route('password.change'));
    }

    public function test_user_without_must_change_password_can_access(): void
    {
        $user = User::where('email', 'teacher@test.com')->first();
        $user->update(['must_change_password' => false]);

        $this->actingAs($user)
            ->getJson(route('teacher.classes.index'))
            ->assertOk();
    }
}