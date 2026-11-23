@php
    $dashboardRoute = Auth::check() ? route(Auth::user()->dashboardRouteName()) : route('dashboard');
    $isDashboardActive = request()->routeIs('*.dashboard', 'dashboard');

    $activeLinkClass = 'flex min-h-[44px] items-center gap-3 rounded-lg border-l-4 border-white bg-white/15 px-3 py-2 text-sm font-semibold text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white';
    $inactiveLinkClass = 'flex min-h-[44px] items-center gap-3 rounded-lg border-l-4 border-transparent px-3 py-2 text-sm font-medium text-green-100 transition-colors hover:bg-white/10 hover:text-white active:bg-green-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white';
    $upcomingItemClass = 'flex min-h-[44px] items-center justify-between gap-2 rounded-lg border-l-4 border-transparent px-3 py-2 text-sm font-medium text-green-100';
@endphp

<nav aria-label="Menu aplikasi" class="flex-1 px-3 py-3 space-y-1">
    {{-- Item yang modulnya belum tersedia ditampilkan non-interaktif dengan label Segera (TODO: aktifkan otomatis saat route terdaftar) --}}
    <a
        href="{{ $dashboardRoute }}"
        @click="sidebarOpen = false"
        @if ($isDashboardActive) aria-current="page" @endif
        class="{{ $isDashboardActive ? $activeLinkClass : $inactiveLinkClass }}"
    >
        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span class="truncate">Beranda</span>
    </a>

    @canany(['surat.view', 'surat.jenis.manage', 'surat.kode.manage', 'surat.template.manage', 'surat.approval-log.view', 'shared.signature.upload'])
        <div class="pt-4">
            <p class="px-3 pb-1.5 text-xs font-semibold text-green-100">Persuratan</p>
            <div class="space-y-1">
                @can('surat.view')
                    @if (Route::has('surat-keluar.index'))
                        @php $isSuratKeluarActive = request()->routeIs('surat-keluar.*') && ! request()->routeIs('surat-keluar.master.*', 'surat-keluar.approval-logs.*'); @endphp
                        <a
                            href="{{ route('surat-keluar.index') }}"
                            @click="sidebarOpen = false"
                            @if ($isSuratKeluarActive) aria-current="page" @endif
                            class="{{ $isSuratKeluarActive ? $activeLinkClass : $inactiveLinkClass }}"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">Surat Keluar</span>
                        </a>
                    @else
                        <span aria-disabled="true" class="{{ $upcomingItemClass }}">
                            <span class="flex min-w-0 items-center gap-3">
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="truncate">Surat Keluar</span>
                            </span>
                            <span class="shrink-0 rounded bg-green-900 px-2 py-0.5 text-xs font-medium text-green-100">Segera</span>
                        </span>
                    @endif
                @endcan

                @canany(['surat.jenis.manage', 'surat.kode.manage', 'surat.template.manage'])
                    @if (Route::has('surat-keluar.master.index'))
                        @php $isMasterSuratActive = request()->routeIs('surat-keluar.master.*'); @endphp
                        <a
                            href="{{ route('surat-keluar.master.index') }}"
                            @click="sidebarOpen = false"
                            @if ($isMasterSuratActive) aria-current="page" @endif
                            class="{{ $isMasterSuratActive ? $activeLinkClass : $inactiveLinkClass }}"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="truncate">Master Surat</span>
                        </a>
                    @else
                        <span aria-disabled="true" class="{{ $upcomingItemClass }}">
                            <span class="flex min-w-0 items-center gap-3">
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="truncate">Master Surat</span>
                            </span>
                            <span class="shrink-0 rounded bg-green-900 px-2 py-0.5 text-xs font-medium text-green-100">Segera</span>
                        </span>
                    @endif
                @endcanany

                @can('surat.approval-log.view')
                    @if (Route::has('surat-keluar.approval-logs.index'))
                        @php $isApprovalLogActive = request()->routeIs('surat-keluar.approval-logs.*'); @endphp
                        <a
                            href="{{ route('surat-keluar.approval-logs.index') }}"
                            @click="sidebarOpen = false"
                            @if ($isApprovalLogActive) aria-current="page" @endif
                            class="{{ $isApprovalLogActive ? $activeLinkClass : $inactiveLinkClass }}"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <span class="truncate">Log Persetujuan</span>
                        </a>
                    @else
                        <span aria-disabled="true" class="{{ $upcomingItemClass }}">
                            <span class="flex min-w-0 items-center gap-3">
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                <span class="truncate">Log Persetujuan</span>
                            </span>
                            <span class="shrink-0 rounded bg-green-900 px-2 py-0.5 text-xs font-medium text-green-100">Segera</span>
                        </span>
                    @endif
                @endcan

                @can('shared.signature.upload')
                    @php $isSignatureActive = request()->routeIs('shared.signature.*'); @endphp
                    <a
                        href="{{ route('shared.signature.edit') }}"
                        @click="sidebarOpen = false"
                        @if ($isSignatureActive) aria-current="page" @endif
                        class="{{ $isSignatureActive ? $activeLinkClass : $inactiveLinkClass }}"
                    >
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <span class="truncate">Tanda Tangan Saya</span>
                    </a>
                @endcan
            </div>
        </div>
    @endcanany

    @canany(['surat-masuk.create', 'dokumen.view', 'dokumen.create', 'aset.view', 'aset.create'])
        <div class="pt-4">
            <p class="px-3 pb-1.5 text-xs font-semibold text-green-100">Dokumen &amp; Aset</p>
            <div class="space-y-1">
                @can('surat-masuk.create')
                    @if (Route::has('dokumen-aset.surat-masuk.index'))
                        @php $isSuratMasukActive = request()->routeIs('dokumen-aset.surat-masuk.*'); @endphp
                        <a
                            href="{{ route('dokumen-aset.surat-masuk.index') }}"
                            @click="sidebarOpen = false"
                            @if ($isSuratMasukActive) aria-current="page" @endif
                            class="{{ $isSuratMasukActive ? $activeLinkClass : $inactiveLinkClass }}"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <span class="truncate">Surat Masuk</span>
                        </a>
                    @else
                        <span aria-disabled="true" class="{{ $upcomingItemClass }}">
                            <span class="flex min-w-0 items-center gap-3">
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <span class="truncate">Surat Masuk</span>
                            </span>
                            <span class="shrink-0 rounded bg-green-900 px-2 py-0.5 text-xs font-medium text-green-100">Segera</span>
                        </span>
                    @endif
                @endcan

                @canany(['dokumen.view', 'dokumen.create'])
                    @if (Route::has('dokumen-aset.dokumen.index'))
                        @php $isDokumenActive = request()->routeIs('dokumen-aset.dokumen.*'); @endphp
                        <a
                            href="{{ route('dokumen-aset.dokumen.index') }}"
                            @click="sidebarOpen = false"
                            @if ($isDokumenActive) aria-current="page" @endif
                            class="{{ $isDokumenActive ? $activeLinkClass : $inactiveLinkClass }}"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                            <span class="truncate">Dokumen &amp; Arsip</span>
                        </a>
                    @else
                        <span aria-disabled="true" class="{{ $upcomingItemClass }}">
                            <span class="flex min-w-0 items-center gap-3">
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                                <span class="truncate">Dokumen &amp; Arsip</span>
                            </span>
                            <span class="shrink-0 rounded bg-green-900 px-2 py-0.5 text-xs font-medium text-green-100">Segera</span>
                        </span>
                    @endif
                @endcanany

                @canany(['aset.view', 'aset.create'])
                    @if (Route::has('dokumen-aset.aset.index'))
                        @php $isAsetActive = request()->routeIs('dokumen-aset.aset.*'); @endphp
                        <a
                            href="{{ route('dokumen-aset.aset.index') }}"
                            @click="sidebarOpen = false"
                            @if ($isAsetActive) aria-current="page" @endif
                            class="{{ $isAsetActive ? $activeLinkClass : $inactiveLinkClass }}"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            <span class="truncate">Aset Organisasi</span>
                        </a>
                    @else
                        <span aria-disabled="true" class="{{ $upcomingItemClass }}">
                            <span class="flex min-w-0 items-center gap-3">
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                <span class="truncate">Aset Organisasi</span>
                            </span>
                            <span class="shrink-0 rounded bg-green-900 px-2 py-0.5 text-xs font-medium text-green-100">Segera</span>
                        </span>
                    @endif
                @endcanany
            </div>
        </div>
    @endcanany

    @can('kegiatan.view')
        <div class="pt-4">
            <p class="px-3 pb-1.5 text-xs font-semibold text-green-100">Kegiatan</p>
            <div class="space-y-1">
                @if (Route::has('kegiatan.index'))
                    @php $isKegiatanActive = request()->routeIs('kegiatan.*'); @endphp
                    <a
                        href="{{ route('kegiatan.index') }}"
                        @click="sidebarOpen = false"
                        @if ($isKegiatanActive) aria-current="page" @endif
                        class="{{ $isKegiatanActive ? $activeLinkClass : $inactiveLinkClass }}"
                    >
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate">Kegiatan</span>
                    </a>
                @else
                    <span aria-disabled="true" class="{{ $upcomingItemClass }}">
                        <span class="flex min-w-0 items-center gap-3">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">Kegiatan</span>
                        </span>
                        <span class="shrink-0 rounded bg-green-900 px-2 py-0.5 text-xs font-medium text-green-100">Segera</span>
                    </span>
                @endif
            </div>
        </div>
    @endcan

    @canany(['shared.periods.manage', 'shared.users.view', 'shared.org-profile.edit'])
        <div class="pt-4">
            <p class="px-3 pb-1.5 text-xs font-semibold text-green-100">Administrasi Sistem</p>
            <div class="space-y-1">
                @can('shared.periods.manage')
                    @php $isPeriodsActive = request()->routeIs('admin.periods.*'); @endphp
                    <a
                        href="{{ route('admin.periods.index') }}"
                        @click="sidebarOpen = false"
                        @if ($isPeriodsActive) aria-current="page" @endif
                        class="{{ $isPeriodsActive ? $activeLinkClass : $inactiveLinkClass }}"
                    >
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="truncate">Periode Kepengurusan</span>
                    </a>
                @endcan

                @can('shared.users.view')
                    @if (Route::has('admin.users.index'))
                        @php $isUsersActive = request()->routeIs('admin.users.*'); @endphp
                        <a
                            href="{{ route('admin.users.index') }}"
                            @click="sidebarOpen = false"
                            @if ($isUsersActive) aria-current="page" @endif
                            class="{{ $isUsersActive ? $activeLinkClass : $inactiveLinkClass }}"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="truncate">Kelola Pengguna</span>
                        </a>
                    @else
                        <span aria-disabled="true" class="{{ $upcomingItemClass }}">
                            <span class="flex min-w-0 items-center gap-3">
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="truncate">Kelola Pengguna</span>
                            </span>
                            <span class="shrink-0 rounded bg-green-900 px-2 py-0.5 text-xs font-medium text-green-100">Segera</span>
                        </span>
                    @endif
                @endcan

                @can('shared.org-profile.edit')
                    @if (Route::has('admin.org-profile.edit'))
                        @php $isOrgProfileActive = request()->routeIs('admin.org-profile.*'); @endphp
                        <a
                            href="{{ route('admin.org-profile.edit') }}"
                            @click="sidebarOpen = false"
                            @if ($isOrgProfileActive) aria-current="page" @endif
                            class="{{ $isOrgProfileActive ? $activeLinkClass : $inactiveLinkClass }}"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="truncate">Profil &amp; Cap Organisasi</span>
                        </a>
                    @else
                        <span aria-disabled="true" class="{{ $upcomingItemClass }}">
                            <span class="flex min-w-0 items-center gap-3">
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="truncate">Profil &amp; Cap Organisasi</span>
                            </span>
                            <span class="shrink-0 rounded bg-green-900 px-2 py-0.5 text-xs font-medium text-green-100">Segera</span>
                        </span>
                    @endif
                @endcan
            </div>
        </div>
    @endcanany
</nav>
