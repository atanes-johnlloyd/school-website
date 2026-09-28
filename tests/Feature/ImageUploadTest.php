<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\Track;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * @mixin \Illuminate\Foundation\Testing\TestCase
 * @property \Illuminate\Filesystem\FilesystemAdapter $storage
 */
class ImageUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('public');
    }

    protected function teacher(): User
    {
        return User::where('email', 'teacher@test.com')->first();
    }

    protected function admin(): User
    {
        return User::where('email', 'admin@test.com')->first();
    }

    // ─── Avatar ──────────────────────────────────────

    public function test_user_can_upload_avatar(): void
    {
        $user = $this->teacher();
        $file = UploadedFile::fake()->image('avatar.jpg', 300, 300);

        $response = $this->actingAs($user)->post(
            route('profile.avatar.update'),
            ['image' => $file]
        );

        $response->assertOk();
        $user->refresh();
        $this->assertNotNull($user->avatar_path);
        Storage::disk('public')->assertExists($user->avatar_path);
    }

    public function test_avatar_must_be_an_image(): void
    {
        $file = UploadedFile::fake()->create('not-an-image.pdf', 100);

        $response = $this->actingAs($this->teacher())
            ->from(route('profile.edit'))
            ->post(route('profile.avatar.update'), ['image' => $file]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHasErrors('image');
    }

    public function test_avatar_replaces_old_file(): void
    {
        $user = $this->teacher();

        // First upload
        $first = UploadedFile::fake()->image('first.jpg', 200, 200);
        $this->actingAs($user)->post(route('profile.avatar.update'), ['image' => $first]);
        $firstPath = $user->fresh()->avatar_path;
        Storage::disk('public')->assertExists($firstPath);

        // Second upload
        $second = UploadedFile::fake()->image('second.jpg', 200, 200);
        $this->actingAs($user)->post(route('profile.avatar.update'), ['image' => $second]);

        $user->refresh();
        $this->assertNotSame($firstPath, $user->avatar_path);
        Storage::disk('public')->assertMissing($firstPath);
        Storage::disk('public')->assertExists($user->avatar_path);
    }

    public function test_user_can_remove_avatar(): void
    {
        $user = $this->teacher();
        $file = UploadedFile::fake()->image('avatar.jpg', 200, 200);
        $this->actingAs($user)->post(route('profile.avatar.update'), ['image' => $file]);

        $path = $user->fresh()->avatar_path;

        $this->actingAs($user)
            ->delete(route('profile.avatar.destroy'))
            ->assertOk();

        $this->assertNull($user->fresh()->avatar_path);
        Storage::disk('public')->assertMissing($path);
    }

    // ─── Subject Image ───────────────────────────────

    public function test_admin_can_set_subject_image(): void
    {
        $subject = Subject::first();
        $file = UploadedFile::fake()->image('subject.jpg', 400, 400);

        $this->actingAs($this->admin())
            ->post(route('admin.subjects.image.update', $subject->id), ['image' => $file])
            ->assertOk();

        $this->assertNotNull($subject->fresh()->image_path);
    }

    public function test_teacher_cannot_set_subject_image(): void
    {
        $subject = Subject::first();
        $file = UploadedFile::fake()->image('subject.jpg', 400, 400);

        $this->actingAs($this->teacher())
            ->post(route('admin.subjects.image.update', $subject->id), ['image' => $file])
            ->assertForbidden();
    }

    public function test_admin_can_set_subject_icon_and_color(): void
    {
        $subject = Subject::first();

        $this->actingAs($this->admin())
            ->put(route('admin.subjects.meta.update', $subject->id), [
                'icon'  => 'bi-calculator',
                'color' => '#6366f1',
            ])
            ->assertOk();

        $this->assertSame('bi-calculator', $subject->fresh()->icon);
        $this->assertSame('#6366f1', $subject->fresh()->color);
    }

    // ─── Track Image ─────────────────────────────────

    public function test_admin_can_set_track_image(): void
    {
        $track = Track::first();
        $file = UploadedFile::fake()->image('track.jpg', 400, 400);

        $this->actingAs($this->admin())
            ->post(route('admin.tracks.image.update', $track->id), ['image' => $file])
            ->assertOk();

        $this->assertNotNull($track->fresh()->image_path);
    }

    // ─── Announcement Image ──────────────────────────

    public function test_teacher_can_post_announcement_with_image(): void
    {
        $teacher = $this->teacher();
        $classroom = $teacher->teacher->classroom()->first();

        $file = UploadedFile::fake()->image('announcement.jpg', 800, 600);

        $response = $this->actingAs($teacher)->post(
            route('teacher.classes.announcements.store', $classroom->id),
            [
                'title'        => 'With image',
                'body'         => 'Body',
                'is_published' => true,
                'image'        => $file,
            ]
        );

        $response->assertCreated();
        $this->assertNotNull($response->json('announcement.image_path'));
    }
}