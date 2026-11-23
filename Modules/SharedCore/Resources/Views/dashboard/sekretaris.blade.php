<x-app-layout>
    <x-slot name="header">Beranda Sekretaris</x-slot>

    @php
        $sekretarisActions = [
            [
                'label' => 'Tanda Tangan Saya',
                'description' => 'Unggah atau perbarui berkas tanda tangan PNG transparan untuk pengesahan surat keluar.',
                'href' => route('shared.signature.edit'),
                'active' => true,
                'badge' => 'Aktif',
            ],
            [
                'label' => 'Draf Surat Keluar',
                'description' => 'Susun, perbarui, dan ajukan draf surat keluar kepada Ketua PD untuk disetujui.',
                'href' => Route::has('surat-keluar.index') ? route('surat-keluar.index') : null,
                'active' => Route::has('surat-keluar.index'),
                'badge' => Route::has('surat-keluar.index') ? 'Aktif' : 'Segera',
            ],
            [
                'label' => 'Surat Masuk',
                'description' => 'Catat surat masuk yang diterima sekretariat dan simpan salinan arsipnya.',
                'href' => Route::has('dokumen-aset.surat-masuk.index') ? route('dokumen-aset.surat-masuk.index') : null,
                'active' => Route::has('dokumen-aset.surat-masuk.index'),
                'badge' => Route::has('dokumen-aset.surat-masuk.index') ? 'Aktif' : 'Segera',
            ],
            [
                'label' => 'Dokumen & Arsip',
                'description' => 'Telusuri arsip dokumen organisasi yang diperlukan untuk keperluan administrasi.',
                'href' => Route::has('dokumen-aset.dokumen.index') ? route('dokumen-aset.dokumen.index') : null,
                'active' => Route::has('dokumen-aset.dokumen.index'),
                'badge' => Route::has('dokumen-aset.dokumen.index') ? 'Aktif' : 'Segera',
            ],
            [
                'label' => 'Pengelolaan Kegiatan',
                'description' => 'Kelola jadwal kegiatan, data peserta, dan kebutuhan administrasi acara.',
                'href' => Route::has('kegiatan.index') ? route('kegiatan.index') : null,
                'active' => Route::has('kegiatan.index'),
                'badge' => Route::has('kegiatan.index') ? 'Aktif' : 'Segera',
            ],
        ];

        $sekretarisScopeItems = [
            [
                'title' => 'Draf dan Pengajuan Surat Keluar',
                'detail' => 'Pembuatan draf surat resmi menggunakan template organisasi, penyuntingan draf, dan pengajuan persetujuan ke Ketua PD.',
                'status' => Route::has('surat-keluar.index') ? 'Aktif' : 'Segera',
            ],
            [
                'title' => 'Pencatatan Surat Masuk',
                'detail' => 'Registrasi surat masuk khusus sekretariat beserta berkas lampiran untuk memudahkan penelusuran.',
                'status' => Route::has('dokumen-aset.surat-masuk.index') ? 'Aktif' : 'Segera',
            ],
            [
                'title' => 'Dokumen dan Arsip Organisasi',
                'detail' => 'Akses terhadap dokumen resmi organisasi lintas bidang dan periode kepengurusan.',
                'status' => Route::has('dokumen-aset.dokumen.index') ? 'Aktif' : 'Segera',
            ],
            [
                'title' => 'Pengelolaan Kegiatan',
                'detail' => 'Penyusunan agenda kegiatan organisasi serta pemantauan pendaftaran dan catatan kegiatan.',
                'status' => Route::has('kegiatan.index') ? 'Aktif' : 'Segera',
            ],
        ];
    @endphp

    <div class="mx-auto max-w-6xl space-y-6 pb-8">
        @include('shared-core::dashboard.partials.welcome-banner', [
            'user' => $user,
            'userRole' => $userRole,
            'activePeriod' => $activePeriod,
            'roleFocus' => 'Siapkan tanda tangan digital Anda untuk penandatanganan surat keluar serta kelola alur persuratan dan arsip sekretariat.',
        ])

        <div class="grid grid-cols-1 items-start gap-5 lg:grid-cols-12">
            <div class="min-w-0 lg:col-span-7">
                @include('shared-core::dashboard.partials.signature-status-card', [
                    'user' => $user,
                    'signature' => $signature,
                    'hasSignature' => $hasSignature,
                    'signatureUrl' => $signatureUrl,
                    'signatureHistoryCount' => $signatureHistoryCount,
                ])
            </div>

            <div class="min-w-0 lg:col-span-5">
                @include('shared-core::dashboard.partials.quick-actions', [
                    'title' => 'Akses Persuratan dan Arsip',
                    'subtitle' => 'Tanda tangan digital sudah siap digunakan; modul persuratan lainnya sedang disiapkan.',
                    'actions' => $sekretarisActions,
                ])
            </div>
        </div>

        @include('shared-core::dashboard.partials.module-notice', [
            'title' => 'Ruang Lingkup Administrasi Sekretaris',
            'description' => 'Modul operasional sekretariat yang akan terhubung ke beranda ini seiring penyelesaian pengembangan.',
            'items' => $sekretarisScopeItems,
        ])
    </div>
</x-app-layout>
