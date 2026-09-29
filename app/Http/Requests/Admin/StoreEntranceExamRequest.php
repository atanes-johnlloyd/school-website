<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntranceExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'school_year_id' => ['required', 'exists:school_years,id'],
            'track_id'       => ['nullable', 'exists:tracks,id'],
            'exam_name'      => ['required', 'string', 'max:100'],
            'exam_date'      => ['required', 'date', 'after_or_equal:today'],
            'exam_time'      => ['required', 'date_format:H:i'],
            'venue'          => ['nullable', 'string', 'max:150'],
            'max_capacity'   => ['nullable', 'integer', 'min:1', 'max:500'],
            'grade_level'    => ['required', 'in:11,12,All'],
        ];
    }
}