<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreAssignmentRequest;
use App\Http\Requests\Teacher\UpdateAssignmentRequest;
use App\Models\Assignment;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssignmentController extends Controller
{
    /* ═══════════════════════════════════════════════════════════ */
    /* INDEX — per-class listing                                    */
    /* ═══════════════════════════════════════════════════════════ */
    public function index(Request $request, ClassRoom $classroom)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        $assignments = $classroom->assignments()
            ->withCount([
                'submissions',
                'submissions as submitted_count' => fn ($q) => $q->whereIn('status', ['submitted', 'late', 'graded']),
                'submissions as pending_count'   => fn ($q) => $q->whereIn('status', ['submitted', 'late']),
                'submissions as graded_count'    => fn ($q) => $q->whereNotNull('graded_at'),
            ])
            ->orderByDesc('due_at')
            ->get()
            ->map(fn (Assignment $a) => [
                'id'                => $a->id,
                'title'             => $a->title,
                'category'          => $a->category,
                'due_at'            => $a->due_at?->toIso8601String(),
                'points'            => $a->points,
                'is_published'      => (bool) $a->is_published,
                'allow_late'        => (bool) $a->allow_late,
                'submissions_count' => (int) $a->submissions_count,
                'submitted_count'   => (int) $a->submitted_count,
                'pending_count'     => (int) $a->pending_count,
                'graded_count'      => (int) $a->graded_count,
                'students_count'    => $classroom->students()->count(),
            ]);

        $payload = [
            'classroom'   => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'section' => $classroom->section?->name,
            ],
            'assignments' => $assignments,
        ];

        if ($request->wantsJson()) {
            return response()->json($payload);
        }

        return redirect()->route('teacher.tasks.index');
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* STORE                                                        */
    /* ═══════════════════════════════════════════════════════════ */
    public function store(StoreAssignmentRequest $request, ClassRoom $classroom)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        $assignment = $classroom->assignments()->create([
            'title'           => $request->validated('title'),
            'category'        => $request->validated('category') ?? 'written_work',
            'instructions'    => $request->validated('instructions'),
            'due_at'          => $request->validated('due_at'),
            'points'          => $request->validated('points'),
            'allow_late'      => $request->boolean('allow_late', true),
            'is_published'    => $request->boolean('is_published', false),
            'class_module_id' => $request->validated('class_module_id'),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message'    => 'Assignment created.',
                'assignment' => $assignment,
            ], 201);
        }

        return redirect()
            ->route('teacher.classes.assignments.index', $classroom->id)
            ->with('success', 'Assignment created.');
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* SHOW — detail with submissions + stats                       */
    /* ═══════════════════════════════════════════════════════════ */
    public function show(Request $request, Assignment $assignment)
    {
        abort_unless($assignment->classroom->isTaughtBy($request->user()), 403);

        $submissions = $assignment->submissions()
            ->with('student.user:id,name')
            ->get()
            ->map(fn ($s) => [
                'id'           => $s->id,
                'student_id'   => $s->student_id,
                'student_name' => $s->student?->user?->name,
                'status'       => $s->status,
                'submitted_at' => $s->submitted_at?->toIso8601String(),
                'grade'        => $s->grade,
                'feedback'     => $s->feedback,
                'graded_at'    => $s->graded_at?->toIso8601String(),
                'text_content' => $s->text_content,
                'file_path'    => $s->file_path,
                'has_file'     => (bool) $s->file_path,
                'download_url' => $s->file_path ? route('teacher.submissions.download', $s->id) : null,
            ]);

        $payload = [
            'assignment'  => [
                'id'           => $assignment->id,
                'title'        => $assignment->title,
                'instructions' => $assignment->instructions,
                'due_at'       => $assignment->due_at?->toIso8601String(),
                'points'       => $assignment->points,
                'allow_late'   => (bool) $assignment->allow_late,
                'is_published' => (bool) $assignment->is_published,
                'classroom_id' => $assignment->class_id,
                'category'     => $assignment->category,
                'subject'      => $assignment->classroom?->subject?->name,
                'section'      => $assignment->classroom?->section?->name,
            ],
            'submissions' => $submissions,
            'stats'       => [
                'total_students' => $assignment->classroom->students()->count(),
                'submitted'      => $assignment->submissions()->whereIn('status', ['submitted', 'late', 'graded'])->count(),
                'graded'         => $assignment->submissions()->whereNotNull('graded_at')->count(),
            ],
        ];

        if ($request->wantsJson()) {
            return response()->json($payload);
        }

        return Inertia::render('Teacher/Assignments/Show', $payload);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* UPDATE                                                       */
    /* ═══════════════════════════════════════════════════════════ */
    public function update(UpdateAssignmentRequest $request, Assignment $assignment)
    {
        abort_unless($assignment->classroom->isTaughtBy($request->user()), 403);

        $assignment->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'message'    => 'Assignment updated.',
                'assignment' => $assignment->fresh(),
            ]);
        }

        return back()->with('success', 'Assignment updated.');
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* DESTROY — with guard against graded submissions              */
    /* ═══════════════════════════════════════════════════════════ */
    public function destroy(Request $request, Assignment $assignment)
    {
        abort_unless($assignment->classroom->isTaughtBy($request->user()), 403);

        // Guard: block if any submission has already been graded.
        if ($assignment->submissions()->whereNotNull('graded_at')->exists()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Cannot delete: some submissions have already been graded. Unpublish the assignment instead.',
                ], 422);
            }
            return back()->with('error', 'Cannot delete: some submissions have already been graded.');
        }

        $assignment->delete();

        // Where to send them back to.
        $fallback = route('teacher.tasks.index');
        $target   = $fallback;

        if ($request->filled('redirect_to')) {
            $candidate = (string) $request->input('redirect_to');

            // Only trust internal paths. Reject absolute URLs, protocol-relative
            // URLs, and anything that isn't a path on this app.
            if (
                str_starts_with($candidate, '/') &&
                ! str_starts_with($candidate, '//') &&
                ! preg_match('#^/[a-z]+:#i', $candidate)
            ) {
                // Strip any query string the user had — we don't want to carry
                // filters back into the destination after a delete.
                $path = parse_url($candidate, PHP_URL_PATH) ?: $candidate;
                $target = url($path);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message'  => 'Assignment deleted.',
                'redirect' => $target,
            ]);
        }

        return redirect()->to($target)->with('success', 'Assignment deleted.');
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* CREATE — UI shell                                            */
    /* ═══════════════════════════════════════════════════════════ */
    public function create(Request $request, ClassRoom $classroom)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        return Inertia::render('Teacher/Assignments/Create', [
            'classroom' => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'section' => $classroom->section?->name,
            ],
        ]);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* EDIT — now includes graded_count so the UI can disable       */
    /*        the delete button when submissions have been graded.  */
    /* ═══════════════════════════════════════════════════════════ */
    public function edit(Request $request, Assignment $assignment)
    {
        abort_unless($assignment->classroom->isTaughtBy($request->user()), 403);

        return Inertia::render('Teacher/Assignments/Edit', [
            'classroom' => [
                'id'      => $assignment->class_id,
                'subject' => $assignment->classroom?->subject?->name,
                'section' => $assignment->classroom?->section?->name,
            ],
            'assignment' => [
                'id'            => $assignment->id,
                'title'         => $assignment->title,
                'instructions'  => $assignment->instructions,
                'due_at'        => $assignment->due_at?->format('Y-m-d\TH:i'),
                'points'        => $assignment->points,
                'allow_late'    => (bool) $assignment->allow_late,
                'is_published'  => (bool) $assignment->is_published,
                'category'      => $assignment->category,
                'graded_count'  => $assignment->submissions()->whereNotNull('graded_at')->count(),
                'submitted_count' => $assignment->submissions()->whereIn('status', ['submitted', 'late', 'graded'])->count(),
                'students_count' => $assignment->classroom ? $assignment->classroom->students()->count() : 0,
            ],
        ]);
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* ALL TASKS — cross-class Hub                                  */
    /* ═══════════════════════════════════════════════════════════ */
    public function allTasks(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        $activeTerm = \App\Models\Term::where('is_active', true)->first();

        $classroomIds = ClassRoom::query()
            ->where('teacher_id', $teacher->id)
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
            ->pluck('id');

        $assignments = \App\Models\Assignment::query()
            ->whereIn('class_id', $classroomIds)
            ->with([
                'classroom:id,subject_id,section_id',
                'classroom.subject:id,code,name',
                'classroom.section:id,name',
            ])
            ->withCount([
                'submissions',
                'submissions as submitted_count' => fn ($q) => $q->whereIn('status', ['submitted', 'late', 'graded']),
                'submissions as pending_count'   => fn ($q) => $q->whereIn('status', ['submitted', 'late']),
                'submissions as graded_count'    => fn ($q) => $q->whereNotNull('graded_at'),
            ])
            ->orderByDesc('due_at')
            ->get()
            ->map(fn ($a) => [
                'id'                => $a->id,
                'title'             => $a->title,
                'category'          => $a->category,
                'due_at'            => $a->due_at?->toIso8601String(),
                'points'            => (float) $a->points,
                'is_published'      => (bool) $a->is_published,
                'allow_late'        => (bool) $a->allow_late,
                'submissions_count' => (int) $a->submissions_count,
                'submitted_count'   => (int) $a->submitted_count,
                'pending_count'     => (int) $a->pending_count,
                'graded_count'      => (int) $a->graded_count,
                'students_count'    => $a->classroom ? $a->classroom->students()->count() : 0,
                'classroom_id'      => $a->class_id,
                'subject'           => $a->classroom?->subject?->name,
                'subject_code'      => $a->classroom?->subject?->code,
                'section'           => $a->classroom?->section?->name,
            ]);

        $classrooms = ClassRoom::query()
            ->whereIn('id', $classroomIds)
            ->with(['subject:id,name,code', 'section:id,name'])
            ->get()
            ->map(fn ($c) => [
                'id'      => $c->id,
                'subject' => $c->subject?->name,
                'code'    => $c->subject?->code,
                'section' => $c->section?->name,
            ]);

        return \Inertia\Inertia::render('Teacher/Assignments/Index', [
            'assignments' => $assignments,
            'classrooms'  => $classrooms,
            'stats'       => [
                'total'             => $assignments->count(),
                'published'         => $assignments->where('is_published', true)->count(),
                'draft'             => $assignments->where('is_published', false)->count(),
                'total_submissions' => $assignments->sum('submitted_count'),   // ← now counts real turn-ins
                'pending_grading'   => $assignments->sum('pending_count'),
            ],
            'active_term' => $activeTerm?->name,
        ]);
    }
}