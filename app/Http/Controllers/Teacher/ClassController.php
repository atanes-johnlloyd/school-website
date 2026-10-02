<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Student;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;

        if (! $teacher) {
            abort(403, 'No teacher profile linked to this account.');
        }

        $activeTerm = Term::where('is_active', true)->first();

        // ─── Validate query params ───────────────────────────────
        $validated = $request->validate([
            'search'         => ['nullable', 'string', 'max:100'],
            'grade_level'    => ['nullable', 'in:11,12'],
            'strand_id'      => ['nullable', 'integer', 'exists:strands,id'],
            'term_id'        => ['nullable', 'integer', 'exists:terms,id'],
            'is_published'   => ['nullable', 'in:0,1'],
            'sort'           => ['nullable', 'in:subject,section,grade_level,created_at'],
            'direction'      => ['nullable', 'in:asc,desc'],
            'per_page'       => ['nullable', 'integer', 'min:5', 'max:100'],
        ]);

        $search       = $validated['search'] ?? null;
        $gradeLevel   = $validated['grade_level'] ?? null;
        $strandId     = $validated['strand_id'] ?? null;
        $termId       = $validated['term_id'] ?? $activeTerm?->id;
        $isPublished  = $validated['is_published'] ?? null;
        $sort         = $validated['sort'] ?? 'section';
        $direction    = $validated['direction'] ?? 'asc';
        $perPage      = $validated['per_page'] ?? 20;

        // ─── Build query ─────────────────────────────────────────
        $query = ClassRoom::query()
            ->with([
                'subject:id,code,name',
                'section:id,name,grade_level,strand_id',
                'section.strand:id,code,name',
                'term:id,name',
            ])
            ->withCount('assignments')
            ->withCount('students')
            ->where('teacher_id', $teacher->id);

        // Search — subject name, code, or section name
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('subject', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
                })
                ->orWhereHas('section', fn ($sq) => $sq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($gradeLevel) {
            $query->whereHas('section', fn ($q) => $q->where('grade_level', $gradeLevel));
        }

        if ($strandId) {
            $query->whereHas('section', fn ($q) => $q->where('strand_id', $strandId));
        }

        if ($termId) {
            $query->where('term_id', $termId);
        }

        if ($isPublished !== null) {
            $query->where('is_published', (bool) $isPublished);
        }

        // ─── Sorting (whitelist-mapped to prevent injection) ────
        $sortColumn = match ($sort) {
            'subject'     => 'subjects.name',
            'section'     => 'sections.name',
            'grade_level' => 'sections.grade_level',
            default       => 'classes.created_at',
        };

        if (in_array($sort, ['subject', 'section', 'grade_level'])) {
            $query->join('subjects', 'subjects.id', '=', 'classes.subject_id')
                ->join('sections', 'sections.id', '=', 'classes.section_id')
                ->orderBy($sortColumn, $direction)
                ->select('classes.*');
        } else {
            $query->orderBy($sortColumn, $direction);
        }

        // ─── Paginate ────────────────────────────────────────────
        $classes = $query->paginate($perPage)->withQueryString();

        // ─── Shape response ──────────────────────────────────────
        $classes->getCollection()->transform(fn (ClassRoom $k) => [
            'id'                => $k->id,
            'subject'           => $k->subject?->name,
            'subject_code'      => $k->subject?->code,
            'section'           => $k->section?->name,
            'grade_level'       => $k->section?->grade_level,
            'strand'            => $k->section?->strand?->name,
            'strand_code'       => $k->section?->strand?->code,
            'term'              => $k->term?->name,
            'is_published'      => $k->is_published,
            'assignments_count' => $k->assignments_count,
            'students_count'    => $k->students_count,
        ]);

        $totals = [
            'classes' => ClassRoom::where('teacher_id', $teacher->id)
                ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
                ->count(),
            'students' => Student::whereHas('classroom', function ($q) use ($teacher, $activeTerm) {
                $q->where('classes.teacher_id', $teacher->id);
                if ($activeTerm) $q->where('classes.term_id', $activeTerm->id);
            })->count(),
            'assignments' => \App\Models\Assignment::whereHas('classroom', function ($q) use ($teacher, $activeTerm) {
                $q->where('teacher_id', $teacher->id);
                if ($activeTerm) $q->where('term_id', $activeTerm->id);
            })->count(),
        ];

        $payload = [
            'classes'    => $classes,
            'activeTerm' => $activeTerm?->name,
            'filters'    => [
                'search'       => $search,
                'grade_level'  => $gradeLevel,
                'strand_id'    => $strandId,
                'term_id'      => $termId,
                'is_published' => $isPublished,
                'sort'         => $sort,
                'direction'    => $direction,
                'per_page'     => $perPage,
            ],
            'filterOptions' => [
                'strands' => \App\Models\Strand::select('id', 'code', 'name')->orderBy('name')->get(),
                'terms'   => Term::select('id', 'name')->orderByDesc('start_date')->get(),
            ],
            'totals' => $totals,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : \Inertia\Inertia::render('Teacher/Classes/Index', $payload);
    }

    public function show(Request $request, ClassRoom $classroom)
    {
        $teacher = $request->user()->teacher;

        if (! $teacher || $classroom->teacher_id !== $teacher->id) {
            abort(403);
        }

        $classroom->load([
            'subject', 'section', 'term',
            'students.user:id,name,email',
        ]);

        return Inertia::render('Teacher/Classes/Show', [
            'classroom' => [
                'id'           => $classroom->id,
                'subject'      => $classroom->subject?->name,
                'subject_code' => $classroom->subject?->code,
                'section'      => $classroom->section?->name,
                'grade_level'  => $classroom->section?->grade_level,
                'term'         => $classroom->term?->name,
                'schedule'     => null,
            ],
            'students' => $classroom->students->map(fn ($s) => [
                'id'             => $s->id,
                'name'           => $s->user?->name,
                'lrn'            => $s->lrn,
                'email'          => $s->user?->email,
                'sex'            => $s->sex,
                'contact_number' => $s->contact_number,
                'barangay'       => $s->barangay,
                'status'         => $s->pivot?->status ?? 'active',
                'enrolled_at'    => $s->pivot?->enrolled_at
                    ? \Carbon\Carbon::parse($s->pivot->enrolled_at)->toIso8601String()
                    : null,
            ]),
        ]);
    }
}