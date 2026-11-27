@php
    $user = $authUser ?? Auth::user();
    $isDashboardActive = request()->routeIs('dashboard');

    // Muat seluruh permission user dalam 1 query agar sidebar cepat tanpa query berulang
    $userPermissionMap = $user
        ? \Illuminate\Support\Facades\DB::table('user_roles')
            ->join('role_permissions', 'user_roles.role_id', '=', 'role_permissions.role_id')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where('user_roles.user_id', $user->id)
            ->pluck('permissions.slug')
            ->flip()
            ->all()
        : [];

    $activeLinkClass = 'flex min-h-[44px] items-center gap-2.5 rounded-xl bg-white px-3 py-2 text-sm font-semibold text-pine shadow-sm transition-colors duration-150 ease-spring focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-pine';
    $inactiveLinkClass = 'flex min-h-[44px] items-center gap-2.5 rounded-xl px-3 py-2 text-sm font-medium text-pine-text transition-colors duration-150 ease-spring hover:bg-white/10 hover:text-white active:bg-pine-deep focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white';
    $upcomingItemClass = 'flex min-h-[44px] items-center justify-between gap-2 rounded-xl px-3 py-2 text-sm font-medium text-pine-subtle';
    $groupHeadingClass = 'truncate whitespace-nowrap px-3 pb-1.5 text-[11px] font-semibold uppercase tracking-wider text-pine-subtle';

    $menuGroups = [
        [
            'heading' => 'Persuratan',
            'items' => [
                [
                    'label' => 'Surat Keluar',
                    'permissions' => ['surat.view'],
                    'route' => 'surat-keluar.index',
                    'active' => request()->routeIs('surat-keluar.*') && ! request()->routeIs('surat-keluar.master.*', 'surat-keluar.approval-logs.*'),
                    'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                ],
                [
                    'label' => 'Master Surat',
                    'permissions' => ['surat.jenis.manage', 'surat.kode.manage', 'surat.template.manage'],
                    'route' => 'surat-keluar.master.index',
                    'active' => request()->routeIs('surat-keluar.master.*'),
                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                ],
                [
                    'label' => 'Log Persetujuan',
                    'permissions' => ['surat.approval-log.view'],
                    'route' => 'surat-keluar.approval-logs.index',
                    'active' => request()->routeIs('surat-keluar.approval-logs.*'),
                    'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                ],
                [
                    'label' => 'Tanda Tangan Saya',
                    'permissions' => ['shared.signature.upload'],
                    'route' => 'shared.signature.edit',
                    'active' => request()->routeIs('shared.signature.*'),
                    'icon' => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z',
                ],
            ],
        ],
        [
            'heading' => 'Dokumen & Aset',
            'items' => [
                [
                    'label' => 'Surat Masuk',
                    'permissions' => ['surat-masuk.create'],
                    'route' => 'dokumen-aset.surat-masuk.index',
                    'active' => request()->routeIs('dokumen-aset.surat-masuk.*'),
                    'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4',
                ],
                [
                    'label' => 'Dokumen & Arsip',
                    'permissions' => ['dokumen.view', 'dokumen.create'],
                    'route' => 'dokumen-aset.dokumen.index',
                    'active' => request()->routeIs('dokumen-aset.dokumen.*'),
                    'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
                ],
                [
                    'label' => 'Aset Organisasi',
                    'permissions' => ['aset.view', 'aset.create'],
                    'route' => 'dokumen-aset.aset.index',
                    'active' => request()->routeIs('dokumen-aset.aset.*'),
                    'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4',
                ],
            ],
        ],
        [
            'heading' => 'Kegiatan',
            'items' => [
                [
                    'label' => 'Kegiatan',
                    'permissions' => ['kegiatan.view'],
                    'route' => 'kegiatan.index',
                    'active' => request()->routeIs('kegiatan.*'),
                    'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                ],
            ],
        ],
        [
            'heading' => 'Administrasi',
            'items' => [
                [
                    'label' => 'Periode',
                    'permissions' => ['shared.periods.manage'],
                    'route' => 'admin.periods.index',
                    'active' => request()->routeIs('admin.periods.*'),
                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                ],
                [
                    'label' => 'Pengguna',
                    'permissions' => ['shared.users.view'],
                    'route' => 'admin.users.index',
                    'active' => request()->routeIs('admin.users.*'),
                    'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                ],
                [
                    'label' => 'Profil & Cap',
                    'permissions' => ['shared.org-profile.edit'],
                    'route' => 'admin.org-profile.edit',
                    'active' => request()->routeIs('admin.org-profile.*'),
                    'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                ],
            ],
        ],
    ];
@endphp

<aside
    id="app-sidebar"
    aria-label="Navigasi utama"
    :class="[
        sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        sidebarReady ? 'lg:transition-[width] lg:duration-200 lg:ease-spring' : ''
    ]"
    class="fixed inset-y-0 left-0 z-40 flex h-full w-64 max-w-[85vw] shrink-0 -translate-x-full flex-col overflow-x-hidden bg-pine text-white transition-transform duration-200 ease-spring lg:static lg:w-64 lg:translate-x-0"
>
    <div
        data-sidebar-header
        class="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-white/10 px-3.5"
    >
        <a
            href="{{ route('dashboard') }}"
            @click="sidebarOpen = false"
            data-sidebar-brand
            :title="sidebarCollapsed ? 'PD Pemuda Persis Kab. Bandung' : null"
            :aria-label="sidebarCollapsed ? 'Beranda PD Pemuda Persis Kab. Bandung' : null"
            class="flex min-h-[44px] min-w-0 flex-1 items-center gap-2.5 rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white"
        >
            <div class="squircle flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-xs font-bold tracking-tight text-pine shadow-sm" aria-hidden="true">
                PP
            </div>
            <div data-sidebar-label class="min-w-0">
                <div class="truncate text-sm font-semibold leading-tight text-white">PD Pemuda Persis</div>
                <div class="mt-0.5 truncate text-xs text-pine-text">Kab. Bandung</div>
            </div>
        </a>

        <button
            type="button"
            @click="toggleCollapse()"
            aria-expanded="true"
            aria-label="Ciutkan bilah sisi"
            :aria-expanded="(!sidebarCollapsed).toString()"
            :aria-label="sidebarCollapsed ? 'Perluas bilah sisi' : 'Ciutkan bilah sisi'"
            :title="sidebarCollapsed ? 'Perluas bilah sisi' : 'Ciutkan bilah sisi'"
            class="hidden h-11 w-11 shrink-0 items-center justify-center rounded-xl text-pine-text transition-colors duration-150 ease-spring hover:bg-white/10 hover:text-white active:bg-pine-deep focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white lg:inline-flex"
        >
            <svg data-sidebar-icon-collapse class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <rect width="18" height="18" x="3" y="3" rx="2" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v18M16 15l-3-3 3-3" />
            </svg>
            <svg data-sidebar-icon-expand class="hidden h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <rect width="18" height="18" x="3" y="3" rx="2" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v18M14 9l3 3-3 3" />
            </svg>
        </button>

        <button
            type="button"
            x-ref="closeSidebarBtn"
            @click="closeSidebar()"
            aria-label="Tutup menu navigasi"
            class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-white transition-colors duration-150 ease-spring hover:bg-white/10 active:bg-pine-deep focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white lg:hidden"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav aria-label="Menu aplikasi" class="flex-1 space-y-1 overflow-y-auto px-3 py-4 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
        {{-- TODO: menu bertanda Segera aktif otomatis saat route modul terdaftar --}}
        <a
            href="{{ route('dashboard') }}"
            @click="sidebarOpen = false"
            @if ($isDashboardActive) aria-current="page" @endif
            data-sidebar-item
            :title="sidebarCollapsed ? 'Beranda' : null"
            :aria-label="sidebarCollapsed ? 'Beranda' : null"
            class="{{ $isDashboardActive ? $activeLinkClass : $inactiveLinkClass }}"
        >
            <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span data-sidebar-label class="truncate whitespace-nowrap">Beranda</span>
        </a>

        @foreach ($menuGroups as $group)
            @php
                $visibleItems = collect($group['items'])->filter(function (array $item) use ($userPermissionMap): bool {
                    foreach ($item['permissions'] as $permission) {
                        if (isset($userPermissionMap[$permission])) {
                            return true;
                        }
                    }

                    return false;
                });
            @endphp

            @if ($visibleItems->isNotEmpty())
                <div class="pt-4">
                    <p data-sidebar-label class="{{ $groupHeadingClass }}">{{ $group['heading'] }}</p>
                    <div data-sidebar-divider class="hidden" aria-hidden="true"></div>

                    <div class="space-y-0.5">
                        @foreach ($visibleItems as $item)
                            @if (Route::has($item['route']))
                                <a
                                    href="{{ route($item['route']) }}"
                                    @click="sidebarOpen = false"
                                    @if ($item['active']) aria-current="page" @endif
                                    data-sidebar-item
                                    :title="sidebarCollapsed ? @js($item['label']) : null"
                                    :aria-label="sidebarCollapsed ? @js($item['label']) : null"
                                    class="{{ $item['active'] ? $activeLinkClass : $inactiveLinkClass }}"
                                >
                                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                    </svg>
                                    <span data-sidebar-label class="truncate whitespace-nowrap">{{ $item['label'] }}</span>
                                </a>
                            @else
                                <span
                                    aria-disabled="true"
                                    data-sidebar-item
                                    :title="sidebarCollapsed ? @js($item['label'] . ' (Segera)') : null"
                                    :aria-label="sidebarCollapsed ? @js($item['label'] . ', segera hadir') : null"
                                    class="{{ $upcomingItemClass }}"
                                >
                                    <span class="flex min-w-0 items-center gap-2.5">
                                        <svg class="h-[18px] w-[18px] shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                        </svg>
                                        <span data-sidebar-label class="truncate whitespace-nowrap">{{ $item['label'] }}</span>
                                    </span>
                                    <span data-sidebar-label class="shrink-0 whitespace-nowrap text-[11px] font-medium text-pine-subtle">Segera</span>
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </nav>
</aside>
