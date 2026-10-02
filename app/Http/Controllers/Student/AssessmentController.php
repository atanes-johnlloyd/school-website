<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        abort_unless($student, 403);

        $activeTerm = Term::where('is_active', true)->first();

        $classroomIds = $student->classroom()
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
            ->pluck('classes.id');

        // ─── Assignments ────────────────────────────────
        $assignments = Assignment::query()
            ->whereIn('class_id', $classroomIds)
            ->published()
            ->with([
                'classroom.subject:id,name,code',
                'classroom.section:id,name',
                'classroom.teacher.user:id,name',
                'submissions' => fn ($q) => $q->where('student_id', $student->id),
            ])
            ->orderBy('due_at')
            ->get()
            ->map(function (Assignment $a) {
                $sub = $a->submissions->first();
                return [
                    'id'           => 'a-' . $a->id,
                    'raw_id'       => $a->id,
                    'type'         => 'assignment',
                    'title'        => $a->title,
                    'description'  => \Str::limit(strip_tags($a->instructions ?? ''), 220),
                    'category'     => $a->category,
                    'subject'      => $a->classroom?->subject?->name,
                    'subject_code' => $a->classroom?->subject?->code,
                    'section'      => $a->classroom?->section?->name,
                    'instructor'   => $a->classroom?->teacher?->user?->name,
                    'due_at'       => $a->due_at?->toIso8601String(),
                    'points'       => (float) $a->points,
                    'allow_late'   => $a->allow_late,
                    'status'       => $this->statusFor($sub),
                    'days_left'    => $a->due_at ? (int) now()->diffInDays($a->due_at, false) : null,
                    'submission'   => $sub ? [
                        'id'           => $sub->id,
                        'status'       => $sub->status,
                        'grade'        => $sub->grade,
                        'feedback'     => $sub->feedback,
                        'text_content' => $sub->text_content,
                        'submitted_at' => $sub->submitted_at?->toIso8601String(),
                        'graded_at'    => $sub->graded_at?->toIso8601String(),
                        'has_file'     => (bool) $sub->file_path,
                        'download_url' => $sub->file_path
                            ? route('student.assignments.submission.download', $a->id)
                            : null,
                    ] : null,
                    'show_url'     => route('student.assignments.show', $a->id),
                ];
            });

        // ─── Quizzes ────────────────────────────────────
        $quizzes = Quiz::query()
            ->whereIn('class_id', $classroomIds)
            ->where('is_published', true)
            ->with([
                'classroom.subject:id,name,code',
                'classroom.section:id,name',
                'classroom.teacher.user:id,name',
                'questions',
            ])
            ->orderBy('available_until')
            ->get()
            ->map(function (Quiz $q) use ($student) {
                $attempt = $q->attempts()
                    ->where('student_id', $student->id)
                    ->orderByDesc('attempt_number')
                    ->first();

                $totalPoints = (float) $q->questions->sum(function ($qq) {
                    return $qq->pivot->points_override ?? $qq->points;
                });

                return [
                    'id'                 => 'q-' . $q->id,
                    'raw_id'             => $q->id,
                    'type'               => 'quiz',
                    'title'              => $q->title,
                    'description'        => \Str::limit(strip_tags($q->description ?? ''), 220),
                    'category'           => $q->category,
                    'subject'            => $q->classroom?->subject?->name,
                    'subject_code'       => $q->classroom?->subject?->code,
                    'section'            => $q->classroom?->section?->name,
                    'instructor'         => $q->classroom?->teacher?->user?->name,
                    'due_at'             => $q->available_until?->toIso8601String(),
                    'available_from'     => $q->available_from?->toIso8601String(),
                    'points'             => $totalPoints,
                    'time_limit_minutes' => $q->time_limit_minutes,
                    'questions_count'    => $q->questions->count(),
                    'allow_late'         => false,
                    'status'             => $this->quizStatusFor($q, $attempt),
                    'days_left'          => $q->available_until
                        ? (int) now()->diffInDays($q->available_until, false)
                        : null,
                    'submission'         => $attempt ? [
                        'id'           => $attempt->id,
                        'status'       => $attempt->status,
                        'grade'        => $attempt->score,
                        'feedback'     => null,
                        'text_content' => null,
                        'submitted_at' => $attempt->submitted_at?->toIso8601String(),
                        'graded_at'    => null,
                        'has_file'     => false,
                        'download_url' => null,
                    ] : null,
                    'show_url'           => route('student.quizzes.show', $q->id),
                ];
            });

        $all = $assignments->merge($quizzes)->values();

        $counts = [
            'all'        => $all->count(),
            'pending'    => $all->where('status', 'pending')->count(),
            'in_progress' => $all->whereIn('status', ['submitted', 'late', 'in_progress'])->count(),
            'graded'     => $all->where('status', 'graded')->count(),
            'overdue'    => $all->where('status', 'overdue')->count(),
        ];

        $payload = [
            'assessments' => $all,
            'counts'      => $counts,
            'active_term' => $activeTerm?->name,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Student/Assessments/Index', $payload);
    }

    protected function statusFor($submission): string
    {
        if (! $submission) return 'pending';

        return match ($submission->status) {
            'graded'    => 'graded',
            'submitted' => 'submitted',
            'late'      => 'late',
            default     => 'pending',
        };
    }

    protected function quizStatusFor($quiz, $attempt): string
    {
        if ($attempt && in_array($attempt->status, ['graded', 'submitted'], true)) {
            return 'graded';
        }
        if ($attempt?->status === 'in_progress') {
            return 'in_progress';
        }

        // No attempt yet — check availability window
        $now = now();
        if ($quiz->available_from && $now->lt($quiz->available_from)) return 'pending';
        if ($quiz->available_until && $now->gt($quiz->available_until)) return 'overdue';
        return 'pending';
    }
}