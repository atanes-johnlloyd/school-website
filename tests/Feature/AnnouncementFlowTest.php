<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_teacher_can_create_announcement(): void
    {
        $classroom = ClassRoom::first();
        $teacher = User::find($classroom->teacher->user_id);

        $response = $this->actingAs($teacher)->postJson(
            route('teacher.classes.announcements.store', $classroom->id),
            [
                'title'        => 'No class on Friday',
                'body'         => 'Enjoy the long weekend.',
                'is_published' => true,
                'is_pinned'    => true,
            ]
        );

        $response->assertCreated();

        $this->assertDatabaseHas('announcements', [
            'title'      => 'No class on Friday',
            'class_id'   => $classroom->id,
            'is_pinned'  => true,
        ]);

        $this->assertNotNull(
            Announcement::where('title', 'No class on Friday')->first()->published_at
        );
    }

    public function test_teacher_can_save_draft(): void
    {
        $classroom = ClassRoom::first();
        $teacher = User::find($classroom->teacher->user_id);

        $this->actingAs($teacher)->postJson(
            route('teacher.classes.announcements.store', $classroom->id),
            [
                'title'        => 'Draft Notice',
                'body'         => 'Not ready.',
                'is_published' => false,
            ]
        )->assertCreated();

        $this->assertNull(
            Announcement::where('title', 'Draft Notice')->first()->published_at
        );
    }

    public function test_student_sees_only_published_announcements(): void
    {
        $classroom = ClassRoom::first();
        $studentUser = User::where('id', $classroom->students->first()->user_id)->first();
        $teacherUser = User::find($classroom->teacher->user_id);

        Announcement::create([
            'created_by'   => $teacherUser->id,
            'class_id'     => $classroom->id,
            'title'        => 'Visible',
            'body'         => 'Published',
            'published_at' => now(),
        ]);
        Announcement::create([
            'created_by' => $teacherUser->id,
            'class_id'   => $classroom->id,
            'title'      => 'Hidden',
            'body'       => 'Draft',
        ]);

        $response = $this->actingAs($studentUser)
            ->getJson(route('student.classes.announcements.index', $classroom->id));

        $response->assertOk();
        $response->assertJsonFragment(['title' => 'Visible']);
        $response->assertJsonMissing(['title' => 'Hidden']);
    }

    public function test_student_does_not_see_expired_announcement(): void
    {
        $classroom = ClassRoom::first();
        $studentUser = User::where('id', $classroom->students->first()->user_id)->first();
        $teacherUser = User::find($classroom->teacher->user_id);

        Announcement::create([
            'created_by'   => $teacherUser->id,
            'class_id'     => $classroom->id,
            'title'        => 'Expired Notice',
            'body'         => 'Old',
            'published_at' => now()->subDays(10),
            'expires_at'   => now()->subDay(),
        ]);

        $this->actingAs($studentUser)
            ->getJson(route('student.classes.announcements.index', $classroom->id))
            ->assertOk()
            ->assertJsonMissing(['title' => 'Expired Notice']);
    }

    public function test_student_cannot_view_other_class_announcement(): void
    {
        $classroomA = ClassRoom::first();
        $classroomB = ClassRoom::where('section_id', '!=', $classroomA->section_id)->first();
        $teacherUser = User::find($classroomB->teacher->user_id);

        $announcement = Announcement::create([
            'created_by'   => $teacherUser->id,
            'class_id'     => $classroomB->id,
            'title'        => 'Not Yours',
            'body'         => 'Body',
            'published_at' => now(),
        ]);

        $studentUser = User::where('id', $classroomA->students->first()->user_id)->first();

        $this->actingAs($studentUser)
            ->getJson(route('student.announcements.show', $announcement->id))
            ->assertForbidden();
    }

    public function test_teacher_can_pin_and_unpin(): void
    {
        $classroom = ClassRoom::first();
        $teacher = User::find($classroom->teacher->user_id);

        $announcement = Announcement::create([
            'created_by'   => $teacher->id,
            'class_id'     => $classroom->id,
            'title'        => 'Notice',
            'body'         => 'Body',
            'published_at' => now(),
            'is_pinned'    => false,
        ]);

        // Pin
        $this->actingAs($teacher)
            ->putJson(route('teacher.announcements.toggle-pin', $announcement->id))
            ->assertOk();
        $this->assertTrue($announcement->fresh()->is_pinned);

        // Unpin
        $this->actingAs($teacher)
            ->putJson(route('teacher.announcements.toggle-pin', $announcement->id))
            ->assertOk();
        $this->assertFalse($announcement->fresh()->is_pinned);
    }

    public function test_teacher_can_delete_announcement(): void
    {
        $classroom = ClassRoom::first();
        $teacher = User::find($classroom->teacher->user_id);

        $announcement = Announcement::create([
            'created_by' => $teacher->id,
            'class_id'   => $classroom->id,
            'title'      => 'Delete Me',
            'body'       => 'Body',
        ]);

        $this->actingAs($teacher)
            ->deleteJson(route('teacher.announcements.destroy', $announcement->id))
            ->assertOk();

        $this->assertSoftDeleted('announcements', ['id' => $announcement->id]);
    }

    public function test_student_feed_returns_cross_class_announcements(): void
    {
        $studentUser = User::where('email', 'student@test.com')->first();
        $student = $studentUser->student;

        // Get all classes the student is in
        $classroomIds = $student->classroom()->pluck('classes.id');
        $this->assertGreaterThan(0, $classroomIds->count());

        // Create one published announcement in each
        foreach ($classroomIds as $classroomId) {
            $classroom = ClassRoom::find($classroomId);
            Announcement::create([
                'created_by'   => $classroom->teacher->user_id,
                'class_id'     => $classroomId,
                'title'        => "Notice for class {$classroomId}",
                'body'         => 'Body',
                'published_at' => now(),
            ]);
        }

        $response = $this->actingAs($studentUser)
            ->getJson(route('student.announcements.feed') . '?limit=20');

        $response->assertOk();
        $this->assertCount(
            $classroomIds->count(),
            $response->json('announcements')
        );
    }
}