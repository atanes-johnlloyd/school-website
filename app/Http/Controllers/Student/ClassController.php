<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;

        if (! $student) {
            abort(403, 'No student profile linked to this account.');
        }

        $activeTerm = Term::where('is_active', true)->first();

        $validated = $request->validate([
            'search'      => ['nullable', 'string', 'max:100'],
            'grade_level' => ['nullable', 'in:11,12'],
            'strand_id'   => ['nullable', 'integer', 'exists:strands,id'],
            'term_id'     => ['nullable', 'integer', 'exists:terms,id'],
            'sort'        => ['nullable', 'in:subject,section,teacher,grade_level,created_at'],
            'direction'   => ['nullable', 'in:asc,desc'],
            'per_page'    => ['nullable', 'integer', 'min:5', 'max:100'],
        ]);

        $search     = $validated['search'] ?? null;
        $gradeLevel = $validated['grade_level'] ?? null;
        $strandId   = $validated['strand_id'] ?? null;
        $termId     = $validated['term_id'] ?? $activeTerm?->id;
        $sort       = $validated['sort'] ?? 'section';
        $direction  = $validated['direction'] ?? 'asc';
        $perPage    = $validated['per_page'] ?? 20;

        // ──────────────────────────────────────────────────────
        // STEP 1: Get classroom IDs from the pivot — direct query
        // ──────────────────────────────────────────────────────
        $classroomIds = \DB::table('class_students')
            ->where('student_id', $student->id)
            ->where('status', 'active')          // only active enrollments
            ->pluck('class_id');

        // ──────────────────────────────────────────────────────
        // STEP 2: Query ClassRoom with those IDs
        // ──────────────────────────────────────────────────────
        $query = ClassRoom::query()
            ->with([
                'subject:id,code,name',
                'section:id,name,grade_level,strand_id',
                'section.strand:id,code,name',
                'teacher.user:id,name',
                'term:id,name',
            ])
            ->withCount('assignments')
            ->whereIn('classes.id', $classroomIds);

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('subject', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
                })
                ->orWhereHas('section', fn ($sq) => $sq->where('name', 'like', "%{$search}%"))
                ->orWhereHas('teacher.user', fn ($sq) => $sq->where('name', 'like', "%{$search}%"));
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

        // Sorting
        $sortColumn = match ($sort) {
            'subject'     => 'subjects.name',
            'section'     => 'sections.name',
            'teacher'     => 'users.name',
            'grade_level' => 'sections.grade_level',
            default       => 'classes.created_at',
        };

        if (in_array($sort, ['subject', 'section', 'grade_level'])) {
            $query->join('subjects', 'subjects.id', '=', 'classes.subject_id')
                ->join('sections', 'sections.id', '=', 'classes.section_id')
                ->orderBy($sortColumn, $direction)
                ->select('classes.*');
        } elseif ($sort === 'teacher') {
            $query->join('teachers', 'teachers.id', '=', 'classes.teacher_id')
                ->join('users', 'users.id', '=', 'teachers.user_id')
                ->orderBy('users.name', $direction)
                ->select('classes.*');
        } else {
            $query->orderBy($sortColumn, $direction);
        }

        $classes = $query->paginate($perPage)->withQueryString();

        $classes->getCollection()->transform(fn (ClassRoom $k) => [
            'id'            => $k->id,
            'subject'       => $k->subject?->name,
            'subject_code'  => $k->subject?->code,
            'section'       => $k->section?->name,
            'grade_level'   => $k->section?->grade_level,
            'strand'        => $k->section?->strand?->name,
            'teacher'       => $k->teacher?->user?->name,
            'term'          => $k->term?->name,
            'assignments_count' => $k->assignments_count,
        ]);

        $payload = [
            'classes'    => $classes,
            'activeTerm' => $activeTerm?->name,
            'filters'    => [
                'search'      => $search,
                'grade_level' => $gradeLevel,
                'strand_id'   => $strandId,
                'term_id'     => $termId,
                'sort'        => $sort,
                'direction'   => $direction,
                'per_page'    => $perPage,
            ],
            'filterOptions' => [
                'strands' => \App\Models\Strand::select('id', 'code', 'name')->orderBy('name')->get(),
                'terms'   => Term::select('id', 'name')->orderByDesc('start_date')->get(),
            ],
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : \Inertia\Inertia::render('Student/Classes/Index', $payload);
    }

    public function show(Request $request, ClassRoom $classroom)
    {
        $student = $request->user()->student;

        // Authorization: student must be enrolled in this class
        $isEnrolled = $classroom->students()->where('students.id', $student?->id)->exists();

        if (! $student || ! $isEnrolled) {
            abort(403);
        }

        $classroom->load(['subject', 'section', 'term', 'teacher.user:id,name']);

        return Inertia::render('Student/Classes/Show', [
            'classroom' => [
                'id'           => $classroom->id,
                'subject'      => $classroom->subject?->name,
                'subject_code' => $classroom->subject?->code,
                'section'      => $classroom->section?->name,
                'teacher'      => $classroom->teacher?->user?->name,
                'term'         => $classroom->term?->name,
            ],
        ]);
    }
}