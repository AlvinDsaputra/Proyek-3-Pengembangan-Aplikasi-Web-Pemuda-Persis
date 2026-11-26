@php
    $hasStamp = ! empty($stats['hasOrganizationStamp']);
    $hasProfile = ! empty($stats['hasOrganizationProfile']);
    $totalUsers = (int) ($stats['totalUsers'] ?? 0);
    $activeUsers = (int) ($stats['activeUsers'] ?? 0);
    $inactiveUsers = (int) ($stats['inactiveUsers'] ?? 0);
    $totalPeriods = (int) ($stats['totalPeriods'] ?? 0);
    $activeRatio = $totalUsers > 0 ? (int) round(($activeUsers / $totalUsers) * 100) : 0;

    $roles = $roleSummaries ?? [];
    $periods = $recentPeriods ?? collect();

    $activePeriodRange = ($activePeriod && $activePeriod->start_date && $activePeriod->end_date)
        ? $activePeriod->start_date->format('d/m/Y') . ' s.d. ' . $activePeriod->end_date->format('d/m/Y')
        : null;

    $adminLinks = [
        [
            'label' => 'Periode Kepengurusan',
            'href' => route('admin.periods.index'),
            'active' => true,
        ],
        [
            'label' => 'Kelola Akun Pengguna',
            'href' => Route::has('admin.users.index') ? route('admin.users.index') : null,
            'active' => Route::has('admin.users.index'),
        ],
        [
            'label' => 'Profil & Cap Organisasi',
            'href' => Route::has('admin.org-profile.edit') ? route('admin.org-profile.edit') : null,
            'active' => Route::has('admin.org-profile.edit'),
        ],
        [
            'label' => 'Log Persetujuan Surat',
            'href' => Route::has('surat-keluar.approval-logs.index') ? route('surat-keluar.approval-logs.index') : null,
            'active' => Route::has('surat-keluar.approval-logs.index'),
        ],
    ];
@endphp

<div class="mx-auto max-w-6xl space-y-6 pb-8">
    <div class="min-w-0">
        <p class="text-sm font-medium text-ink-muted">Assalamu'alaikum, <span class="font-semibold text-ink">{{ $user->name }}</span></p>
    </div>

    <section aria-label="Ringkasan periode dan sistem" class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        <div class="flex min-w-0 flex-col justify-between rounded-2xl squircle bg-pine p-6 text-white shadow-[0_14px_30px_-16px_rgba(13,59,46,0.5)] lg:col-span-5">
            <div>
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-pine-subtle">Periode Aktif</span>
                    @if ($activePeriod)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-2.5 py-1 text-xs font-semibold text-white">
                            <span class="h-1.5 w-1.5 rounded-full bg-pine-subtle" aria-hidden="true"></span>
                            Berjalan
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-ochre-bg px-2.5 py-1 text-xs font-semibold text-ochre">
                            Belum aktif
                        </span>
                    @endif
                </div>

                <h2 class="mt-3 text-2xl font-bold tracking-tight text-white">
                    {{ $activePeriod ? $activePeriod->name : 'Belum Ada Periode Aktif' }}
                </h2>

                <p class="mt-1.5 text-sm tabular-nums text-pine-text">
                    {{ $activePeriodRange ?? 'Aktifkan satu periode kepengurusan agar operasional berjalan.' }}
                </p>
            </div>

            <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-white/15 pt-4">
                <span class="text-xs tabular-nums text-pine-subtle">
                    {{ $totalPeriods }} periode tercatat
                </span>

                <a
                    href="{{ route('admin.periods.index') }}"
                    class="inline-flex min-h-[44px] items-center justify-center gap-1.5 rounded-full bg-white px-4 py-2 text-sm font-semibold text-pine shadow-sm transition-[transform,background-color] duration-150 ease-spring hover:-translate-y-0.5 hover:bg-brand-soft active:translate-y-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-pine"
                >
                    <span>Kelola Periode</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="surface-card flex min-w-0 flex-col overflow-hidden lg:col-span-7">
            <div class="flex items-center justify-between gap-2 border-b border-stone-line px-6 py-3.5">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-ink-muted">Ringkasan Data Sistem</h2>
                <span class="text-xs font-medium tabular-nums text-ink-muted">{{ $stats['totalRoles'] ?? count($roles) }} peran terdaftar</span>
            </div>

            <div class="grid flex-1 grid-cols-1 divide-y divide-stone-line sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                <div class="flex min-w-0 flex-col justify-between gap-4 p-6">
                    <div>
                        <p class="text-xs font-medium text-ink-muted">Total Pengguna</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums tracking-tight text-ink">{{ $totalUsers }}</p>
                    </div>
                    <p class="rounded-lg bg-stone-soft/75 px-2.5 py-1.5 text-xs font-medium text-ink-muted">
                        {{ $inactiveUsers > 0 ? $inactiveUsers . ' akun nonaktif' : 'Semua akun aktif' }}
                    </p>
                </div>

                <div class="flex min-w-0 flex-col justify-between gap-4 p-6">
                    <div>
                        <p class="text-xs font-medium text-ink-muted">Pengguna Aktif</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums tracking-tight text-brand-ink">{{ $activeUsers }}</p>
                    </div>
                    <p class="rounded-lg bg-brand-soft/80 px-2.5 py-1.5 text-xs font-medium tabular-nums text-brand-ink">
                        {{ $totalUsers > 0 ? $activeRatio . '% dari total akun' : 'Belum ada akun' }}
                    </p>
                </div>

                <div class="flex min-w-0 flex-col justify-between gap-4 p-6">
                    <div>
                        <p class="text-xs font-medium text-ink-muted">Cap Organisasi</p>
                        <p class="mt-2 text-2xl font-bold tracking-tight {{ $hasStamp ? 'text-brand-ink' : 'text-ochre' }}">
                            {{ $hasStamp ? 'Tersedia' : 'Belum ada' }}
                        </p>
                    </div>
                    <p class="rounded-lg px-2.5 py-1.5 text-xs font-medium {{ $hasProfile ? 'bg-stone-soft/75 text-ink-muted' : 'bg-ochre-bg text-ochre' }}">
                        {{ $hasProfile ? 'Profil tercatat' : 'Profil belum diisi' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section aria-label="Sebaran akun per peran" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="shrink-0 text-xs font-semibold uppercase tracking-wider text-ink-muted">Sebaran Akun</h2>

        @if (! empty($roles))
            <ul class="flex flex-wrap items-center gap-2">
                @foreach ($roles as $roleItem)
                    <li class="inline-flex items-center gap-2 rounded-full border border-stone-line bg-white px-3.5 py-1.5 text-sm shadow-[0_1px_2px_rgba(13,59,46,0.03)]">
                        <span class="font-medium text-ink">{{ $roleItem['name'] }}</span>
                        <span class="inline-flex h-5 min-w-[20px] items-center justify-center rounded-full bg-brand-soft px-1.5 text-xs font-semibold tabular-nums text-brand-ink">
                            {{ (int) ($roleItem['usersCount'] ?? 0) }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-sm text-ink-muted">Belum ada data peran.</p>
        @endif
    </section>

    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
        <section class="surface-card min-w-0 overflow-hidden lg:col-span-8">
            <div class="flex items-center justify-between gap-2 border-b border-stone-line px-6 py-4">
                <h2 class="text-sm font-semibold text-ink">Riwayat Periode</h2>
                <a
                    href="{{ route('admin.periods.index') }}"
                    class="inline-flex min-h-[44px] items-center rounded-lg px-2 text-xs font-semibold text-brand-ink transition-colors duration-150 hover:text-brand hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand"
                >
                    Lihat semua
                </a>
            </div>

            @if ($periods->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-line text-sm">
                        <thead class="bg-alabaster/80 text-left text-xs font-semibold text-ink-muted">
                            <tr>
                                <th class="px-6 py-3">Nama Periode</th>
                                <th class="px-6 py-3">Masa Bakti</th>
                                <th class="px-6 py-3 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-line">
                            @foreach ($periods as $period)
                                <tr class="transition-colors duration-150 hover:bg-alabaster/80">
                                    <td class="px-6 py-3.5 font-medium text-ink">{{ $period->name }}</td>
                                    <td class="px-6 py-3.5 tabular-nums text-ink-muted">
                                        @if ($period->start_date && $period->end_date)
                                            {{ $period->start_date->format('d/m/Y') }} s.d. {{ $period->end_date->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-right">
                                        @if ($period->is_active)
                                            <span class="inline-flex rounded-full bg-brand-soft px-2.5 py-0.5 text-xs font-semibold text-brand-ink">Aktif</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-stone-soft px-2.5 py-0.5 text-xs font-medium text-ink-muted">Arsip</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="px-6 py-8 text-sm text-ink-muted">
                    Belum ada periode kepengurusan. Tambahkan periode melalui halaman Periode Kepengurusan.
                </p>
            @endif
        </section>

        <section class="surface-card min-w-0 overflow-hidden lg:col-span-4">
            <div class="border-b border-stone-line px-6 py-4">
                <h2 class="text-sm font-semibold text-ink">Akses Cepat Sistem</h2>
            </div>

            <div class="divide-y divide-stone-line">
                @foreach ($adminLinks as $item)
                    @if ($item['active'] && ! empty($item['href']))
                        <a
                            href="{{ $item['href'] }}"
                            class="group flex min-h-[48px] items-center justify-between gap-3 px-6 py-3 text-sm font-medium text-ink transition-colors duration-150 hover:bg-alabaster/80 hover:text-brand-ink focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-brand"
                        >
                            <span>{{ $item['label'] }}</span>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-brand-ink">
                                Buka
                                <svg class="h-3.5 w-3.5 transition-transform duration-150 ease-spring group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </a>
                    @else
                        {{-- TODO: aktifkan saat route modul administrasi tersedia --}}
                        <div aria-disabled="true" class="flex min-h-[48px] items-center justify-between gap-3 px-6 py-3 text-sm text-ink-muted">
                            <span>{{ $item['label'] }}</span>
                            <span class="rounded-full bg-stone-soft px-2.5 py-0.5 text-xs font-medium text-ink-muted">Segera</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>
    </div>
</div>
