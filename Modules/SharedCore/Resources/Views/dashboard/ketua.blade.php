@php
    $isSignatureReady = ! empty($hasSignature);

    $approvalSteps = [
        [
            'title' => 'Tinjau draf surat',
            'detail' => 'Periksa draf surat keluar yang diajukan Sekretaris.',
            'ready' => false,
        ],
        [
            'title' => 'Setujui atau minta revisi',
            'detail' => 'Terbitkan persetujuan surat atau kirim catatan perbaikan.',
            'ready' => false,
        ],
        [
            'title' => 'Bubuhkan tanda tangan',
            'detail' => $isSignatureReady
                ? 'Tanda tangan PNG Anda aktif dan siap dibubuhkan otomatis.'
                : 'Unggah tanda tangan PNG agar proses pengesahan dapat berjalan.',
            'ready' => $isSignatureReady,
        ],
        [
            'title' => 'Unduh PDF & pantau arsip',
            'detail' => 'Akses salinan PDF resmi serta rekap persuratan keluar.',
            'ready' => false,
        ],
    ];

    $ketuaGroups = [
        [
            'heading' => 'Persuratan',
            'items' => [
                [
                    'label' => 'Persetujuan & Arsip Surat Keluar',
                    'href' => Route::has('surat-keluar.index') ? route('surat-keluar.index') : null,
                    'active' => Route::has('surat-keluar.index'),
                ],
                [
                    'label' => 'Log Persetujuan',
                    'href' => Route::has('surat-keluar.approval-logs.index') ? route('surat-keluar.approval-logs.index') : null,
                    'active' => Route::has('surat-keluar.approval-logs.index'),
                ],
            ],
        ],
        [
            'heading' => 'Pengawasan Bidang',
            'items' => [
                [
                    'label' => 'Dokumen & Arsip',
                    'href' => Route::has('dokumen-aset.dokumen.index') ? route('dokumen-aset.dokumen.index') : null,
                    'active' => Route::has('dokumen-aset.dokumen.index'),
                ],
                [
                    'label' => 'Aset Organisasi',
                    'href' => Route::has('dokumen-aset.aset.index') ? route('dokumen-aset.aset.index') : null,
                    'active' => Route::has('dokumen-aset.aset.index'),
                ],
                [
                    'label' => 'Laporan Kegiatan',
                    'href' => Route::has('kegiatan.index') ? route('kegiatan.index') : null,
                    'active' => Route::has('kegiatan.index'),
                ],
            ],
        ],
    ];
@endphp

<div class="mx-auto max-w-6xl space-y-8 pb-8">
    <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-12">
        <div class="min-w-0 space-y-5 lg:col-span-7">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-brand-ink">
                    {{ $activePeriod ? 'Periode ' . $activePeriod->name : 'Belum ada periode kepengurusan aktif' }}
                </p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-ink">
                    Assalamu'alaikum, {{ $user->name }}
                </h2>
                <p class="mt-1 text-sm text-ink-muted">
                    Meja pengesahan surat keluar dan pemantauan kinerja bidang.
                </p>
            </div>

            @include('shared-core::dashboard.partials.signature-status-card', [
                'cardTitle' => 'Tanda Tangan Ketua PD',
                'user' => $user,
                'signature' => $signature,
                'hasSignature' => $hasSignature,
                'signatureUrl' => $signatureUrl,
                'signatureHistoryCount' => $signatureHistoryCount,
            ])
        </div>

        <aside aria-label="Alur pengesahan surat" class="min-w-0 lg:col-span-5 lg:pt-2">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-ink-muted">Alur Pengesahan Surat</h2>

            <ol class="relative mt-5 ml-3.5 space-y-6 border-l border-stone-line pl-7">
                @foreach ($approvalSteps as $index => $step)
                    <li class="relative">
                        <span
                            class="absolute -left-[43px] top-0 flex h-7 w-7 items-center justify-center rounded-full border text-xs font-bold tabular-nums {{ $step['ready'] ? 'border-brand bg-brand-soft text-brand-ink' : 'border-stone-line bg-white text-pine' }}"
                            aria-hidden="true"
                        >
                            {{ $index + 1 }}
                        </span>
                        <p class="text-sm font-semibold text-ink">{{ $step['title'] }}</p>
                        <p class="mt-0.5 text-sm leading-relaxed text-ink-muted">{{ $step['detail'] }}</p>
                    </li>
                @endforeach
            </ol>
        </aside>
    </div>

    <section aria-label="Akses modul Ketua PD" class="surface-card min-w-0 overflow-hidden">
        <div class="grid grid-cols-1 divide-y divide-stone-line md:grid-cols-2 md:divide-x md:divide-y-0">
            @foreach ($ketuaGroups as $group)
                <div class="min-w-0">
                    <div class="border-b border-stone-line bg-alabaster/70 px-6 py-3">
                        <h2 class="text-xs font-semibold uppercase tracking-wider text-ink-muted">{{ $group['heading'] }}</h2>
                    </div>

                    <div class="divide-y divide-stone-line">
                        @foreach ($group['items'] as $item)
                            @if ($item['active'] && ! empty($item['href']))
                                <a
                                    href="{{ $item['href'] }}"
                                    class="group flex min-h-[48px] items-center justify-between gap-3 px-6 py-3.5 text-sm font-medium text-ink transition-colors duration-150 hover:bg-alabaster/80 hover:text-brand-ink focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-brand"
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
                                {{-- TODO: aktifkan saat route modul tersedia --}}
                                <div aria-disabled="true" class="flex min-h-[48px] items-center justify-between gap-3 px-6 py-3.5 text-sm text-ink-muted">
                                    <span>{{ $item['label'] }}</span>
                                    <span class="rounded-full bg-stone-soft px-2.5 py-0.5 text-xs font-medium text-ink-muted">Segera</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
