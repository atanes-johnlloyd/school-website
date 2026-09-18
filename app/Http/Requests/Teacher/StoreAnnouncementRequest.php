<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('teacher');
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'body'         => ['required', 'string', 'max:20000'],
            'is_pinned'    => ['boolean'],
            'is_published' => ['boolean'],
            'expires_at'   => ['nullable', 'date', 'after:now'],
        ];
    }
}