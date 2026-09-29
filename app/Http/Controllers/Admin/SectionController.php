<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\ClassRoom;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Section::with([
            'schoolYear:id,label',
            'strand:id,code,name',
            'adviser.user:id,name',
        ])->withCount('students');

        if ($request->filled('school_year_id')) {
            $query->where('school_year_id', $request->input('school_year_id'));
        }
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->input('grade_level'));
        }
        if ($request->filled('strand_id')) {
            $query->where('strand_id', $request->input('strand_id'));
        }

        return response()->json([
            'sections' => $query->orderBy('name')->paginate(20),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_year_id' => ['required', 'exists:school_years,id'],
            'strand_id'      => ['nullable', 'exists:strands,id'],
            'grade_level'    => ['required', 'in:11,12'],
            'name'           => ['required', 'string', 'max:100'],
            'adviser_id'     => ['nullable', 'exists:teachers,id'],
            'max_capacity'   => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        // Ensure uniqueness within school year
        $exists = Section::where('school_year_id', $validated['school_year_id'])
            ->where('grade_level', $validated['grade_level'])
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Section with this name already exists for this school year and grade level.',
            ], 422);
        }

        $section = Section::create($validated);

        return response()->json(['section' => $section], 201);
    }

    public function show(Section $section)
    {
        $section->load([
            'schoolYear', 'strand', 'adviser.user',
            'students.user:id,name,email',
        ]);

        return response()->json([
            'section' => $section,
            'student_count' => $section->students->count(),
            'classes_count' => ClassRoom::where('section_id', $section->id)->count(),
        ]);
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'strand_id'    => ['nullable', 'exists:strands,id'],
            'name'         => ['sometimes', 'string', 'max:100'],
            'adviser_id'   => ['nullable', 'exists:teachers,id'],
            'max_capacity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $section->update($validated);

        return response()->json(['section' => $section->fresh()]);
    }

    public function destroy(Section $section)
    {
        if ($section->students()->exists()) {
            return response()->json([
                'message' => 'Cannot delete: section has enrolled students.',
            ], 422);
        }

        $section->delete();

        return response()->json(['message' => 'Section deleted.']);
    }

    public function enrollStudent(Request $request, Section $section)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
        ]);

        $student = Student::findOrFail($validated['student_id']);

        // Check capacity
        if ($section->students()->count() >= $section->max_capacity) {
            return response()->json(['message' => 'Section is at maximum capacity.'], 422);
        }

        // Check if already enrolled in the same school year
        $existing = Enrollment::where('student_id', $student->id)
            ->where('school_year_id', $section->school_year_id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Student already enrolled for this school year.',
            ], 422);
        }

        DB::transaction(function () use ($student, $section, $request) {
            Enrollment::create([
                'student_id'     => $student->id,
                'school_year_id' => $section->school_year_id,
                'section_id'     => $section->id,
                'status'         => 'enrolled',
                'enrolled_at'    => now(),
                'enrolled_by'    => $request->user()->id,
            ]);

            // Attach to all classes in this section
            ClassRoom::where('section_id', $section->id)->each(function ($class) use ($student) {
                $class->students()->syncWithoutDetaching([
                    $student->id => ['status' => 'active', 'enrolled_at' => now()],
                ]);
            });
        });

        return response()->json(['message' => 'Student enrolled in section.'], 201);
    }

    public function removeStudent(Section $section, Student $student)
    {
        DB::transaction(function () use ($section, $student) {
            Enrollment::where('student_id', $student->id)
                ->where('section_id', $section->id)
                ->get()
                ->each
                ->delete();   // soft delete via model

            ClassRoom::where('section_id', $section->id)->each(function ($class) use ($student) {
                $class->students()->detach($student->id);
            });
        });

        return response()->json(['message' => 'Student removed from section.']);
    }
}