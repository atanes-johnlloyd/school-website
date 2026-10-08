<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Support\AuditContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class EnrollmentController extends Controller
{
    public function index(Request $request): Response
    {
        $activeYear = SchoolYear::where('is_active', true)->first();

        return Inertia::render('Admin/Enrollments/Index', [
            'schoolYears' => SchoolYear::select('id', 'label', 'is_active')
                ->orderByDesc('start_date')->get(),
            'sections' => Section::with(['strand:id,code'])
                ->orderBy('name')->get()
                ->map(fn ($s) => [
                    'id'             => $s->id,
                    'name'           => $s->name,
                    'grade_level'    => $s->grade_level,
                    'strand_code'    => $s->strand?->code,
                    'school_year_id' => $s->school_year_id,
                ]),
            'activeYearId' => $activeYear?->id,
        ]);
    }

    public function list(Request $request)
    {
        $validated = $request->validate([
            'status'         => ['nullable', 'in:pending,enrolled,dropped,transferred,completed'],
            'school_year_id' => ['nullable', 'integer', 'exists:school_years,id'],
            'section_id'     => ['nullable', 'integer', 'exists:sections,id'],
            'unassigned'     => ['nullable', 'boolean'],
            'search'         => ['nullable', 'string', 'max:100'],
            'per_page'       => ['nullable', 'integer', 'min:10', 'max:100'],
            'sort_by'        => ['nullable', 'in:student_name,status,enrolled_at'],
            'sort_dir'       => ['nullable', 'in:asc,desc'],
        ]);

        $sortBy  = $validated['sort_by']  ?? 'enrolled_at';
        $sortDir = $validated['sort_dir'] ?? 'desc';

        $query = Enrollment::query()->with([
            'student.user:id,name,email',
            'student:id,user_id,lrn',
            'section.strand:id,code',
            'schoolYear:id,label',
            'enrolledBy:id,name',
        ]);

        if (! empty($validated['status']))         $query->where('status', $validated['status']);
        if (! empty($validated['school_year_id'])) $query->where('school_year_id', $validated['school_year_id']);
        if (! empty($validated['section_id']))     $query->where('section_id', $validated['section_id']);
        if (! empty($validated['unassigned']))     $query->whereNull('section_id');

        if (! empty($validated['search'])) {
            $s = $validated['search'];
            $query->whereHas('student', function ($q) use ($s) {
                $q->where('lrn', 'like', "%{$s}%")
                  ->orWhereHas('user', fn ($uq) => $uq
                      ->where('name', 'like', "%{$s}%")
                      ->orWhere('email', 'like', "%{$s}%"));
            });
        }

        if ($sortBy === 'student_name') {
            $query->join('students', 'students.id', '=', 'enrollments.student_id')
                  ->join('users', 'users.id', '=', 'students.user_id')
                  ->orderBy('users.name', $sortDir)
                  ->select('enrollments.*');
        } else {
            $query->orderBy($sortBy, $sortDir);
        }

        $enrollments = $query->paginate($validated['per_page'] ?? 15);

        $enrollments->getCollection()->transform(fn (Enrollment $e) => [
            'id'            => $e->id,
            'student_id'    => $e->student_id,
            'lrn'           => $e->student?->lrn,
            'student_name'  => $e->student?->user?->name,
            'student_email' => $e->student?->user?->email,
            'school_year'   => $e->schoolYear?->label,
            'section'       => $e->section?->name,
            'section_id'    => $e->section_id,
            'strand_code'   => $e->section?->strand?->code,
            'status'        => $e->status,
            'enrolled_at'   => $e->enrolled_at?->toIso8601String(),
            'enrolled_by'   => $e->enrolledBy?->name,
        ]);

        $countsQuery = Enrollment::query()
            ->when(! empty($validated['school_year_id']),
                fn ($q) => $q->where('school_year_id', $validated['school_year_id']));

        return response()->json([
            'enrollments' => $enrollments,
            'filters'     => [
                'status'         => $validated['status']         ?? null,
                'school_year_id' => $validated['school_year_id'] ?? null,
                'section_id'     => $validated['section_id']     ?? null,
                'unassigned'     => $validated['unassigned']     ?? null,
                'search'         => $validated['search']         ?? null,
            ],
            'sort'   => ['by' => $sortBy, 'dir' => $sortDir],
            'counts' => [
                'total'      => (clone $countsQuery)->count(),
                'pending'    => (clone $countsQuery)->where('status', 'pending')->count(),
                'enrolled'   => (clone $countsQuery)->where('status', 'enrolled')->count(),
                'dropped'    => (clone $countsQuery)->where('status', 'dropped')->count(),
                'unassigned' => (clone $countsQuery)->whereNull('section_id')->where('status', 'enrolled')->count(),
            ],
        ]);
    }

    public function approve(Request $request, Enrollment $enrollment)
    {
        if ($enrollment->status !== 'pending') {
            return response()->json(['message' => 'Only pending enrollments can be approved.'], 422);
        }
        if (! $enrollment->section_id) {
            return response()->json(['message' => 'Assign a section before approving.'], 422);
        }

        AuditContext::wrap('approve_enrollment', function () use ($enrollment, $request) {
            DB::transaction(function () use ($enrollment, $request) {
                $enrollment->update([
                    'status'      => 'enrolled',
                    'enrolled_at' => now(),
                    'enrolled_by' => $request->user()->id,
                ]);

                ClassRoom::where('section_id', $enrollment->section_id)->each(function ($class) use ($enrollment) {
                    $class->students()->syncWithoutDetaching([
                        $enrollment->student_id => ['status' => 'active', 'enrolled_at' => now()],
                    ]);
                });
            });
        });

        return response()->json(['message' => 'Enrollment approved.']);
    }

    public function reject(Request $request, Enrollment $enrollment)
    {
        if ($enrollment->status !== 'pending') {
            return response()->json(['message' => 'Only pending enrollments can be rejected.'], 422);
        }

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        AuditContext::wrap('reject_enrollment', function () use ($enrollment) {
            $enrollment->update(['status' => 'dropped']);
        }, ['reason' => $validated['reason'] ?? null]);

        return response()->json(['message' => 'Enrollment rejected.']);
    }

    public function assignSection(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'section_id' => ['required', 'integer', 'exists:sections,id'],
        ]);

        $newSection = Section::with('classrooms')->findOrFail($validated['section_id']);

        if ($newSection->school_year_id !== $enrollment->school_year_id) {
            return response()->json(['message' => 'Section belongs to a different school year.'], 422);
        }

        $currentCount = Enrollment::where('section_id', $newSection->id)
            ->where('status', 'enrolled')
            ->count();

        if ($newSection->max_capacity !== null && $currentCount >= $newSection->max_capacity) {
            return response()->json(['message' => 'Section is at maximum capacity.'], 422);
        }

        AuditContext::wrap('assign_section', function () use ($enrollment, $newSection) {
            DB::transaction(function () use ($enrollment, $newSection) {
                if ($enrollment->section_id && $enrollment->section_id !== $newSection->id) {
                    $old = Section::with('classrooms')->find($enrollment->section_id);
                    if ($old) {
                        foreach ($old->classrooms as $class) {
                            $class->students()->detach($enrollment->student_id);
                        }
                    }
                }

                $enrollment->update([
                    'section_id'  => $newSection->id,
                    'status'      => 'enrolled',
                    'enrolled_at' => $enrollment->enrolled_at ?? now(),
                ]);

                foreach ($newSection->classrooms as $class) {
                    $class->students()->syncWithoutDetaching([
                        $enrollment->student_id => ['status' => 'active', 'enrolled_at' => now()],
                    ]);
                }
            });
        }, ['section_id' => $newSection->id]);

        return response()->json(['message' => 'Section assigned.']);
    }
}