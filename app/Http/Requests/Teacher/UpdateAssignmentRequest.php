<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('teacher');
    }

    public function rules(): array
    {
        return [
            'title'         => ['sometimes', 'string', 'max:255'],
            'instructions'  => ['nullable', 'string'],
            'due_at'        => ['sometimes', 'date'],
            'points'        => ['sometimes', 'numeric', 'min:1', 'max:1000'],
            'allow_late'    => ['boolean'],
            'is_published'  => ['boolean'],
        ];
    }
}