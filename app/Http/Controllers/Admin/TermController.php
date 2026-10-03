<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use App\Models\Term;
use App\Support\AuditContext;
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

        $schoolYear = SchoolYear::findOrFail($validated['school_year_id']);

        if ($validated['start_date'] < $schoolYear->start_date->toDateString()) {
            return response()->json([
                'message' => "Term start date cannot be earlier than the school year start ({$schoolYear->start_date->format('M d, Y')}).",
                'errors'  => ['start_date' => ["Must be on or after {$schoolYear->start_date->format('M d, Y')}."]],
            ], 422);
        }
        if ($validated['end_date'] > $schoolYear->end_date->toDateString()) {
            return response()->json([
                'message' => "Term end date cannot be later than the school year end ({$schoolYear->end_date->format('M d, Y')}).",
                'errors'  => ['end_date' => ["Must be on or before {$schoolYear->end_date->format('M d, Y')}."]],
            ], 422);
        }

        if ($this->overlapsExisting($schoolYear->id, $validated['start_date'], $validated['end_date'])) {
            return response()->json([
                'message' => 'This term overlaps an existing term for the same school year.',
                'errors'  => ['start_date' => ['Dates overlap with an existing term.']],
            ], 422);
        }

        $term = AuditContext::wrap('create_term', function () use ($validated) {
            if (! empty($validated['is_active'])) {
                Term::where('is_active', true)->get()
                    ->each(fn (Term $t) => $t->update(['is_active' => false]));
            }
            return Term::create($validated);
        });

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

        $syId       = $validated['school_year_id'] ?? $term->school_year_id;
        $schoolYear = SchoolYear::findOrFail($syId);

        $start = $validated['start_date'] ?? $term->start_date->toDateString();
        $end   = $validated['end_date']   ?? $term->end_date->toDateString();

        if ($start < $schoolYear->start_date->toDateString()) {
            return response()->json([
                'message' => "Term start date cannot be earlier than the school year start ({$schoolYear->start_date->format('M d, Y')}).",
                'errors'  => ['start_date' => ["Must be on or after {$schoolYear->start_date->format('M d, Y')}."]],
            ], 422);
        }
        if ($end > $schoolYear->end_date->toDateString()) {
            return response()->json([
                'message' => "Term end date cannot be later than the school year end ({$schoolYear->end_date->format('M d, Y')}).",
                'errors'  => ['end_date' => ["Must be on or before {$schoolYear->end_date->format('M d, Y')}."]],
            ], 422);
        }

        if ($this->overlapsExisting($schoolYear->id, $start, $end, $term->id)) {
            return response()->json([
                'message' => 'This term overlaps another term for the same school year.',
                'errors'  => ['start_date' => ['Dates overlap with another term.']],
            ], 422);
        }

        AuditContext::wrap('update_term', function () use ($term, $validated) {
            if (! empty($validated['is_active'])) {
                Term::where('id', '!=', $term->id)
                    ->where('is_active', true)
                    ->get()
                    ->each(fn (Term $t) => $t->update(['is_active' => false]));
            }
            $term->update($validated);
        });

        return response()->json(['term' => $term->fresh()]);
    }

    public function destroy(Term $term)
    {
        if ($term->classroom()->exists()) {
            return response()->json([
                'message' => 'Cannot delete: term has linked classes.',
            ], 422);
        }

        AuditContext::wrap('delete_term', function () use ($term) {
            $term->delete();
        });

        return response()->json(['message' => 'Term deleted.']);
    }

    public function activate(Term $term)
    {
        AuditContext::wrap('activate_term', function () use ($term) {
            Term::where('id', '!=', $term->id)
                ->where('is_active', true)
                ->get()
                ->each(fn (Term $t) => $t->update(['is_active' => false]));

            $term->update(['is_active' => true]);
        });

        return response()->json([
            'message' => 'Term activated.',
            'term'    => $term,
        ]);
    }

    protected function overlapsExisting(int $schoolYearId, string $start, string $end, ?int $ignoreTermId = null): bool
    {
        return Term::query()
            ->where('school_year_id', $schoolYearId)
            ->when($ignoreTermId, fn ($q) => $q->where('id', '!=', $ignoreTermId))
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end])
                  ->orWhere(function ($q3) use ($start, $end) {
                      $q3->where('start_date', '<=', $start)->where('end_date', '>=', $end);
                  });
            })
            ->exists();
    }
}