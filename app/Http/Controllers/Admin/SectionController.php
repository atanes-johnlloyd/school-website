<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Strand;
use App\Models\Student;
use App\Models\Teacher;
use App\Support\AuditContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class SectionController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Sections/Index', [
            'strands' => Strand::select('id', 'code', 'name')->orderBy('code')->get(),
            'teachers' => Teacher::with('user:id,name')
                ->where('is_active', true)->get()
                ->map(fn ($t) => ['id' => $t->id, 'name' => $t->user?->name])
                ->filter(fn ($t) => $t['name'])->values(),
            'schoolYears' => SchoolYear::select('id', 'label', 'is_active')
                ->orderByDesc('start_date')->get(),
            'defaultMaxCapacity' => \App\Models\SystemSetting::maxClassSize(),
        ]);
    }

    public function list(Request $request)
    {
        $validated = $request->validate([
            'school_year_id' => ['nullable', 'integer', 'exists:school_years,id'],
            'strand_id'      => ['nullable', 'integer', 'exists:strands,id'],
            'grade_level'    => ['nullable', 'in:11,12'],
            'search'         => ['nullable', 'string', 'max:100'],
            'per_page'       => ['nullable', 'integer', 'min:10', 'max:100'],
            'sort_by'        => ['nullable', 'in:name,grade_level,strand,capacity,enrolled'],
            'sort_dir'       => ['nullable', 'in:asc,desc'],
        ]);

        $sortBy  = $validated['sort_by']  ?? 'name';
        $sortDir = $validated['sort_dir'] ?? 'asc';

        $schoolYearId = $validated['school_year_id']
            ?? SchoolYear::where('is_active', true)->value('id');

        $query = Section::query()
            ->with(['schoolYear:id,label', 'strand:id,code,name', 'adviser.user:id,name'])
            ->withCount(['enrollments as enrolled_count' => fn ($q) => $q->where('status', 'enrolled')]);

        if ($schoolYearId) $query->where('school_year_id', $schoolYearId);
        if (! empty($validated['strand_id']))   $query->where('strand_id', $validated['strand_id']);
        if (! empty($validated['grade_level'])) $query->where('grade_level', $validated['grade_level']);
        if (! empty($validated['search']))      $query->where('name', 'like', '%' . $validated['search'] . '%');

        switch ($sortBy) {
            case 'strand':
                $query->leftJoin('strands', 'strands.id', '=', 'sections.strand_id')
                    ->orderBy('strands.code', $sortDir)->select('sections.*');
                break;
            case 'capacity': $query->orderBy('max_capacity', $sortDir); break;
            case 'enrolled': $query->orderBy('enrolled_count', $sortDir); break;
            default:         $query->orderBy($sortBy, $sortDir);
        }

        $sections = $query->paginate($validated['per_page'] ?? 10);

        $sections->getCollection()->transform(fn (Section $s) => [
            'id'             => $s->id,
            'name'           => $s->name,
            'grade_level'    => $s->grade_level,
            'strand_id'      => $s->strand_id,
            'strand'         => $s->strand?->name,
            'strand_code'    => $s->strand?->code,
            'school_year'    => $s->schoolYear?->label,
            'school_year_id' => $s->school_year_id,
            'adviser'        => $s->adviser?->user?->name,
            'adviser_id'     => $s->adviser_id,
            'max_capacity'   => $s->max_capacity,
            'enrolled_count' => $s->enrolled_count,
        ]);

        $allSectionsQuery = Section::query();
        if ($schoolYearId) $allSectionsQuery->where('school_year_id', $schoolYearId);
        $allSections = $allSectionsQuery->get();
        $totalCapacity = $allSections->sum('max_capacity');
        $totalEnrolled = Enrollment::whereIn('section_id', $allSections->pluck('id'))
            ->where('status', 'enrolled')->count();

        return response()->json([
            'sections' => $sections,
            'filters'  => [
                'school_year_id' => $schoolYearId,
                'strand_id'      => $validated['strand_id']  ?? null,
                'grade_level'    => $validated['grade_level'] ?? null,
                'search'         => $validated['search']      ?? null,
            ],
            'sort'   => ['by' => $sortBy, 'dir' => $sortDir],
            'counts' => [
                'total_sections' => $allSections->count(),
                'total_capacity' => $totalCapacity,
                'total_enrolled' => $totalEnrolled,
                'occupancy_rate' => $totalCapacity > 0 ? round(($totalEnrolled / $totalCapacity) * 100, 1) : 0,
            ],
        ]);
    }

    public function show(Section $section)
    {
        $section->load(['schoolYear:id,label', 'strand:id,code,name', 'adviser.user:id,name']);

        $enrollments = Enrollment::where('section_id', $section->id)
            ->where('status', 'enrolled')
            ->with('student.user:id,name,email,avatar_path')
            ->orderBy('enrolled_at')
            ->get()
            ->map(fn ($e) => [
                'id'          => $e->id,
                'student_id'  => $e->student_id,
                'lrn'         => $e->student?->lrn,
                'name'        => $e->student?->user?->name,
                'email'       => $e->student?->user?->email,
                'enrolled_at' => $e->enrolled_at?->toIso8601String(),
            ]);

        $classes = ClassRoom::where('section_id', $section->id)
            ->with(['subject:id,code,name', 'teacher.user:id,name'])
            ->get()
            ->map(fn ($c) => [
                'id'           => $c->id,
                'subject'      => $c->subject?->name,
                'subject_code' => $c->subject?->code,
                'teacher'      => $c->teacher?->user?->name,
            ]);

        return response()->json([
            'section' => [
                'id'             => $section->id,
                'name'           => $section->name,
                'grade_level'    => $section->grade_level,
                'strand'         => $section->strand?->name,
                'strand_code'    => $section->strand?->code,
                'school_year'    => $section->schoolYear?->label,
                'school_year_id' => $section->school_year_id,
                'adviser'        => $section->adviser?->user?->name,
                'adviser_id'     => $section->adviser_id,
                'max_capacity'   => $section->max_capacity,
                'enrolled_count' => $enrollments->count(),
            ],
            'students' => $enrollments,
            'classes'  => $classes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_year_id' => ['required', 'exists:school_years,id'],
            'strand_id'      => ['nullable', 'exists:strands,id'],
            'grade_level'    => ['required', 'in:11,12'],
            'name'           => [
                'required', 'string', 'max:100',
                Rule::unique('sections')->where(function ($query) use ($request) {
                    return $query->where('school_year_id', $request->input('school_year_id'))
                                 ->where('grade_level', $request->input('grade_level'));
                }),
            ],
            'adviser_id'     => ['nullable', 'exists:teachers,id'],
            'max_capacity'   => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $section = AuditContext::wrap('create_section', function () use ($validated) {
            return Section::create($validated);
        });

        return response()->json(['section' => $section], 201);
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'strand_id'    => ['nullable', 'exists:strands,id'],
            'name'         => [
                'sometimes', 'string', 'max:100',
                Rule::unique('sections')->where(function ($query) use ($section) {
                    return $query->where('school_year_id', $section->school_year_id)
                                 ->where('grade_level', $section->grade_level);
                })->ignore($section->id),
            ],
            'adviser_id'   => ['nullable', 'exists:teachers,id'],
            'max_capacity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        AuditContext::wrap('update_section', function () use ($section, $validated) {
            $section->update($validated);
        });

        return response()->json(['section' => $section->fresh()]);
    }

    public function destroy(Section $section)
    {
        if ($section->enrollments()->where('status', 'enrolled')->exists()) {
            return response()->json([
                'message' => 'Cannot delete: section has enrolled students.',
            ], 422);
        }

        AuditContext::wrap('delete_section', function () use ($section) {
            $section->delete();
        });

        return response()->json(['message' => 'Section deleted.']);
    }

    public function eligibleStudents(Request $request, Section $section)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Student::with('user:id,name,email')
            ->whereDoesntHave('enrollments', function ($q) use ($section) {
                $q->where('school_year_id', $section->school_year_id)->where('status', 'enrolled');
            })
            ->where('status', 'active');

        if (! empty($validated['search'])) {
            $s = $validated['search'];
            $query->where(function ($q) use ($s) {
                $q->where('lrn', 'like', "%{$s}%")
                  ->orWhereHas('user', fn ($uq) => $uq
                      ->where('name', 'like', "%{$s}%")
                      ->orWhere('email', 'like', "%{$s}%"));
            });
        }

        $students = $query->orderBy('lrn')->limit(100)->get()->map(fn ($s) => [
            'id'    => $s->id,
            'lrn'   => $s->lrn,
            'name'  => $s->user?->name,
            'email' => $s->user?->email,
        ]);

        $currentCount = Enrollment::where('section_id', $section->id)
            ->where('status', 'enrolled')->count();

        return response()->json([
            'students' => $students,
            'capacity' => [
                'max'       => $section->max_capacity,
                'current'   => $currentCount,
                'remaining' => max(0, ($section->max_capacity ?? 0) - $currentCount),
            ],
        ]);
    }

    public function enrollStudent(Request $request, Section $section)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
        ]);

        $student = Student::findOrFail($validated['student_id']);

        $currentCount = Enrollment::where('section_id', $section->id)
            ->where('status', 'enrolled')->count();

        if ($section->max_capacity !== null && $currentCount >= $section->max_capacity) {
            return response()->json(['message' => 'Section is at maximum capacity.'], 422);
        }

        $existing = Enrollment::where('student_id', $student->id)
            ->where('school_year_id', $section->school_year_id)
            ->where('status', 'enrolled')
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Student already enrolled for this school year.'], 422);
        }

        AuditContext::wrap('enroll_in_section', function () use ($student, $section, $request) {
            DB::transaction(function () use ($student, $section, $request) {
                Enrollment::create([
                    'student_id'     => $student->id,
                    'school_year_id' => $section->school_year_id,
                    'section_id'     => $section->id,
                    'status'         => 'enrolled',
                    'enrolled_at'    => now(),
                    'enrolled_by'    => $request->user()?->id,
                ]);

                ClassRoom::where('section_id', $section->id)->each(function ($class) use ($student) {
                    $class->students()->syncWithoutDetaching([
                        $student->id => ['status' => 'active', 'enrolled_at' => now()],
                    ]);
                });
            });
        }, ['student_id' => $student->id]);

        return response()->json(['message' => 'Student enrolled in section.'], 201);
    }

    public function removeStudent(Section $section, Student $student)
    {
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('section_id', $section->id)
            ->where('status', 'enrolled')
            ->first();

        if (! $enrollment) {
            return response()->json(['message' => 'Student is not enrolled in this section.'], 422);
        }

        AuditContext::wrap('remove_from_section', function () use ($section, $student, $enrollment) {
            DB::transaction(function () use ($section, $student, $enrollment) {
                $enrollment->update(['status' => 'dropped']);

                ClassRoom::where('section_id', $section->id)->each(function ($class) use ($student) {
                    $class->students()->detach($student->id);
                });
            });
        }, ['student_id' => $student->id]);

        return response()->json(['message' => 'Student removed from section.']);
    }
}