@php
    $displayNpa = filled($memberNpa ?? null) ? $memberNpa : 'Belum diisi';
    $branchName = filled($memberBranchName ?? null) ? $memberBranchName : null;

    $memberServices = [
        [
            'label' => 'Dokumen Publik Organisasi',
            'note' => 'Unduh edaran, pedoman, dan dokumen terbuka PD.',
            'href' => Route::has('dokumen-aset.dokumen.index') ? route('dokumen-aset.dokumen.index') : null,
            'active' => Route::has('dokumen-aset.dokumen.index'),
            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        ],
        [
            'label' => 'Pendaftaran Kegiatan',
            'note' => 'Lihat jadwal kajian, pelatihan, dan agenda cabang.',
            'href' => Route::has('kegiatan.index') ? route('kegiatan.index') : null,
            'active' => Route::has('kegiatan.index'),
            'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        ],
    ];
@endphp

<div class="mx-auto max-w-3xl space-y-6 pb-8">
    <section
        aria-label="Kartu anggota digital"
        class="overflow-hidden rounded-2xl squircle border border-stone-line bg-white shadow-[0_2px_6px_rgba(13,59,46,0.05),0_16px_32px_-20px_rgba(13,59,46,0.2)]"
    >
        <div class="bg-pine px-6 py-6 text-white">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-pine-subtle">
                    PD Pemuda Persis Kab. Bandung
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-2.5 py-1 text-xs font-semibold text-white">
                    <span class="h-1.5 w-1.5 rounded-full bg-pine-subtle" aria-hidden="true"></span>
                    Anggota Aktif
                </span>
            </div>

            <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-pine-text">Assalamu'alaikum,</p>
                    <h2 class="mt-0.5 truncate text-2xl font-bold tracking-tight text-white">
                        {{ $user->name }}
                    </h2>
                    <p class="mt-1 text-sm text-pine-text">
                        {{ $activePeriod ? 'Periode ' . $activePeriod->name : 'Belum ada periode kepengurusan aktif' }}
                    </p>
                </div>

                <div class="shrink-0 rounded-xl border border-white/15 bg-pine-deep px-4 py-2.5 sm:text-right">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-pine-subtle">NPA</p>
                    <p class="mt-0.5 text-base font-bold tabular-nums tracking-tight text-white">{{ $displayNpa }}</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-5">
            <dl class="divide-y divide-stone-line text-sm">
                @if ($branchName)
                    <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3">
                        <dt class="text-ink-muted">Asal PC</dt>
                        <dd class="font-semibold text-ink sm:col-span-2">{{ $branchName }}</dd>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3">
                    <dt class="text-ink-muted">Email</dt>
                    <dd class="break-all font-medium text-ink sm:col-span-2">{{ $user->email }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3">
                    <dt class="text-ink-muted">Status Akun</dt>
                    <dd class="font-semibold text-brand-ink sm:col-span-2">Aktif</dd>
                </div>
            </dl>

            <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-stone-line pt-4">
                <p class="text-xs text-ink-muted">Perbarui data diri atau kata sandi melalui halaman profil.</p>

                <a
                    href="{{ route('profile.edit') }}"
                    class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-stone-line bg-white px-4 py-2 text-sm font-semibold text-brand-ink transition-[transform,background-color,border-color] duration-150 ease-spring hover:-translate-y-0.5 hover:border-brand/40 hover:bg-brand-soft active:translate-y-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2"
                >
                    Ubah Profil
                </a>
            </div>
        </div>
    </section>

    <section aria-label="Layanan anggota" class="space-y-3">
        <h2 class="text-xs font-semibold uppercase tracking-wider text-ink-muted">Layanan Anggota</h2>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach ($memberServices as $item)
                @if ($item['active'] && ! empty($item['href']))
                    <a
                        href="{{ $item['href'] }}"
                        class="group flex min-h-[116px] flex-col justify-between gap-4 rounded-2xl squircle border border-stone-line bg-white p-5 shadow-[0_1px_2px_rgba(13,59,46,0.04)] transition-[transform,border-color,box-shadow] duration-200 ease-spring hover:-translate-y-0.5 hover:border-brand/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-soft text-brand-ink" aria-hidden="true">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                </svg>
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-brand-ink">
                                Buka
                                <svg class="h-3.5 w-3.5 transition-transform duration-150 ease-spring group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-ink transition-colors duration-150 group-hover:text-brand-ink">{{ $item['label'] }}</p>
                            <p class="mt-1 text-xs leading-relaxed text-ink-muted">{{ $item['note'] }}</p>
                        </div>
                    </a>
                @else
                    {{-- TODO: aktifkan saat route layanan anggota tersedia --}}
                    <div
                        aria-disabled="true"
                        class="flex min-h-[116px] flex-col justify-between gap-4 rounded-2xl squircle border border-stone-line bg-white/80 p-5"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-stone-soft text-ink-muted" aria-hidden="true">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                </svg>
                            </span>
                            <span class="rounded-full bg-stone-soft px-2.5 py-0.5 text-xs font-medium text-ink-muted">Segera</span>
                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-ink-muted">{{ $item['label'] }}</p>
                            <p class="mt-1 text-xs leading-relaxed text-ink-muted">{{ $item['note'] }}</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </section>
</div>
