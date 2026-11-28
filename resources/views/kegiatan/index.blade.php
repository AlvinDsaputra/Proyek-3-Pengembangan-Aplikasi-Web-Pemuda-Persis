<x-kegiatan-shell title="Katalog Kegiatan">
    <div class="mx-auto max-w-6xl space-y-6">
        {{-- Header & Ringkasan --}}
        <div class="surface-card p-6 sm:p-8 bg-gradient-to-br from-white via-white to-brand-soft/40">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="space-y-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-soft px-3 py-1 text-xs font-semibold text-brand-ink">
                        <span class="h-1.5 w-1.5 rounded-full bg-brand"></span>
                        Agenda &amp; Pendaftaran Kegiatan
                    </span>
                    <h1 class="text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                        Katalog Kegiatan PD Pemuda Persis
                    </h1>
                    <p class="max-w-2xl text-sm text-ink-muted">
                        Daftar agenda resmi yang sedang dibuka oleh bidang kepengurusan PD Pemuda Persis Kabupaten Bandung. Pilih kegiatan untuk melihat rincian pemateri, lokasi, dan kuota.
                    </p>
                </div>

                <div class="shrink-0 rounded-2xl border border-stone-line bg-alabaster px-5 py-3.5 text-left md:text-right">
                    <p class="text-xs font-medium uppercase tracking-wider text-ink-muted">Kegiatan Dibuka</p>
                    <p class="mt-0.5 text-2xl font-bold text-pine">{{ $activities->total() }} Agenda</p>
                </div>
            </div>

            {{-- Filter & Pencarian --}}
            <form method="GET" action="{{ route('kegiatan.index') }}" class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-12">
                <div class="sm:col-span-6">
                    <label for="search" class="sr-only">Cari kegiatan</label>
                    <input
                        id="search"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari judul acara, pemateri, atau lokasi..."
                        class="w-full rounded-xl border-stone-line bg-white text-sm text-ink shadow-sm focus:border-brand focus:ring-brand"
                    >
                </div>

                <div class="sm:col-span-4">
                    <label for="department" class="sr-only">Filter bidang penyelenggara</label>
                    <select
                        id="department"
                        name="department"
                        class="w-full rounded-xl border-stone-line bg-white text-sm text-ink shadow-sm focus:border-brand focus:ring-brand"
                    >
                        <option value="">Semua Bidang Penyelenggara</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected((string) request('department') === (string) $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2 sm:col-span-2">
                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-pine px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-pine-deep"
                    >
                        Filter
                    </button>
                    @if (request()->hasAny(['search', 'department']) && (request('search') || request('department')))
                        <a
                            href="{{ route('kegiatan.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-stone-line bg-white px-3 py-2.5 text-sm font-medium text-ink transition-colors hover:bg-stone-soft"
                            title="Reset filter"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Grid Card Kegiatan --}}
        @if ($activities->isEmpty())
            <div class="surface-card p-12 text-center">
                <p class="text-base font-semibold text-ink">Belum ada kegiatan yang sesuai</p>
                <p class="mt-1 text-sm text-ink-muted">
                    Silakan ubah kata kunci pencarian atau pilih semua bidang penyelenggara.
                </p>
                @if (request()->hasAny(['search', 'department']))
                    <a
                        href="{{ route('kegiatan.index') }}"
                        class="mt-4 inline-flex items-center rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-hover"
                    >
                        Tampilkan Semua Kegiatan
                    </a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($activities as $activity)
                    <article class="surface-card flex flex-col justify-between p-5 transition-transform duration-200 hover:-translate-y-0.5">
                        <div class="space-y-3.5">
                            {{-- Badge Bidang & Status --}}
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="inline-flex items-center rounded-lg bg-stone-soft px-2.5 py-1 text-xs font-semibold text-ink">
                                    {{ $activity->department?->name ?? 'Bidang Umum' }}
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-full bg-brand-soft px-2.5 py-1 text-xs font-semibold text-brand-ink">
                                    <span class="h-1.5 w-1.5 rounded-full bg-brand"></span>
                                    {{ $activity->statusLabel() }}
                                </span>
                            </div>

                            {{-- Judul --}}
                            <h2 class="text-lg font-bold leading-snug text-ink line-clamp-2">
                                <a href="{{ route('kegiatan.show', $activity->id) }}" class="hover:text-brand transition-colors">
                                    {{ $activity->name }}
                                </a>
                            </h2>

                            {{-- Metadata Waktu, Lokasi, Pemateri --}}
                            <dl class="space-y-2 pt-1 text-sm text-ink-muted">
                                <div class="flex items-start gap-2.5">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <dd>{{ $activity->start_datetime->locale('id')->translatedFormat('d M Y • H:i') }} WIB</dd>
                                </div>

                                <div class="flex items-start gap-2.5">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <dd class="line-clamp-2">{{ $activity->location }}</dd>
                                </div>

                                @if ($activity->speaker_name)
                                    <div class="flex items-start gap-2.5">
                                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <dd class="truncate">{{ $activity->speaker_name }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        {{-- Footer Card: Kuota & Tombol Detail --}}
                        <div class="mt-5 flex items-center justify-between border-t border-stone-line/80 pt-4">
                            <div>
                                <p class="text-[11px] font-medium uppercase tracking-wider text-ink-muted">Kuota Tersedia</p>
                                <p class="text-sm font-bold text-ink">
                                    {{ $activity->quota > 0 ? $activity->quota . ' Peserta' : 'Tidak Terbatas' }}
                                </p>
                            </div>

                            <a
                                href="{{ route('kegiatan.show', $activity->id) }}"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-brand-hover"
                            >
                                <span>Detail</span>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="pt-2">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</x-kegiatan-shell>
