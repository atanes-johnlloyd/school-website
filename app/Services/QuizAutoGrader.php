<?php

namespace App\Services;

use App\Models\QuizAttempt;

class QuizAutoGrader
{
    public const MAX_WARNINGS = 3;

    /**
     * Auto-grade an attempt on submit. Marks MC/TF answers correct/incorrect,
     * leaves essays pending. Sets attempt score and status.
     */
    public function grade(QuizAttempt $attempt): void
    {
        $attempt->load(['answers.question.options', 'answers.option', 'quiz.questions']);

        $totalPoints = 0.0;

        foreach ($attempt->answers as $answer) {
            $question = $answer->question;
            if (! $question) continue;

            $questionPoints = $this->pointsForQuestion($attempt, $question);

            switch ($question->type) {
                case 'multiple_choice':
                case 'true_false':
                    $chosen = $answer->option;
                    $isCorrect = $chosen && $chosen->is_correct;
                    $awarded = $isCorrect ? $questionPoints : 0;

                    $answer->update([
                        'is_correct'     => $isCorrect,
                        'points_awarded' => $awarded,
                    ]);

                    $totalPoints += $awarded;
                    break;

                case 'essay':
                    // Left for manual grading; points_awarded stays null
                    break;
            }
        }

        $hasPendingEssays = $attempt->answers()
            ->whereHas('question', fn ($q) => $q->where('type', 'essay'))
            ->whereNull('points_awarded')
            ->exists();

        $attempt->update([
            'score'  => $totalPoints,
            'status' => $hasPendingEssays ? 'submitted' : 'graded',
        ]);
    }

    /**
     * Get points for a question in this attempt, respecting pivot override.
     */
    protected function pointsForQuestion(QuizAttempt $attempt, $question): float
    {
        $pivot = $attempt->quiz->questions
            ->firstWhere('id', $question->id)?->pivot;

        return (float) ($pivot?->points_override ?? $question->points ?? 1);
    }

    /**
     * Submit an attempt (used for auto-submit on expiry / warnings).
     * Idempotent — won't re-run if already submitted.
     */
    public function submit(QuizAttempt $attempt, string $reason = 'manual'): QuizAttempt
    {
        $attempt->refresh();

        if ($attempt->submitted_at) {
            return $attempt;
        }

        $attempt->update([
            'submitted_at' => now(),
        ]);

        $this->grade($attempt);

        return $attempt->fresh();
    }
}