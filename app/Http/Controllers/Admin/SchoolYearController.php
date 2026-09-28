<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolYearController extends Controller
{
    public function index(Request $request)
    {
        $years = SchoolYear::orderByDesc('label')->get();

        return response()->json(['school_years' => $years]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label'      => ['required', 'string', 'max:20', 'unique:school_years,label'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
            'is_active'  => ['boolean'],
        ]);

        // If this one is active, deactivate others
        if (! empty($validated['is_active'])) {
            SchoolYear::query()->update(['is_active' => false]);
        }

        $year = SchoolYear::create($validated);

        return response()->json(['school_year' => $year], 201);
    }

    public function show(SchoolYear $schoolYear)
    {
        return response()->json([
            'school_year' => $schoolYear->load('terms'),
        ]);
    }

    public function update(Request $request, SchoolYear $schoolYear)
    {
        $validated = $request->validate([
            'label'      => ['sometimes', 'string', 'max:20',
                             Rule::unique('school_years', 'label')->ignore($schoolYear->id)],
            'start_date' => ['sometimes', 'date'],
            'end_date'   => ['sometimes', 'date', 'after:start_date'],
            'is_active'  => ['boolean'],
        ]);

        if (! empty($validated['is_active'])) {
            SchoolYear::where('id', '!=', $schoolYear->id)->update(['is_active' => false]);
        }

        $schoolYear->update($validated);

        return response()->json(['school_year' => $schoolYear->fresh()]);
    }

    public function destroy(SchoolYear $schoolYear)
    {
        // Block if it has terms, sections, or enrollments
        if ($schoolYear->terms()->exists()
            || $schoolYear->sections()->exists()
            || $schoolYear->enrollments()->exists()) {
            return response()->json([
                'message' => 'Cannot delete: school year has linked terms, sections, or enrollments.',
            ], 422);
        }

        $schoolYear->delete();

        return response()->json(['message' => 'School year deleted.']);
    }

    public function activate(SchoolYear $schoolYear)
    {
        SchoolYear::query()->update(['is_active' => false]);
        $schoolYear->update(['is_active' => true]);

        return response()->json(['message' => 'Activated.', 'school_year' => $schoolYear]);
    }
}