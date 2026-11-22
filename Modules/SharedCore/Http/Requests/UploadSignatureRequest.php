<?php

namespace Modules\SharedCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UploadSignatureRequest extends FormRequest
{
    private const PNG_MAGIC_BYTES = "\x89PNG\r\n\x1a\n";

    public function authorize(): bool
    {
        return $this->user()?->hasPermission('shared.signature.upload') ?? false;
    }

    public function rules(): array
    {
        return [
            'signature' => [
                'required',
                'file',
                'mimes:png',
                'mimetypes:image/png',
                'max:2048',
                'dimensions:min_width=80,min_height=40,max_width=3000,max_height=3000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'signature.required'   => 'Pilih file tanda tangan berformat PNG terlebih dahulu.',
            'signature.file'       => 'Berkas tanda tangan tidak valid. Pastikan Anda mengunggah file gambar PNG.',
            'signature.mimes'      => 'Format file harus PNG (.png).',
            'signature.mimetypes'  => 'File harus berupa gambar PNG yang valid.',
            'signature.max'        => 'Ukuran file terlalu besar. Maksimal ukuran file tanda tangan adalah 2 MB.',
            'signature.dimensions' => 'Dimensi gambar tidak sesuai ketentuan. Ukuran minimal 80x40 px dan maksimal 3000x3000 px.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->has('signature')) {
                    return;
                }

                $file = $this->file('signature');

                if (! $file || ! $file->isValid()) {
                    return;
                }

                // Cek 8 byte header PNG agar file non-PNG yang diubah ekstensinya tetap ditolak.
                if (! $this->hasValidPngHeader($file->getRealPath() ?: $file->getPathname())) {
                    $validator->errors()->add(
                        'signature',
                        'Isi file bukan gambar PNG yang valid. Gunakan file PNG asli, bukan hasil ganti ekstensi.'
                    );
                }
            },
        ];
    }

    private function hasValidPngHeader(string|false $path): bool
    {
        if (! is_string($path) || $path === '' || ! is_readable($path)) {
            return false;
        }

        $handle = @fopen($path, 'rb');

        if ($handle === false) {
            return false;
        }

        try {
            $header = fread($handle, 8);
        } finally {
            fclose($handle);
        }

        return $header === self::PNG_MAGIC_BYTES;
    }
}
