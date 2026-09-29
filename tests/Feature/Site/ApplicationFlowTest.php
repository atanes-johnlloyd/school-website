<?php

namespace Tests\Feature\Site;

use App\Models\Applicant;
use App\Models\ApplicantDocument;
use App\Models\SchoolYear;
use App\Models\Strand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplicationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('local');
    }

    protected function admin(): User
    {
        return User::where('email', 'admin@test.com')->first();
    }

    protected function validPayload(array $overrides = []): array
    {
        return array_merge([
            'applicant_type'       => 'Grade11',
            'desired_grade_level'  => '11',
            'strand_id'            => Strand::first()->id,
            'first_name'           => 'Juan',
            'middle_name'          => 'Santos',
            'last_name'            => 'Dela Cruz',
            'lrn'                  => '123456789099',
            'date_of_birth'        => '2009-05-15',
            'sex'                  => 'Male',
            'religion'             => 'Roman Catholic',
            'contact_number'       => '09171234567',
            'email'                => 'juan@example.com',
            'house_street'         => '123 Rizal St',
            'barangay'             => 'Paliparan',
            'municipality'         => 'Dasmariñas',
            'province'             => 'Cavite',
            'zip_code'             => '4114',
            'prev_school_name'     => 'Dasmariñas National High School',
            'prev_school_address'  => 'Dasmariñas, Cavite',
            'prev_school_type'     => 'Public',
            'last_school_year'     => '2025-2026',
        ], $overrides);
    }

    // ─── Public submission ──────────────────────────

    public function test_public_user_can_submit_application(): void
    {
        $response = $this->postJson(
            route('site.admission.apply'),
            $this->validPayload()
        );

        $response->assertCreated();
        $this->assertDatabaseHas('applicants', [
            'lrn'    => '123456789099',
            'status' => 'pending',
        ]);

        $this->assertStringStartsWith(
            now()->format('Y') . '-',
            $response->json('reference_number')
        );
    }

    public function test_application_requires_valid_lrn(): void
    {
        $this->postJson(route('site.admission.apply'), $this->validPayload(['lrn' => '123']))
            ->assertStatus(422)
            ->assertJsonValidationErrors('lrn');
    }

    public function test_duplicate_lrn_for_same_year_is_rejected(): void
    {
        $this->postJson(route('site.admission.apply'), $this->validPayload())->assertCreated();

        $this->postJson(route('site.admission.apply'), $this->validPayload(['email' => 'other@example.com']))
            ->assertStatus(422);
    }

    public function test_application_with_documents_and_contacts(): void
    {
        $payload = $this->validPayload([
            'contacts' => [
                ['role' => 'father', 'full_name' => 'Pedro Dela Cruz', 'contact_number' => '09171234568'],
            ],
            'documents' => [
                UploadedFile::fake()->create('form138.pdf', 500, 'application/pdf'),
                UploadedFile::fake()->image('id.jpg', 300, 300),
            ],
            'document_types' => ['Form 138', 'PSA Birth Certificate'],
        ]);

        $this->postJson(route('site.admission.apply'), $payload)
            ->assertCreated();

        $applicant = Applicant::where('lrn', '123456789099')->first();
        $this->assertSame(1, $applicant->contacts()->count());
        $this->assertSame(2, $applicant->documents()->count());

        foreach ($applicant->documents as $doc) {
            Storage::disk('local')->assertExists($doc->file_path);
        }
    }

    public function test_public_status_endpoint_returns_applicant_by_reference(): void
    {
        $response = $this->postJson(route('site.admission.apply'), $this->validPayload());
        $reference = $response->json('reference_number');

        $this->getJson(route('site.admission.status', $reference))
            ->assertOk()
            ->assertJson([
                'applicant' => [
                    'reference_number' => $reference,
                    'status'           => 'pending',
                    'status_label'     => 'Pending Review',
                ],
            ]);
    }

    public function test_status_endpoint_404_for_unknown_reference(): void
    {
        $this->getJson(route('site.admission.status', '9999-9999'))
            ->assertStatus(404);
    }

    // ─── Admin workflow ─────────────────────────────

    public function test_admin_can_list_applicants(): void
    {
        $this->postJson(route('site.admission.apply'), $this->validPayload());

        $this->actingAs($this->admin())
            ->getJson(route('admin.applicants.index'))
            ->assertOk()
            ->assertJsonStructure(['applicants', 'filters', 'counts']);
    }

    public function test_teacher_cannot_list_applicants(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();

        $this->actingAs($teacher)
            ->getJson(route('admin.applicants.index'))
            ->assertForbidden();
    }

    public function test_admin_can_move_applicant_under_review(): void
    {
        $this->postJson(route('site.admission.apply'), $this->validPayload());
        $applicant = Applicant::first();

        $this->actingAs($this->admin())
            ->putJson(route('admin.applicants.under-review', $applicant->id))
            ->assertOk();

        $this->assertSame('under_review', $applicant->fresh()->status);
        $this->assertSame($this->admin()->id, $applicant->fresh()->reviewed_by);
    }

    public function test_admin_can_approve_applicant(): void
    {
        $this->postJson(route('site.admission.apply'), $this->validPayload());
        $applicant = Applicant::first();

        $this->actingAs($this->admin())
            ->putJson(route('admin.applicants.approve', $applicant->id))
            ->assertOk();

        $this->assertSame('approved', $applicant->fresh()->status);
    }

    public function test_admin_can_reject_applicant_with_reason(): void
    {
        $this->postJson(route('site.admission.apply'), $this->validPayload());
        $applicant = Applicant::first();

        $this->actingAs($this->admin())
            ->putJson(route('admin.applicants.reject', $applicant->id), [
                'reason' => 'Incomplete requirements.',
            ])
            ->assertOk();

        $this->assertSame('rejected', $applicant->fresh()->status);
        $this->assertSame('Incomplete requirements.', $applicant->fresh()->rejection_reason);
    }

    public function test_reject_requires_reason(): void
    {
        $this->postJson(route('site.admission.apply'), $this->validPayload());
        $applicant = Applicant::first();

        $this->actingAs($this->admin())
            ->putJson(route('admin.applicants.reject', $applicant->id), [])
            ->assertStatus(422)
            ->assertJsonValidationErrors('reason');
    }

    public function test_admin_can_request_resubmission(): void
    {
        $this->postJson(route('site.admission.apply'), $this->validPayload());
        $applicant = Applicant::first();

        $this->actingAs($this->admin())
            ->putJson(route('admin.applicants.request-resubmission', $applicant->id), [
                'reason' => 'Missing Form 137.',
            ])
            ->assertOk();

        $this->assertSame('needs_resubmission', $applicant->fresh()->status);
    }

    public function test_admin_can_verify_a_document(): void
    {
        $payload = $this->validPayload([
            'documents'      => [UploadedFile::fake()->create('form138.pdf', 500, 'application/pdf')],
            'document_types' => ['Form 138'],
        ]);
        $this->postJson(route('site.admission.apply'), $payload);

        $doc = ApplicantDocument::first();

        $this->actingAs($this->admin())
            ->putJson(route('admin.applicant-documents.verify', $doc->id), [
                'status'  => 'verified',
                'remarks' => 'Looks good.',
            ])
            ->assertOk();

        $this->assertSame('verified', $doc->fresh()->status);
    }

    public function test_admin_can_download_applicant_document(): void
    {
        $payload = $this->validPayload([
            'documents'      => [UploadedFile::fake()->create('form138.pdf', 500, 'application/pdf')],
            'document_types' => ['Form 138'],
        ]);
        $this->postJson(route('site.admission.apply'), $payload);

        $doc = ApplicantDocument::first();

        $this->actingAs($this->admin())
            ->get(route('admin.applicant-documents.download', $doc->id))
            ->assertOk();
    }
}