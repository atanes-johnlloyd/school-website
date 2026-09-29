<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\ApplyRequest;
use App\Models\Applicant;
use App\Models\ApplicantContact;
use App\Models\ApplicantDocument;
use App\Models\SchoolYear;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function __construct(protected ImageUploadService $uploader) {}

    /**
     * POST /site/admission/apply
     * Submit a complete application with optional documents.
     */
    public function store(ApplyRequest $request)
    {
        // Require an open, active school year
        $activeYear = SchoolYear::where('is_active', true)->first();
        if (! $activeYear) {
            return response()->json([
                'message' => 'Enrollment is not currently open. Please check back later.',
            ], 422);
        }

        // Reject duplicate LRN in the same school year
        $duplicate = Applicant::where('lrn', $request->validated('lrn'))
            ->where('school_year_id', $activeYear->id)
            ->exists();

        if ($duplicate) {
            return response()->json([
                'message' => 'An application with this LRN already exists for the current school year.',
                'errors'  => ['lrn' => ['Duplicate LRN for this school year.']],
            ], 422);
        }

        $applicant = DB::transaction(function () use ($request, $activeYear) {
            // Create applicant
            $applicant = Applicant::create([
                'reference_number'   => Applicant::generateReferenceNumber(),
                'school_year_id'     => $activeYear->id,
                'strand_id'          => $request->validated('strand_id'),
                'applicant_type'     => $request->validated('applicant_type'),
                'first_name'         => $request->validated('first_name'),
                'middle_name'        => $request->validated('middle_name'),
                'last_name'          => $request->validated('last_name'),
                'extension_name'     => $request->validated('extension_name'),
                'lrn'                => $request->validated('lrn'),
                'date_of_birth'      => $request->validated('date_of_birth'),
                'sex'                => $request->validated('sex'),
                'religion'           => $request->validated('religion'),
                'contact_number'     => $request->validated('contact_number'),
                'email'              => $request->validated('email'),
                'house_street'       => $request->validated('house_street'),
                'barangay'           => $request->validated('barangay'),
                'municipality'       => $request->validated('municipality'),
                'province'           => $request->validated('province'),
                'zip_code'           => $request->validated('zip_code'),
                'prev_school_name'   => $request->validated('prev_school_name'),
                'prev_school_address'=> $request->validated('prev_school_address'),
                'prev_school_type'   => $request->validated('prev_school_type'),
                'last_school_year'   => $request->validated('last_school_year'),
                'desired_grade_level'=> $request->validated('desired_grade_level'),
                'status'             => 'pending',
                'submitted_at'       => now(),
            ]);

            // Create contacts
            foreach ($request->validated('contacts', []) as $contact) {
                ApplicantContact::create([
                    'applicant_id'  => $applicant->id,
                    'role'          => $contact['role'],
                    'full_name'     => $contact['full_name'],
                    'relationship'  => $contact['relationship'] ?? null,
                    'occupation'    => $contact['occupation'] ?? null,
                    'contact_number'=> $contact['contact_number'] ?? null,
                    'email'         => $contact['email'] ?? null,
                ]);
            }

            // Store documents
            $files = $request->file('documents', []);
            $types = $request->input('document_types', []);

            foreach ($files as $index => $file) {
                $path = $this->uploader->store(
                    $file,
                    "applicant-documents/{$applicant->id}",
                    0,
                    'local'      // private storage — admin only
                );

                ApplicantDocument::create([
                    'applicant_id'  => $applicant->id,
                    'document_type' => $types[$index] ?? 'Other',
                    'file_path'     => $path,
                    'file_name'     => $file->getClientOriginalName(),
                    'file_size'     => $file->getSize(),
                    'mime_type'     => $file->getClientMimeType(),
                    'status'        => 'pending',
                ]);
            }

            return $applicant;
        });

        return response()->json([
            'message'          => 'Application submitted successfully. Keep your reference number.',
            'reference_number' => $applicant->reference_number,
            'applicant'        => [
                'id'               => $applicant->id,
                'reference_number' => $applicant->reference_number,
                'full_name'        => $applicant->full_name,
                'status'           => $applicant->status,
                'submitted_at'     => $applicant->submitted_at?->toIso8601String(),
            ],
        ], 201);
    }

    /**
     * GET /site/admission/status/{reference}
     * Public status check — no login required.
     */
    public function status(Request $request, string $reference)
    {
        $applicant = Applicant::where('reference_number', $reference)
            ->with(['schoolYear:id,label', 'strand:id,code,name'])
            ->first();

        if (! $applicant) {
            return response()->json(['message' => 'Application not found.'], 404);
        }

        return response()->json([
            'applicant' => [
                'reference_number'   => $applicant->reference_number,
                'full_name'          => $applicant->full_name,
                'status'             => $applicant->status,
                'status_label'       => $this->statusLabel($applicant->status),
                'submitted_at'       => $applicant->submitted_at?->toIso8601String(),
                'reviewed_at'        => $applicant->reviewed_at?->toIso8601String(),
                'rejection_reason'   => $applicant->status === 'rejected'
                                        ? $applicant->rejection_reason
                                        : null,
                'school_year'        => $applicant->schoolYear?->label,
                'strand'             => $applicant->strand?->name,
                'desired_grade_level'=> $applicant->desired_grade_level,
            ],
        ]);
    }

    protected function statusLabel(string $status): string
    {
        return match ($status) {
            'pending'            => 'Pending Review',
            'under_review'       => 'Under Review',
            'approved'           => 'Approved — Awaiting Enrollment',
            'rejected'           => 'Rejected',
            'enrolled'           => 'Enrolled',
            'needs_resubmission' => 'Needs Resubmission',
            default              => ucfirst($status),
        };
    }
}