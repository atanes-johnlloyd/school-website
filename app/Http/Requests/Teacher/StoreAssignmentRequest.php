<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('teacher');
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'in:written_work,performance_task,quarterly_exam'],
            'instructions'  => ['nullable', 'string'],
            'due_at'        => ['required', 'date', 'after:now'],
            'points'        => ['required', 'numeric', 'min:1', 'max:1000'],
            'allow_late'    => ['boolean'],
            'is_published'  => ['boolean'],
            'class_module_id' => ['nullable', 'exists:class_modules,id'],
        ];
    }
}