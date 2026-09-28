<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TrackController extends Controller
{
    public function index()
    {
        return response()->json(['tracks' => Track::withCount('strands')->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'        => ['required', 'string', 'max:10', 'unique:tracks,code'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ]);

        return response()->json(['track' => Track::create($validated)], 201);
    }

    public function show(Track $track)
    {
        return response()->json(['track' => $track->load('strands')]);
    }

    public function update(Request $request, Track $track)
    {
        $validated = $request->validate([
            'code'        => ['sometimes', 'string', 'max:10',
                             Rule::unique('tracks', 'code')->ignore($track->id)],
            'name'        => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ]);

        $track->update($validated);

        return response()->json(['track' => $track->fresh()]);
    }

    public function destroy(Track $track)
    {
        if ($track->strands()->exists()) {
            return response()->json([
                'message' => 'Cannot delete: track has linked strands.',
            ], 422);
        }

        $track->delete();

        return response()->json(['message' => 'Track deleted.']);
    }
}