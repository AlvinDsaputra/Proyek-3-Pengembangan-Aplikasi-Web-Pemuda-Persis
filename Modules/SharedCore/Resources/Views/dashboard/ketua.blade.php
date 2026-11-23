<x-app-layout>
    <x-slot name="header">Beranda Ketua PD</x-slot>

    @php
        $ketuaActions = [
            [
                'label' => 'Tanda Tangan Saya',
                'description' => 'Unggah, pratinjau, atau perbarui tanda tangan PNG transparan untuk pengesahan surat.',
                'href' => route('shared.signature.edit'),
                'active' => true,
                'badge' => 'Aktif',
            ],
            [
                'label' => 'Persetujuan Surat Keluar',
                'description' => 'Tinjau draf surat yang diajukan Sekretaris dan berikan keputusan persetujuan.',
                'href' => Route::has('surat-keluar.index') ? route('surat-keluar.index') : null,
                'active' => Route::has('surat-keluar.index'),
                'badge' => Route::has('surat-keluar.index') ? 'Aktif' : 'Segera',
            ],
            [
                'label' => 'Laporan Dokumen & Arsip',
                'description' => 'Periksa arsip dokumen resmi dan rekap laporan dokumen organisasi.',
                'href' => Route::has('dokumen-aset.dokumen.index') ? route('dokumen-aset.dokumen.index') : null,
                'active' => Route::has('dokumen-aset.dokumen.index'),
                'badge' => Route::has('dokumen-aset.dokumen.index') ? 'Aktif' : 'Segera',
            ],
            [
                'label' => 'Laporan Kegiatan',
                'description' => 'Pantau jadwal pelaksanaan dan rekap laporan kegiatan PD Pemuda Persis.',
                'href' => Route::has('kegiatan.index') ? route('kegiatan.index') : null,
                'active' => Route::has('kegiatan.index'),
                'badge' => Route::has('kegiatan.index') ? 'Aktif' : 'Segera',
            ],
        ];

        $ketuaScopeItems = [
            [
                'title' => 'Persetujuan Surat Keluar',
                'detail' => 'Meninjau draf surat resmi dari Sekretaris, menyetujui penerbitan nomor surat, atau mengembalikan draf dengan catatan revisi.',
                'status' => Route::has('surat-keluar.index') ? 'Aktif' : 'Segera',
            ],
            [
                'title' => 'Laporan Dokumen dan Arsip',
                'detail' => 'Memantau arsip dokumen resmi organisasi serta melihat laporan dokumen lintas bidang.',
                'status' => Route::has('dokumen-aset.dokumen.index') ? 'Aktif' : 'Segera',
            ],
            [
                'title' => 'Laporan Kegiatan Organisasi',
                'detail' => 'Melihat agenda kegiatan yang dijadwalkan serta laporan kehadiran dan hasil pelaksanaan kegiatan.',
                'status' => Route::has('kegiatan.index') ? 'Aktif' : 'Segera',
            ],
        ];
    @endphp

    <div class="mx-auto max-w-6xl space-y-6 pb-8">
        @include('shared-core::dashboard.partials.welcome-banner', [
            'user' => $user,
            'userRole' => $userRole,
            'activePeriod' => $activePeriod,
            'roleFocus' => 'Pastikan berkas tanda tangan digital Anda aktif untuk pengesahan surat keluar, serta pantau laporan dokumen dan kegiatan.',
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
                    'title' => 'Akses Utama Ketua PD',
                    'subtitle' => 'Kelola kesiapan tanda tangan digital dan pantau modul kewenangan Ketua.',
                    'actions' => $ketuaActions,
                ])
            </div>
        </div>

        @include('shared-core::dashboard.partials.module-notice', [
            'title' => 'Kewenangan Keputusan Ketua PD',
            'description' => 'Daftar modul persetujuan dan pemantauan yang menjadi kewenangan Ketua pada sistem ini.',
            'items' => $ketuaScopeItems,
        ])
    </div>
</x-app-layout>
