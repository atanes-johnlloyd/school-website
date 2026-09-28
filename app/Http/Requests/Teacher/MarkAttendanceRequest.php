<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class MarkAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('teacher');
    }

    public function rules(): array
    {
        return [
            'records'                => ['required', 'array', 'min:1'],
            'records.*.student_id'   => ['required', 'integer', 'exists:students,id'],
            'records.*.status'       => ['required', 'in:present,absent,late,excused'],
            'records.*.notes'        => ['nullable', 'string', 'max:255'],
        ];
    }
}