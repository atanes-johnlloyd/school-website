<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Strand;
use App\Support\AuditContext;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class StrandController extends Controller
{
    public function index(Request $request): Response
    {
        $strands = Strand::withCount('subjects')
            ->when($request->filled('track_type'), fn ($query) => $query->where('track_type', $request->input('track_type')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Strands/Index', ['strands' => $strands]);
    }

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

        AuditContext::wrap('create_strand', function () use ($validated) {
            Strand::create($validated);
        });

        return redirect()->back()->with('success', 'Academic strand registered successfully.');
    }

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

        AuditContext::wrap('update_strand', function () use ($strand, $validated) {
            $strand->update($validated);
        });

        return redirect()->back()->with('success', 'Academic strand updated successfully.');
    }

    public function destroy(Strand $strand)
    {
        if ($strand->subjects()->exists() || $strand->sections()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete: strand has linked subjects or sections.');
        }

        AuditContext::wrap('delete_strand', function () use ($strand) {
            $strand->delete();
        });

        return redirect()->back()->with('success', 'Academic strand deleted successfully.');
    }
}