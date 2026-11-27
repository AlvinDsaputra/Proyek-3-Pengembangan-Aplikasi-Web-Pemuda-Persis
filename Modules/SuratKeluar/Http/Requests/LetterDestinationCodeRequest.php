<?php

namespace Modules\SuratKeluar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\SuratKeluar\Models\LetterDestinationCode;

class LetterDestinationCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', LetterDestinationCode::class) ?? false;
    }

    public function rules(): array
    {
        $destinationCodeId = $this->route('destination_code')?->id;

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('letter_destination_codes', 'code')->ignore($destinationCodeId),
            ],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode tujuan surat wajib diisi (contoh: J.200).',
            'code.unique' => 'Kode tujuan surat tersebut sudah terdaftar.',
            'name.required' => 'Nama instansi/tujuan wajib diisi.',
        ];
    }
}