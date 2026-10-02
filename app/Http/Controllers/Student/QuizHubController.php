<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuizHubController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        abort_unless($student, 403);

        $activeTerm = Term::where('is_active', true)->first();

        $classroomIds = $student->classroom()
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
            ->pluck('classes.id');

        $now = now();

        $quizzes = Quiz::query()
            ->whereIn('class_id', $classroomIds)
            ->where('is_published', true)
            ->with([
                'classroom.subject:id,name,code',
                'classroom.section:id,name',
                'classroom.teacher.user:id,name',
                'questions',
            ])
            ->orderByDesc('available_until')
            ->get()
            ->map(function (Quiz $q) use ($student, $now) {
                $attempt = $q->attempts()
                    ->where('student_id', $student->id)
                    ->orderByDesc('attempt_number')
                    ->first();

                $totalPoints = (float) $q->questions->sum(function ($qq) {
                    return $qq->pivot->points_override ?? $qq->points;
                });

                // Compute availability window
                $status = 'available';
                $availabilityNote = null;

                if ($q->available_from && $now->lt($q->available_from)) {
                    $status = 'upcoming';
                    $availabilityNote = 'Opens ' . $q->available_from->diffForHumans();
                } elseif ($q->available_until && $now->gt($q->available_until)) {
                    $status = 'closed';
                    $availabilityNote = 'Closed ' . $q->available_until->diffForHumans();
                }

                // Attempt override status
                if ($attempt) {
                    if (in_array($attempt->status, ['graded', 'submitted'], true)) {
                        $status = 'completed';
                    } elseif ($attempt->status === 'in_progress') {
                        $status = 'in_progress';
                    }
                }

                return [
                    'id'                  => $q->id,
                    'title'               => $q->title,
                    'description'         => $q->description,
                    'subject'             => $q->classroom?->subject?->name,
                    'subject_code'        => $q->classroom?->subject?->code,
                    'section'             => $q->classroom?->section?->name,
                    'instructor'          => $q->classroom?->teacher?->user?->name,
                    'time_limit_minutes'  => $q->time_limit_minutes,
                    'passing_score'       => $q->passing_score,
                    'questions_count'     => $q->questions->count(),
                    'total_points'        => $totalPoints,
                    'available_from'      => $q->available_from?->toIso8601String(),
                    'available_until'     => $q->available_until?->toIso8601String(),
                    'status'              => $status,
                    'availability_note'   => $availabilityNote,
                    'my_attempt'          => $attempt ? [
                        'id'           => $attempt->id,
                        'status'       => $attempt->status,
                        'score'        => $attempt->score,
                        'submitted_at' => $attempt->submitted_at?->toIso8601String(),
                    ] : null,
                ];
            });

        $counts = [
            'all'         => $quizzes->count(),
            'available'   => $quizzes->where('status', 'available')->count(),
            'in_progress' => $quizzes->where('status', 'in_progress')->count(),
            'upcoming'    => $quizzes->where('status', 'upcoming')->count(),
            'completed'   => $quizzes->where('status', 'completed')->count(),
            'closed'      => $quizzes->where('status', 'closed')->count(),
        ];

        $payload = [
            'quizzes'     => $quizzes,
            'counts'      => $counts,
            'active_term' => $activeTerm?->name,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Student/QuizHub/Index', $payload);
    }
}