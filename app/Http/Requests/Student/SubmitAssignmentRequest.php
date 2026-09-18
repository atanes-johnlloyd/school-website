<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class SubmitAssignmentRequest extends FormRequest
{
    // public function authorize(): bool
    // {
    //     return $this->user()->hasRole('student');
    // }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text_content' => ['required', 'string', 'min:1', 'max:50000'],
        ];
    }
}