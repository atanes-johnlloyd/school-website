<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index(Request $request)
    {
        $query = Term::with('schoolYear:id,label');

        if ($request->filled('school_year_id')) {
            $query->where('school_year_id', $request->input('school_year_id'));
        }

        return response()->json(['terms' => $query->orderByDesc('start_date')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_year_id' => ['required', 'exists:school_years,id'],
            'name'           => ['required', 'string', 'max:50'],
            'start_date'     => ['required', 'date'],
            'end_date'       => ['required', 'date', 'after:start_date'],
            'is_active'      => ['boolean'],
        ]);

        if (! empty($validated['is_active'])) {
            Term::query()->update(['is_active' => false]);
        }

        $term = Term::create($validated);

        return response()->json(['term' => $term], 201);
    }

    public function show(Term $term)
    {
        return response()->json(['term' => $term->load('schoolYear')]);
    }

    public function update(Request $request, Term $term)
    {
        $validated = $request->validate([
            'school_year_id' => ['sometimes', 'exists:school_years,id'],
            'name'           => ['sometimes', 'string', 'max:50'],
            'start_date'     => ['sometimes', 'date'],
            'end_date'       => ['sometimes', 'date', 'after:start_date'],
            'is_active'      => ['boolean'],
        ]);

        if (! empty($validated['is_active'])) {
            Term::where('id', '!=', $term->id)->update(['is_active' => false]);
        }

        $term->update($validated);

        return response()->json(['term' => $term->fresh()]);
    }

    public function destroy(Term $term)
    {
        if ($term->classroom()->exists()) {
            return response()->json([
                'message' => 'Cannot delete: term has linked classes.',
            ], 422);
        }

        $term->delete();

        return response()->json(['message' => 'Term deleted.']);
    }
}
