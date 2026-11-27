@php
    $branchCount = (int) ($totalBranches ?? 0);
    $periodRange = ($activePeriod && $activePeriod->start_date && $activePeriod->end_date)
        ? $activePeriod->start_date->format('d/m/Y') . ' s.d. ' . $activePeriod->end_date->format('d/m/Y')
        : null;

    $masterRouteAvailable = Route::has('surat-keluar.master.index');

    $masterRows = [
        [
            'code' => '01',
            'name' => 'Jenis Surat & Kode Jenis',
            'scope' => 'Tambah, ubah, nonaktifkan',
        ],
        [
            'code' => '02',
            'name' => 'Indeks Tujuan Surat',
            'scope' => 'Tambah, ubah, nonaktifkan',
        ],
        [
            'code' => '03',
            'name' => 'Template Surat',
            'scope' => 'Tambah, ubah, nonaktifkan',
        ],
    ];

    $otherModules = [
        [
            'label' => 'Dokumen & Arsip',
            'note' => 'Katalog berkas dan arsip kelembagaan',
            'href' => Route::has('dokumen-aset.dokumen.index') ? route('dokumen-aset.dokumen.index') : null,
            'active' => Route::has('dokumen-aset.dokumen.index'),
            'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
        ],
        [
            'label' => 'Inventaris Aset & Label QR',
            'note' => 'Pendataan aset organisasi dan cetak kode QR',
            'href' => Route::has('dokumen-aset.aset.index') ? route('dokumen-aset.aset.index') : null,
            'active' => Route::has('dokumen-aset.aset.index'),
            'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        ],
        [
            'label' => 'Absensi & Laporan Kegiatan',
            'note' => 'Rekap daftar hadir serta laporan program kerja',
            'href' => Route::has('kegiatan.index') ? route('kegiatan.index') : null,
            'active' => Route::has('kegiatan.index'),
            'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        ],
    ];
@endphp

<div class="mx-auto max-w-6xl space-y-7 pb-8">
    <section aria-label="Ringkasan periode dan cabang" class="flex flex-col gap-5 border-b border-stone-line pb-6 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider text-brand-ink">
                {{ $activePeriod ? 'Periode ' . $activePeriod->name : 'Belum ada periode kepengurusan aktif' }}
            </p>
            <h2 class="mt-1 text-2xl font-bold tracking-tight text-ink">
                Assalamu'alaikum, {{ $user->name }}
            </h2>
            <p class="mt-1 text-sm tabular-nums text-ink-muted">
                {{ $periodRange ? 'Masa bakti ' . $periodRange : 'Pengelolaan data master persuratan dan operasional bidang.' }}
            </p>
        </div>

        <div class="shrink-0 sm:text-right">
            <p class="text-4xl font-bold tabular-nums leading-none tracking-tight text-pine">
                {{ $branchCount }}
            </p>
            <p class="mt-1.5 text-xs font-semibold text-ink-muted">Pimpinan Cabang Terdaftar</p>
            <p class="mt-0.5 text-xs font-medium {{ $branchCount > 0 ? 'text-brand-ink' : 'text-ochre' }}">
                {{ $branchCount > 0 ? 'Data cabang tersedia' : 'Belum ada data cabang' }}
            </p>
        </div>
    </section>

    <section aria-label="Katalog master data persuratan" class="surface-card min-w-0 overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-line px-6 py-4">
            <div>
                <h2 class="text-sm font-semibold text-ink">Master Data Persuratan</h2>
                <p class="mt-0.5 text-xs text-ink-muted">Referensi kode jenis, indeks tujuan, dan template surat keluar.</p>
            </div>

            @if ($masterRouteAvailable)
                <a
                    href="{{ route('surat-keluar.master.index') }}"
                    class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-brand px-4 py-2 text-xs font-semibold text-white transition-[transform,background-color] duration-150 ease-spring hover:-translate-y-0.5 hover:bg-brand-hover active:translate-y-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2"
                >
                    Buka Master Surat
                </a>
            @else
                {{-- TODO: aktifkan saat route surat-keluar.master.index terdaftar --}}
                <span class="inline-flex rounded-full bg-stone-soft px-3 py-1 text-xs font-medium text-ink-muted">
                    Modul sedang disiapkan
                </span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-line text-sm">
                <thead class="bg-alabaster/80 text-left text-xs font-semibold text-ink-muted">
                    <tr>
                        <th class="px-6 py-3">Data Master</th>
                        <th class="px-6 py-3">Hak Kelola</th>
                        <th class="px-6 py-3 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-line">
                    @foreach ($masterRows as $row)
                        <tr class="transition-colors duration-150 hover:bg-alabaster/80">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-semibold tabular-nums text-ink-muted">{{ $row['code'] }}</span>
                                    <span class="font-semibold text-ink">{{ $row['name'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-ink-muted">{{ $row['scope'] }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $masterRouteAvailable ? 'bg-brand-soft text-brand-ink' : 'bg-stone-soft text-ink-muted' }}">
                                    {{ $masterRouteAvailable ? 'Aktif' : 'Segera' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section aria-label="Dock operasional bidang" class="space-y-3">
        <h2 class="text-xs font-semibold uppercase tracking-wider text-ink-muted">Dokumen, Aset &amp; Kegiatan</h2>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            @foreach ($otherModules as $item)
                @if ($item['active'] && ! empty($item['href']))
                    <a
                        href="{{ $item['href'] }}"
                        class="group flex min-h-[124px] flex-col justify-between gap-4 rounded-2xl squircle border border-stone-line bg-white p-5 shadow-[0_1px_2px_rgba(13,59,46,0.04)] transition-[transform,border-color,box-shadow] duration-200 ease-spring hover:-translate-y-0.5 hover:border-brand/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2"
                    >
                        <div class="flex items-start justify-between gap-3">
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
                    {{-- TODO: aktifkan saat route modul tersedia --}}
                    <div
                        aria-disabled="true"
                        class="flex min-h-[124px] flex-col justify-between gap-4 rounded-2xl squircle border border-stone-line bg-white/80 p-5"
                    >
                        <div class="flex items-start justify-between gap-3">
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
