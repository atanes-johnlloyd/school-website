<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreQuestionRequest;
use App\Http\Requests\Teacher\UpdateQuestionRequest;
use App\Models\Question;
use App\Services\QuestionCsvImporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionBankController extends Controller
{
    public function __construct(protected QuestionCsvImporter $importer) {}

    /**
     * List teacher's questions, filterable.
     */
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        $validated = $request->validate([
            'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
            'category'   => ['nullable', 'string', 'max:100'],
            'type'       => ['nullable', 'in:multiple_choice,true_false,essay,short_answer'],
            'search'     => ['nullable', 'string', 'max:200'],
            'per_page'   => ['nullable', 'integer', 'min:5', 'max:100'],
        ]);

        $query = Question::query()
            ->where('teacher_id', $teacher->id)
            ->with(['subject:id,code,name', 'options'])
            ->latest();

        if (! empty($validated['subject_id'])) {
            $query->where('subject_id', $validated['subject_id']);
        }
        if (! empty($validated['category'])) {
            $query->where('category', $validated['category']);
        }
        if (! empty($validated['type'])) {
            $query->where('type', $validated['type']);
        }
        if (! empty($validated['search'])) {
            $query->where('question_text', 'like', '%' . $validated['search'] . '%');
        }

        $questions = $query->paginate($validated['per_page'] ?? 20);

        $subjectIds = \App\Models\ClassRoom::query()
            ->where('teacher_id', $teacher->id)
            ->pluck('subject_id')
            ->unique();

        $subjects = \App\Models\Subject::query()
            ->whereIn('id', $subjectIds)
            ->orderBy('name')
            ->get(['id', 'code', 'name']);

        $payload = [
            'questions'  => $questions,
            'categories' => Question::where('teacher_id', $teacher->id)
                ->whereNotNull('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category'),
            'subjects'   => $subjects,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : \Inertia\Inertia::render('Teacher/QuestionBank/Index', $payload);
    }

    public function store(StoreQuestionRequest $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        $question = DB::transaction(function () use ($request, $teacher) {
            $q = Question::create([
                'teacher_id'    => $teacher->id,
                'subject_id'    => $request->validated('subject_id'),
                'category'      => $request->validated('category'),
                'type'          => $request->validated('type'),
                'question_text' => $request->validated('question_text'),
                'points'        => $request->validated('points') ?? 1,
                'explanation'   => $request->validated('explanation'),
            ]);

            if ($request->filled('options')) {
                foreach ($request->validated('options') as $i => $opt) {
                    $q->options()->create([
                        'option_text' => $opt['option_text'],
                        'is_correct'  => (bool) $opt['is_correct'],
                        'position'    => $i,
                    ]);
                }
            }

            return $q;
        });

        return response()->json([
            'message'  => 'Question saved to bank.',
            'question' => $question->load('options'),
        ], 201);
    }

    public function show(Request $request, Question $question)
    {
        abort_unless($question->teacher_id === $request->user()->teacher?->id, 403);

        return response()->json([
            'question' => $question->load(['subject', 'options']),
        ]);
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        abort_unless($question->teacher_id === $request->user()->teacher?->id, 403);

        DB::transaction(function () use ($request, $question) {
            $data = $request->safe()->except(['options']);
            if (! empty($data)) {
                $question->update($data);
            }

            if ($request->has('options')) {
                $question->options()->delete();
                foreach ($request->validated('options') as $i => $opt) {
                    $question->options()->create([
                        'option_text' => $opt['option_text'],
                        'is_correct'  => (bool) $opt['is_correct'],
                        'position'    => $i,
                    ]);
                }
            }
        });

        return response()->json([
            'message'  => 'Question updated.',
            'question' => $question->fresh()->load('options'),
        ]);
    }

    public function destroy(Request $request, Question $question)
    {
        abort_unless($question->teacher_id === $request->user()->teacher?->id, 403);

        // Prevent deletion if the question is used in a quiz
        if ($question->quizzes()->exists()) {
            return response()->json([
                'message' => 'Cannot delete: question is used in one or more quizzes.',
            ], 422);
        }

        $question->delete();

        return response()->json(['message' => 'Question deleted.']);
    }

    /**
     * CSV import.
     */
    public function importCsv(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        $validated = $request->validate([
            'file'       => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'category'   => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $result = $this->importer->import(
                $request->file('file'),
                $teacher,
                (int) $validated['subject_id'],
                $validated['category'] ?? null,
            );
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Import failed: ' . $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => "Import complete. {$result['created']} questions added.",
            'created' => $result['created'],
            'failed'  => $result['failed'],
        ], 201);
    }
}