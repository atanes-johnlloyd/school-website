<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Strand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class StrandController extends Controller
{
    /**
     * Display a listing of strands.
     */
    public function index(Request $request): Response
    {
        $strands = Strand::withCount('subjects')
            ->when($request->filled('track_type'), function ($query) use ($request) {
                $query->where('track_type', $request->input('track_type'));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Strands/Index', [
            'strands' => $strands,
        ]);
    }

    /**
     * Store a newly created strand in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'        => ['required', 'string', 'max:20', 'unique:strands,code'],
            'name'        => ['required', 'string', 'max:255'],
            'track_type'  => ['required', 'string', 'max:50'],
            'track_id'    => ['nullable', 'exists:tracks,id'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ]);

        Strand::create($validated);

        return redirect()->back()->with('success', 'Academic strand registered successfully.');
    }

    /**
     * Update the specified strand in storage.
     */
    public function update(Request $request, Strand $strand)
    {
        $validated = $request->validate([
            'code'        => ['required', 'string', 'max:20', Rule::unique('strands', 'code')->ignore($strand->id)],
            'name'        => ['required', 'string', 'max:255'],
            'track_type'  => ['required', 'string', 'max:50'],
            'track_id'    => ['nullable', 'exists:tracks,id'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ]);

        $strand->update($validated);

        return redirect()->back()->with('success', 'Academic strand updated successfully.');
    }

    /**
     * Remove the specified strand from storage.
     */
    public function destroy(Strand $strand)
    {
        if ($strand->subjects()->exists() || $strand->sections()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete: strand has linked subjects or sections.');
        }

        $strand->delete();

        return redirect()->back()->with('success', 'Academic strand deleted successfully.');
    }
}