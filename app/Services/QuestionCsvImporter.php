<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Teacher;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class QuestionCsvImporter
{
    /**
     * Import questions from a CSV file.
     *
     * Expected CSV columns (header row required):
     *   type, question_text, option_a, option_b, option_c, option_d, correct, explanation
     *
     * - MC:  option_a..option_d filled, `correct` is A|B|C|D
     * - TF:  option_a=True, option_b=False, `correct` is A or B
     * - Essay: only question_text + explanation
     */
    public function import(UploadedFile $file, Teacher $teacher, int $subjectId, ?string $category): array
    {
        $handle = fopen($file->getRealPath(), 'r');
        if (! $handle) {
            throw new \RuntimeException('Could not open CSV file.');
        }

        $header = fgetcsv($handle);
        if (! $header) {
            fclose($handle);
            throw new \RuntimeException('CSV file is empty.');
        }

        $header = array_map(fn ($h) => strtolower(trim($h)), $header);

        $required = ['type', 'question_text'];
        foreach ($required as $col) {
            if (! in_array($col, $header, true)) {
                fclose($handle);
                throw new \RuntimeException("CSV missing required column: {$col}");
            }
        }

        $created = 0;
        $failed  = [];
        $rowNum  = 1;

        DB::transaction(function () use ($handle, $header, $teacher, $subjectId, $category, &$created, &$failed, &$rowNum) {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNum++;

                // Skip blank rows
                if (count(array_filter($row)) === 0) continue;

                $data = array_combine($header, array_pad($row, count($header), null));

                try {
                    $this->createQuestionFromRow($data, $teacher, $subjectId, $category);
                    $created++;
                } catch (\Throwable $e) {
                    $failed[] = ['row' => $rowNum, 'error' => $e->getMessage()];
                }
            }
        });

        fclose($handle);

        return ['created' => $created, 'failed' => $failed];
    }

    protected function createQuestionFromRow(array $data, Teacher $teacher, int $subjectId, ?string $category): void
    {
        $type = strtolower(trim($data['type'] ?? ''));
        if (! in_array($type, Question::TYPES, true)) {
            throw new \InvalidArgumentException("Invalid type '{$type}'.");
        }

        $text = trim($data['question_text'] ?? '');
        if ($text === '') {
            throw new \InvalidArgumentException('Empty question_text.');
        }

        $points = isset($data['points']) && $data['points'] !== null && $data['points'] !== ''
            ? (float) $data['points']
            : 1.0;

        $question = Question::create([
            'teacher_id'    => $teacher->id,
            'subject_id'    => $subjectId,
            'category'      => $category,
            'type'          => $type,
            'question_text' => $text,
            'points'        => $points,
            'explanation'   => $data['explanation'] ?? null,
        ]);

        if ($type === 'essay') {
            return; // no options
        }

        // MC / TF — need options
        $letters = ['A', 'B', 'C', 'D'];
        $letterToIndex = ['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3];
        $correctLetter = strtoupper(trim($data['correct'] ?? ''));

        if (! isset($letterToIndex[$correctLetter])) {
            throw new \InvalidArgumentException("Invalid 'correct' letter: '{$correctLetter}'.");
        }

        $options = [];
        foreach ($letters as $letter) {
            $col = 'option_' . strtolower($letter);
            $text = trim($data[$col] ?? '');

            if ($text === '') continue;

            $options[] = [
                'option_text' => $text,
                'is_correct'  => ($letter === $correctLetter),
            ];
        }

        if (count($options) < 2) {
            throw new \InvalidArgumentException('MC/TF requires at least 2 options.');
        }

        // TF — auto-fill if missing
        if ($type === 'true_false' && count($options) < 2) {
            $options = [
                ['option_text' => 'True',  'is_correct' => $correctLetter === 'A'],
                ['option_text' => 'False', 'is_correct' => $correctLetter === 'B'],
            ];
        }

        foreach ($options as $i => $opt) {
            $question->options()->create([
                'option_text' => $opt['option_text'],
                'is_correct'  => $opt['is_correct'],
                'position'    => $i,
            ]);
        }
    }
}