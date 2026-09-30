<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'image',
                'max:' . \App\Models\SystemSetting::maxFileUploadKb(),                                    // 5 MB
                'mimes:jpg,jpeg,png,webp,svg,gif',
                'dimensions:min_width=32,min_height=32,max_width=5000,max_height=5000',
            ],
        ];
    }
}