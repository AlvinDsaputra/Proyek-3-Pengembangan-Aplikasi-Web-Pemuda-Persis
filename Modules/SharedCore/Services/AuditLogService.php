<?php

namespace Modules\SharedCore\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AuditLogService
{
    /**
     * Catat aksi pengguna ke audit log.
     *
     * @param string      $action   Contoh: 'created', 'updated', 'approved', 'submitted'
     * @param string      $module   Contoh: 'SuratKeluar', 'DokumenAset', 'Kegiatan', 'SharedCore'
     * @param Model|null  $subject  Model yang menjadi subjek aksi (opsional)
     * @param array|null  $changes  ['before' => [...], 'after' => [...]] (opsional)
     */
    public static function record(
        User $user,
        string $action,
        string $module,
        ?Model $subject = null,
        ?array $changes = null
    ): void {
        // TODO: Implementasi penuh — simpan ke tabel audit_logs
        // Gunakan setelah migration 000014 (create_audit_logs_table) dijalankan

        Log::channel('daily')->info('[AUDIT]', [
            'user_id'      => $user->id,
            'user_name'    => $user->name,
            'action'       => $action,
            'module'       => $module,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject?->getKey(),
            'changes'      => $changes,
            'ip'           => request()->ip(),
        ]);
    }
}
