<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Contribution;
use App\Models\ContributionAssignment;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Services\Payment\ContributionService;
use App\Support\AuditContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ContributionController extends Controller
{
    public function __construct(protected ContributionService $contributions) {}

    /* ═══════════════════════════════════════════════════════════ */
    /* INDEX                                                        */
    /* ═══════════════════════════════════════════════════════════ */
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        // Classes this teacher teaches
        $classIds = ClassRoom::where('teacher_id', $teacher->id)->pluck('id');

        // Sections this teacher is adviser of
        $sectionIds = Section::where('adviser_id', $teacher->id)->pluck('id');

        $contributions = Contribution::query()
            ->where(function ($q) use ($classIds, $sectionIds) {
                $q->whereIn('class_id', $classIds)
                  ->orWhereIn('section_id', $sectionIds);
            })
            ->with([
                'classroom.subject:id,name',
                'classroom.section:id,name',
                'section.strand:id,code,name',
            ])
            ->withCount([
                'assignments as total_count',
                'assignments as paid_count' => fn ($q) => $q->where('status', 'paid'),
                'assignments as authorized_count' => fn ($q) => $q->where('status', 'cash_pending'),
                'assignments as awaiting_count' => fn ($q) => $q->where('status', 'notified'),
                'assignments as pending_count' => fn ($q) => $q->where('status', 'pending'),
            ])
            ->orderByDesc('id')
            ->get()
            ->map(fn (Contribution $c) => $this->transform($c));

        $classrooms = ClassRoom::with(['subject:id,name', 'section:id,name'])
            ->whereIn('id', $classIds)
            ->get()
            ->map(fn ($c) => [
                'id'      => $c->id,
                'subject' => $c->subject?->name,
                'section' => $c->section?->name,
            ]);

        return Inertia::render('Teacher/Contributions/Index', [
            'contributions' => $contributions,
            'classrooms'    => $classrooms,
        ]);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* CREATE                                                       */
    /* ═══════════════════════════════════════════════════════════ */
    public function create(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        // Sections where THIS teacher is the adviser
        $advisorySections = Section::where('adviser_id', $teacher->id)
            ->with(['strand:id,code,name', 'schoolYear:id,label'])
            ->withCount(['enrollments as students_count' => fn ($q) => $q->where('status', 'enrolled')])
            ->get()
            ->map(fn ($s) => [
                'id'             => $s->id,
                'name'           => $s->name,
                'grade_level'    => $s->grade_level,
                'strand'         => $s->strand?->name,
                'strand_code'    => $s->strand?->code,
                'school_year'    => $s->schoolYear?->label,
                'students_count' => $s->students_count,
            ]);

        // Classes the teacher teaches (for class-scoped fallback)
        $classrooms = ClassRoom::with(['subject:id,name', 'section:id,name,grade_level'])
            ->where('teacher_id', $teacher->id)
            ->get()
            ->map(fn ($c) => [
                'id'             => $c->id,
                'subject'        => $c->subject?->name,
                'section'        => $c->section?->name,
                'grade_level'    => $c->section?->grade_level,
                'students_count' => $c->students()->count(),
            ]);

        return Inertia::render('Teacher/Contributions/Form', [
            'advisorySections' => $advisorySections,
            'classrooms'       => $classrooms,
            'contribution'     => null,
        ]);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* STORE                                                        */
    /* ═══════════════════════════════════════════════════════════ */
    public function store(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        $validated = $request->validate([
            'scope'                     => ['required', 'in:section,class'],
            'section_ids'               => ['required_if:scope,section', 'nullable', 'array', 'min:1'],
            'section_ids.*'             => ['integer', 'exists:sections,id'],
            'class_ids'                 => ['required_if:scope,class', 'nullable', 'array', 'min:1'],
            'class_ids.*'               => ['integer', 'exists:classes,id'],
            'title'                     => ['required', 'string', 'max:255'],
            'description'               => ['nullable', 'string', 'max:2000'],
            'purpose'                   => ['nullable', 'string', 'max:200'],
            'amount_type'               => ['required', 'in:fixed,open'],
            'amount'                    => ['required_if:amount_type,fixed', 'nullable', 'numeric', 'min:1', 'max:100000'],
            'min_amount'                => ['nullable', 'numeric', 'min:1', 'max:100000'],
            'target_amount'             => ['nullable', 'numeric', 'min:1', 'max:10000000'],
            'is_required'               => ['boolean'],
            'deadline_at'               => ['nullable', 'date', 'after:today'],
            'is_published'              => ['boolean'],
            'requires_guardian_consent' => ['boolean'],
        ]);

        $activeYearId = SchoolYear::where('is_active', true)->value('id');
        if (! $activeYearId) {
            return response()->json(['message' => 'No active school year.'], 422);
        }

        // ─── Verify ownership ────────────────────────────────────
        if ($validated['scope'] === 'section') {
            $owned = Section::whereIn('id', $validated['section_ids'])
                ->where('adviser_id', $teacher->id)
                ->count();

            if ($owned !== count($validated['section_ids'])) {
                return response()->json([
                    'message' => 'You are not the adviser of one or more selected sections.',
                ], 403);
            }
        } else {
            $owned = ClassRoom::whereIn('id', $validated['class_ids'])
                ->where('teacher_id', $teacher->id)
                ->count();

            if ($owned !== count($validated['class_ids'])) {
                return response()->json([
                    'message' => 'One or more selected classes do not belong to you.',
                ], 403);
            }
        }

        // ─── Create ──────────────────────────────────────────────
        $created = AuditContext::wrap('create_contribution_batch', function () use ($validated, $request, $teacher, $activeYearId) {
            return DB::transaction(function () use ($validated, $request, $teacher, $activeYearId) {
                $out = [];

                $base = [
                    'created_by'                => $request->user()->id,
                    'school_year_id'            => $activeYearId,
                    'title'                     => $validated['title'],
                    'description'               => $validated['description'] ?? null,
                    'purpose'                   => $validated['purpose'] ?? null,
                    'amount_type'               => $validated['amount_type'],
                    'amount'                    => $validated['amount_type'] === 'fixed' ? $validated['amount'] : null,
                    'min_amount'                => $validated['amount_type'] === 'open' ? ($validated['min_amount'] ?? null) : null,
                    'target_amount'             => $validated['target_amount'] ?? null,
                    'is_required'               => $validated['is_required'] ?? false,
                    'deadline_at'               => $validated['deadline_at'] ?? null,
                    'is_published'              => $validated['is_published'] ?? false,
                    'published_at'              => ($validated['is_published'] ?? false) ? now() : null,
                    'requires_guardian_consent' => $validated['requires_guardian_consent'] ?? true,
                ];

                if ($validated['scope'] === 'section') {
                    $sections = Section::whereIn('id', $validated['section_ids'])->get();

                    foreach ($sections as $section) {
                        $contribution = Contribution::create(array_merge($base, [
                            'scope'      => 'section',
                            'section_id' => $section->id,
                            'class_id'   => null,
                        ]));

                        $studentIds = Enrollment::where('section_id', $section->id)
                            ->where('status', 'enrolled')
                            ->pluck('student_id');

                        $this->contributions->assignToStudents($contribution, $studentIds);

                        $out[] = $contribution;
                    }
                } else {
                    $classrooms = ClassRoom::whereIn('id', $validated['class_ids'])->get();

                    foreach ($classrooms as $classroom) {
                        $contribution = Contribution::create(array_merge($base, [
                            'scope'      => 'class',
                            'class_id'   => $classroom->id,
                            'section_id' => null,
                        ]));

                        $studentIds = $classroom->students()->pluck('students.id');

                        $this->contributions->assignToStudents($contribution, $studentIds);

                        $out[] = $contribution;
                    }
                }

                return $out;
            });
        });

        $created = collect($created);

        // ─── Auto-notify guardians for published contributions ──
        if ($created->first()?->is_published) {
            foreach ($created as $contribution) {
                foreach ($contribution->assignments()->with('student.user')->get() as $assignment) {
                    try {
                        $this->contributions->notifyGuardian($assignment);
                    } catch (\Throwable $e) {
                        Log::warning('Guardian auto-notify failed', [
                            'assignment_id' => $assignment->id,
                            'error'         => $e->getMessage(),
                        ]);
                    }
                }
            }
        }

        $count = $created->count();

        return response()->json([
            'message'  => "{$count} contribution" . ($count === 1 ? '' : 's') . ' created successfully.'
                . ($created->first()?->is_published ? ' Guardians have been notified.' : ''),
            'redirect' => $count === 1
                ? route('teacher.contributions.show', $created->first()->id)
                : route('teacher.contributions.index'),
            'ids'      => $created->pluck('id'),
        ], 201);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* SHOW                                                         */
    /* ═══════════════════════════════════════════════════════════ */
    public function show(Request $request, Contribution $contribution)
    {
        $this->authorizeTeacher($request, $contribution);

        $contribution->load([
            'classroom.subject:id,name',
            'classroom.section:id,name',
            'section.strand:id,code,name',
            'creator:id,name',
        ]);

        $assignments = ContributionAssignment::where('contribution_id', $contribution->id)
            ->with(['student.user:id,name', 'student:id,user_id,lrn', 'guardian:id,full_name,email'])
            ->orderBy('id')
            ->get()
            ->map(fn ($a) => [
                'id'             => $a->id,
                'student_name'   => $a->student->user?->name,
                'lrn'            => $a->student->lrn,
                'amount_owed'    => (float) $a->amount_owed,
                'status'         => $a->status,
                'guardian_name'  => $a->guardian?->full_name,
                'guardian_email' => $a->guardian?->email,
                'authorized_at'  => $a->authorized_at?->toIso8601String(),
                'paid_at'        => $a->paid_at?->toIso8601String(),
                'decline_reason' => $a->decline_reason,
                'last_sent_at'   => $a->last_consent_sent_at?->toIso8601String(),
            ]);

        return Inertia::render('Teacher/Contributions/Show', [
            'contribution' => $this->transform($contribution),
            'assignments'  => $assignments,
        ]);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* UPDATE                                                       */
    /* ═══════════════════════════════════════════════════════════ */
    public function update(Request $request, Contribution $contribution)
    {
        $this->authorizeTeacher($request, $contribution);

        $validated = $request->validate([
            'title'                     => ['required', 'string', 'max:255'],
            'description'               => ['nullable', 'string', 'max:2000'],
            'purpose'                   => ['nullable', 'string', 'max:200'],
            'amount_type'               => ['required', 'in:fixed,open'],
            'amount'                    => ['required_if:amount_type,fixed', 'nullable', 'numeric', 'min:1', 'max:100000'],
            'min_amount'                => ['nullable', 'numeric', 'min:1', 'max:100000'],
            'target_amount'             => ['nullable', 'numeric', 'min:1', 'max:10000000'],
            'is_required'               => ['boolean'],
            'deadline_at'               => ['nullable', 'date'],
            'is_published'              => ['boolean'],
            'requires_guardian_consent' => ['boolean'],
        ]);

        $wasPublished = (bool) $contribution->is_published;
        $willPublish  = $validated['is_published'] ?? false;

        AuditContext::wrap('update_contribution', function () use ($contribution, $validated, $wasPublished, $willPublish) {
            $contribution->update([
                'title'                     => $validated['title'],
                'description'               => $validated['description'] ?? null,
                'purpose'                   => $validated['purpose'] ?? null,
                'amount_type'               => $validated['amount_type'],
                'amount'                    => $validated['amount_type'] === 'fixed' ? $validated['amount'] : null,
                'min_amount'                => $validated['amount_type'] === 'open' ? ($validated['min_amount'] ?? null) : null,
                'target_amount'             => $validated['target_amount'] ?? null,
                'is_required'               => $validated['is_required'] ?? false,
                'deadline_at'               => $validated['deadline_at'] ?? null,
                'is_published'              => $willPublish,
                'published_at'              => $willPublish ? ($contribution->published_at ?? now()) : null,
                'requires_guardian_consent' => $validated['requires_guardian_consent'] ?? true,
            ]);
        });

        // If we just published it for the first time, notify guardians
        if (! $wasPublished && $contribution->is_published) {
            foreach ($contribution->assignments()->with('student.user')->get() as $assignment) {
                if (! $assignment->isPaid()) {
                    try {
                        $this->contributions->notifyGuardian($assignment);
                    } catch (\Throwable $e) {
                        Log::warning('Guardian notify on publish failed', [
                            'assignment_id' => $assignment->id,
                            'error'         => $e->getMessage(),
                        ]);
                    }
                }
            }
        }

        return response()->json(['message' => 'Contribution updated.']);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* DESTROY                                                      */
    /* ═══════════════════════════════════════════════════════════ */
    public function destroy(Request $request, Contribution $contribution)
    {
        $this->authorizeTeacher($request, $contribution);

        if ($contribution->assignments()->where('status', 'paid')->exists()) {
            return response()->json([
                'message' => 'Cannot delete: some students already paid.',
            ], 422);
        }

        AuditContext::wrap('delete_contribution', function () use ($contribution) {
            $contribution->delete();
        });

        return response()->json(['message' => 'Contribution deleted.']);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* NOTIFY GUARDIAN (resend)                                     */
    /* ═══════════════════════════════════════════════════════════ */
    public function notifyGuardian(Request $request, ContributionAssignment $assignment)
    {
        $this->authorizeAssignment($request, $assignment);

        try {
            $this->contributions->notifyGuardian($assignment);
            return response()->json(['message' => 'Email sent to guardian.']);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* MARK CASH RECEIVED                                           */
    /* ═══════════════════════════════════════════════════════════ */
    public function markCashReceived(Request $request, ContributionAssignment $assignment)
    {
        $this->authorizeAssignment($request, $assignment);

        if (! $assignment->isCashPending() && ! $assignment->isNotified()) {
            return response()->json([
                'message' => 'Only pending or cash-declared assignments can be marked paid.',
            ], 422);
        }

        $this->contributions->markCashReceived($assignment, $request->user()->id);

        return response()->json(['message' => 'Marked as paid. Receipt emailed.']);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* REJECT CASH                                                  */
    /* ═══════════════════════════════════════════════════════════ */
    public function rejectCash(Request $request, ContributionAssignment $assignment)
    {
        $this->authorizeAssignment($request, $assignment);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $this->contributions->rejectCash($assignment, $validated['reason']);

        return response()->json(['message' => 'Cash rejected. Guardian has been re-notified.']);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* AUTHORIZATION HELPERS                                        */
    /* ═══════════════════════════════════════════════════════════ */
    protected function authorizeTeacher(Request $request, Contribution $contribution): void
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        $owns = false;

        if ($contribution->scope === 'class' && $contribution->class_id) {
            $owns = $contribution->classroom?->teacher_id === $teacher->id;
        } elseif ($contribution->scope === 'section' && $contribution->section_id) {
            $owns = $contribution->section?->adviser_id === $teacher->id;
        } else {
            // Legacy / unset — fall back to class check
            $owns = $contribution->classroom?->teacher_id === $teacher->id;
        }

        abort_unless($owns, 403);
    }

    protected function authorizeAssignment(Request $request, ContributionAssignment $assignment): void
    {
        $assignment->loadMissing('contribution.classroom', 'contribution.section');
        $this->authorizeTeacher($request, $assignment->contribution);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* TRANSFORM                                                    */
    /* ═══════════════════════════════════════════════════════════ */
    protected function transform(Contribution $c): array
    {
        return [
            'id'            => $c->id,
            'scope'         => $c->scope ?? 'class',
            'title'         => $c->title,
            'purpose'       => $c->purpose,
            'description'   => $c->description,
            'display_name'  => $c->display_name,
            'classroom'     => $c->classroom ? [
                'id'      => $c->classroom->id,
                'subject' => $c->classroom->subject?->name,
                'section' => $c->classroom->section?->name,
            ] : null,
            'section'       => $c->section ? [
                'id'          => $c->section->id,
                'name'        => $c->section->name,
                'grade_level' => $c->section->grade_level,
                'strand'      => $c->section->strand?->name,
                'strand_code' => $c->section->strand?->code,
            ] : null,
            'amount_type'   => $c->amount_type,
            'amount'        => $c->amount ? (float) $c->amount : null,
            'min_amount'    => $c->min_amount ? (float) $c->min_amount : null,
            'target_amount' => $c->target_amount ? (float) $c->target_amount : null,
            'is_required'   => (bool) $c->is_required,
            'is_published'  => (bool) $c->is_published,
            'requires_guardian_consent' => (bool) $c->requires_guardian_consent,
            'deadline_at'   => $c->deadline_at?->toIso8601String(),
            'published_at'  => $c->published_at?->toIso8601String(),
            'total_count'   => $c->total_count ?? null,
            'paid_count'    => $c->paid_count ?? null,
            'authorized_count' => $c->authorized_count ?? null,
            'awaiting_count'=> $c->awaiting_count ?? null,
            'pending_count' => $c->pending_count ?? null,
            'collected'     => isset($c->paid_count) && $c->amount
                ? (float) ($c->paid_count * $c->amount)
                : null,
            'created_at'    => $c->created_at?->toIso8601String(),
        ];
    }
}