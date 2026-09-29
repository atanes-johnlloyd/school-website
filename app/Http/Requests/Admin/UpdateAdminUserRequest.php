<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name'              => ['sometimes', 'string', 'max:100'],
            'email'             => ['sometimes', 'email', 'max:255',
                                   Rule::unique('users', 'email')->ignore($this->route('user')->id ?? null)],
            'admin_position_id' => ['nullable', 'exists:admin_positions,id'],
        ];
    }
}