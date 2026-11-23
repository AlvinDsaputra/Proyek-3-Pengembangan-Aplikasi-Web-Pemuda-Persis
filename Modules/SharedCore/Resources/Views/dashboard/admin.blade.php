<x-app-layout>
    <x-slot name="header">Beranda Admin</x-slot>

    @php
        $adminActions = [
            [
                'label' => 'Periode Kepengurusan',
                'description' => 'Tambah masa bakti kepengurusan dan aktifkan periode yang sedang berjalan.',
                'href' => route('admin.periods.index'),
                'active' => true,
                'badge' => 'Aktif',
            ],
            [
                'label' => 'Kelola Pengguna',
                'description' => 'Atur data akun pengurus dan anggota, penetapan peran, serta status aktif akun.',
                'href' => Route::has('admin.users.index') ? route('admin.users.index') : null,
                'active' => Route::has('admin.users.index'),
                'badge' => Route::has('admin.users.index') ? 'Aktif' : 'Segera',
            ],
            [
                'label' => 'Profil & Cap Organisasi',
                'description' => 'Perbarui data kop surat resmi dan berkas cap PD Pemuda Persis Kab. Bandung.',
                'href' => Route::has('admin.org-profile.edit') ? route('admin.org-profile.edit') : null,
                'active' => Route::has('admin.org-profile.edit'),
                'badge' => Route::has('admin.org-profile.edit') ? 'Aktif' : 'Segera',
            ],
        ];

        $adminModuleItems = [
            [
                'title' => 'Kelola Pengguna',
                'detail' => 'Pengelolaan akun lintas ' . ($stats['totalRoles'] ?? 5) . ' peran organisasi beserta aktivasi akses pada periode berjalan.',
                'status' => Route::has('admin.users.index') ? 'Aktif' : 'Segera',
            ],
            [
                'title' => 'Profil & Cap Organisasi',
                'detail' => 'Pembaruan alamat sekretariat, kop surat resmi, dan gambar cap organisasi untuk pencetakan dokumen.',
                'status' => Route::has('admin.org-profile.edit') ? 'Aktif' : 'Segera',
            ],
            [
                'title' => 'Log Persetujuan Surat',
                'detail' => 'Pemantauan jejak audit persetujuan surat keluar untuk keperluan pengawasan administrasi sistem.',
                'status' => Route::has('surat-keluar.approval-logs.index') ? 'Aktif' : 'Segera',
            ],
        ];
    @endphp

    <div class="mx-auto max-w-6xl space-y-6 pb-8">
        @include('shared-core::dashboard.partials.welcome-banner', [
            'user' => $user,
            'userRole' => $userRole,
            'activePeriod' => $activePeriod,
            'roleFocus' => 'Kelola periode kepengurusan, akun pengguna, serta pengaturan profil dan cap organisasi.',
        ])

        <section aria-label="Ringkasan data sistem" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card
                label="Total Pengguna"
                :value="$stats['totalUsers']"
                :note="$stats['inactiveUsers'] > 0 ? $stats['inactiveUsers'] . ' akun nonaktif' : 'Seluruh akun berstatus aktif'"
            />

            <x-stat-card
                label="Pengguna Aktif"
                :value="$stats['activeUsers']"
                tone="accent"
                :note="'Dari ' . $stats['totalUsers'] . ' akun terdaftar'"
            />

            <x-stat-card
                label="Periode Kepengurusan"
                :value="$stats['totalPeriods']"
                :tone="$activePeriod ? 'neutral' : 'warning'"
                :note="$activePeriod ? 'Aktif: ' . $activePeriod->name : 'Belum ada periode aktif'"
            />

            <x-stat-card
                label="Cap Organisasi"
                :value="$stats['hasOrganizationStamp'] ? 'Tersedia' : 'Belum diunggah'"
                :tone="$stats['hasOrganizationStamp'] ? 'accent' : 'warning'"
                :note="$stats['hasOrganizationProfile'] ? 'Profil organisasi tercatat' : 'Profil organisasi belum diatur'"
            />
        </section>

        <div class="grid grid-cols-1 items-start gap-5 lg:grid-cols-12">
            <div class="min-w-0 lg:col-span-7">
                @include('shared-core::dashboard.partials.quick-actions', [
                    'title' => 'Akses Cepat Administrasi',
                    'subtitle' => 'Buka fitur sistem yang sudah aktif atau lihat fitur administrasi yang sedang disiapkan.',
                    'actions' => $adminActions,
                ])
            </div>

            <div class="min-w-0 lg:col-span-5">
                @include('shared-core::dashboard.partials.module-notice', [
                    'title' => 'Status Modul Sistem',
                    'description' => 'Modul administrasi yang sedang dalam tahap pengembangan akan aktif otomatis setelah selesai dibangun.',
                    'items' => $adminModuleItems,
                ])
            </div>
        </div>
    </div>
</x-app-layout>
