<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index(Request $request, Assignment $assignment)
    {
        abort_unless($assignment->classroom->isTaughtBy($request->user()), 403);

        $submissions = $assignment->submissions()
            ->with('student.user:id,name')
            ->orderBy('submitted_at')
            ->get();

        return response()->json([
            'assignment'  => ['id' => $assignment->id, 'title' => $assignment->title],
            'submissions' => $submissions,
        ]);
    }

    public function grade(Request $request, \App\Models\AssignmentSubmission $submission)
    {
        abort_unless($submission->assignment->classroom->isTaughtBy($request->user()), 403);

        $validated = $request->validate([
            'grade'    => ['required', 'numeric', 'min:0', 'max:' . $submission->assignment->points],
            'feedback' => ['nullable', 'string', 'max:5000'],
        ]);

        $submission->update([
            'grade'     => $validated['grade'],
            'feedback'  => $validated['feedback'] ?? null,
            'graded_by' => $request->user()->id,
            'graded_at' => now(),
            'status'    => 'graded',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message'    => 'Submission graded.',
                'submission' => $submission->fresh(),
            ]);
        }

        return back()->with('success', 'Grade saved.');
    }
}