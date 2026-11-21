<?php

namespace Modules\SharedCore\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Modules\SharedCore\Models\Role;

class NotificationService
{
    /**
     * Kirim notifikasi ke satu pengguna.
     *
     * @param string      $type    Contoh: 'surat_pending', 'surat_approved', 'dokumen_uploaded'
     * @param string      $message Teks notifikasi (bahasa Indonesia)
     * @param string|null $url     URL relatif tujuan saat notifikasi diklik (opsional)
     * @param array       $data    Data tambahan bebas (opsional)
     */
    public static function send(
        User $recipient,
        string $type,
        string $message,
        ?string $url = null,
        array $data = []
    ): void {
        // TODO: Implementasi penuh — simpan ke tabel notifications
        // Gunakan setelah migration 000013 (create_notifications_table) dijalankan

        Log::channel('daily')->info('[NOTIFICATION]', [
            'recipient_id'   => $recipient->id,
            'recipient_name' => $recipient->name,
            'type'           => $type,
            'message'        => $message,
            'url'            => $url,
            'data'           => $data,
        ]);
    }

    /**
     * Kirim notifikasi ke semua pengguna dengan role tertentu.
     *
     * @param string $roleSlug Slug role tujuan (contoh: 'ketua', 'sekretaris')
     */
    public static function sendToRole(
        string $roleSlug,
        string $type,
        string $message,
        ?string $url = null,
        array $data = []
    ): void {
        // TODO: Implementasi penuh — query user_roles, kirim ke masing-masing

        Log::channel('daily')->info('[NOTIFICATION_ROLE]', [
            'role'    => $roleSlug,
            'type'    => $type,
            'message' => $message,
            'url'     => $url,
            'data'    => $data,
        ]);
    }
}
