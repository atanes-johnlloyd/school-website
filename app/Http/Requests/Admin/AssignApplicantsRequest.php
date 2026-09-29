<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AssignApplicantsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'applicant_ids'   => ['required', 'array', 'min:1'],
            'applicant_ids.*' => ['integer', 'exists:applicants,id'],
        ];
    }
}