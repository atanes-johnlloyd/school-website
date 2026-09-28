<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('teacher');
    }

    public function rules(): array
    {
        return [
            'subject_id'            => ['sometimes', 'exists:subjects,id'],
            'category'              => ['nullable', 'string', 'max:100'],
            'type'                  => ['sometimes', 'in:multiple_choice,true_false,essay'],
            'question_text'         => ['sometimes', 'string', 'max:5000'],
            'points'                => ['nullable', 'numeric', 'min:0.5', 'max:100'],
            'explanation'           => ['nullable', 'string', 'max:2000'],
            'options'               => ['nullable', 'array'],
            'options.*.option_text' => ['required_with:options', 'string', 'max:500'],
            'options.*.is_correct'  => ['required_with:options', 'boolean'],
        ];
    }
}