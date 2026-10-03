<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Track;
use App\Support\AuditContext;
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

        $track = AuditContext::wrap('create_track', function () use ($validated) {
            return Track::create($validated);
        });

        return response()->json(['track' => $track], 201);
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

        AuditContext::wrap('update_track', function () use ($track, $validated) {
            $track->update($validated);
        });

        return response()->json(['track' => $track->fresh()]);
    }

    public function destroy(Track $track)
    {
        if ($track->strands()->exists()) {
            return response()->json([
                'message' => 'Cannot delete: track has linked strands.',
            ], 422);
        }

        AuditContext::wrap('delete_track', function () use ($track) {
            $track->delete();
        });

        return response()->json(['message' => 'Track deleted.']);
    }
}