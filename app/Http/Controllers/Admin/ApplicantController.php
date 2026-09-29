<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectApplicantRequest;
use App\Http\Requests\Admin\ReviewApplicantRequest;
use App\Models\Applicant;
use App\Models\ApplicantDocument;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApplicantController extends Controller
{
    /**
     * List applications with filters.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'status'         => ['nullable', 'in:pending,under_review,approved,rejected,enrolled,needs_resubmission'],
            'school_year_id' => ['nullable', 'integer', 'exists:school_years,id'],
            'strand_id'      => ['nullable', 'integer', 'exists:strands,id'],
            'search'         => ['nullable', 'string', 'max:100'],
            'per_page'       => ['nullable', 'integer', 'min:10', 'max:100'],
        ]);

        $query = Applicant::query()
            ->with(['schoolYear:id,label', 'strand:id,code,name'])
            ->latest('submitted_at');

        if (! empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }
        if (! empty($validated['school_year_id'])) {
            $query->where('school_year_id', $validated['school_year_id']);
        }
        if (! empty($validated['strand_id'])) {
            $query->where('strand_id', $validated['strand_id']);
        }
        if (! empty($validated['search'])) {
            $s = $validated['search'];
            $query->where(function ($q) use ($s) {
                $q->where('reference_number', 'like', "%{$s}%")
                  ->orWhere('lrn', 'like', "%{$s}%")
                  ->orWhere('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $applicants = $query->paginate($validated['per_page'] ?? 20);

        $applicants->getCollection()->transform(fn (Applicant $a) => [
            'id'                 => $a->id,
            'reference_number'   => $a->reference_number,
            'full_name'          => $a->full_name,
            'lrn'                => $a->lrn,
            'email'              => $a->email,
            'applicant_type'     => $a->applicant_type,
            'desired_grade_level'=> $a->desired_grade_level,
            'strand'             => $a->strand?->name,
            'school_year'        => $a->schoolYear?->label,
            'status'             => $a->status,
            'submitted_at'       => $a->submitted_at?->toIso8601String(),
        ]);

        return response()->json([
            'applicants' => $applicants,
            'filters'    => [
                'status'         => $validated['status'] ?? null,
                'school_year_id' => $validated['school_year_id'] ?? null,
                'strand_id'      => $validated['strand_id'] ?? null,
                'search'         => $validated['search'] ?? null,
            ],
            'counts' => [
                'pending'            => Applicant::pending()->count(),
                'under_review'       => Applicant::underReview()->count(),
                'approved'           => Applicant::approved()->count(),
                'enrolled'           => Applicant::enrolled()->count(),
                'needs_resubmission' => Applicant::where('status', 'needs_resubmission')->count(),
            ],
        ]);
    }

    /**
     * Full applicant detail.
     */
    public function show(Request $request, Applicant $applicant)
    {
        $applicant->load([
            'schoolYear:id,label',
            'strand:id,code,name',
            'reviewer:id,name',
            'contacts',
            'documents.verifier:id,name',
        ]);

        return response()->json([
            'applicant' => [
                'id'                 => $applicant->id,
                'reference_number'   => $applicant->reference_number,
                'applicant_type'     => $applicant->applicant_type,
                'first_name'         => $applicant->first_name,
                'middle_name'        => $applicant->middle_name,
                'last_name'          => $applicant->last_name,
                'extension_name'     => $applicant->extension_name,
                'full_name'          => $applicant->full_name,
                'lrn'                => $applicant->lrn,
                'date_of_birth'      => $applicant->date_of_birth?->toDateString(),
                'sex'                => $applicant->sex,
                'religion'           => $applicant->religion,
                'contact_number'     => $applicant->contact_number,
                'email'              => $applicant->email,
                'house_street'       => $applicant->house_street,
                'barangay'           => $applicant->barangay,
                'municipality'       => $applicant->municipality,
                'province'           => $applicant->province,
                'zip_code'           => $applicant->zip_code,
                'prev_school_name'   => $applicant->prev_school_name,
                'prev_school_address'=> $applicant->prev_school_address,
                'prev_school_type'   => $applicant->prev_school_type,
                'last_school_year'   => $applicant->last_school_year,
                'desired_grade_level'=> $applicant->desired_grade_level,
                'strand'             => $applicant->strand?->name,
                'school_year'        => $applicant->schoolYear?->label,
                'status'             => $applicant->status,
                'rejection_reason'   => $applicant->rejection_reason,
                'reviewer'           => $applicant->reviewer?->name,
                'reviewed_at'        => $applicant->reviewed_at?->toIso8601String(),
                'submitted_at'       => $applicant->submitted_at?->toIso8601String(),
                'converted_student_id' => $applicant->converted_student_id,
            ],
            'contacts' => $applicant->contacts->map(fn ($c) => [
                'id'             => $c->id,
                'role'           => $c->role,
                'full_name'      => $c->full_name,
                'relationship'   => $c->relationship,
                'occupation'     => $c->occupation,
                'contact_number' => $c->contact_number,
                'email'          => $c->email,
            ]),
            'documents' => $applicant->documents->map(fn ($d) => [
                'id'            => $d->id,
                'document_type' => $d->document_type,
                'file_name'     => $d->file_name,
                'file_size'     => $d->file_size,
                'mime_type'     => $d->mime_type,
                'status'        => $d->status,
                'remarks'       => $d->remarks,
                'verifier'      => $d->verifier?->name,
                'verified_at'   => $d->verified_at?->toIso8601String(),
                'download_url'  => $d->download_url,
            ]),
        ]);
    }

    /**
     * Move pending → under_review.
     */
    public function markUnderReview(ReviewApplicantRequest $request, Applicant $applicant)
    {
        if ($applicant->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending applications can be marked under review.',
            ], 422);
        }

        $applicant->update([
            'status'      => 'under_review',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return response()->json(['message' => 'Application marked as under review.', 'applicant' => $applicant]);
    }

    /**
     * Approve application.
     */
    public function approve(ReviewApplicantRequest $request, Applicant $applicant)
    {
        if (! in_array($applicant->status, ['pending', 'under_review'], true)) {
            return response()->json([
                'message' => 'Only pending or under-review applications can be approved.',
            ], 422);
        }

        $applicant->update([
            'status'           => 'approved',
            'rejection_reason' => null,
            'reviewed_by'      => $request->user()->id,
            'reviewed_at'      => now(),
        ]);

        return response()->json(['message' => 'Application approved.', 'applicant' => $applicant]);
    }

    /**
     * Reject application with reason.
     */
    public function reject(RejectApplicantRequest $request, Applicant $applicant)
    {
        if (in_array($applicant->status, ['enrolled'], true)) {
            return response()->json(['message' => 'Cannot reject an enrolled applicant.'], 422);
        }

        $applicant->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->validated('reason'),
            'reviewed_by'      => $request->user()->id,
            'reviewed_at'      => now(),
        ]);

        return response()->json(['message' => 'Application rejected.', 'applicant' => $applicant]);
    }

    /**
     * Request resubmission.
     */
    public function requestResubmission(RejectApplicantRequest $request, Applicant $applicant)
    {
        if ($applicant->status === 'enrolled') {
            return response()->json(['message' => 'Cannot request resubmission for an enrolled applicant.'], 422);
        }

        $applicant->update([
            'status'           => 'needs_resubmission',
            'rejection_reason' => $request->validated('reason'),
            'reviewed_by'      => $request->user()->id,
            'reviewed_at'      => now(),
        ]);

        return response()->json(['message' => 'Resubmission requested.', 'applicant' => $applicant]);
    }

    /**
     * Download an applicant's uploaded document.
     * Access is admin-only; file is stored outside the public disk.
     */
    public function downloadDocument(Request $request, ApplicantDocument $document)
    {
        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        return Storage::disk('local')->download($document->file_path, $document->file_name);
    }

    /**
     * Verify / reject a document.
     */
    public function verifyDocument(Request $request, ApplicantDocument $document)
    {
        $validated = $request->validate([
            'status'  => ['required', 'in:received,verified,incomplete,rejected'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $document->update([
            'status'      => $validated['status'],
            'remarks'     => $validated['remarks'] ?? null,
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
        ]);

        return response()->json([
            'message'  => 'Document status updated.',
            'document' => $document->fresh(),
        ]);
    }
}