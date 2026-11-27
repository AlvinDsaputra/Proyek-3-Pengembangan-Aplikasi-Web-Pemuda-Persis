<?php

namespace Modules\SharedCore\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\SharedCore\Models\SignatureImage;
use RuntimeException;
use Throwable;

class SignatureService
{
    public function getActiveForUser(User $user): ?SignatureImage
    {
        return $user->activeSignature()->first();
    }

    public function uploadForUser(User $user, UploadedFile $file): SignatureImage
    {
        $directory = "signatures/{$user->id}";
        $filename = Str::uuid()->toString().'.png';
        $storedPath = $file->storeAs($directory, $filename, 'local');

        if ($storedPath === false) {
            throw new RuntimeException('Gagal menyimpan file tanda tangan ke storage privat.');
        }

        $previousSignatureId = null;

        try {
            $signature = DB::transaction(function () use ($user, $storedPath, &$previousSignatureId): SignatureImage {
                $previousSignature = $user->signatureImages()
                    ->active()
                    ->latest('id')
                    ->lockForUpdate()
                    ->first();

                $previousSignatureId = $previousSignature?->id;

                // Nonaktifkan record lama tanpa menghapus file fisik agar histori dokumen tetap utuh.
                $user->signatureImages()
                    ->active()
                    ->update(['is_active' => false]);

                return $user->signatureImages()->create([
                    'file_path'   => $storedPath,
                    'is_active'   => true,
                    'uploaded_at' => now(),
                ]);
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($storedPath);

            throw $exception;
        }

        $user->unsetRelation('activeSignature');

        AuditLogService::record(
            $user,
            'uploaded_signature',
            'SharedCore',
            $signature,
            [
                'before' => $previousSignatureId !== null ? [
                    'signature_id' => $previousSignatureId,
                    'is_active'    => true,
                ] : null,
                'after' => [
                    'signature_id' => $signature->id,
                    'is_active'    => true,
                ],
            ]
        );

        return $signature;
    }

    public function removeActiveForUser(User $user): bool
    {
        $deactivated = DB::transaction(function () use ($user): ?SignatureImage {
            $activeSignature = $user->signatureImages()
                ->active()
                ->latest('id')
                ->lockForUpdate()
                ->first();

            if (! $activeSignature) {
                return null;
            }

            // Soft-deactivate agar file historis tetap terjaga untuk arsip surat lama.
            $user->signatureImages()
                ->active()
                ->update(['is_active' => false]);

            return $activeSignature;
        });

        if (! $deactivated) {
            return false;
        }

        $user->unsetRelation('activeSignature');

        AuditLogService::record(
            $user,
            'removed_signature',
            'SharedCore',
            $deactivated,
            [
                'before' => [
                    'signature_id' => $deactivated->id,
                    'is_active'    => true,
                ],
                'after' => [
                    'signature_id' => $deactivated->id,
                    'is_active'    => false,
                ],
            ]
        );

        return true;
    }

    public function getAbsolutePath(SignatureImage $signature): ?string
    {
        if (! $signature->existsInStorage()) {
            return null;
        }

        return $signature->absolutePath();
    }

    public function getDataUri(SignatureImage $signature): ?string
    {
        if (! $signature->existsInStorage()) {
            return null;
        }

        $contents = Storage::disk('local')->get($signature->file_path);

        if ($contents === null) {
            return null;
        }

        return 'data:image/png;base64,'.base64_encode($contents);
    }
}
