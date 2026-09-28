<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('teacher');
    }

    public function rules(): array
    {
        return [
            'subject_id'      => ['required', 'exists:subjects,id'],
            'category'        => ['nullable', 'string', 'max:100'],
            'type'            => ['required', 'in:multiple_choice,true_false,essay'],
            'question_text'   => ['required', 'string', 'max:5000'],
            'points'          => ['nullable', 'numeric', 'min:0.5', 'max:100'],
            'explanation'     => ['nullable', 'string', 'max:2000'],

            // Options only required for MC / TF
            'options'                => ['nullable', 'array'],
            'options.*.option_text'  => ['required_with:options', 'string', 'max:500'],
            'options.*.is_correct'   => ['required_with:options', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $type = $this->input('type');
            $options = $this->input('options', []);

            if (in_array($type, ['multiple_choice', 'true_false'], true)) {
                if (count($options) < 2) {
                    $v->errors()->add('options', 'At least 2 options are required.');
                }

                $correct = collect($options)->where('is_correct', true)->count();

                if ($correct !== 1) {
                    $v->errors()->add('options', 'Exactly one option must be marked correct.');
                }
            }
        });
    }
}