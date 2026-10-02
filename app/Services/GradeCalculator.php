<?php

namespace App\Services;

use App\Models\ClassRoom;
use App\Models\Student;

class GradeCalculator
{
    /**
     * Compute category averages and the weighted final grade for a
     * student in a class. Includes BOTH published assignments and
     * published quizzes, bucketed by their `category` column.
     */
    public function compute(Student $student, ClassRoom $classroom): array
    {
        $buckets = [
            'written_work'     => ['earned' => 0.0, 'possible' => 0.0],
            'performance_task' => ['earned' => 0.0, 'possible' => 0.0],
            'quarterly_exam'   => ['earned' => 0.0, 'possible' => 0.0],
        ];

        // ─── 1. Assignments ─────────────────────────────
        $assignments = $classroom->assignments()
            ->where('is_published', true)
            ->with(['submissions' => function ($q) use ($student) {
                $q->where('student_id', $student->id)
                  ->whereNotNull('graded_at');
            }])
            ->get();

        $grades = [];   // assignment_id => { grade, points }

        foreach ($assignments as $a) {
            $sub = $a->submissions->first();
            if (! $sub || $sub->grade === null) {
                continue;
            }

            $cat = $a->category ?: 'written_work';
            $buckets[$cat]['earned']   += (float) $sub->grade;
            $buckets[$cat]['possible'] += (float) $a->points;

            $grades[$a->id] = [
                'grade'  => (float) $sub->grade,
                'points' => (float) $a->points,
            ];
        }

        // ─── 2. Quizzes ─────────────────────────────────
        // For each published quiz, use the student's BEST graded attempt
        // (highest raw score). Points possible respects any per-question
        // pivot override set by the teacher.
        $quizzes = $classroom->quizzes()
            ->where('is_published', true)
            ->with('questions')
            ->get();

        $quizGrades = [];   // quiz_id => { grade, points, attempt_id }

        foreach ($quizzes as $quiz) {
            $bestAttempt = $quiz->attempts()
                ->where('student_id', $student->id)
                ->whereNotNull('submitted_at')
                ->whereNotNull('score')
                ->orderByDesc('score')
                ->first();

            if (! $bestAttempt) {
                continue;
            }

            $totalPoints = (float) $quiz->questions->sum(function ($q) {
                return $q->pivot->points_override ?? $q->points;
            });

            if ($totalPoints <= 0) {
                continue;
            }

            $cat = $quiz->category ?: 'written_work';
            $buckets[$cat]['earned']   += (float) $bestAttempt->score;
            $buckets[$cat]['possible'] += $totalPoints;

            $quizGrades[$quiz->id] = [
                'grade'      => (float) $bestAttempt->score,
                'points'     => $totalPoints,
                'attempt_id' => $bestAttempt->id,
            ];
        }

        // ─── 3. Category averages (0–100 or null) ───────
        $averages = [];
        foreach ($buckets as $key => $data) {
            $averages[$key] = $data['possible'] > 0
                ? round(($data['earned'] / $data['possible']) * 100, 2)
                : null;
        }

        $weights = [
            'written_work'     => (float) $classroom->weight_written_work,
            'performance_task' => (float) $classroom->weight_performance_task,
            'quarterly_exam'   => (float) $classroom->weight_quarterly_exam,
        ];

        // Weighted final — only counts categories that have data
        $weightedSum = 0.0;
        $totalWeight = 0.0;
        foreach ($averages as $key => $avg) {
            if ($avg !== null) {
                $weightedSum += $avg * $weights[$key];
                $totalWeight += $weights[$key];
            }
        }

        $final = $totalWeight > 0
            ? round($weightedSum / $totalWeight, 2)
            : null;

        $isComplete = ! in_array(null, array_values($averages), true);

        return [
            'written_work'     => $averages['written_work'],
            'performance_task' => $averages['performance_task'],
            'quarterly_exam'   => $averages['quarterly_exam'],
            'final_grade'      => $final,
            'remarks'          => $isComplete ? $this->remarksFor($final) : null,
            'is_complete'      => $isComplete,
            'weights'          => $weights,
            'grades'           => $grades,       // assignments only (unchanged contract)
            'quiz_grades'      => $quizGrades,   // quizzes only (new)
        ];
    }

    protected function remarksFor(?float $final): ?string
    {
        if ($final === null) return null;
        if ($final >= 90) return 'outstanding';
        if ($final >= 85) return 'very_satisfactory';
        if ($final >= 80) return 'satisfactory';
        if ($final >= 75) return 'fairly_satisfactory';
        return 'did_not_meet_expectations';
    }
}