<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'recipient_id' => ['required', 'integer', 'exists:users,id'],
            'subject'      => ['nullable', 'string', 'max:255'],
            'body'         => ['required', 'string', 'max:5000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            if ($this->recipient_id == $this->user()->id) {
                $v->errors()->add('recipient_id', 'You cannot message yourself.');
            }
        });
    }
}