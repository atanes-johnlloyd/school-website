<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class QuizSubmissionController extends Controller
{
    /**
     * List all student attempts for a quiz.
     */
    public function index(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->classroom->isTaughtBy($request->user()), 403);

        $totalPoints = $quiz->questions()->sum('points');

        $attempts = $quiz->attempts()
            ->with('student.user:id,name')
            ->orderByDesc('submitted_at')
            ->get()
            ->map(fn (QuizAttempt $a) => [
                'id'                => $a->id,
                'student_id'        => $a->student_id,
                'student_name'      => $a->student?->user?->name,
                'attempt_number'    => $a->attempt_number,
                'started_at'        => $a->started_at?->toIso8601String(),
                'submitted_at'      => $a->submitted_at?->toIso8601String(),
                'score'             => $a->score,
                'total_points'      => $totalPoints,
                'status'            => $a->status,
                'warning_count'     => $a->warning_count,
                'has_pending_essay' => $a->answers()
                    ->whereHas('question', fn ($q) => $q->where('type', 'essay'))
                    ->whereNull('points_awarded')
                    ->exists(),
            ]);

        $payload = [
            'quiz' => [
                'id'            => $quiz->id,
                'title'         => $quiz->title,
                'subject'       => $quiz->classroom?->subject?->name,
                'section'       => $quiz->classroom?->section?->name,
                'total_points'  => $totalPoints,
                'passing_score' => $quiz->passing_score,
            ],
            'attempts' => $attempts,
            'stats'    => [
                'total_students' => $quiz->classroom->students()->count(),
                'attempted'      => $attempts->count(),
                'passed'         => $attempts
                    ->filter(fn ($a) => $a['score'] !== null && $a['score'] >= ($quiz->passing_score ?? 0))
                    ->count(),
                'average_score'  => $attempts->whereNotNull('score')->avg('score'),
                'pending_essays' => $attempts->where('has_pending_essay', true)->count(),
            ],
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Teacher/Quizzes/Submissions', $payload);
    }

    /**
     * View one student's attempt — every answer + essay grading UI.
     */
    public function show(Request $request, QuizAttempt $attempt)
    {
        abort_unless($attempt->quiz->classroom->isTaughtBy($request->user()), 403);

        $attempt->load(['student.user:id,name', 'quiz']);

        $answers = $attempt->answers()
            ->with(['question.options', 'option'])
            ->get()
            ->map(fn (QuizAnswer $a) => [
                'id'                    => $a->id,
                'question_id'           => $a->question_id,
                'question_text'         => $a->question?->question_text,
                'question_type'         => $a->question?->type,
                'question_points'       => $a->question?->points,
                'points_override'       => $a->question?->pivot?->points_override,
                'explanation'           => $a->question?->explanation,
                'answer_text'           => $a->answer_text,
                'chosen_option_id'      => $a->question_option_id,
                'chosen_option_text'    => $a->option?->option_text,
                'correct_option_id'     => $a->question?->options->where('is_correct', true)->first()?->id,
                'correct_option_text'   => $a->question?->options->where('is_correct', true)->first()?->option_text,
                'is_correct'            => $a->is_correct,
                'points_awarded'        => $a->points_awarded,
                'needs_grading'         => $a->question?->type === 'essay' && $a->points_awarded === null,
            ]);

        $payload = [
            'attempt' => [
                'id'            => $attempt->id,
                'student_name'  => $attempt->student?->user?->name,
                'student_lrn'   => $attempt->student?->lrn,
                'score'         => $attempt->score,
                'status'        => $attempt->status,
                'started_at'    => $attempt->started_at?->toIso8601String(),
                'submitted_at'  => $attempt->submitted_at?->toIso8601String(),
                'warning_count' => $attempt->warning_count,
                'quiz_title'    => $attempt->quiz?->title,
            ],
            'answers' => $answers,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Teacher/Quizzes/AttemptReview', $payload);
    }

    /**
     * Manually grade an essay answer.
     */
    public function gradeAnswer(Request $request, QuizAnswer $answer)
    {
        abort_unless($answer->attempt->quiz->classroom->isTaughtBy($request->user()), 403);

        $max = $answer->question?->pivot?->points_override
            ?? $answer->question?->points
            ?? 10;

        $validated = $request->validate([
            'points_awarded' => ['required', 'numeric', 'min:0', 'max:' . $max],
        ]);

        DB::transaction(function () use ($answer, $validated) {
            $answer->update([
                'points_awarded' => $validated['points_awarded'],
                'is_correct'     => $validated['points_awarded'] >= ($answer->question->points / 2),
            ]);

            $attempt = $answer->attempt;

            // Sum all awarded points
            $total = $attempt->answers()->sum('points_awarded');
            $attempt->update(['score' => $total]);

            // If no pending essays remain, flip status
            $pending = $attempt->answers()
                ->whereHas('question', fn ($q) => $q->where('type', 'essay'))
                ->whereNull('points_awarded')
                ->exists();

            if (! $pending) {
                $attempt->update(['status' => 'graded']);
            }
        });

        return response()->json([
            'message'  => 'Answer graded.',
            'answer'   => $answer->fresh(),
            'attempt'  => $answer->attempt->fresh(),
        ]);
    }

    /**
     * Bulk-grade multiple essay answers at once.
     */
    public function bulkGrade(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->classroom->isTaughtBy($request->user()), 403);

        $validated = $request->validate([
            'grades'                => ['required', 'array', 'min:1'],
            'grades.*.answer_id'    => ['required', 'integer', 'exists:quiz_answers,id'],
            'grades.*.points_awarded' => ['required', 'numeric', 'min:0'],
        ]);

        // Verify every answer belongs to this quiz
        $answerIds = collect($validated['grades'])->pluck('answer_id');
        $validCount = QuizAnswer::whereIn('id', $answerIds)
            ->whereHas('attempt', fn ($q) => $q->where('quiz_id', $quiz->id))
            ->count();

        if ($validCount !== $answerIds->count()) {
            return response()->json([
                'message' => 'One or more answers do not belong to this quiz.',
            ], 403);
        }

        DB::transaction(function () use ($validated) {
            foreach ($validated['grades'] as $g) {
                QuizAnswer::where('id', $g['answer_id'])->update([
                    'points_awarded' => $g['points_awarded'],
                ]);
            }

            // Recompute affected attempts
            $attemptIds = QuizAnswer::whereIn('id', $answerIds = collect($validated['grades'])->pluck('answer_id'))
                ->pluck('quiz_attempt_id')
                ->unique();

            foreach ($attemptIds as $aid) {
                $attempt = QuizAttempt::find($aid);
                $attempt->update(['score' => $attempt->answers()->sum('points_awarded')]);

                $pending = $attempt->answers()
                    ->whereHas('question', fn ($q) => $q->where('type', 'essay'))
                    ->whereNull('points_awarded')
                    ->exists();

                if (! $pending) {
                    $attempt->update(['status' => 'graded']);
                }
            }
        });

        return response()->json(['message' => 'Grades saved.']);
    }
}