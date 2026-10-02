<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\SubmitAssignmentRequest;
use App\Models\Assignment;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class AssignmentController extends Controller
{
    public function index(Request $request, ClassRoom $classroom)
    {
        abort_unless($classroom->hasStudent($request->user()), 403);

        $studentId = $request->user()->student->id;

        $assignments = $classroom->assignments()
            ->published()
            ->with(['submissions' => fn ($q) => $q->where('student_id', $studentId)])
            ->orderBy('due_at')
            ->get()
            ->map(function (Assignment $a) {
                $sub = $a->submissions->first();
                return [
                    'id'         => $a->id,
                    'title'      => $a->title,
                    'due_at'     => $a->due_at?->toIso8601String(),
                    'points'     => $a->points,
                    'allow_late' => $a->allow_late,
                    'submission' => $sub ? [
                        'id'           => $sub->id,
                        'status'       => $sub->status,
                        'grade'        => $sub->grade,
                        'submitted_at' => $sub->submitted_at?->toIso8601String(),
                        'has_file'    => (bool) $sub->file_path,
                        'download_url' => $sub->file_path
                            ? route('student.assignments.submission.download', $assignment->id)
                            : null,
                    ] : null,
                ];
            });

        $payload = [
            'classroom'   => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'section' => $classroom->section?->name,
                'teacher' => $classroom->teacher?->user?->name,
            ],
            'assignments' => $assignments,
        ];

        if ($request->wantsJson()) {
            return response()->json($payload);
        }

        return Inertia::render('Student/Assessments/Index', $payload);
    }

    public function show(Request $request, Assignment $assignment)
    {
        abort_unless($assignment->classroom->hasStudent($request->user()), 403);
        abort_unless($assignment->is_published, 404);

        $studentId  = $request->user()->student->id;
        $submission = $assignment->submissionFor($studentId);

        $payload = [
            'assignment' => [
                'id'            => $assignment->id,
                'title'         => $assignment->title,
                'instructions'  => $assignment->instructions,
                'due_at'        => $assignment->due_at?->toIso8601String(),
                'points'        => $assignment->points,
                'allow_late'    => $assignment->allow_late,
                'category'      => $assignment->category,
                'classroom_id'  => $assignment->class_id,
                'subject'       => $assignment->classroom?->subject?->name,
                'section'       => $assignment->classroom?->section?->name,
                'teacher'       => $assignment->classroom?->teacher?->user?->name,
            ],
            'submission' => $submission ? [
                'id'           => $submission->id,
                'text_content' => $submission->text_content,
                'status'       => $submission->status,
                'submitted_at' => $submission->submitted_at?->toIso8601String(),
                'grade'        => $submission->grade,
                'feedback'     => $submission->feedback,
                'graded_at'    => $submission->graded_at?->toIso8601String(),
                'has_file'     => (bool) $submission->file_path,
                'download_url' => $submission->file_path
                    ? route('student.assignments.submission.download', $assignment->id)
                    : null,
            ] : null,
        ];

        if ($request->wantsJson()) {
            return response()->json($payload);
        }

        return Inertia::render('Student/Assessments/Show', $payload);
    }

    public function submit(SubmitAssignmentRequest $request, Assignment $assignment)
    {
        abort_unless($assignment->classroom->hasStudent($request->user()), 403);
        abort_unless($assignment->is_published, 404);

        $studentId = $request->user()->student->id;
        $existing  = $assignment->submissionFor($studentId);

        if ($existing && $existing->status === 'graded') {
            return $request->wantsJson()
                ? response()->json(['message' => 'Already graded — cannot resubmit.'], 422)
                : back()->with('error', 'Already graded — cannot resubmit.');
        }

        $isLate = $assignment->due_at && now()->greaterThan($assignment->due_at);
        if ($isLate && ! $assignment->allow_late) {
            return $request->wantsJson()
                ? response()->json(['message' => 'Late submissions are not allowed.'], 422)
                : back()->with('error', 'Late submissions are not allowed.');
        }

        $filePath = $existing?->file_path;   // keep old file unless a new one is uploaded

        if ($request->hasFile('file')) {
            // Delete old file if replacing
            if ($filePath && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            $file = $request->file('file');
            // Store under submissions/{class_id}/{assignment_id}/{uuid}.{ext}
            $filePath = $file->storeAs(
                "submissions/{$assignment->class_id}/{$assignment->id}",
                Str::uuid() . '.' . $file->getClientOriginalExtension(),
                'local'   // storage/app (private)
            );
        }

        $data = [
            'text_content' => $request->validated('text_content'),
            'file_path'    => $filePath,
            'submitted_at' => now(),
            'status'       => $isLate ? 'late' : 'submitted',
        ];

        if ($existing) {
            $existing->update($data);
            $submission = $existing;
        } else {
            $submission = $assignment->submissions()->create(array_merge($data, [
                'student_id' => $studentId,
            ]));
        }

        return $request->wantsJson()
            ? response()->json([
                'message'    => $isLate ? 'Submitted (late).' : 'Submitted.',
                'submission' => $submission->fresh(),
            ], 201)
            : redirect()->route('student.assignments.show', $assignment->id)
                        ->with('success', $isLate ? 'Submitted (late).' : 'Submitted.');
    }

    public function downloadSubmission(Request $request, Assignment $assignment)
    {
        abort_unless($assignment->classroom->hasStudent($request->user()), 403);
        $studentId  = $request->user()->student->id;
        $submission = $assignment->submissionFor($studentId);
        abort_unless($submission?->file_path && Storage::exists($submission->file_path), 404);

        return Storage::download($submission->file_path);
    }
}