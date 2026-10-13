<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Normalize the input before validation.
     * Collapses consecutive spaces and trims padding so users can't
     * sneak in "  David   Cruz  " and hit the regex with whitespace noise.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => preg_replace('/\s+/', ' ', trim((string) $this->input('name'))),
            ]);
        }

        if ($this->has('email')) {
            $this->merge([
                'email' => trim((string) $this->input('email')),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // Unicode-aware: letters (incl. accented), spaces, hyphens,
                // apostrophes, periods. Rejects digits, emoji, symbols.
                'regex:/^[\p{L}\s\'\-\.]+$/u',
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }

    /**
     * Custom messages so the regex failure is human-readable.
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'Your name may only contain letters, spaces, hyphens, apostrophes, and periods.',
        ];
    }
}