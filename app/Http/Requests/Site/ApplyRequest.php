<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;   // public — no auth
    }

    public function rules(): array
    {
        return [
            // Program choice
            'applicant_type'       => ['required', 'in:Grade11,Grade12,Transferee,Returning'],
            'desired_grade_level'  => ['required', 'in:11,12'],
            'strand_id'            => ['nullable', 'exists:strands,id'],

            // Personal
            'first_name'           => ['required', 'string', 'max:50'],
            'middle_name'          => ['nullable', 'string', 'max:50'],
            'last_name'            => ['required', 'string', 'max:50'],
            'extension_name'       => ['nullable', 'string', 'max:10'],
            'lrn'                  => ['required', 'digits:12'],
            'date_of_birth'        => ['required', 'date', 'before:today'],
            'sex'                  => ['required', 'in:Male,Female'],
            'religion'             => ['nullable', 'string', 'max:100'],
            'contact_number'       => ['required', 'string', 'max:15'],
            'email'                => ['required', 'email', 'max:100'],

            // Address
            'house_street'         => ['nullable', 'string', 'max:150'],
            'barangay'             => ['nullable', 'string', 'max:100'],
            'municipality'         => ['nullable', 'string', 'max:100'],
            'province'             => ['nullable', 'string', 'max:100'],
            'zip_code'             => ['nullable', 'digits:4'],

            // Previous school
            'prev_school_name'     => ['required', 'string', 'max:150'],
            'prev_school_address'  => ['nullable', 'string', 'max:200'],
            'prev_school_type'     => ['required', 'in:Public,Private,International'],
            'last_school_year'     => ['nullable', 'string', 'max:20'],

            // Contacts (optional, up to 4)
            'contacts'                  => ['nullable', 'array', 'max:4'],
            'contacts.*.role'           => ['required_with:contacts', 'in:father,mother,guardian,emergency'],
            'contacts.*.full_name'      => ['required_with:contacts', 'string', 'max:100'],
            'contacts.*.relationship'   => ['nullable', 'string', 'max:50'],
            'contacts.*.occupation'     => ['nullable', 'string', 'max:100'],
            'contacts.*.contact_number' => ['nullable', 'string', 'max:15'],
            'contacts.*.email'          => ['nullable', 'email', 'max:100'],

            // Documents (up to 8 files, 5 MB each)
            'documents'               => ['nullable', 'array', 'max:8'],
            'documents.*'             => ['file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
            'document_types'          => ['nullable', 'array'],
            'document_types.*'        => ['nullable', 'string', 'max:100'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $docs  = $this->file('documents', []);           // ← fixed: use file()
            $types = $this->input('document_types', []);

            // Only validate when at least one document is present
            if (count($docs) > 0 && count($docs) !== count($types)) {
                $v->errors()->add(
                    'document_types',
                    'Each document must have a matching type label.'
                );
            }
        });
    }
}