<?php

namespace Tests\Feature\Site;

use App\Models\Announcement;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
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

    // ─── Homepage ────────────────────────────────────

    public function test_home_endpoint_returns_stats_and_tracks(): void
    {
        $response = $this->getJson(route('site.home'));

        $response->assertOk()
                ->assertJsonStructure([
                    'school' => ['name', 'address'],
                    'stats'  => ['students', 'sections', 'subjects'],   // ← removed 'teachers'
                    'tracks',
                    'latestNews',
                ]);
    }

    public function test_root_url_renders_home(): void
    {
        $this->get(route('home'))->assertOk();
    }

    // ─── About ──────────────────────────────────────

    public function test_about_endpoint_returns_school_info(): void
    {
        $this->getJson(route('site.about'))
            ->assertOk()
            ->assertJsonStructure(['school']);   // ← removed 'faculty_count'
    }
    // ─── News ───────────────────────────────────────

    public function test_news_only_returns_school_wide_announcements(): void
    {
        // School-wide, published
        Announcement::create([
            'created_by'    => $this->admin()->id,
            'class_id'      => null,
            'title'         => 'School Wide Notice',
            'body'          => 'Body',
            'is_school_wide' => true,
            'published_at'  => now(),
        ]);

        // Class-scoped, published (should NOT appear)
        $classroom = \App\Models\ClassRoom::first();
        Announcement::create([
            'created_by'    => $this->admin()->id,
            'class_id'      => $classroom->id,
            'title'         => 'Class Notice',
            'body'          => 'Body',
            'is_school_wide' => false,
            'published_at'  => now(),
        ]);

        $response = $this->getJson(route('site.news.index'));

        $titles = collect($response->json('news.data'))->pluck('title')->all();
        $this->assertContains('School Wide Notice', $titles);
        $this->assertNotContains('Class Notice', $titles);
    }

    public function test_draft_school_wide_news_is_hidden(): void
    {
        Announcement::create([
            'created_by'    => $this->admin()->id,
            'class_id'      => null,
            'title'         => 'Draft School News',
            'body'          => 'Body',
            'is_school_wide' => true,
            'published_at'  => null,   // draft
        ]);

        $response = $this->getJson(route('site.news.index'));

        $titles = collect($response->json('news.data'))->pluck('title')->all();
        $this->assertNotContains('Draft School News', $titles);
    }

    public function test_expired_news_is_hidden(): void
    {
        Announcement::create([
            'created_by'    => $this->admin()->id,
            'class_id'      => null,
            'title'         => 'Expired Notice',
            'body'          => 'Body',
            'is_school_wide' => true,
            'published_at'  => now()->subDays(10),
            'expires_at'    => now()->subDay(),
        ]);

        $this->getJson(route('site.news.show', Announcement::where('title', 'Expired Notice')->first()->id))
            ->assertStatus(404);
    }

    // ─── Contact + Admission ────────────────────────

    public function test_contact_page_endpoint(): void
    {
        $this->getJson(route('site.contact'))
            ->assertOk()
            ->assertJsonStructure(['school']);
    }

    public function test_admission_page_endpoint(): void
    {
        $this->getJson(route('site.admission'))
            ->assertOk()
            ->assertJsonStructure(['school', 'tracks', 'requirements']);
    }

    // ─── Admin: School-Wide Announcements ───────────

    public function test_admin_can_create_school_wide_announcement(): void
    {
        $this->actingAs($this->admin())
            ->postJson(route('admin.school-news.store'), [
                'title'        => 'Enrollment Now Open',
                'body'         => 'Details inside.',
                'is_published' => true,
                'is_pinned'    => true,
            ])
            ->assertCreated();

        $this->assertDatabaseHas('announcements', [
            'title'          => 'Enrollment Now Open',
            'is_school_wide' => true,
        ]);
    }

    public function test_teacher_cannot_create_school_wide_announcement(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();

        $this->actingAs($teacher)
            ->postJson(route('admin.school-news.store'), [
                'title' => 'Fake',
                'body'  => 'Fake',
            ])
            ->assertForbidden();
    }

    public function test_admin_can_delete_school_wide_announcement(): void
    {
        $announcement = Announcement::create([
            'created_by'    => $this->admin()->id,
            'title'         => 'Delete Me',
            'body'          => 'Body',
            'is_school_wide' => true,
            'published_at'  => now(),
        ]);

        $this->actingAs($this->admin())
            ->deleteJson(route('admin.school-news.destroy', $announcement->id))
            ->assertOk();

        $this->assertSoftDeleted('announcements', ['id' => $announcement->id]);
    }
}