<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEntranceExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'school_year_id' => ['sometimes', 'exists:school_years,id'],
            'track_id'       => ['nullable', 'exists:tracks,id'],
            'exam_name'      => ['sometimes', 'string', 'max:100'],
            'exam_date'      => ['sometimes', 'date'],
            'exam_time'      => ['sometimes', 'date_format:H:i'],
            'venue'          => ['nullable', 'string', 'max:150'],
            'max_capacity'   => ['sometimes', 'integer', 'min:1', 'max:500'],
            'grade_level'    => ['sometimes', 'in:11,12,All'],
        ];
    }
}