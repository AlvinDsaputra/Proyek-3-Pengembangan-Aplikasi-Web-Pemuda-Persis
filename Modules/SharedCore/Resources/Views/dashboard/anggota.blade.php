<x-app-layout>
    <x-slot name="header">Beranda Anggota</x-slot>

    @php
        $displayNpa = filled($memberNpa ?? null) ? $memberNpa : 'Belum diisi';

        $anggotaActions = [
            [
                'label' => 'Profil Saya',
                'description' => 'Periksa data nama, alamat email akun, dan perbarui kata sandi Anda.',
                'href' => route('profile.edit'),
                'active' => true,
                'badge' => 'Aktif',
            ],
            [
                'label' => 'Dokumen Organisasi',
                'description' => 'Lihat dan unduh dokumen publik yang dibagikan untuk anggota PD Pemuda Persis.',
                'href' => Route::has('dokumen-aset.dokumen.index') ? route('dokumen-aset.dokumen.index') : null,
                'active' => Route::has('dokumen-aset.dokumen.index'),
                'badge' => Route::has('dokumen-aset.dokumen.index') ? 'Aktif' : 'Segera',
            ],
            [
                'label' => 'Pendaftaran Kegiatan',
                'description' => 'Pantau jadwal kegiatan organisasi dan daftarkan keikutsertaan Anda.',
                'href' => Route::has('kegiatan.index') ? route('kegiatan.index') : null,
                'active' => Route::has('kegiatan.index'),
                'badge' => Route::has('kegiatan.index') ? 'Aktif' : 'Segera',
            ],
        ];

        $anggotaScopeItems = [
            [
                'title' => 'Akses Dokumen Organisasi',
                'detail' => 'Melihat dokumen informasi dan arsip publik organisasi yang tersedia bagi anggota.',
                'status' => Route::has('dokumen-aset.dokumen.index') ? 'Aktif' : 'Segera',
            ],
            [
                'title' => 'Pendaftaran Kegiatan',
                'detail' => 'Melihat agenda kegiatan mendatang, mendaftar sebagai peserta, dan memantau riwayat keikutsertaan.',
                'status' => Route::has('kegiatan.index') ? 'Aktif' : 'Segera',
            ],
        ];
    @endphp

    <div class="mx-auto max-w-6xl space-y-6 pb-8">
        @include('shared-core::dashboard.partials.welcome-banner', [
            'user' => $user,
            'userRole' => $userRole,
            'activePeriod' => $activePeriod,
            'roleFocus' => 'Periksa identitas keanggotaan Anda, telusuri dokumen organisasi, dan ikuti agenda kegiatan mendatang.',
        ])

        <div class="grid grid-cols-1 items-start gap-5 lg:grid-cols-12">
            <section class="min-w-0 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6 lg:col-span-5">
                <h2 class="text-base font-semibold text-gray-900">Identitas Anggota</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Data akun keanggotaan yang tercatat pada sistem PD Pemuda Persis Kab. Bandung.
                </p>

                <dl class="mt-4 divide-y divide-gray-200 border-t border-gray-200 text-sm">
                    <div class="py-3">
                        <dt class="text-xs font-medium text-gray-600">Nama Lengkap</dt>
                        <dd class="mt-0.5 font-semibold text-gray-900 break-words">{{ $user->name }}</dd>
                    </div>
                    <div class="py-3">
                        <dt class="text-xs font-medium text-gray-600">Nomor Pokok Anggota (NPA)</dt>
                        <dd class="mt-0.5 font-semibold text-gray-900">{{ $displayNpa }}</dd>
                    </div>
                    <div class="py-3">
                        <dt class="text-xs font-medium text-gray-600">Email Akun</dt>
                        <dd class="mt-0.5 text-gray-800 break-all">{{ $user->email }}</dd>
                    </div>
                    <div class="py-3">
                        <dt class="text-xs font-medium text-gray-600">Periode Aktif</dt>
                        <dd class="mt-0.5 font-medium text-gray-900">{{ $activePeriod?->name ?? 'Belum ada periode aktif' }}</dd>
                    </div>
                </dl>

                <div class="mt-4">
                    <a
                        href="{{ route('profile.edit') }}"
                        class="inline-flex min-h-[44px] items-center justify-center rounded-lg border border-green-700 bg-white px-4 py-2.5 text-sm font-semibold text-green-800 transition-colors hover:bg-green-50 active:bg-green-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2"
                    >
                        Perbarui Profil Akun
                    </a>
                </div>
            </section>

            <div class="min-w-0 lg:col-span-7">
                @include('shared-core::dashboard.partials.quick-actions', [
                    'title' => 'Layanan Anggota',
                    'subtitle' => 'Akses pengaturan profil akun atau pantau ketersediaan modul dokumen dan kegiatan.',
                    'actions' => $anggotaActions,
                ])
            </div>
        </div>

        @include('shared-core::dashboard.partials.module-notice', [
            'title' => 'Informasi Akses Modul Anggota',
            'description' => 'Fitur yang tersedia untuk anggota akan muncul di menu navigasi segera setelah modul terkait selesai dibangun.',
            'items' => $anggotaScopeItems,
        ])
    </div>
</x-app-layout>
