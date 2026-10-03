<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\ClassRoom;
use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student; //[cite: 22]

        if (! $student) { //[cite: 22]
            abort(403, 'No student profile linked to this account.'); //[cite: 22]
        }

        $activeTerm = Term::where('is_active', true)->first(); //[cite: 22]

        $validated = $request->validate([ //[cite: 22]
            'search'      => ['nullable', 'string', 'max:100'], //[cite: 22]
            'grade_level' => ['nullable', 'in:11,12'], //[cite: 22]
            'strand_id'   => ['nullable', 'integer', 'exists:strands,id'], //[cite: 22]
            'term_id'     => ['nullable', 'integer', 'exists:terms,id'], //[cite: 22]
            'sort' => ['nullable', 'in:subject,teacher,created_at'],
            'direction'   => ['nullable', 'in:asc,desc'], //[cite: 22]
            'per_page'    => ['nullable', 'integer', 'min:5', 'max:100'], //[cite: 22]
        ]);

        $search     = $validated['search'] ?? null; //[cite: 22]
        $gradeLevel = $validated['grade_level'] ?? null; //[cite: 22]
        $strandId   = $validated['strand_id'] ?? null; //[cite: 22]
        $termId     = $validated['term_id'] ?? $activeTerm?->id; //[cite: 22]
        $sort       = $validated['sort'] ?? 'section'; //[cite: 22]
        $direction  = $validated['direction'] ?? 'asc'; //[cite: 22]
        $perPage    = $validated['per_page'] ?? 20; //[cite: 22]

        // ──────────────────────────────────────────────────────
        // STEP 1: Get classroom IDs from the pivot — direct query
        // ──────────────────────────────────────────────────────
        $classroomIds = \DB::table('class_students') //[cite: 22]
            ->where('student_id', $student->id) //[cite: 22]
            ->where('status', 'active')          // only active enrollments[cite: 22]
            ->pluck('class_id'); //[cite: 22]

        // ──────────────────────────────────────────────────────
        // STEP 2: Query ClassRoom with those IDs
        // ──────────────────────────────────────────────────────
        $query = ClassRoom::query() //[cite: 22]
            ->with([ //[cite: 22]
                'subject:id,code,name', //[cite: 22]
                'section:id,name,grade_level,strand_id', //[cite: 22]
                'section.strand:id,code,name', //[cite: 22]
                'teacher.user:id,name', //[cite: 22]
                'term:id,name', //[cite: 22]
            ])
            ->withCount('assignments') //[cite: 22]
            ->whereIn('classes.id', $classroomIds); //[cite: 22]

        // Search
        if ($search) { //[cite: 22]
            $query->where(function ($q) use ($search) { //[cite: 22]
                $q->whereHas('subject', function ($sq) use ($search) { //[cite: 22]
                    $sq->where('name', 'like', "%{$search}%") //[cite: 22]
                    ->orWhere('code', 'like', "%{$search}%"); //[cite: 22]
                })
                ->orWhereHas('section', fn ($sq) => $sq->where('name', 'like', "%{$search}%")) //[cite: 22]
                ->orWhereHas('teacher.user', fn ($sq) => $sq->where('name', 'like', "%{$search}%")); //[cite: 22]
            });
        }

        if ($gradeLevel) { //[cite: 22]
            $query->whereHas('section', fn ($q) => $q->where('grade_level', $gradeLevel)); //[cite: 22]
        }
        if ($strandId) { //[cite: 22]
            $query->whereHas('section', fn ($q) => $q->where('strand_id', $strandId)); //[cite: 22]
        }
        if ($termId) { //[cite: 22]
            $query->where('term_id', $termId); //[cite: 22]
        }

        // Sorting
        $sortColumn = match ($sort) { //[cite: 22]
            'subject'     => 'subjects.name', //[cite: 22]
            'section'     => 'sections.name', //[cite: 22]
            'teacher'     => 'users.name', //[cite: 22]
            'grade_level' => 'sections.grade_level', //[cite: 22]
            default       => 'classes.created_at', //[cite: 22]
        };

        if (in_array($sort, ['subject', 'section', 'grade_level'])) { //[cite: 22]
            $query->join('subjects', 'subjects.id', '=', 'classes.subject_id') //[cite: 22]
                ->join('sections', 'sections.id', '=', 'classes.section_id') //[cite: 22]
                ->orderBy($sortColumn, $direction) //[cite: 22]
                ->select('classes.*'); //[cite: 22]
        } elseif ($sort === 'teacher') { //[cite: 22]
            $query->join('teachers', 'teachers.id', '=', 'classes.teacher_id') //[cite: 22]
                ->join('users', 'users.id', '=', 'teachers.user_id') //[cite: 22]
                ->orderBy('users.name', $direction) //[cite: 22]
                ->select('classes.*'); //[cite: 22]
        } else {
            $query->orderBy($sortColumn, $direction); //[cite: 22]
        }

        $classes = $query->paginate($perPage)->withQueryString(); //[cite: 22]

        $classes->getCollection()->transform(fn (ClassRoom $k) => [ //[cite: 22]
            'id'            => $k->id, //[cite: 22]
            'subject'       => $k->subject?->name, //[cite: 22]
            'subject_code'  => $k->subject?->code, //[cite: 22]
            'section'       => $k->section?->name, //[cite: 22]
            'grade_level'   => $k->section?->grade_level, //[cite: 22]
            'strand'        => $k->section?->strand?->name, //[cite: 22]
            'teacher'       => $k->teacher?->user?->name, //[cite: 22]
            'term'          => $k->term?->name, //[cite: 22]
            'assignments_count' => $k->assignments_count, //[cite: 22]
        ]);

        $payload = [
            'classes'       => $classes,
            'activeTerm'    => $activeTerm?->name,
            'studentStrand' => $student->strand?->name
                ?? $classroomIds->first()
                    ? ClassRoom::with('section.strand')->find($classroomIds->first())?->section?->strand?->name
                    : null,
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
                'terms' => Term::select('id', 'name')->orderByDesc('start_date')->get(),
                // 'strands' removed — no longer used
            ],
        ];

        return $request->wantsJson() //[cite: 22]
            ? response()->json($payload) //[cite: 22]
            : \Inertia\Inertia::render('Student/Classes/Index', $payload); //[cite: 22]
    }

    public function show(Request $request, ClassRoom $classroom)
    {
        $student = $request->user()->student;

        $isEnrolled = $classroom->students()->where('students.id', $student?->id)->exists();
        if (! $student || ! $isEnrolled) {
            abort(403);
        }

        $classroom->load(['subject', 'section', 'term', 'teacher.user:id,name']);

        $assignments = \App\Models\Assignment::query()
            ->where('class_id', $classroom->id)
            ->published()
            ->with(['submissions' => fn ($q) => $q->where('student_id', $student->id)])
            ->orderBy('due_at', 'asc')
            ->get()
            ->map(function ($a) {
                $submission = $a->submissions->first();
                return [
                    'id'         => $a->id,
                    'title'      => $a->title,
                    'instructions' => $a->instructions,
                    'due_at'     => $a->due_at?->toIso8601String(),
                    'points'     => $a->points,
                    'category'   => $a->category,
                    'submission' => $submission ? [
                        'id'           => $submission->id,
                        'status'       => $submission->status,
                        'grade'        => $submission->grade,
                        'feedback'     => $submission->feedback,
                        'submitted_at' => $submission->submitted_at?->toIso8601String(),
                        'text_content' => $submission->text_content,
                        'has_file'     => (bool) $submission->file_path,
                        'download_url' => $submission->file_path
                            ? route('student.assignments.submission.download', $a->id)
                            : null,
                    ] : null,
                ];
            });

        $announcements = $classroom->announcements()
            ->published()
            ->active()
            ->with('author:id,name')
            ->ordered()
            ->limit(20)
            ->get()
            ->map(fn ($a) => [
                'id'           => $a->id,
                'title'        => $a->title,
                'body'         => $a->body,
                'body_preview' => \Str::limit(strip_tags($a->body), 120),
                'is_pinned'    => $a->is_pinned,
                'author'       => $a->author?->name,
                'published_at' => $a->published_at?->toIso8601String(),
                'category'     => 'General',
            ]);

        return Inertia::render('Student/Classes/Show', [
            'classroom' => [
                'id'           => $classroom->id,
                'subject'      => $classroom->subject?->name,
                'subject_code' => $classroom->subject?->code,
                'section'      => $classroom->section?->name,
                'teacher'      => $classroom->teacher?->user?->name,
                'term'         => $classroom->term?->name,
            ],
            'announcements' => $announcements,
            'assignments'   => $assignments,
        ]);
    }
}