<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreQuizRequest;
use App\Http\Requests\Teacher\UpdateQuizRequest;
use App\Models\ClassRoom;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Term;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class QuizController extends Controller
{
    /**
     * List quizzes in a class.
     */
    public function index(Request $request, ClassRoom $classroom)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        $quizzes = $classroom->quizzes()
            ->withCount('questions')
            ->withCount('attempts')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Quiz $q) => [
                'id'                 => $q->id,
                'title'              => $q->title,
                'time_limit_minutes' => $q->time_limit_minutes,
                'passing_score'      => $q->passing_score,
                'is_published'       => $q->is_published,
                'available_from'     => $q->available_from?->toIso8601String(),
                'available_until'    => $q->available_until?->toIso8601String(),
                'questions_count'    => $q->questions_count,
                'attempts_count'     => $q->attempts_count,
                'total_points'       => $q->questions()->sum('points'),
            ]);

        $payload = [
            'classroom' => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'section' => $classroom->section?->name,
            ],
            'quizzes' => $quizzes,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Teacher/QuizHub/Index', $payload);
    }

    /**
     * Create a quiz.
     */
    public function store(StoreQuizRequest $request, ClassRoom $classroom)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        $quiz = $classroom->quizzes()->create([
            'class_module_id'        => $request->validated('class_module_id'),
            'category'               => $request->validated('category') ?? 'written_work',
            'title'                  => $request->validated('title'),
            'description'            => $request->validated('description'),
            'instructions'           => $request->validated('instructions'),
            'time_limit_minutes'     => $request->validated('time_limit_minutes'),
            'passing_score'          => $request->validated('passing_score'),
            'available_from'         => $request->validated('available_from'),
            'available_until'        => $request->validated('available_until'),
            'shuffle_questions'      => $request->boolean('shuffle_questions', false),
            'shuffle_options'        => $request->boolean('shuffle_options', false),
            'show_score_immediately' => $request->boolean('show_score_immediately', true),
            'show_correct_answers'   => $request->boolean('show_correct_answers', true),
            'show_explanations'      => $request->boolean('show_explanations', true),
            'is_published'           => false,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['quiz' => $quiz], 201);
        }

        return redirect()->route('teacher.quizzes.show', $quiz->id)
            ->with('success', 'Quiz created. Add questions next.');
    }

    /**
     * Quiz detail with attached questions.
     */
    public function show(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->classroom->isTaughtBy($request->user()), 403);

        $quiz->load(['questions.options', 'classroom.subject:id,name', 'classroom.section:id,name']);

        $payload = [
            'quiz' => [
                'id'                     => $quiz->id,
                'title'                  => $quiz->title,
                'category'               => $quiz->category,
                'description'            => $quiz->description,
                'instructions'           => $quiz->instructions,
                'time_limit_minutes'     => $quiz->time_limit_minutes,
                'passing_score'          => $quiz->passing_score,
                'available_from'         => $quiz->available_from?->toIso8601String(),
                'available_until'        => $quiz->available_until?->toIso8601String(),
                'shuffle_questions'      => $quiz->shuffle_questions,
                'shuffle_options'        => $quiz->shuffle_options,
                'show_score_immediately' => $quiz->show_score_immediately,
                'show_correct_answers'   => $quiz->show_correct_answers,
                'show_explanations'      => $quiz->show_explanations,
                'is_published'           => $quiz->is_published,
                'classroom_id'           => $quiz->class_id,
                'subject'                => $quiz->classroom?->subject?->name,
                'subject_id'             => $quiz->classroom?->subject?->id,
                'section'                => $quiz->classroom?->section?->name,
                'total_points'           => $quiz->questions->sum('points'),
            ],
            'questions' => $quiz->questions->map(fn (Question $q) => [
                'id'             => $q->id,
                'type'           => $q->type,
                'question_text'  => $q->question_text,
                'points'         => $q->points,
                'points_override' => $q->pivot?->points_override,
                'position'       => $q->pivot?->position,
                'explanation'    => $q->explanation,
                'options'        => $q->options->map(fn ($o) => [
                    'id'          => $o->id,
                    'option_text' => $o->option_text,
                    'is_correct'  => $o->is_correct,
                    'position'    => $o->position,
                ]),
            ]),
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Teacher/QuizHub/Show', $payload);
    }

    /**
     * Update quiz settings.
     */
    public function update(UpdateQuizRequest $request, Quiz $quiz)
    {
        abort_unless($quiz->classroom->isTaughtBy($request->user()), 403);

        $quiz->update($request->validated());

        return response()->json(['quiz' => $quiz->fresh()]);
    }

    /**
     * Delete quiz (cascades to attempts).
     */
    public function destroy(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->classroom->isTaughtBy($request->user()), 403);

        $classId = $quiz->class_id;

        if ($quiz->attempts()->exists()) {
            return response()->json([
                'message' => 'Cannot delete: students have already taken this quiz.',
            ], 422);
        }

        $quiz->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Quiz deleted.']);
        }

        return redirect()->route('teacher.classes.quizzes.index', $classId)
            ->with('success', 'Quiz deleted.');
    }

    /**
     * Publish / unpublish quiz.
     */
    public function togglePublish(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->classroom->isTaughtBy($request->user()), 403);

        if (! $quiz->is_published && $quiz->questions()->count() === 0) {
            return response()->json([
                'message' => 'Cannot publish a quiz with no questions.',
            ], 422);
        }

        $quiz->update(['is_published' => ! $quiz->is_published]);

        return response()->json([
            'message'      => $quiz->is_published ? 'Quiz published.' : 'Quiz unpublished.',
            'is_published' => $quiz->is_published,
        ]);
    }

    /**
     * Attach questions from the bank to this quiz (bulk).
     */
    public function attachQuestions(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->classroom->isTaughtBy($request->user()), 403);

        $validated = $request->validate([
            'question_ids'   => ['required', 'array', 'min:1'],
            'question_ids.*' => ['integer', 'exists:questions,id'],
        ]);

        // Security: questions must belong to this teacher
        $teacherId = $request->user()->teacher->id;
        $ownedCount = Question::whereIn('id', $validated['question_ids'])
            ->where('teacher_id', $teacherId)
            ->count();

        if ($ownedCount !== count($validated['question_ids'])) {
            return response()->json([
                'message' => 'One or more questions do not belong to you.',
            ], 403);
        }

        // Get current max position for appended order
        $maxPos = (int) $quiz->questions()->max('quiz_questions.position');
        $nextPos = $maxPos + 1;

        $sync = [];
        foreach ($validated['question_ids'] as $i => $qid) {
            $sync[$qid] = [
                'position'        => $nextPos + $i,
                'points_override' => null,
            ];
        }

        $quiz->questions()->syncWithoutDetaching($sync);

        return response()->json([
            'message'          => count($sync) . ' question(s) attached.',
            'questions_count'  => $quiz->questions()->count(),
        ]);
    }

    /**
     * Detach a question from the quiz (doesn't delete from bank).
     */
    public function detachQuestion(Request $request, Quiz $quiz, Question $question)
    {
        abort_unless($quiz->classroom->isTaughtBy($request->user()), 403);

        $quiz->questions()->detach($question->id);

        return response()->json([
            'message'         => 'Question removed from quiz.',
            'questions_count' => $quiz->questions()->count(),
        ]);
    }

    /**
     * Reorder questions + optionally override points.
     */
    public function reorderQuestions(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->classroom->isTaughtBy($request->user()), 403);

        $validated = $request->validate([
            'questions'                => ['required', 'array'],
            'questions.*.id'           => ['required', 'integer', 'exists:questions,id'],
            'questions.*.position'     => ['required', 'integer', 'min:0'],
            'questions.*.points_override' => ['nullable', 'numeric', 'min:0.5', 'max:100'],
        ]);

        DB::transaction(function () use ($quiz, $validated) {
            foreach ($validated['questions'] as $row) {
                $quiz->questions()->updateExistingPivot($row['id'], [
                    'position'        => $row['position'],
                    'points_override' => $row['points_override'] ?? null,
                ]);
            }
        });

        return response()->json(['message' => 'Quiz questions updated.']);
    }

    /**
     * Cross-class Quiz Hub — every quiz the teacher owns + bank summary.
     */
    public function hub(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        $activeTerm = Term::where('is_active', true)->first();

        $classroomIds = ClassRoom::query()
            ->where('teacher_id', $teacher->id)
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
            ->pluck('id');

        $quizzes = Quiz::query()
            ->whereIn('class_id', $classroomIds)
            ->with([
                'classroom:id,subject_id,section_id',
                'classroom.subject:id,code,name',
                'classroom.section:id,name',
            ])
            ->withCount(['questions', 'attempts'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Quiz $q) => [
                'id'                 => $q->id,
                'title'              => $q->title,
                'description'        => $q->description,
                'is_published'       => (bool) $q->is_published,
                'time_limit_minutes' => $q->time_limit_minutes,
                'passing_score'      => $q->passing_score !== null ? (float) $q->passing_score : null,
                'available_from'     => $q->available_from?->toIso8601String(),
                'available_until'    => $q->available_until?->toIso8601String(),
                'questions_count'    => (int) $q->questions_count,
                'attempts_count'     => (int) $q->attempts_count,
                'total_points'       => (float) $q->questions()->sum('points'),
                'classroom_id'       => $q->class_id,
                'subject'            => $q->classroom?->subject?->name,
                'subject_code'       => $q->classroom?->subject?->code,
                'section'            => $q->classroom?->section?->name,
                'created_at'          => $q->created_at?->toIso8601String(),
            ]);

        $classrooms = ClassRoom::query()
            ->whereIn('id', $classroomIds)
            ->with(['subject:id,name,code', 'section:id,name'])
            ->withCount('quizzes')
            ->get()
            ->map(fn ($c) => [
                'id'            => $c->id,
                'subject'       => $c->subject?->name,
                'subject_code'  => $c->subject?->code,
                'section'       => $c->section?->name,
                'quizzes_count' => (int) $c->quizzes_count,
            ]);

        $questionBase = Question::where('teacher_id', $teacher->id);
        $questionTotal = (clone $questionBase)->count();
        $byType = (clone $questionBase)
            ->select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type');

        $recentAttempts = QuizAttempt::query()
            ->whereHas('quiz', fn ($q) => $q->whereIn('class_id', $classroomIds))
            ->whereNotNull('submitted_at')
            ->with([
                'student.user:id,name',
                'quiz' => function ($q) {
                    $q->select('id', 'title', 'class_id')
                    ->with(['classroom:id,subject_id', 'classroom.subject:id,name'])
                    ->withSum('questions', 'points');
                },
            ])
            ->orderByDesc('submitted_at')
            ->limit(8)
            ->get()
            ->map(fn (QuizAttempt $a) => [
                'id'              => $a->id,
                'student_name'    => $a->student?->user?->name,
                'quiz_id'         => $a->quiz_id,
                'quiz_title'      => $a->quiz?->title,
                'subject'         => $a->quiz?->classroom?->subject?->name,
                'score'           => $a->score !== null ? (float) $a->score : null,
                'total_points'    => (float) ($a->quiz?->questions_sum_points ?? 0),
                'status'          => $a->status,
                'submitted_at'    => $a->submitted_at?->toIso8601String(),
                'submitted_human' => $a->submitted_at?->diffForHumans(),
            ]);

        return Inertia::render('Teacher/QuizHub/Index', [
            'quizzes'    => $quizzes,
            'classrooms' => $classrooms,
            'questionStats' => [
                'total'   => $questionTotal,
                'by_type' => $byType,
            ],
            'stats' => [
                'total'          => $quizzes->count(),
                'published'      => $quizzes->where('is_published', true)->count(),
                'draft'          => $quizzes->where('is_published', false)->count(),
                'total_attempts' => $quizzes->sum('attempts_count'),
            ],
            'active_term' => $activeTerm?->name,
            'recent_attempts' => $recentAttempts,
        ]);
    }
}