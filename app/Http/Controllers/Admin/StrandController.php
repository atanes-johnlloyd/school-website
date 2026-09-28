<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Strand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Strand::with('track:id,code,name');

        if ($request->filled('track_id')) {
            $query->where('track_id', $request->input('track_id'));
        }

        return response()->json(['strands' => $query->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'track_id'    => ['required', 'exists:tracks,id'],
            'code'        => ['required', 'string', 'max:20', 'unique:strands,code'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ]);

        return response()->json(['strand' => Strand::create($validated)], 201);
    }

    public function show(Strand $strand)
    {
        return response()->json(['strand' => $strand->load(['track', 'subjects'])]);
    }

    public function update(Request $request, Strand $strand)
    {
        $validated = $request->validate([
            'track_id'    => ['sometimes', 'exists:tracks,id'],
            'code'        => ['sometimes', 'string', 'max:20',
                             Rule::unique('strands', 'code')->ignore($strand->id)],
            'name'        => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ]);

        $strand->update($validated);

        return response()->json(['strand' => $strand->fresh()]);
    }

    public function destroy(Strand $strand)
    {
        if ($strand->subjects()->exists() || $strand->sections()->exists()) {
            return response()->json([
                'message' => 'Cannot delete: strand has linked subjects or sections.',
            ], 422);
        }

        $strand->delete();

        return response()->json(['message' => 'Strand deleted.']);
    }
}