<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class SchoolYearController extends Controller
{
    /* ═══════════════ INDEX — page shell with nested data ═══════════════ */
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/SchoolYears/Index', [
            'schoolYears' => SchoolYear::with(['terms' => fn ($q) => $q->orderBy('start_date')])
                ->withCount(['sections', 'enrollments'])
                ->orderByDesc('start_date')
                ->get(),
        ]);
    }

    /* ═══════════════ LIST — JSON endpoint (kept for API parity) ═══════════════ */
    public function list(Request $request)
    {
        $validated = $request->validate([
            'status'   => ['nullable', 'in:active,inactive'],
            'search'   => ['nullable', 'string', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:10', 'max:100'],
            'sort_by'  => ['nullable', 'in:label,start_date,end_date,is_active'],
            'sort_dir' => ['nullable', 'in:asc,desc'],
        ]);

        $sortBy  = $validated['sort_by']  ?? 'start_date';
        $sortDir = $validated['sort_dir'] ?? 'desc';

        $query = SchoolYear::query()
            ->with(['terms' => fn ($q) => $q->orderBy('start_date')])
            ->withCount(['sections', 'enrollments']);

        if (! empty($validated['status'])) {
            $query->where('is_active', $validated['status'] === 'active');
        }
        if (! empty($validated['search'])) {
            $query->where('label', 'like', '%' . $validated['search'] . '%');
        }

        $query->orderBy($sortBy, $sortDir);

        $years = $query->paginate($validated['per_page'] ?? 10);

        return response()->json([
            'schoolYears' => $years,
            'counts' => [
                'total'  => SchoolYear::count(),
                'active' => SchoolYear::where('is_active', true)->count(),
                'terms'  => \App\Models\Term::count(),
            ],
        ]);
    }

    /* ═══════════════ STORE ═══════════════ */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label'      => ['required', 'string', 'max:20', 'unique:school_years,label'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
            'is_active'  => ['boolean'],
        ]);

        if (! empty($validated['is_active'])) {
            SchoolYear::query()->update(['is_active' => false]);
        }

        $year = SchoolYear::create($validated);

        return response()->json(['school_year' => $year], 201);
    }

    /* ═══════════════ SHOW ═══════════════ */
    public function show(SchoolYear $schoolYear)
    {
        return response()->json([
            'school_year' => $schoolYear->load('terms'),
        ]);
    }

    /* ═══════════════ UPDATE ═══════════════ */
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

    /* ═══════════════ DESTROY ═══════════════ */
    public function destroy(SchoolYear $schoolYear)
    {
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

    /* ═══════════════ ACTIVATE ═══════════════ */
    public function activate(SchoolYear $schoolYear)
    {
        SchoolYear::query()->update(['is_active' => false]);
        $schoolYear->update(['is_active' => true]);

        return response()->json([
            'message' => 'Activated.',
            'school_year' => $schoolYear,
        ]);
    }

    /* ═══════════════ SET ACTIVE (legacy alias) ═══════════════ */
    public function setActive(SchoolYear $schoolYear)
    {
        return $this->activate($schoolYear);
    }
}