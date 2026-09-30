<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Strand;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class SubjectController extends Controller
{
    /**
     * Display a listing of curriculum subjects.
     */
    public function index(Request $request): Response
    {
        $subjects = Subject::with(['strand:id,code,name', 'prerequisite:id,code,name'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->strand_id, function ($query, $strandId) {
                $query->where('strand_id', $strandId);
            })
            ->when($request->subject_type, function ($query, $type) {
                $query->where('subject_type', $type);
            })
            ->orderBy('code')
            ->get();

        $strands = Strand::select('id', 'code', 'name')->orderBy('code')->get();
        $prerequisites = Subject::select('id', 'code', 'name')->orderBy('code')->get();

        return Inertia::render('Admin/Subjects/Index', [
            'subjects'      => $subjects,
            'strands'       => $strands,
            'prerequisites' => $prerequisites,
            'filters'       => $request->only(['search', 'strand_id', 'subject_type']),
        ]);
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'                    => ['required', 'string', 'max:20', 'unique:subjects,code'],
            'name'                    => ['required', 'string', 'max:255'],
            'strand_id'               => ['nullable', 'exists:strands,id'],
            'grade_level'             => ['required', 'in:11,12,both'],
            'hours'                   => ['required', 'integer', 'min:1', 'max:1000'],
            'is_core'                 => ['boolean'],
            'description'             => ['nullable', 'string'],
            'prerequisite_subject_id' => ['nullable', 'exists:subjects,id'],
            'is_active'               => ['boolean'],
        ]);

        Subject::create($validated);

        return redirect()->back()->with('success', 'Subject created successfully.');
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'code'                    => ['required', 'string', 'max:20', Rule::unique('subjects', 'code')->ignore($subject->id)],
            'name'                    => ['required', 'string', 'max:255'],
            'strand_id'               => ['nullable', 'exists:strands,id'],
            'grade_level'             => ['required', 'in:11,12,both'],
            'hours'                   => ['required', 'integer', 'min:1', 'max:1000'],
            'is_core'                 => ['boolean'],
            'description'             => ['nullable', 'string'],
            'prerequisite_subject_id' => ['nullable', 'exists:subjects,id'],
            'is_active'               => ['boolean'],
        ]);

        if (isset($validated['prerequisite_subject_id']) && $validated['prerequisite_subject_id'] == $subject->id) {
            return redirect()->back()->withErrors([
                'prerequisite_subject_id' => 'A subject cannot be its own prerequisite.',
            ]);
        }

        $subject->update($validated);

        return redirect()->back()->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy(Subject $subject)
    {
        if (method_exists($subject, 'classroom') && $subject->classroom()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete subject: linked active classes exist.');
        }

        if (method_exists($subject, 'sections') && $subject->sections()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete subject: linked section assignments exist.');
        }

        $subject->delete();

        return redirect()->back()->with('success', 'Subject deleted successfully.');
    }
}