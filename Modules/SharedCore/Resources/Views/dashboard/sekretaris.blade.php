@php
    $signatureReady = ! empty($hasSignature);
    $stampReady = ! empty($hasOrganizationStamp);
    $suratActive = Route::has('surat-keluar.index');

    $persuratanItems = [
        [
            'label' => 'Draf & Arsip Surat Keluar',
            'note' => 'Susun draf surat, ajukan persetujuan Ketua PD, dan unduh PDF final.',
            'href' => $suratActive ? route('surat-keluar.index') : null,
            'active' => $suratActive,
        ],
        [
            'label' => 'Surat Masuk',
            'note' => 'Catat nomor agenda serta arsip berkas surat masuk.',
            'href' => Route::has('dokumen-aset.surat-masuk.index') ? route('dokumen-aset.surat-masuk.index') : null,
            'active' => Route::has('dokumen-aset.surat-masuk.index'),
        ],
        [
            'label' => 'Log Persetujuan',
            'note' => 'Pantau riwayat pengajuan, revisi, dan pengesahan surat keluar.',
            'href' => Route::has('surat-keluar.approval-logs.index') ? route('surat-keluar.approval-logs.index') : null,
            'active' => Route::has('surat-keluar.approval-logs.index'),
        ],
    ];

    $dokumenItems = [
        [
            'label' => 'Dokumen & Arsip',
            'note' => 'Kelola dokumen resmi serta arsip administrasi periode berjalan.',
            'href' => Route::has('dokumen-aset.dokumen.index') ? route('dokumen-aset.dokumen.index') : null,
            'active' => Route::has('dokumen-aset.dokumen.index'),
        ],
        [
            'label' => 'Aset Organisasi',
            'note' => 'Lihat daftar inventaris dan pemeliharaan aset PD.',
            'href' => Route::has('dokumen-aset.aset.index') ? route('dokumen-aset.aset.index') : null,
            'active' => Route::has('dokumen-aset.aset.index'),
        ],
        [
            'label' => 'Kegiatan',
            'note' => 'Kelola agenda, pendaftaran peserta, dan laporan kegiatan.',
            'href' => Route::has('kegiatan.index') ? route('kegiatan.index') : null,
            'active' => Route::has('kegiatan.index'),
        ],
    ];
@endphp

<div class="mx-auto max-w-6xl space-y-6 pb-8">
    <section aria-label="Status kesiapan sekretariat" class="surface-card px-5 py-4">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0">
                <h2 class="truncate text-base font-bold text-ink">Assalamu'alaikum, {{ $user->name }}</h2>
                <p class="mt-0.5 text-xs text-ink-muted">
                    {{ $activePeriod ? 'Periode aktif: ' . $activePeriod->name : 'Belum ada periode kepengurusan aktif' }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold {{ $signatureReady ? 'bg-brand-soft text-brand-ink' : 'bg-ochre-bg text-ochre' }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $signatureReady ? 'bg-brand' : 'bg-ochre-border' }}" aria-hidden="true"></span>
                    TTD Sekretaris: {{ $signatureReady ? 'Aktif' : 'Belum diunggah' }}
                </span>

                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold {{ $stampReady ? 'bg-brand-soft text-brand-ink' : 'bg-ochre-bg text-ochre' }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $stampReady ? 'bg-brand' : 'bg-ochre-border' }}" aria-hidden="true"></span>
                    Cap Organisasi: {{ $stampReady ? 'Tersedia' : 'Belum diunggah' }}
                </span>

                <a
                    href="{{ route('shared.signature.edit') }}"
                    class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-white transition-[transform,background-color] duration-150 ease-spring hover:-translate-y-0.5 hover:bg-brand-hover active:translate-y-0 active:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2"
                >
                    Kelola TTD
                </a>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
        <section
            class="surface-card min-w-0 overflow-hidden lg:col-span-7"
            x-data="{
                tab: 'persuratan',
                pillLeft: 0,
                pillWidth: 0,
                pillAnimated: false,
                resizeObserver: null,
                init() {
                    this.$nextTick(() => this.syncPill());
                    if (document.fonts && document.fonts.ready) {
                        document.fonts.ready.then(() => this.syncPill());
                    }
                    if (typeof ResizeObserver !== 'undefined' && this.$refs.tablist) {
                        this.resizeObserver = new ResizeObserver(() => this.syncPill());
                        this.resizeObserver.observe(this.$refs.tablist);
                    }
                },
                destroy() {
                    this.resizeObserver?.disconnect();
                },
                movePill(el, animate = false) {
                    if (!el) return;
                    this.pillAnimated = animate;
                    this.pillLeft = el.offsetLeft;
                    this.pillWidth = el.offsetWidth;
                },
                select(name, el) {
                    this.tab = name;
                    this.movePill(el, true);
                },
                syncPill() {
                    const target = this.tab === 'persuratan' ? this.$refs.tabPersuratan : this.$refs.tabDokumen;
                    this.movePill(target, false);
                }
            }"
            @resize.window="syncPill()"
        >
            <div class="flex flex-col gap-3 border-b border-stone-line px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-sm font-semibold text-ink">Ruang Kerja Sekretariat</h2>

                <div
                    x-ref="tablist"
                    role="tablist"
                    aria-label="Kategori menu sekretariat"
                    class="relative inline-flex max-w-full items-center rounded-full border border-stone-line bg-stone-soft p-1"
                    @keydown.arrow-right.prevent="tab === 'persuratan' ? (select('dokumen', $refs.tabDokumen), $refs.tabDokumen.focus()) : (select('persuratan', $refs.tabPersuratan), $refs.tabPersuratan.focus())"
                    @keydown.arrow-left.prevent="tab === 'persuratan' ? (select('dokumen', $refs.tabDokumen), $refs.tabDokumen.focus()) : (select('persuratan', $refs.tabPersuratan), $refs.tabPersuratan.focus())"
                    @keydown.home.prevent="select('persuratan', $refs.tabPersuratan); $refs.tabPersuratan.focus()"
                    @keydown.end.prevent="select('dokumen', $refs.tabDokumen); $refs.tabDokumen.focus()"
                >
                    <span
                        aria-hidden="true"
                        :class="pillAnimated ? 'transition-[transform,width] duration-200 ease-spring' : 'transition-none'"
                        class="pointer-events-none absolute bottom-1 left-0 top-1 rounded-full bg-white shadow-sm"
                        :style="pillWidth ? `transform: translateX(${pillLeft}px); width: ${pillWidth}px;` : 'opacity: 0;'"
                    ></span>

                    <button
                        type="button"
                        x-ref="tabPersuratan"
                        id="tab-persuratan"
                        role="tab"
                        aria-controls="panel-persuratan"
                        :aria-selected="(tab === 'persuratan').toString()"
                        :tabindex="tab === 'persuratan' ? 0 : -1"
                        @click="select('persuratan', $el)"
                        class="relative z-10 inline-flex min-h-[44px] items-center gap-2 rounded-full px-4 text-xs font-semibold transition-colors duration-200 ease-spring focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand"
                        :class="tab === 'persuratan' ? (pillWidth ? 'text-pine' : 'bg-white text-pine shadow-sm') : 'text-ink-muted hover:text-ink'"
                    >
                        <span>Persuratan</span>
                        <span
                            class="inline-flex h-5 min-w-[20px] items-center justify-center rounded-full px-1.5 text-[11px] font-semibold tabular-nums transition-colors duration-200 ease-spring"
                            :class="tab === 'persuratan' ? 'bg-brand-soft text-brand-ink' : 'bg-white/70 text-ink-muted'"
                        >
                            {{ count($persuratanItems) }}
                        </span>
                    </button>

                    <button
                        type="button"
                        x-ref="tabDokumen"
                        id="tab-dokumen"
                        role="tab"
                        aria-controls="panel-dokumen"
                        :aria-selected="(tab === 'dokumen').toString()"
                        :tabindex="tab === 'dokumen' ? 0 : -1"
                        @click="select('dokumen', $el)"
                        class="relative z-10 inline-flex min-h-[44px] items-center gap-2 rounded-full px-4 text-xs font-semibold transition-colors duration-200 ease-spring focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand"
                        :class="tab === 'dokumen' ? (pillWidth ? 'text-pine' : 'bg-white text-pine shadow-sm') : 'text-ink-muted hover:text-ink'"
                    >
                        <span>Dokumen &amp; Kegiatan</span>
                        <span
                            class="inline-flex h-5 min-w-[20px] items-center justify-center rounded-full px-1.5 text-[11px] font-semibold tabular-nums transition-colors duration-200 ease-spring"
                            :class="tab === 'dokumen' ? 'bg-brand-soft text-brand-ink' : 'bg-white/70 text-ink-muted'"
                        >
                            {{ count($dokumenItems) }}
                        </span>
                    </button>
                </div>
            </div>

            <div
                id="panel-persuratan"
                role="tabpanel"
                tabindex="0"
                aria-labelledby="tab-persuratan"
                x-show="tab === 'persuratan'"
                class="divide-y divide-stone-line focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-brand"
            >
                @foreach ($persuratanItems as $item)
                    @if ($item['active'] && ! empty($item['href']))
                        <a
                            href="{{ $item['href'] }}"
                            class="group flex min-h-[60px] items-center justify-between gap-4 px-6 py-4 transition-colors duration-150 hover:bg-alabaster/80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-brand"
                        >
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-ink transition-colors duration-150 group-hover:text-brand-ink">{{ $item['label'] }}</p>
                                <p class="mt-0.5 text-xs text-ink-muted">{{ $item['note'] }}</p>
                            </div>
                            <span class="inline-flex shrink-0 items-center gap-1 text-xs font-semibold text-brand-ink">
                                Buka
                                <svg class="h-3.5 w-3.5 transition-transform duration-150 ease-spring group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </a>
                    @else
                        {{-- TODO: aktifkan saat route modul persuratan tersedia --}}
                        <div aria-disabled="true" class="flex min-h-[60px] items-center justify-between gap-4 px-6 py-4">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-ink-muted">{{ $item['label'] }}</p>
                                <p class="mt-0.5 text-xs text-ink-muted">{{ $item['note'] }}</p>
                            </div>
                            <span class="shrink-0 rounded-full bg-stone-soft px-2.5 py-0.5 text-xs font-medium text-ink-muted">Segera</span>
                        </div>
                    @endif
                @endforeach
            </div>

            <div
                id="panel-dokumen"
                role="tabpanel"
                tabindex="0"
                aria-labelledby="tab-dokumen"
                x-show="tab === 'dokumen'"
                x-cloak
                class="divide-y divide-stone-line focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-brand"
            >
                @foreach ($dokumenItems as $item)
                    @if ($item['active'] && ! empty($item['href']))
                        <a
                            href="{{ $item['href'] }}"
                            class="group flex min-h-[60px] items-center justify-between gap-4 px-6 py-4 transition-colors duration-150 hover:bg-alabaster/80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-brand"
                        >
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-ink transition-colors duration-150 group-hover:text-brand-ink">{{ $item['label'] }}</p>
                                <p class="mt-0.5 text-xs text-ink-muted">{{ $item['note'] }}</p>
                            </div>
                            <span class="inline-flex shrink-0 items-center gap-1 text-xs font-semibold text-brand-ink">
                                Buka
                                <svg class="h-3.5 w-3.5 transition-transform duration-150 ease-spring group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </a>
                    @else
                        {{-- TODO: aktifkan saat route modul dokumen dan kegiatan tersedia --}}
                        <div aria-disabled="true" class="flex min-h-[60px] items-center justify-between gap-4 px-6 py-4">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-ink-muted">{{ $item['label'] }}</p>
                                <p class="mt-0.5 text-xs text-ink-muted">{{ $item['note'] }}</p>
                            </div>
                            <span class="shrink-0 rounded-full bg-stone-soft px-2.5 py-0.5 text-xs font-medium text-ink-muted">Segera</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>

        <div class="min-w-0 lg:col-span-5">
            @include('shared-core::dashboard.partials.signature-status-card', [
                'cardTitle' => 'Tanda Tangan Sekretaris',
                'user' => $user,
                'signature' => $signature,
                'hasSignature' => $hasSignature,
                'signatureUrl' => $signatureUrl,
                'signatureHistoryCount' => $signatureHistoryCount,
                'compact' => true,
            ])
        </div>
    </div>
</div>
