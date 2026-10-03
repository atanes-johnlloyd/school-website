<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectApplicantRequest;
use App\Http\Requests\Admin\ReviewApplicantRequest;
use App\Models\Applicant;
use App\Models\ApplicantDocument;
use App\Models\EntranceExam;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Strand;
use App\Models\Student;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Support\AuditContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ApplicantController extends Controller
{
    public function __construct(protected NotificationService $notifications) {}

    /* ═══════════════ INDEX ═══════════════ */
    public function index(Request $request)
    {
        return Inertia::render('Admin/Applicants/Index', [
            'strands'     => Strand::select('id', 'code', 'name')->orderBy('code')->get(),
            'schoolYears' => SchoolYear::select('id', 'label')->orderByDesc('start_date')->get(),
            'sections'    => Section::select('id', 'name', 'grade_level')->orderBy('name')->get(),
        ]);
    }

    /* ═══════════════ LIST ═══════════════ */
    public function list(Request $request)
    {
        $validated = $request->validate([
            'status'         => ['nullable', 'in:pending,under_review,approved,rejected,enrolled,needs_resubmission'],
            'school_year_id' => ['nullable', 'integer', 'exists:school_years,id'],
            'strand_id'      => ['nullable', 'integer', 'exists:strands,id'],
            'grade_level'    => ['nullable', 'in:11,12'],
            'search'         => ['nullable', 'string', 'max:100'],
            'per_page'       => ['nullable', 'integer', 'min:10', 'max:100'],
            'sort_by'        => ['nullable', 'in:full_name,desired_grade_level,strand,lrn,email,status,submitted_at'],
            'sort_dir'       => ['nullable', 'in:asc,desc'],
        ]);

        $sortBy  = $validated['sort_by']  ?? 'submitted_at';
        $sortDir = $validated['sort_dir'] ?? 'desc';

        $query = $this->buildApplicantQuery($validated);

        switch ($sortBy) {
            case 'full_name':
                $query->orderByRaw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) {$sortDir}");
                break;
            case 'strand':
                $query->leftJoin('strands', 'strands.id', '=', 'applicants.strand_id')
                    ->orderBy('strands.name', $sortDir)
                    ->select('applicants.*');
                break;
            case 'submitted_at':
            default:
                $query->orderByRaw("
                    CASE status
                        WHEN 'pending'            THEN 0
                        WHEN 'under_review'       THEN 1
                        WHEN 'needs_resubmission' THEN 2
                        WHEN 'approved'           THEN 3
                        WHEN 'enrolled'           THEN 4
                        WHEN 'rejected'           THEN 5
                        ELSE 6
                    END ASC
                ")->orderBy('submitted_at', $sortDir);
                break;
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
            'strand_id'          => $a->strand_id,
            'school_year'        => $a->schoolYear?->label,
            'school_year_id'     => $a->school_year_id,
            'status'             => $a->status,
            'reviewed_by_id'     => $a->reviewed_by,
            'reviewer_name'      => $a->reviewer?->name,
            'submitted_at'       => $a->submitted_at?->toIso8601String(),
        ]);

        return response()->json([
            'applicants' => $applicants,
            'filters'    => [
                'status'         => $validated['status']         ?? null,
                'school_year_id' => $validated['school_year_id'] ?? null,
                'strand_id'      => $validated['strand_id']      ?? null,
                'grade_level'    => $validated['grade_level']    ?? null,
                'search'         => $validated['search']         ?? null,
            ],
            'sort'   => ['by' => $sortBy, 'dir' => $sortDir],
            'counts' => [
                'pending'            => Applicant::pending()->count(),
                'under_review'       => Applicant::underReview()->count(),
                'approved'           => Applicant::approved()->count(),
                'enrolled'           => Applicant::enrolled()->count(),
                'needs_resubmission' => Applicant::where('status', 'needs_resubmission')->count(),
                'rejected'           => Applicant::where('status', 'rejected')->count(),
            ],
        ]);
    }

    /* ═══════════════ SHOW ═══════════════ */
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
                'id'                   => $applicant->id,
                'reference_number'     => $applicant->reference_number,
                'applicant_type'       => $applicant->applicant_type,
                'first_name'           => $applicant->first_name,
                'middle_name'          => $applicant->middle_name,
                'last_name'            => $applicant->last_name,
                'extension_name'       => $applicant->extension_name,
                'full_name'            => $applicant->full_name,
                'lrn'                  => $applicant->lrn,
                'date_of_birth'        => $applicant->date_of_birth?->toDateString(),
                'sex'                  => $applicant->sex,
                'religion'             => $applicant->religion,
                'contact_number'       => $applicant->contact_number,
                'email'                => $applicant->email,
                'house_street'         => $applicant->house_street,
                'barangay'             => $applicant->barangay,
                'municipality'         => $applicant->municipality,
                'province'             => $applicant->province,
                'zip_code'             => $applicant->zip_code,
                'prev_school_name'     => $applicant->prev_school_name,
                'prev_school_address'  => $applicant->prev_school_address,
                'prev_school_type'     => $applicant->prev_school_type,
                'last_school_year'     => $applicant->last_school_year,
                'desired_grade_level'  => $applicant->desired_grade_level,
                'strand_id'            => $applicant->strand_id,
                'school_year_id'       => $applicant->school_year_id,
                'strand'               => $applicant->strand?->name,
                'school_year'          => $applicant->schoolYear?->label,
                'status'               => $applicant->status,
                'rejection_reason'     => $applicant->rejection_reason,
                'reviewed_by_id'       => $applicant->reviewed_by,
                'reviewer'             => $applicant->reviewer?->name,
                'reviewed_at'          => $applicant->reviewed_at?->toIso8601String(),
                'submitted_at'         => $applicant->submitted_at?->toIso8601String(),
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

    /* ═══════════════ MARK UNDER REVIEW ═══════════════ */
    public function markUnderReview(ReviewApplicantRequest $request, Applicant $applicant)
    {
        if ($applicant->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending applications can be marked under review.',
            ], 422);
        }

        AuditContext::wrap('mark_under_review', function () use ($request, $applicant) {
            $applicant->update([
                'status'      => 'under_review',
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
            ]);
        });

        return response()->json(['message' => 'Application marked as under review.', 'applicant' => $applicant]);
    }

    /* ═══════════════ APPROVE ═══════════════ */
    public function approve(ReviewApplicantRequest $request, Applicant $applicant)
    {
        if (! in_array($applicant->status, ['pending', 'under_review'], true)) {
            return response()->json([
                'message' => 'Only pending or under-review applications can be approved.',
            ], 422);
        }

        $exam = EntranceExam::query()
            ->where('status', '!=', 'Cancelled')
            ->whereDate('exam_date', '>=', now()->addDays(7))
            ->forGrade($applicant->desired_grade_level)
            ->forTrack($applicant->strand?->track_id)
            ->orderBy('exam_date')
            ->first();

        AuditContext::wrap('approve', function () use ($request, $applicant, $exam) {
            DB::transaction(function () use ($request, $applicant, $exam) {
                $applicant->update([
                    'status'           => 'approved',
                    'rejection_reason' => null,
                    'reviewed_by'      => $request->user()->id,
                    'reviewed_at'      => now(),
                ]);

                if ($exam) {
                    \App\Models\EntranceExamResult::firstOrCreate(
                        ['entrance_exam_id' => $exam->id, 'applicant_id' => $applicant->id],
                        ['result' => 'Pending']
                    );
                }
            });
        }, ['assigned_exam_id' => $exam?->id]);

        $this->safeNotify(fn () => $this->sendApprovalEmail($applicant->fresh(), $exam), $applicant, 'approve');

        return response()->json([
            'message'       => 'Application approved.' . ($exam ? ' Assigned to exam.' : ''),
            'applicant'     => $applicant,
            'assigned_exam' => $exam?->only(['id', 'exam_name', 'exam_date', 'exam_time']),
        ]);
    }

    /* ═══════════════ REJECT ═══════════════ */
    public function reject(RejectApplicantRequest $request, Applicant $applicant)
    {
        if ($applicant->status === 'enrolled') {
            return response()->json(['message' => 'Cannot reject an enrolled applicant.'], 422);
        }

        AuditContext::wrap('reject', function () use ($request, $applicant) {
            $applicant->update([
                'status'           => 'rejected',
                'rejection_reason' => $request->validated('reason'),
                'reviewed_by'      => $request->user()->id,
                'reviewed_at'      => now(),
            ]);
        });

        $this->safeNotify(fn () => $this->sendRejectionEmail($applicant->fresh()), $applicant, 'reject');

        return response()->json(['message' => 'Application rejected.', 'applicant' => $applicant]);
    }

    /* ═══════════════ REQUEST RESUBMISSION ═══════════════ */
    public function requestResubmission(RejectApplicantRequest $request, Applicant $applicant)
    {
        if ($applicant->status === 'enrolled') {
            return response()->json(['message' => 'Cannot request resubmission for an enrolled applicant.'], 422);
        }

        AuditContext::wrap('request_resubmission', function () use ($request, $applicant) {
            $applicant->update([
                'status'           => 'needs_resubmission',
                'rejection_reason' => $request->validated('reason'),
                'reviewed_by'      => $request->user()->id,
                'reviewed_at'      => now(),
            ]);
        });

        $this->safeNotify(fn () => $this->sendResubmissionEmail($applicant->fresh()), $applicant, 'resubmission');

        return response()->json(['message' => 'Resubmission requested.', 'applicant' => $applicant]);
    }

    /* ═══════════════ RELEASE ═══════════════ */
    public function release(Request $request, Applicant $applicant)
    {
        if ($applicant->status !== 'under_review') {
            return response()->json(['message' => 'Only under-review applications can be released.'], 422);
        }

        if ((int) $applicant->reviewed_by !== (int) $request->user()->id) {
            return response()->json(['message' => 'You are not the reviewer of this application.'], 403);
        }

        AuditContext::wrap('release', function () use ($applicant) {
            $applicant->update([
                'status'      => 'pending',
                'reviewed_by' => null,
                'reviewed_at' => null,
            ]);
        });

        return response()->json(['message' => 'Application released back to pending.']);
    }

    /* ═══════════════ MANUAL ENROLL ═══════════════ */
    public function enroll(Request $request, Applicant $applicant)
    {
        if ($applicant->status === 'enrolled') {
            return response()->json(['message' => 'Applicant is already enrolled.'], 422);
        }

        $validated = $request->validate([
            'section_id' => ['required', 'integer', 'exists:sections,id'],
        ]);

        $student = AuditContext::wrap('manual_enroll', function () use ($applicant, $validated) {
            return DB::transaction(function () use ($applicant, $validated) {
                $user = User::firstOrCreate(
                    ['email' => $applicant->email],
                    [
                        'name'     => $applicant->full_name,
                        'password' => Hash::make('password123'),
                        'role'     => 'student',
                    ]
                );

                $guardian = $applicant->contacts
                ->sortBy(fn ($c) => match ($c->role) {
                    'guardian' => 0,
                    'father'   => 1,
                    'mother'   => 2,
                    default    => 9,
                })
                ->first(fn ($c) => filled($c->full_name));


                $student = Student::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'lrn'            => $applicant->lrn ?? 'LRN-' . rand(100000, 999999),
                        'first_name'     => $applicant->first_name,
                        'middle_name'    => $applicant->middle_name,
                        'last_name'      => $applicant->last_name,
                        'extension_name' => $applicant->extension_name,
                        'contact_no'     => $applicant->contact_number,
                        'strand_id'      => $applicant->strand_id,
                        'guardian_name'    => $guardian?->full_name,
                        'guardian_contact' => $guardian?->contact_number,
                    ]
                );

                $section = Section::with('classrooms')->findOrFail($validated['section_id']);
                $section->students()->syncWithoutDetaching([
                    $student->id => ['status' => 'active', 'enrolled_at' => now()],
                ]);

                foreach ($section->classrooms as $classroom) {
                    $classroom->students()->syncWithoutDetaching([
                        $student->id => ['status' => 'active', 'enrolled_at' => now()],
                    ]);
                }

                $applicant->update([
                    'status'               => 'enrolled',
                    'converted_student_id' => $student->id,
                ]);

                return $student;
            });
        }, ['section_id' => $validated['section_id']]);

        return response()->json([
            'message'   => 'Applicant successfully enrolled and attached to section.',
            'applicant' => $applicant->fresh(['schoolYear', 'strand']),
            'student'   => $student,
        ]);
    }

    /* ═══════════════ DOCUMENTS ═══════════════ */
    public function downloadDocument(Request $request, ApplicantDocument $document)
    {
        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        return Storage::disk('local')->download($document->file_path, $document->file_name);
    }

    public function verifyDocument(Request $request, ApplicantDocument $document)
    {
        $validated = $request->validate([
            'status'  => ['required', 'in:received,verified,incomplete,rejected'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        AuditContext::wrap('verify_document', function () use ($request, $document, $validated) {
            $document->update([
                'status'      => $validated['status'],
                'remarks'     => $validated['remarks'] ?? null,
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
            ]);
        });

        return response()->json([
            'message'  => 'Document status updated.',
            'document' => $document->fresh(),
        ]);
    }

    /* ═══════════════ STORE ═══════════════ */
    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->applicantRules(),
            $this->applicantMessages()
        );

        $validated['reference_number'] = 'APP-' . now()->format('Y') . '-' . str_pad(
            (string) (Applicant::whereYear('created_at', now()->year)->count() + 1),
            4, '0', STR_PAD_LEFT
        );
        $validated['status']       = 'pending';
        $validated['submitted_at'] = now();

        $applicant = AuditContext::wrap('store', function () use ($request, $validated) {
            return DB::transaction(function () use ($request, $validated) {
                $a = Applicant::create($validated);
                $this->syncContacts($a, $request->input('contacts', []));
                $this->storeUploadedDocuments($a, $request);
                return $a;
            });
        });

        return response()->json([
            'message'   => 'Application created.',
            'applicant' => $applicant->load('strand', 'schoolYear'),
        ], 201);
    }

    /* ═══════════════ UPDATE ═══════════════ */
    public function update(Request $request, Applicant $applicant)
    {
        $validated = $request->validate(
            $this->applicantRules() + [
                'status' => ['nullable', 'in:pending,approved,rejected,needs_resubmission'],
            ],
            $this->applicantMessages()
        );

        AuditContext::wrap('update', function () use ($request, $applicant, $validated) {
            DB::transaction(function () use ($request, $applicant, $validated) {
                $applicant->update($validated);
                $this->syncContacts($applicant, $request->input('contacts', []));
                $this->storeUploadedDocuments($applicant, $request);
            });
        });

        return response()->json([
            'message'   => 'Application updated.',
            'applicant' => $applicant->fresh(['strand', 'schoolYear', 'contacts', 'documents']),
        ]);
    }

    /* ═══════════════ IMPORT ═══════════════ */
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:' . \App\Models\SystemSetting::maxFileUploadKb()],
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');
        if (! $handle) {
            return response()->json(['message' => 'Could not read the uploaded file.'], 422);
        }

        $rawHeader = fgetcsv($handle);
        if (! $rawHeader) {
            fclose($handle);
            return response()->json(['message' => 'The CSV file is empty.'], 422);
        }
        $header = array_map(fn ($h) => Str::slug(trim((string) $h), '_'), $rawHeader);

        $required = [
            'first_name', 'last_name', 'lrn', 'date_of_birth', 'sex',
            'contact_number', 'email', 'prev_school_name', 'prev_school_type',
            'applicant_type', 'desired_grade_level', 'school_year',
        ];
        $missing = array_diff($required, $header);
        if (! empty($missing)) {
            fclose($handle);
            return response()->json([
                'message' => 'Missing required columns: ' . implode(', ', $missing),
            ], 422);
        }

        $schoolYears = [];
        foreach (SchoolYear::all() as $sy) {
            $schoolYears[trim((string) $sy->label)] = $sy->id;
        }
        $strandLookup = [];
        foreach (Strand::all() as $s) {
            if (filled($s->code)) $strandLookup[strtolower(trim($s->code))] = $s->id;
            if (filled($s->name)) $strandLookup[strtolower(trim($s->name))] = $s->id;
        }

        $created = 0;
        $errors  = [];
        $rowNum  = 1;
        $nextRef = (int) (Applicant::whereYear('created_at', now()->year)->max('id') ?? 0) + 1;

        AuditContext::wrap('bulk_import', function () use (
            &$created, &$errors, &$rowNum, &$nextRef,
            $handle, $header, $required, $schoolYears, $strandLookup
        ) {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNum++;
                if (empty(array_filter($row, fn ($v) => $v !== null && $v !== ''))) continue;

                $row  = array_pad($row, count($header), null);
                $row  = array_slice($row, 0, count($header));
                $data = array_combine($header, $row);

                try {
                    $syLabel = trim((string) ($data['school_year'] ?? ''));
                    if (! isset($schoolYears[$syLabel])) {
                        throw new \RuntimeException("School year '{$syLabel}' not found.");
                    }

                    $strandId  = null;
                    $strandRaw = trim((string) ($data['strand'] ?? ''));
                    if ($strandRaw !== '') {
                        $key = strtolower($strandRaw);
                        if (! isset($strandLookup[$key])) {
                            throw new \RuntimeException("Strand '{$strandRaw}' not found.");
                        }
                        $strandId = $strandLookup[$key];
                    }

                    $lrn = trim((string) ($data['lrn'] ?? ''));
                    $dupe = Applicant::where('lrn', $lrn)
                        ->where('school_year_id', $schoolYears[$syLabel])
                        ->exists();
                    if ($dupe) {
                        throw new \RuntimeException("Applicant with LRN {$lrn} already exists for {$syLabel}.");
                    }

                    foreach ($required as $col) {
                        if (empty(trim((string) ($data[$col] ?? '')))) {
                            throw new \RuntimeException("Column '{$col}' is empty.");
                        }
                    }

                    Applicant::create([
                        'reference_number'    => 'APP-' . now()->format('Y') . '-' . str_pad((string) $nextRef, 4, '0', STR_PAD_LEFT),
                        'school_year_id'      => $schoolYears[$syLabel],
                        'strand_id'           => $strandId,
                        'applicant_type'      => trim($data['applicant_type']),
                        'first_name'          => trim($data['first_name']),
                        'middle_name'         => filled($data['middle_name'] ?? null)  ? trim($data['middle_name'])  : null,
                        'last_name'           => trim($data['last_name']),
                        'extension_name'      => filled($data['extension_name'] ?? null) ? trim($data['extension_name']) : null,
                        'lrn'                 => $lrn,
                        'date_of_birth'       => $data['date_of_birth'],
                        'sex'                 => trim($data['sex']),
                        'religion'            => filled($data['religion'] ?? null) ? trim($data['religion']) : null,
                        'contact_number'      => trim($data['contact_number']),
                        'email'               => trim($data['email']),
                        'house_street'        => filled($data['house_street'] ?? null) ? trim($data['house_street']) : null,
                        'barangay'            => filled($data['barangay'] ?? null) ? trim($data['barangay']) : null,
                        'municipality'        => filled($data['municipality'] ?? null) ? trim($data['municipality']) : null,
                        'province'            => filled($data['province'] ?? null) ? trim($data['province']) : null,
                        'zip_code'            => filled($data['zip_code'] ?? null) ? trim($data['zip_code']) : null,
                        'prev_school_name'    => trim($data['prev_school_name']),
                        'prev_school_address' => filled($data['prev_school_address'] ?? null) ? trim($data['prev_school_address']) : null,
                        'prev_school_type'    => trim($data['prev_school_type']),
                        'last_school_year'    => filled($data['last_school_year'] ?? null) ? trim($data['last_school_year']) : null,
                        'desired_grade_level' => trim((string) $data['desired_grade_level']),
                        'status'              => 'pending',
                        'submitted_at'        => now(),
                    ]);

                    $created++;
                    $nextRef++;
                } catch (\Throwable $e) {
                    $errors[] = "Row {$rowNum}: " . $e->getMessage();
                }
            }
        });

        fclose($handle);

        return response()->json([
            'message' => "Imported {$created} applicant(s)."
                . (count($errors) ? ' ' . count($errors) . ' row(s) skipped.' : ''),
            'created' => $created,
            'errors'  => $errors,
        ]);
    }

    /* ═══════════════ EXPORT ═══════════════ */
    public function export(Request $request)
    {
        $validated = $request->validate([
            'status'         => ['nullable', 'in:pending,under_review,approved,rejected,enrolled,needs_resubmission'],
            'school_year_id' => ['nullable', 'integer', 'exists:school_years,id'],
            'strand_id'      => ['nullable', 'integer', 'exists:strands,id'],
            'grade_level'    => ['nullable', 'in:11,12'],
            'search'         => ['nullable', 'string', 'max:100'],
        ]);

        $query = $this->buildApplicantQuery($validated)->orderBy('submitted_at', 'desc');
        $filename = 'applicants-' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'first_name', 'middle_name', 'last_name', 'extension_name',
                'lrn', 'date_of_birth', 'sex', 'religion', 'contact_number', 'email',
                'house_street', 'barangay', 'municipality', 'province', 'zip_code',
                'prev_school_name', 'prev_school_address', 'prev_school_type', 'last_school_year',
                'applicant_type', 'desired_grade_level', 'strand', 'school_year',
                'reference_number', 'status', 'submitted_at',
            ]);

            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $a) {
                    fputcsv($out, [
                        $a->first_name, $a->middle_name, $a->last_name, $a->extension_name,
                        $a->lrn, $a->date_of_birth?->toDateString(), $a->sex, $a->religion,
                        $a->contact_number, $a->email,
                        $a->house_street, $a->barangay, $a->municipality, $a->province, $a->zip_code,
                        $a->prev_school_name, $a->prev_school_address, $a->prev_school_type, $a->last_school_year,
                        $a->applicant_type, $a->desired_grade_level,
                        $a->strand?->code, $a->schoolYear?->label,
                        $a->reference_number, $a->status, $a->submitted_at?->toDateTimeString(),
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /* ═══════════════ RULES / MESSAGES ═══════════════ */
    protected function applicantRules(): array
    {
        return [
            'applicant_type'      => ['required', 'in:Grade11,Grade12,Transferee,Returning'],
            'desired_grade_level' => ['required', 'in:11,12'],
            'strand_id'           => ['required', 'integer', 'exists:strands,id'],
            'school_year_id'      => ['required', 'integer', 'exists:school_years,id'],

            'first_name'     => ['required', 'string', 'max:50'],
            'last_name'      => ['required', 'string', 'max:50'],
            'middle_name'    => ['nullable', 'string', 'max:50'],
            'extension_name' => ['nullable', 'string', 'max:10'],

            'lrn'            => ['required', 'string', 'size:12', 'regex:/^\d{12}$/'],
            'date_of_birth'  => ['required', 'date', 'before:today'],
            'sex'            => ['required', 'in:Male,Female'],
            'contact_number' => ['required', 'string', 'max:15'],
            'email'          => ['required', 'email', 'max:100'],
            'religion'       => ['nullable', 'string', 'max:100'],

            'house_street' => ['nullable', 'string', 'max:150'],
            'barangay'     => ['nullable', 'string', 'max:100'],
            'municipality' => ['nullable', 'string', 'max:100'],
            'province'     => ['nullable', 'string', 'max:100'],
            'zip_code'     => ['nullable', 'string', 'size:4'],

            'prev_school_name'    => ['required', 'string', 'max:150'],
            'prev_school_type'    => ['required', 'in:Public,Private,International'],
            'prev_school_address' => ['nullable', 'string', 'max:200'],
            'last_school_year'    => ['nullable', 'string', 'max:20'],

            'contacts'                  => ['nullable', 'array'],
            'contacts.*.role'           => ['required', 'in:father,mother,guardian,emergency'],
            'contacts.*.full_name'      => ['required', 'string', 'max:100'],
            'contacts.*.relationship'   => ['nullable', 'string', 'max:50'],
            'contacts.*.occupation'     => ['nullable', 'string', 'max:100'],
            'contacts.*.contact_number' => ['nullable', 'string', 'max:15'],
            'contacts.*.email'          => ['nullable', 'email', 'max:100'],

            'documents'         => ['nullable', 'array'],
            'documents.*.type'  => ['nullable', 'string', 'max:100'],
            'documents.*.file'  => ['nullable', 'file', 'max:' . \App\Models\SystemSetting::maxFileUploadKb()],
        ];
    }

    protected function applicantMessages(): array
    {
        return [
            'first_name.required'          => 'First name is required.',
            'last_name.required'           => 'Last name is required.',
            'email.required'               => 'Email address is required.',
            'email.email'                  => 'Please enter a valid email address.',
            'strand_id.required'           => 'Please choose a target strand.',
            'school_year_id.required'      => 'Please choose a school year.',
            'applicant_type.required'      => 'Please choose an applicant type.',
            'desired_grade_level.required' => 'Please choose a desired grade level.',
            'lrn.required'                 => 'LRN is required.',
            'lrn.size'                     => 'LRN must be exactly 12 digits.',
            'lrn.regex'                    => 'LRN must contain digits only.',
            'date_of_birth.required'       => 'Date of birth is required.',
            'date_of_birth.before'         => 'Date of birth must be in the past.',
            'sex.required'                 => 'Sex is required.',
            'contact_number.required'      => 'Contact number is required.',
            'prev_school_name.required'    => 'Previous school name is required.',
            'prev_school_type.required'    => 'Previous school type is required.',
            'zip_code.size'                => 'ZIP code must be 4 digits.',
            'contacts.*.full_name.required'=> 'Contact name is required when filling a contact row.',
            'contacts.*.email.email'       => 'Contact email must be a valid email address.',
            'documents.*.file.max'         => 'Each document must be 10 MB or smaller.',
        ];
    }

    /* ═══════════════ HELPERS ═══════════════ */
    protected function syncContacts(Applicant $applicant, array $contacts): void
    {
        if (empty($contacts)) return;
        $applicant->contacts()->delete();
        foreach ($contacts as $row) {
            if (empty($row['full_name'])) continue;
            $applicant->contacts()->create([
                'role'           => $row['role'],
                'full_name'      => $row['full_name'],
                'relationship'   => $row['relationship'] ?? null,
                'occupation'     => $row['occupation'] ?? null,
                'contact_number' => $row['contact_number'] ?? null,
                'email'          => $row['email'] ?? null,
            ]);
        }
    }

    protected function storeUploadedDocuments(Applicant $applicant, Request $request): void
    {
        if (! $request->hasFile('documents')) return;
        foreach ($request->file('documents') as $entry) {
            $file = is_array($entry) ? ($entry['file'] ?? null) : $entry;
            if (! $file || ! $file->isValid()) continue;
            $type = is_array($entry) && ! empty($entry['type']) ? $entry['type'] : 'Uploaded Document';
            $path = $file->store("applicants/{$applicant->reference_number}", 'local');
            $applicant->documents()->create([
                'document_type' => $type,
                'file_path'     => $path,
                'file_name'     => $file->getClientOriginalName(),
                'file_size'     => $file->getSize(),
                'mime_type'     => $file->getMimeType(),
                'status'        => 'pending',
            ]);
        }
    }

    protected function buildApplicantQuery(array $validated): \Illuminate\Database\Eloquent\Builder
    {
        $query = Applicant::query()
            ->with(['schoolYear:id,label', 'strand:id,code,name', 'reviewer:id,name']);

        if (! empty($validated['status']))         $query->where('status', $validated['status']);
        if (! empty($validated['school_year_id'])) $query->where('school_year_id', $validated['school_year_id']);
        if (! empty($validated['strand_id']))      $query->where('strand_id', $validated['strand_id']);
        if (! empty($validated['grade_level']))    $query->where('desired_grade_level', (string) $validated['grade_level']);

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

        return $query;
    }

    protected function sendApprovalEmail(Applicant $applicant, ?EntranceExam $exam): void
    {
        $activeYear = SchoolYear::where('is_active', true)->first();
        $base = [
            'student_name'   => $applicant->full_name,
            'control_number' => $applicant->reference_number,
            'strand'         => $applicant->strand?->name ?? 'N/A',
            'school_year'    => $activeYear?->label ?? '',
        ];
        if ($exam) {
            $base += [
                'schedule' => $exam->exam_date->format('F d, Y') . ' at ' . $exam->exam_time,
                'room'     => $exam->venue ?: 'TBA',
            ];
            $this->notifications->send($applicant->email, 'Admission Accepted & Exam Scheduled - Salawag Senior High School', 'accepted_with_exam', $base);
        } else {
            $this->notifications->send($applicant->email, 'Admission Accepted - Salawag Senior High School', 'accepted_no_exam', $base);
        }
    }

    protected function sendRejectionEmail(Applicant $applicant): void
    {
        $activeYear = SchoolYear::where('is_active', true)->first();
        $this->notifications->send($applicant->email, 'Application Status - Salawag Senior High School', 'reject', [
            'student_name'   => $applicant->full_name,
            'control_number' => $applicant->reference_number,
            'strand'         => $applicant->strand?->name ?? 'N/A',
            'school_year'    => $activeYear?->label ?? '',
            'reason'         => $applicant->rejection_reason ?? 'Not specified',
        ]);
    }

    protected function sendResubmissionEmail(Applicant $applicant): void
    {
        $this->notifications->send($applicant->email, 'Document Resubmission Required - Salawag Senior High School', 'resubmission_request', [
            'student_name'   => $applicant->full_name,
            'control_number' => $applicant->reference_number,
            'strand'         => $applicant->strand?->name ?? 'N/A',
            'resubmit_link'  => route('site.admission.status', ['reference' => $applicant->reference_number]),
        ]);
    }

    protected function safeNotify(callable $fn, Applicant $applicant, string $kind): void
    {
        try {
            $fn();
        } catch (\Throwable $e) {
            Log::error('Applicant notification failed', [
                'kind'         => $kind,
                'applicant_id' => $applicant->id,
                'error'        => $e->getMessage(),
            ]);
        }
    }
}