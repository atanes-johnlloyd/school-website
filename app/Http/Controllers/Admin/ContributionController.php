<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Contribution;
use App\Models\ContributionAssignment;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Strand;
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
    /* INDEX — see everything                                        */
    /* ═══════════════════════════════════════════════════════════ */
    public function index(Request $request)
    {
        $contributions = Contribution::query()
            ->with([
                'creator:id,name',
                'classroom.subject:id,name',
                'classroom.section:id,name',
                'section.strand:id,code,name',
            ])
            ->withCount([
                'assignments as total_count',
                'assignments as paid_count' => fn ($q) => $q->where('status', 'paid'),
                'assignments as cash_pending_count' => fn ($q) => $q->where('status', 'cash_pending'),
                'assignments as notified_count' => fn ($q) => $q->where('status', 'notified'),
                'assignments as declined_count' => fn ($q) => $q->where('status', 'declined'),
            ])
            ->orderByDesc('id')
            ->get()
            ->map(fn (Contribution $c) => $this->transform($c));

        $stats = [
            'total'     => $contributions->count(),
            'active'    => $contributions->where('is_published', true)->count(),
            'drafts'    => $contributions->where('is_published', false)->count(),
            'collected' => $contributions->sum('collected'),
            'students_paid' => ContributionAssignment::where('status', 'paid')->count(),
            'students_total' => ContributionAssignment::count(),
        ];

        return Inertia::render('Admin/Contributions/Index', [
            'contributions' => $contributions,
            'stats'         => $stats,
        ]);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* CREATE                                                        */
    /* ═══════════════════════════════════════════════════════════ */
    public function create(Request $request)
    {
        return Inertia::render('Admin/Contributions/Form', [
            'advisorySections' => Section::with(['strand:id,code,name', 'schoolYear:id,label'])
                ->withCount(['enrollments as students_count' => fn ($q) => $q->where('status', 'enrolled')])
                ->orderBy('name')
                ->get()
                ->map(fn ($s) => [
                    'id'             => $s->id,
                    'name'           => $s->name,
                    'grade_level'    => $s->grade_level,
                    'strand'         => $s->strand?->name,
                    'strand_code'    => $s->strand?->code,
                    'school_year'    => $s->schoolYear?->label,
                    'students_count' => $s->students_count,
                    'adviser_id'     => $s->adviser_id,
                ]),
            'classrooms' => ClassRoom::with(['subject:id,name', 'section:id,name,grade_level'])
                ->get()
                ->map(fn ($c) => [
                    'id'             => $c->id,
                    'subject'        => $c->subject?->name,
                    'section'        => $c->section?->name,
                    'grade_level'    => $c->section?->grade_level,
                    'students_count' => $c->students()->count(),
                ]),
            'strands'     => Strand::select('id', 'code', 'name')->orderBy('code')->get(),
            'schoolYears' => SchoolYear::select('id', 'label', 'is_active')->orderByDesc('start_date')->get(),
            'contribution' => null,
        ]);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* STORE                                                         */
    /* ═══════════════════════════════════════════════════════════ */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'scope'                     => ['required', 'in:section,class,strand,grade,school'],
            'section_ids'               => ['required_if:scope,section', 'nullable', 'array', 'min:1'],
            'section_ids.*'             => ['integer', 'exists:sections,id'],
            'class_ids'                 => ['required_if:scope,class', 'nullable', 'array', 'min:1'],
            'class_ids.*'               => ['integer', 'exists:classes,id'],
            'strand_id'                 => ['required_if:scope,strand', 'nullable', 'integer', 'exists:strands,id'],
            'grade_level'               => ['required_if:scope,grade', 'nullable', 'in:11,12'],
            'school_year_id'            => ['required', 'integer', 'exists:school_years,id'],
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

        $created = AuditContext::wrap('admin_create_contribution_batch', function () use ($validated, $request) {
            return DB::transaction(function () use ($validated, $request) {
                $out = [];
                $base = [
                    'created_by'                => $request->user()->id,
                    'school_year_id'            => $validated['school_year_id'],
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

                switch ($validated['scope']) {
                    case 'section':
                        foreach (Section::whereIn('id', $validated['section_ids'])->get() as $section) {
                            $c = Contribution::create(array_merge($base, [
                                'scope' => 'section', 'section_id' => $section->id,
                            ]));
                            $this->contributions->assignToStudents($c,
                                Enrollment::where('section_id', $section->id)->where('status', 'enrolled')->pluck('student_id'));
                            $out[] = $c;
                        }
                        break;

                    case 'class':
                        foreach (ClassRoom::whereIn('id', $validated['class_ids'])->get() as $classroom) {
                            $c = Contribution::create(array_merge($base, [
                                'scope' => 'class', 'class_id' => $classroom->id,
                            ]));
                            $this->contributions->assignToStudents($c, $classroom->students()->pluck('students.id'));
                            $out[] = $c;
                        }
                        break;

                    case 'strand':
                        $c = Contribution::create(array_merge($base, [
                            'scope' => 'class',
                            'strand_id' => $validated['strand_id'],
                            'grade_level' => null,
                        ]));
                        $students = \App\Models\Student::whereHas('enrollments.section',
                            fn ($q) => $q->where('strand_id', $validated['strand_id'])
                                ->where('school_year_id', $validated['school_year_id'])
                                ->where('status', 'enrolled'))->pluck('id');
                        $this->contributions->assignToStudents($c, $students);
                        $out[] = $c;
                        break;

                    case 'grade':
                        $c = Contribution::create(array_merge($base, [
                            'scope' => 'class',
                            'grade_level' => $validated['grade_level'],
                            'strand_id' => null,
                        ]));
                        $students = \App\Models\Student::whereHas('enrollments.section',
                            fn ($q) => $q->where('grade_level', $validated['grade_level'])
                                ->where('school_year_id', $validated['school_year_id'])
                                ->where('status', 'enrolled'))->pluck('id');
                        $this->contributions->assignToStudents($c, $students);
                        $out[] = $c;
                        break;

                    case 'school':
                        $c = Contribution::create(array_merge($base, ['scope' => 'class']));
                        $students = Enrollment::where('school_year_id', $validated['school_year_id'])
                            ->where('status', 'enrolled')->pluck('student_id');
                        $this->contributions->assignToStudents($c, $students);
                        $out[] = $c;
                        break;
                }

                return $out;
            });
        });

        $created = collect($created);

        // Auto-notify guardians for published contributions
        if ($created->first()?->is_published) {
            foreach ($created as $c) {
                foreach ($c->assignments()->with('student.user')->get() as $assignment) {
                    try { $this->contributions->notifyGuardian($assignment); }
                    catch (\Throwable $e) { Log::warning('Admin auto-notify failed', ['a_id' => $assignment->id, 'error' => $e->getMessage()]); }
                }
            }
        }

        $count = $created->count();

        return response()->json([
            'message'  => "{$count} contribution" . ($count === 1 ? '' : 's') . ' created.'
                . ($created->first()?->is_published ? ' Guardians notified.' : ''),
            'redirect' => $count === 1
                ? route('admin.contributions.show', $created->first()->id)
                : route('admin.contributions.index'),
            'ids'      => $created->pluck('id'),
        ], 201);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* SHOW                                                          */
    /* ═══════════════════════════════════════════════════════════ */
    public function show(Request $request, Contribution $contribution)
    {
        $contribution->load([
            'classroom.subject:id,name', 'classroom.section:id,name',
            'section.strand:id,code,name', 'strand:id,code,name',
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

        return Inertia::render('Admin/Contributions/Show', [
            'contribution' => $this->transform($contribution),
            'assignments'  => $assignments,
        ]);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* DETAIL (JSON — for modals)                                    */
    /* ═══════════════════════════════════════════════════════════ */
    public function detail(Request $request, Contribution $contribution)
    {
        $contribution->load([
            'classroom.subject:id,name', 'classroom.section:id,name',
            'section.strand:id,code,name', 'strand:id,code,name',
            'creator:id,name',
        ]);

        // Reuse the same assignment shape that show() uses
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

        return response()->json([
            'contribution' => $this->transform($contribution),
            'assignments'  => $assignments,
        ]);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* DESTROY                                                       */
    /* ═══════════════════════════════════════════════════════════ */
    public function destroy(Request $request, Contribution $contribution)
    {
        if ($contribution->assignments()->where('status', 'paid')->exists()) {
            return response()->json(['message' => 'Cannot delete: some students already paid.'], 422);
        }

        AuditContext::wrap('admin_delete_contribution', fn () => $contribution->delete());

        return response()->json(['message' => 'Contribution deleted.']);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* ADMIN ACTIONS                                                 */
    /* ═══════════════════════════════════════════════════════════ */
    public function notifyGuardian(Request $request, ContributionAssignment $assignment)
    {
        try {
            $this->contributions->notifyGuardian($assignment);
            return response()->json(['message' => 'Email sent to guardian.']);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function markCashReceived(Request $request, ContributionAssignment $assignment)
    {
        if (! in_array($assignment->status, ['pending', 'notified', 'cash_pending', 'overdue'], true)) {
            return response()->json(['message' => 'This assignment cannot be marked as paid.'], 422);
        }

        $this->contributions->markCashReceived($assignment, $request->user()->id);

        return response()->json(['message' => 'Marked as paid. Receipt emailed.']);
    }

    /** Override — force-authorize without guardian consent (e.g. parent called office). */
    public function overrideAuthorize(Request $request, ContributionAssignment $assignment)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        if ($assignment->isPaid()) {
            return response()->json(['message' => 'Already paid.'], 422);
        }

        AuditContext::wrap('admin_override_authorize', function () use ($assignment, $validated, $request) {
            $assignment->update([
                'status'         => ContributionAssignment::STATUS_PAID,
                'paid_at'        => now(),
                'decline_reason' => 'Admin override: ' . $validated['reason'],
            ]);
        }, ['assignment_id' => $assignment->id, 'admin_id' => $request->user()->id]);

        return response()->json(['message' => 'Marked as paid (override).']);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* TRANSFORM                                                     */
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
            'creator'       => $c->creator?->name,
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
            'cash_pending_count' => $c->cash_pending_count ?? null,
            'notified_count'=> $c->notified_count ?? null,
            'declined_count'=> $c->declined_count ?? null,
            'collected'     => isset($c->paid_count) && $c->amount
                ? (float) ($c->paid_count * $c->amount)
                : null,
            'created_at'    => $c->created_at?->toIso8601String(),
        ];
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* UPDATE (admin — title/purpose/amount/deadline/toggles)        */
    /* ═══════════════════════════════════════════════════════════ */
    public function update(Request $request, Contribution $contribution)
    {
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

        AuditContext::wrap('admin_update_contribution', function () use ($contribution, $validated) {
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
                'is_published'              => $validated['is_published'] ?? false,
                'published_at'              => ($validated['is_published'] ?? false)
                                                ? ($contribution->published_at ?? now())
                                                : null,
                'requires_guardian_consent' => $validated['requires_guardian_consent'] ?? true,
            ], ['contribution_id' => $contribution->id]);
        });

        return response()->json([
            'message' => 'Contribution updated.',
            'id'      => $contribution->id,
        ]);
    }
}