<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('teacher');
    }

    public function rules(): array
    {
        return [
            'title'                  => ['sometimes', 'string', 'max:255'],
            'description'            => ['nullable', 'string', 'max:2000'],
            'instructions'           => ['nullable', 'string', 'max:5000'],
            'time_limit_minutes'     => ['nullable', 'integer', 'min:1', 'max:300'],
            'passing_score'          => ['nullable', 'numeric', 'min:0', 'max:100'],
            'available_from'         => ['nullable', 'date'],
            'available_until'        => ['nullable', 'date', 'after:available_from'],
            'shuffle_questions'      => ['boolean'],
            'shuffle_options'        => ['boolean'],
            'show_score_immediately' => ['boolean'],
            'show_correct_answers'   => ['boolean'],
            'show_explanations'      => ['boolean'],
            'class_module_id'        => ['nullable', 'exists:class_modules,id'],
        ];
    }
}