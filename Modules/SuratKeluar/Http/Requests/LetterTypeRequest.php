<?php

namespace Modules\SuratKeluar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\SuratKeluar\Models\LetterType;

class LetterTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', LetterType::class) ?? false;
    }

    public function rules(): array
    {
        $letterTypeId = $this->route('letter_type')?->id;

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('letter_types', 'code')->ignore($letterTypeId),
            ],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode jenis surat wajib diisi (contoh: C.11).',
            'code.unique' => 'Kode jenis surat tersebut sudah terdaftar.',
            'name.required' => 'Nama jenis surat wajib diisi.',
        ];
    }
}