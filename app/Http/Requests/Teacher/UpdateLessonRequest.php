<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('teacher');
    }

    public function rules(): array
    {
        return [
            'title'           => ['sometimes', 'string', 'max:255'],
            'body'            => ['sometimes', 'string', 'max:50000'],
            'class_module_id' => ['nullable', 'exists:class_modules,id'],
            'position'        => ['sometimes', 'integer', 'min:0'],
            'is_published'    => ['boolean'],
        ];
    }
}