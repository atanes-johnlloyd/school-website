<?php

namespace App\Services;

use App\Models\ClassRoom;
use App\Models\Student;

class GradeCalculator
{
    /**
     * Compute the category averages and weighted final grade
     * for a student in a specific class.
     */
    public function compute(Student $student, ClassRoom $classroom): array
    {
        $assignments = $classroom->assignments()
            ->where('is_published', true)
            ->with(['submissions' => function ($q) use ($student) {
                $q->where('student_id', $student->id)
                  ->whereNotNull('graded_at');
            }])
            ->get();

        $buckets = [
            'written_work'     => ['earned' => 0.0, 'possible' => 0.0],
            'performance_task' => ['earned' => 0.0, 'possible' => 0.0],
            'quarterly_exam'   => ['earned' => 0.0, 'possible' => 0.0],
        ];

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

        // Category averages (0-100) or null if nothing graded
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

        // Complete means every category has at least one graded item
        $isComplete = ! in_array(null, array_values($averages), true);

        return [
            'written_work'     => $averages['written_work'],
            'performance_task' => $averages['performance_task'],
            'quarterly_exam'   => $averages['quarterly_exam'],
            'final_grade'      => $final,
            'remarks'          => $isComplete ? $this->remarksFor($final) : null,
            'is_complete'      => $isComplete,
            'weights'          => $weights,
            'grades'           => $grades,   // assignment_id => { grade, points }
        ];
    }

    /**
     * DepEd uses 75 as the passing threshold.
     */
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