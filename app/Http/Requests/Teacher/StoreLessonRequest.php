<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('teacher');
    }

    public function rules(): array
    {
        return [
            'title'           => ['required', 'string', 'max:255'],
            'body'            => ['required', 'string', 'max:50000'],
            'class_module_id' => ['nullable', 'exists:class_modules,id'],
            'position'        => ['nullable', 'integer', 'min:0'],
            'is_published'    => ['boolean'],
            'attachments'     => ['nullable', 'array', 'max:5'],
            'attachments.*'   => [
                'file',
                'max:10240',
                'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,jpg,jpeg,png,zip,mp4,mp3',
            ],
        ];
    }
}