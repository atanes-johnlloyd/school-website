<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class SubmitAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('student');
    }

    public function rules(): array
    {
        return [
            // Text OR file must be present (at least one)
            'text_content' => ['nullable', 'string', 'max:50000'],
            'file'         => [
                'nullable',
                'file',
                'max:10240',                 // 10 MB
                'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,jpg,jpeg,png,zip',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            if (! $this->filled('text_content') && ! $this->hasFile('file')) {
                $v->errors()->add('text_content', 'Provide text or upload a file.');
            }
        });
    }
}