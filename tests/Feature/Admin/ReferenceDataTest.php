<?php

namespace Tests\Feature\Admin;

use App\Models\SchoolYear;
use App\Models\Strand;
use App\Models\Subject;
use App\Models\Term;
use App\Models\Track;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReferenceDataTest extends TestCase
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

    public function test_admin_can_create_school_year(): void
    {
        $response = $this->actingAs($this->admin())->postJson(
            route('admin.school-years.store'),
            [
                'label'      => '2027-2028',
                'start_date' => '2027-08-01',
                'end_date'   => '2028-04-15',
                'is_active'  => false,
            ]
        );

        $response->assertCreated();
        $this->assertDatabaseHas('school_years', ['label' => '2027-2028']);
    }

    public function test_teacher_cannot_create_school_year(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();

        $this->actingAs($teacher)
            ->postJson(route('admin.school-years.store'), [
                'label' => '2028-2029', 'start_date' => '2028-08-01',
                'end_date' => '2029-04-15', 'is_active' => false,
            ])
            ->assertForbidden();
    }

    public function test_activating_a_school_year_deactivates_others(): void
    {
        $oldYear = SchoolYear::where('is_active', true)->first();

        $newYear = SchoolYear::create([
            'label'      => '2027-2028',
            'start_date' => '2027-08-01',
            'end_date'   => '2028-04-15',
            'is_active'  => false,
        ]);

        $this->actingAs($this->admin())
            ->putJson(route('admin.school-years.activate', $newYear->id))
            ->assertOk();

        $this->assertFalse($oldYear->fresh()->is_active);
        $this->assertTrue($newYear->fresh()->is_active);
    }

    public function test_cannot_delete_school_year_with_terms(): void
    {
        $year = SchoolYear::first();
        $this->assertGreaterThan(0, $year->terms()->count());

        $this->actingAs($this->admin())
            ->deleteJson(route('admin.school-years.destroy', $year->id))
            ->assertStatus(422);
    }

    public function test_admin_can_create_subject(): void
    {
        $strand = Strand::where('code', 'STEM')->first();

        $response = $this->actingAs($this->admin())->postJson(
            route('admin.subjects.store'),
            [
                'code'        => 'TEST-SUBJ',
                'name'        => 'Test Subject',
                'strand_id'   => $strand->id,
                'grade_level' => '11',
                'is_core'     => false,
                'hours'       => 80,
                'is_active'   => true,
            ]
        );

        $response->assertCreated();
        $this->assertDatabaseHas('subjects', ['code' => 'TEST-SUBJ']);
    }

    public function test_admin_can_list_strands_filtered_by_track(): void
    {
        $track = Track::where('code', 'ACAD')->first();

        $response = $this->actingAs($this->admin())
            ->getJson(route('admin.strands.index', ['track_id' => $track->id]));

        $response->assertOk();
        foreach ($response->json('strands') as $strand) {
            $this->assertSame($track->id, $strand['track_id']);
        }
    }
}