<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::with('strand:id,code,name');

        if ($request->filled('strand_id')) {
            $query->where('strand_id', $request->input('strand_id'));
        }
        if ($request->filled('grade_level')) {
            $query->whereIn('grade_level', [$request->input('grade_level'), 'both']);
        }
        if ($request->filled('is_core')) {
            $query->where('is_core', filter_var($request->input('is_core'), FILTER_VALIDATE_BOOLEAN));
        }

        return response()->json(['subjects' => $query->orderBy('code')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'                    => ['required', 'string', 'max:20', 'unique:subjects,code'],
            'name'                    => ['required', 'string', 'max:255'],
            'description'             => ['nullable', 'string'],
            'strand_id'               => ['nullable', 'exists:strands,id'],
            'grade_level'             => ['required', 'in:11,12,both'],
            'is_core'                 => ['boolean'],
            'hours'                   => ['nullable', 'integer', 'min:1', 'max:1000'],
            'prerequisite_subject_id' => ['nullable', 'exists:subjects,id'],
            'is_active'               => ['boolean'],
        ]);

        return response()->json(['subject' => Subject::create($validated)], 201);
    }

    public function show(Subject $subject)
    {
        return response()->json([
            'subject' => $subject->load(['strand', 'prerequisite']),
        ]);
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'code'                    => ['sometimes', 'string', 'max:20',
                                         Rule::unique('subjects', 'code')->ignore($subject->id)],
            'name'                    => ['sometimes', 'string', 'max:255'],
            'description'             => ['nullable', 'string'],
            'strand_id'               => ['nullable', 'exists:strands,id'],
            'grade_level'             => ['sometimes', 'in:11,12,both'],
            'is_core'                 => ['boolean'],
            'hours'                   => ['nullable', 'integer', 'min:1', 'max:1000'],
            'prerequisite_subject_id' => ['nullable', 'exists:subjects,id'],
            'is_active'               => ['boolean'],
        ]);

        // Prevent self-reference
        if (isset($validated['prerequisite_subject_id'])
            && $validated['prerequisite_subject_id'] == $subject->id) {
            return response()->json(['message' => 'A subject cannot be its own prerequisite.'], 422);
        }

        $subject->update($validated);

        return response()->json(['subject' => $subject->fresh()]);
    }

    public function destroy(Subject $subject)
    {
        if ($subject->classroom()->exists()) {
            return response()->json([
                'message' => 'Cannot delete: subject is used in classes.',
            ], 422);
        }

        $subject->delete();

        return response()->json(['message' => 'Subject deleted.']);
    }
}
