<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RecordExamResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'score'   => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'result'  => ['required', 'in:Pending,Passed,Failed,Absent,For Interview'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }
}