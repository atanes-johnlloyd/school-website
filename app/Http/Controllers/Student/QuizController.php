<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Services\QuizAutoGrader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\QuizRetakeGrant;
use Illuminate\Database\QueryException;

class QuizController extends Controller
{
    public function __construct(protected QuizAutoGrader $grader) {}

    // ═══════════════════════════════════════════════════════════
    // 1. LIST — Quizzes available in a class
    // ═══════════════════════════════════════════════════════════
    public function index(Request $request, ClassRoom $classroom)
    {
        abort_unless($classroom->hasStudent($request->user()), 403);

        $student = $request->user()->student;

        $quizzes = $classroom->quizzes()
            ->where('is_published', true)
            ->withCount('questions')
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Quiz $q) use ($student) {
                $attempt = $q->attempts()
                    ->where('student_id', $student->id)
                    ->orderByDesc('attempt_number')
                    ->first();
                $now = now();

                $available = true;
                $reason = null;

                if ($q->available_from && $now->lt($q->available_from)) {
                    $available = false;
                    $reason = 'Opens ' . $q->available_from->diffForHumans();
                } elseif ($q->available_until && $now->gt($q->available_until)) {
                    $available = false;
                    $reason = 'Closed ' . $q->available_until->diffForHumans();
                }

                return [
                    'id'                 => $q->id,
                    'title'              => $q->title,
                    'description'        => $q->description,
                    'time_limit_minutes' => $q->time_limit_minutes,
                    'passing_score'      => $q->passing_score,
                    'questions_count'    => $q->questions_count,
                    'total_points'       => $q->questions()->sum('points'),
                    'available_from'     => $q->available_from?->toIso8601String(),
                    'available_until'    => $q->available_until?->toIso8601String(),
                    'is_available'       => $available,
                    'availability_note'  => $reason,
                    'my_attempt'         => $attempt ? [
                        'id'           => $attempt->id,
                        'status'       => $attempt->status,
                        'score'        => $attempt->score,
                        'submitted_at' => $attempt->submitted_at?->toIso8601String(),
                    ] : null,
                ];
            });

        $payload = [
            'classroom' => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'section' => $classroom->section?->name,
                'teacher' => $classroom->teacher?->user?->name,
            ],
            'quizzes' => $quizzes,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Student/QuizHub/Index', $payload);
    }

    // ═══════════════════════════════════════════════════════════
    // 2. PREVIEW — Quiz info before starting (no questions yet)
    // ═══════════════════════════════════════════════════════════
    public function show(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->classroom->hasStudent($request->user()), 403);
        abort_unless($quiz->is_published, 404);

        $student = $request->user()->student;
        $attempt = $quiz->attempts()
            ->where('student_id', $student->id)
            ->orderByDesc('attempt_number')
            ->first();

        $payload = [
            'quiz' => [
                'id'                 => $quiz->id,
                'title'              => $quiz->title,
                'description'        => $quiz->description,
                'instructions'       => $quiz->instructions,
                'time_limit_minutes' => $quiz->time_limit_minutes,
                'passing_score'      => $quiz->passing_score,
                'questions_count'    => $quiz->questions()->count(),
                'total_points'       => $quiz->questions()->sum('points'),
                'available_from'     => $quiz->available_from?->toIso8601String(),
                'available_until'    => $quiz->available_until?->toIso8601String(),
                'subject'            => $quiz->classroom?->subject?->name,
                'section'            => $quiz->classroom?->section?->name,
                'teacher'            => $quiz->classroom?->teacher?->user?->name,
            ],
            'my_attempt' => $attempt ? [
                'id'           => $attempt->id,
                'status'       => $attempt->status,
                'submitted_at' => $attempt->submitted_at?->toIso8601String(),
            ] : null,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Student/QuizHub/Show', $payload);
    }

    // ═══════════════════════════════════════════════════════════
    // 3. START — Create attempt, return questions
    // ═══════════════════════════════════════════════════════════
    public function start(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->classroom->hasStudent($request->user()), 403);
        abort_unless($quiz->is_published, 404);

        $student = $request->user()->student;

        $now = now();
        if ($quiz->available_from && $now->lt($quiz->available_from)) {
            return response()->json(['message' => 'Quiz is not yet available.'], 422);
        }
        if ($quiz->available_until && $now->gt($quiz->available_until)) {
            return response()->json(['message' => 'This quiz has closed.'], 422);
        }

        $attempt = DB::transaction(function () use ($quiz, $student) {
            $attempts = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('student_id', $student->id)
                ->orderByDesc('attempt_number')
                ->lockForUpdate()
                ->get();

            // Resume if there's an in-progress attempt
            $inProgress = $attempts->firstWhere('submitted_at', null);
            if ($inProgress) {
                return $inProgress;
            }

            $maxAttempts = $quiz->attempts_allowed ?? 1;
            $attemptCount = $attempts->count();
            $pendingGrant = null;

            if ($attemptCount >= $maxAttempts) {
                $pendingGrant = QuizRetakeGrant::query()
                    ->where('quiz_id', $quiz->id)
                    ->where('student_id', $student->id)
                    ->whereNull('used_at')
                    ->lockForUpdate()
                    ->oldest()
                    ->first();

                if (! $pendingGrant) {
                    return response()->json([
                        'message' => 'You have already taken this quiz. Ask your teacher for a retake.',
                        'code'    => 'NO_ATTEMPTS_REMAINING',
                    ], 422);
                }
            }

            $questions = $quiz->questions()->with('options')->get();
            if ($questions->isEmpty()) {
                return response()->json(['message' => 'Quiz has no questions.'], 422);
            }

            if ($pendingGrant) {
                $pendingGrant->update(['used_at' => now()]);
            }

            $expires = $quiz->time_limit_minutes
                ? now()->addMinutes($quiz->time_limit_minutes)
                : null;

            $attempt = QuizAttempt::create([
                'quiz_id'         => $quiz->id,
                'student_id'      => $student->id,
                'attempt_number'  => $attemptCount + 1,
                'started_at'      => now(),
                'expires_at'      => $expires,
                'status'          => 'in_progress',
                'warning_count'   => 0,
                'questions_order' => $this->buildQuestionsOrder($quiz, $questions),
                'options_order'   => $this->buildOptionsOrder($quiz, $questions),
            ]);

            foreach ($questions as $q) {
                $attempt->answers()->create(['question_id' => $q->id]);
            }

            return $attempt;
        });

        if ($attempt instanceof \Illuminate\Http\JsonResponse) {
            return $attempt;
        }

        return $this->respondWithAttempt($request, $attempt);
    }

    // ═══════════════════════════════════════════════════════════
    // 4. RESUME — Fetch active attempt state
    // ═══════════════════════════════════════════════════════════
    public function active(Request $request, QuizAttempt $attempt)
    {
        $this->authorizeAttempt($request, $attempt);

        // Auto-submit if expired
        $this->ensureActive($attempt);

        return $this->respondWithAttempt($request, $attempt->fresh());
    }

    // ═══════════════════════════════════════════════════════════
    // 5. ANSWER — Autosave one answer
    // ═══════════════════════════════════════════════════════════
    public function answer(Request $request, QuizAttempt $attempt)
    {
        $this->authorizeAttempt($request, $attempt);
        $this->ensureActive($attempt);

        $attempt->refresh();

        if ($attempt->submitted_at) {
            return response()->json([
                'message' => 'Attempt already submitted.',
                'status'  => 'submitted',
            ], 409);
        }

        $validated = $request->validate([
            'question_id'       => ['required', 'integer', 'exists:questions,id'],
            'question_option_id'=> ['nullable', 'integer', 'exists:question_options,id'],
            'answer_text'       => ['nullable', 'string', 'max:20000'],
        ]);

        // Verify question is part of this quiz
        $questionIds = $attempt->quiz->questions()->pluck('questions.id')->all();
        if (! in_array($validated['question_id'], $questionIds, true)) {
            return response()->json(['message' => 'Question is not part of this quiz.'], 422);
        }

        $answer = $attempt->answers()->where('question_id', $validated['question_id'])->firstOrFail();

        $answer->update([
            'question_option_id' => $validated['question_option_id'] ?? null,
            'answer_text'        => $validated['answer_text'] ?? null,
        ]);

        return response()->json([
            'saved'  => true,
            'answer' => [
                'question_id'        => $answer->question_id,
                'question_option_id' => $answer->question_option_id,
                'answer_text'        => $answer->answer_text,
            ],
        ]);
    }

    // ═══════════════════════════════════════════════════════════
    // 6. WARNING — Tab switch / fullscreen exit
    // ═══════════════════════════════════════════════════════════
    public function warning(Request $request, QuizAttempt $attempt)
    {
        $this->authorizeAttempt($request, $attempt);

        $attempt->increment('warning_count');
        $attempt->refresh();

        if ($attempt->submitted_at) {
            return response()->json([
                'message'        => 'Attempt already submitted.',
                'warning_count'  => $attempt->warning_count,
                'auto_submitted' => true,
            ], 409);
        }

        $auto = false;
        if ($attempt->warning_count >= QuizAutoGrader::MAX_WARNINGS) {
            $this->grader->submit($attempt, 'warnings');
            $auto = true;
        }

        return response()->json([
            'message'        => $auto
                ? 'Maximum warnings reached. Your quiz was auto-submitted.'
                : "Warning {$attempt->warning_count} of " . QuizAutoGrader::MAX_WARNINGS . '.',
            'warning_count'  => $attempt->warning_count,
            'max_warnings'   => QuizAutoGrader::MAX_WARNINGS,
            'auto_submitted' => $auto,
        ]);
    }

    // ═══════════════════════════════════════════════════════════
    // 7. SUBMIT — Final submission
    // ═══════════════════════════════════════════════════════════
    public function submit(Request $request, QuizAttempt $attempt)
    {
        $this->authorizeAttempt($request, $attempt);

        if ($attempt->submitted_at) {
            return response()->json([
                'message' => 'Already submitted.',
                'attempt' => $attempt,
            ]);
        }

        $reason = $request->input('reason', 'manual');

        // Enforce expiration check server-side. Even if the client lies, 
        // if the time is up, we mark it as expired.
        if ($attempt->expires_at && now()->greaterThan($attempt->expires_at)) {
            $reason = 'expired';
        }

        $this->grader->submit($attempt, $reason);

        return response()->json([
            'message' => 'Quiz submitted.',
            'attempt' => $attempt->fresh()->load('answers'),
        ], 201);
    }

    // ═══════════════════════════════════════════════════════════
    // 8. RESULT — Score + review per quiz config
    // ═══════════════════════════════════════════════════════════
    public function result(Request $request, QuizAttempt $attempt)
    {
        $this->authorizeAttempt($request, $attempt);

        if (! $attempt->submitted_at) {
            return response()->json(['message' => 'Attempt is not submitted yet.'], 422);
        }

        $quiz = $attempt->quiz;
        $totalPoints = $quiz->questions()->sum('points');

        $showScore    = $quiz->show_score_immediately;
        $showCorrect  = $quiz->show_correct_answers;
        $showExplain  = $quiz->show_explanations;

        $answers = $attempt->answers()
            ->with(['question.options', 'option'])
            ->get()
            ->map(function (QuizAnswer $a) use ($showCorrect, $showExplain, $showScore) {
                $q = $a->question;
                $correct = $q?->options->firstWhere('is_correct', true);

                return [
                    'question_id'       => $a->question_id,
                    'question_text'     => $q?->question_text,
                    'type'              => $q?->type,
                    'your_option_id'    => $a->question_option_id,
                    'your_option_text'  => $a->option?->option_text,
                    'your_text'         => $a->answer_text,

                    // Only expose the correct answer if quiz allows it
                    'is_correct'        => $showCorrect ? $a->is_correct : null,
                    'correct_option_id' => $showCorrect ? $correct?->id : null,
                    'correct_option_text' => $showCorrect ? $correct?->option_text : null,

                    'points_awarded'    => $showScore ? $a->points_awarded : null,
                    'needs_grading'     => $q?->type === 'essay' && $a->points_awarded === null,

                    'explanation'       => $showExplain ? $q?->explanation : null,
                ];
            });

        $hasPendingEssays = $answers->where('needs_grading', true)->count() > 0;
        // Passing score is a percentage threshold; convert raw score to %
        $scorePercent = null;
        if ($attempt->score !== null && $totalPoints > 0) {
            $scorePercent = round(((float) $attempt->score / (float) $totalPoints) * 100, 2);
        }

        $passed = $scorePercent !== null
            && $quiz->passing_score !== null
            && $scorePercent >= (float) $quiz->passing_score;

        $payload = [
            'quiz' => [
                'id'            => $quiz->id,
                'title'         => $quiz->title,
                'subject'       => $quiz->classroom?->subject?->name,
                'total_points'  => $totalPoints,
                'passing_score' => $quiz->passing_score,
            ],
            'attempt' => [
                'id'                => $attempt->id,
                'status'            => $attempt->status,
                'score'             => $showScore ? $attempt->score : null,
                'score_hidden'      => ! $showScore,
                'passed'            => $showScore ? $passed : null,
                'submitted_at'      => $attempt->submitted_at?->toIso8601String(),
                'warning_count'     => $attempt->warning_count,
                'has_pending_essays'=> $hasPendingEssays,
                'show_correct_answers' => $quiz->show_correct_answers,
                'show_explanations'    => $quiz->show_explanations,
            ],
            'answers' => $answers,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Student/QuizHub/Result', $payload);
    }

    // ═══════════════════════════════════════════════════════════
    // Helpers
    // ═══════════════════════════════════════════════════════════

    protected function authorizeAttempt(Request $request, QuizAttempt $attempt): void
    {
        $student = $request->user()->student;
        abort_unless($student, 403);
        abort_unless($attempt->student_id === $student->id, 403);
    }

    protected function ensureActive(QuizAttempt $attempt): void
    {
        $attempt->refresh();
        if (! $attempt->submitted_at
            && $attempt->expires_at
            && now()->greaterThan($attempt->expires_at)) {
            $this->grader->submit($attempt, 'expired');
        }
    }

    /**
     * Return the active-attempt payload — questions WITHOUT is_correct,
     * in the stored (shuffled) order, along with current answers.
     */
    protected function respondWithAttempt(Request $request, QuizAttempt $attempt, ?string $notice = null)
    {
        $attempt->refresh();

        if ($attempt->submitted_at) {
            // Already submitted — send them to result endpoint
            return response()->json([
                'message' => 'Attempt already submitted.',
                'attempt' => ['id' => $attempt->id, 'status' => $attempt->status],
            ], 409);
        }

        // If expired, auto-submit before responding
        $this->ensureActive($attempt);
        $attempt->refresh();

        if ($attempt->submitted_at) {
            return response()->json([
                'message' => 'Attempt expired and was auto-submitted.',
                'attempt' => ['id' => $attempt->id, 'status' => $attempt->status],
            ], 409);
        }

        $questionsOrder = $attempt->questions_order ?? [];
        $optionsOrder   = $attempt->options_order ?? [];

        // Load questions in the stored order
        $questions = Question::whereIn('id', $questionsOrder)
            ->with('options')
            ->get()
            ->sortBy(fn ($q) => array_search($q->id, $questionsOrder))
            ->values();

        // Existing answers keyed by question_id
        $answers = $attempt->answers()->get()->keyBy('question_id');

        $payload = [
            'attempt' => [
                'id'            => $attempt->id,
                'started_at'    => $attempt->started_at?->toIso8601String(),
                'expires_at'    => $attempt->expires_at?->toIso8601String(),
                'warning_count' => $attempt->warning_count,
                'max_warnings'  => QuizAutoGrader::MAX_WARNINGS,
            ],
            'quiz' => [
                'id'                 => $attempt->quiz->id,
                'title'              => $attempt->quiz->title,
                'instructions'       => $attempt->quiz->instructions,
                'time_limit_minutes' => $attempt->quiz->time_limit_minutes,
                'subject'            => $attempt->quiz->classroom?->subject?->name,
            ],
            'questions' => $questions->map(function (Question $q) use ($optionsOrder, $answers) {
                // Shuffle options if requested (using stored order)
                $optionIds = $optionsOrder[(string) $q->id] ?? null;
                $options = $q->options;

                if ($optionIds) {
                    $options = $options->sortBy(fn ($o) => array_search($o->id, $optionIds))->values();
                }

                $myAnswer = $answers->get($q->id);

                return [
                    'id'            => $q->id,
                    'type'          => $q->type,
                    'question_text' => $q->question_text,
                    'options'       => $options->map(fn ($o) => [
                        'id'          => $o->id,
                        'option_text' => $o->option_text,
                        // NOTE: is_correct is intentionally omitted — students cannot see it
                    ]),
                    'my_answer' => [
                        'question_option_id' => $myAnswer?->question_option_id,
                        'answer_text'        => $myAnswer?->answer_text,
                    ],
                ];
            }),
        ];

        if ($notice) {
            $payload['notice'] = $notice;
        }

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Student/QuizHub/Take', $payload);
    }

    protected function buildQuestionsOrder(Quiz $quiz, $questions): array
    {
        $ids = $questions->pluck('id')->all();

        return $quiz->shuffle_questions ? collect($ids)->shuffle()->values()->all() : $ids;
    }

    protected function buildOptionsOrder(Quiz $quiz, $questions): array
    {
        if (! $quiz->shuffle_options) {
            return [];
        }

        $map = [];
        foreach ($questions as $q) {
            if (in_array($q->type, ['multiple_choice', 'true_false'], true)) {
                $map[(string) $q->id] = collect($q->options->pluck('id'))
                    ->shuffle()
                    ->values()
                    ->all();
            }
        }

        return $map;
    }
}