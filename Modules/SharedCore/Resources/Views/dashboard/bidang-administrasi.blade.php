<x-app-layout>
    <x-slot name="header">Beranda Bidang Administrasi</x-slot>

    @php
        $branchCount = (int) ($totalBranches ?? 0);

        $bidangActions = [
            [
                'label' => 'Master Surat',
                'description' => 'Kelola daftar jenis surat, indeks kode tujuan, dan template surat resmi organisasi.',
                'href' => Route::has('surat-keluar.master.index') ? route('surat-keluar.master.index') : null,
                'active' => Route::has('surat-keluar.master.index'),
                'badge' => Route::has('surat-keluar.master.index') ? 'Aktif' : 'Segera',
            ],
            [
                'label' => 'Dokumen & Arsip',
                'description' => 'Unggah, perbarui, dan tata arsip dokumen resmi PD Pemuda Persis Kab. Bandung.',
                'href' => Route::has('dokumen-aset.dokumen.index') ? route('dokumen-aset.dokumen.index') : null,
                'active' => Route::has('dokumen-aset.dokumen.index'),
                'badge' => Route::has('dokumen-aset.dokumen.index') ? 'Aktif' : 'Segera',
            ],
            [
                'label' => 'Inventaris Aset & Label QR',
                'description' => 'Catat data barang inventaris organisasi, kondisi aset, dan cetak label QR pelacakan.',
                'href' => Route::has('dokumen-aset.aset.index') ? route('dokumen-aset.aset.index') : null,
                'active' => Route::has('dokumen-aset.aset.index'),
                'badge' => Route::has('dokumen-aset.aset.index') ? 'Aktif' : 'Segera',
            ],
            [
                'label' => 'Absensi & Laporan Kegiatan',
                'description' => 'Kelola pencatatan kehadiran peserta dan rekap laporan pelaksanaan kegiatan.',
                'href' => Route::has('kegiatan.index') ? route('kegiatan.index') : null,
                'active' => Route::has('kegiatan.index'),
                'badge' => Route::has('kegiatan.index') ? 'Aktif' : 'Segera',
            ],
        ];

        $bidangScopeItems = [
            [
                'title' => 'Master Surat (Jenis Surat, Indeks Kode Tujuan, Template Surat)',
                'detail' => 'Pemeliharaan data acuan penomoran dan format template resmi yang dipakai saat penyusunan surat.',
                'status' => Route::has('surat-keluar.master.index') ? 'Aktif' : 'Segera',
            ],
            [
                'title' => 'Pengelolaan Dokumen Organisasi',
                'detail' => 'Pengunggahan berkas dokumen resmi, pembaruan kategori arsip, dan penataan riwayat dokumen.',
                'status' => Route::has('dokumen-aset.dokumen.index') ? 'Aktif' : 'Segera',
            ],
            [
                'title' => 'Inventaris Aset & Label QR',
                'detail' => 'Pencatatan inventaris barang milik organisasi beserta kode label QR untuk pengecekan kondisi aset.',
                'status' => Route::has('dokumen-aset.aset.index') ? 'Aktif' : 'Segera',
            ],
            [
                'title' => 'Absensi dan Laporan Kegiatan',
                'detail' => 'Rekap daftar hadir peserta kegiatan serta penyusunan laporan administrasi pelaksanaan acara.',
                'status' => Route::has('kegiatan.index') ? 'Aktif' : 'Segera',
            ],
        ];
    @endphp

    <div class="mx-auto max-w-6xl space-y-6 pb-8">
        @include('shared-core::dashboard.partials.welcome-banner', [
            'user' => $user,
            'userRole' => $userRole,
            'activePeriod' => $activePeriod,
            'roleFocus' => 'Kelola master data persuratan, arsip dokumen, inventaris aset berlabel QR, serta dukungan absensi kegiatan.',
        ])

        <div class="grid grid-cols-1 items-start gap-5 lg:grid-cols-12">
            <div class="min-w-0 space-y-4 lg:col-span-5">
                <x-stat-card
                    label="Pimpinan Cabang Terdaftar"
                    :value="$branchCount"
                    :tone="$branchCount > 0 ? 'accent' : 'warning'"
                    :note="$branchCount > 0 ? 'Tercatat pada basis data cabang organisasi' : 'Data Pimpinan Cabang belum di-seed ke basis data'"
                />

                <x-stat-card
                    label="Periode Kepengurusan Aktif"
                    :value="$activePeriod ? $activePeriod->name : 'Belum aktif'"
                    :tone="$activePeriod ? 'neutral' : 'warning'"
                    :note="$activePeriod && $activePeriod->start_date && $activePeriod->end_date ? $activePeriod->start_date->format('d/m/Y') . ' s.d. ' . $activePeriod->end_date->format('d/m/Y') : 'Menunggu aktivasi periode oleh Super Admin'"
                />
            </div>

            <div class="min-w-0 lg:col-span-7">
                @include('shared-core::dashboard.partials.quick-actions', [
                    'title' => 'Fokus Tugas Bidang Administrasi',
                    'subtitle' => 'Daftar modul kerja utama Bidang Administrasi akan aktif setelah masing-masing modul selesai dibangun.',
                    'actions' => $bidangActions,
                ])
            </div>
        </div>

        @include('shared-core::dashboard.partials.module-notice', [
            'title' => 'Ruang Lingkup Modul Bidang Administrasi',
            'description' => 'Rincian area kerja yang dikelola Bidang Administrasi pada periode kepengurusan berjalan.',
            'items' => $bidangScopeItems,
        ])
    </div>
</x-app-layout>
