<x-kegiatan-shell :title="$activity->name">
    <div class="mx-auto max-w-4xl space-y-6" x-data="{ showDemoNotice: false }">
        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-sm text-ink-muted">
            <a href="{{ route('kegiatan.index') }}" class="inline-flex items-center gap-1.5 font-medium text-brand hover:text-brand-hover">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Katalog Kegiatan
            </a>
        </nav>

        {{-- Card Detail Utama --}}
        <article class="surface-card overflow-hidden">
            {{-- Header Banner --}}
            <div class="border-b border-stone-line bg-gradient-to-br from-pine to-pine-deep p-6 text-white sm:p-8">
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1 text-xs font-semibold text-pine-text">
                        {{ $activity->department?->name ?? 'Bidang Umum' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-soft px-3 py-1 text-xs font-semibold text-brand-ink">
                        <span class="h-1.5 w-1.5 rounded-full bg-brand"></span>
                        Status: {{ $activity->statusLabel() }}
                    </span>
                </div>

                <h1 class="mt-4 text-2xl font-bold leading-snug text-white sm:text-3xl">
                    {{ $activity->name }}
                </h1>
            </div>

            {{-- Konten Detail --}}
            <div class="grid grid-cols-1 gap-6 p-6 sm:p-8 lg:grid-cols-3">
                {{-- Kolom Kiri: Deskripsi --}}
                <div class="space-y-4 lg:col-span-2">
                    <h2 class="text-base font-bold text-ink">Deskripsi &amp; Informasi Acara</h2>
                    <div class="prose prose-sm max-w-none whitespace-pre-line leading-relaxed text-ink-muted">
                        {{ $activity->description ?: 'Belum ada deskripsi tambahan untuk kegiatan ini.' }}
                    </div>

                    {{-- Notifikasi Interaktif Mockup Pendaftaran 1-Klik --}}
                    <div
                        x-show="showDemoNotice"
                        x-cloak
                        x-transition
                        class="rounded-2xl border border-ochre-border/50 bg-ochre-bg p-4 text-sm text-ochre"
                    >
                        <p class="font-semibold">Pendaftaran 1-Klik Siap Diintegrasikan</p>
                        <p class="mt-1">
                            Tombol ini terhubung langsung dengan entitas <code class="font-mono font-semibold">Activity #{{ $activity->id }}</code>. Pada fase sprint berikutnya, aksi ini akan mencatat pendaftaran peserta ke tabel <code class="font-mono font-semibold">activity_registrations</code> dan mengurangi kuota secara otomatis.
                        </p>
                    </div>
                </div>

                {{-- Kolom Kanan: Info Teknis & Aksi --}}
                <aside class="space-y-5 rounded-2xl border border-stone-line bg-alabaster p-5">
                    <dl class="space-y-4 text-sm">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-ink-muted">Waktu Pelaksanaan</dt>
                            <dd class="mt-1 font-semibold text-ink">
                                {{ $activity->start_datetime->locale('id')->translatedFormat('l, d F Y') }}
                            </dd>
                            <dd class="text-ink-muted">
                                Pukul {{ $activity->start_datetime->format('H:i') }}
                                @if ($activity->end_datetime)
                                    &ndash; {{ $activity->end_datetime->format('H:i') }}
                                @endif
                                WIB
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-ink-muted">Lokasi</dt>
                            <dd class="mt-1 font-medium text-ink">{{ $activity->location }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-ink-muted">Pemateri / Narasumber</dt>
                            <dd class="mt-1 font-medium text-ink">{{ $activity->speaker_name ?: '-' }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-ink-muted">Narahubung (CP)</dt>
                            <dd class="mt-1 font-medium text-ink">{{ $activity->contact_person ?: '-' }}</dd>
                        </div>

                        <div class="border-t border-stone-line pt-4">
                            <dt class="text-xs font-semibold uppercase tracking-wider text-ink-muted">Kuota Tentatif</dt>
                            <dd class="mt-1 text-xl font-bold text-pine">
                                {{ $activity->quota > 0 ? $activity->quota . ' Peserta' : 'Terbuka / Tanpa Batas' }}
                            </dd>
                        </div>
                    </dl>

                    <button
                        type="button"
                        @click="showDemoNotice = !showDemoNotice"
                        class="inline-flex min-h-[44px] w-full items-center justify-center gap-2 rounded-xl bg-brand px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-hover focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2"
                    >
                        <span>Daftar Kegiatan (1-Klik)</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>

                    <p class="text-center text-xs text-ink-muted">
                        Pendaftaran tanpa formulir ulang bagi anggota yang sudah login.
                    </p>
                </aside>
            </div>
        </article>
    </div>
</x-kegiatan-shell>
