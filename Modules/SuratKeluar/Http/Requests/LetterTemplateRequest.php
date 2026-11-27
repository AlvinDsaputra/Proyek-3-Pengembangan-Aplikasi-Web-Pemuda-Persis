<?php

namespace Modules\SuratKeluar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\SuratKeluar\Models\LetterTemplate;

class LetterTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', LetterTemplate::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'letter_type_id' => ['required', 'exists:letter_types,id'],
            'name' => ['required', 'string', 'max:150'],
            'default_subject' => ['nullable', 'string', 'max:255'],
            'body_template' => ['required', 'string', 'min:10'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'letter_type_id.required' => 'Jenis surat wajib dipilih.',
            'letter_type_id.exists' => 'Jenis surat yang dipilih tidak valid.',
            'name.required' => 'Nama template wajib diisi.',
            'body_template.required' => 'Isi kerangka template surat wajib diisi.',
            'body_template.min' => 'Isi kerangka template minimal 10 karakter.',
        ];
    }
}