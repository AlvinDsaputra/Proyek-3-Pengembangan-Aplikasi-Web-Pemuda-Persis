<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'PD Pemuda Persis Kab. Bandung') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link
            rel="stylesheet"
            href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap"
            media="print"
            onload="this.media='all'"
        >
        <noscript>
            <link rel="stylesheet" href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap">
        </noscript>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-alabaster font-sans text-ink antialiased flex flex-col">
        <a
            href="#main-content"
            class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:rounded-xl focus:bg-brand focus:px-4 focus:py-2.5 focus:text-sm focus:font-semibold focus:text-white focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2"
        >
            Langsung ke konten utama
        </a>

        <header class="sticky top-0 z-30 px-4 pt-5 sm:px-6">
            <nav
                aria-label="Navigasi utama"
                class="mx-auto flex max-w-5xl items-center justify-between gap-4 rounded-full border border-stone-line bg-white/95 py-2 pl-3 pr-2.5 shadow-sm backdrop-blur-md sm:pl-4"
            >
                <div class="flex min-w-0 items-center gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-pine text-xs font-bold tracking-tight text-white" aria-hidden="true">
                        PP
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold leading-tight text-ink">PD Pemuda Persis</p>
                        <p class="hidden truncate text-xs leading-tight text-ink-muted sm:block">Kabupaten Bandung</p>
                    </div>
                </div>

                <div class="flex shrink-0 items-center">
                    @auth
                        <a
                            href="{{ route('dashboard') }}"
                            class="inline-flex min-h-[44px] items-center gap-2 rounded-full bg-brand px-5 text-sm font-semibold text-white shadow-sm transition-[transform,background-color] duration-200 ease-spring hover:-translate-y-0.5 hover:bg-brand-hover active:translate-y-0 active:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2"
                        >
                            <span>Buka Beranda</span>
                        </a>
                    @else
                        @if (Route::has('login'))
                            <a
                                href="{{ route('login') }}"
                                class="inline-flex min-h-[44px] items-center gap-2 rounded-full bg-pine px-5 text-sm font-semibold text-white shadow-sm transition-[transform,background-color] duration-200 ease-spring hover:-translate-y-0.5 hover:bg-pine-deep active:translate-y-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-pine focus-visible:ring-offset-2"
                            >
                                <span>Masuk</span>
                            </a>
                        @endif
                    @endauth
                </div>
            </nav>
        </header>

        <main id="main-content" tabindex="-1" class="mx-auto flex w-full max-w-5xl flex-1 items-center px-5 py-12 focus:outline-none sm:px-6 sm:py-16 lg:py-20">
            <div class="grid w-full grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-12">
                <section class="min-w-0 lg:col-span-7">
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-brand-ink">
                        Sistem Informasi Internal
                    </p>

                    <h1 class="mt-3.5 text-3xl font-bold tracking-tight text-ink sm:text-4xl lg:text-[2.75rem] lg:leading-[1.12]">
                        Administrasi organisasi yang tertib dalam satu ruang kerja.
                    </h1>

                    <p class="mt-4 max-w-xl text-base leading-relaxed text-ink-muted">
                        Portal resmi PD Pemuda Persis Kabupaten Bandung untuk pengelolaan surat keluar bertanda tangan digital, arsip dokumen dan aset, serta pencatatan kegiatan pengurus.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        @auth
                            <a
                                href="{{ route('dashboard') }}"
                                class="inline-flex min-h-[44px] items-center gap-2.5 rounded-xl bg-brand px-6 py-3 text-sm font-semibold text-white shadow-sm transition-[transform,background-color] duration-200 ease-spring hover:-translate-y-0.5 hover:bg-brand-hover active:translate-y-0 active:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 focus-visible:ring-offset-alabaster"
                            >
                                <span>Buka Beranda</span>
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        @else
                            @if (Route::has('login'))
                                <a
                                    href="{{ route('login') }}"
                                    class="inline-flex min-h-[44px] items-center gap-2.5 rounded-xl bg-brand px-6 py-3 text-sm font-semibold text-white shadow-sm transition-[transform,background-color] duration-200 ease-spring hover:-translate-y-0.5 hover:bg-brand-hover active:translate-y-0 active:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 focus-visible:ring-offset-alabaster"
                                >
                                    <span>Masuk ke Aplikasi</span>
                                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            @endif
                        @endauth

                        <p class="text-xs text-ink-muted">
                            Akses khusus pengurus dan anggota terdaftar.
                        </p>
                    </div>
                </section>

                <aside aria-label="Struktur modul aplikasi" class="min-w-0 lg:col-span-5">
                    <div class="surface-card overflow-hidden">
                        <div class="flex items-center justify-between gap-3 bg-pine px-5 py-4 text-white">
                            <div>
                                <p class="text-sm font-semibold leading-tight">Cakupan Modul</p>
                                <p class="mt-0.5 text-xs text-pine-text">PD Pemuda Persis Kab. Bandung</p>
                            </div>
                            <span class="rounded-full bg-white/10 px-2.5 py-1 text-[11px] font-medium text-pine-text">
                                Terintegrasi
                            </span>
                        </div>

                        <ul class="divide-y divide-stone-line/80 bg-white">
                            <li class="flex items-start gap-3.5 px-5 py-4">
                                <span class="squircle mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-soft text-brand-ink" aria-hidden="true">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.85" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <h2 class="text-sm font-semibold text-ink">Persuratan Keluar</h2>
                                    <p class="mt-0.5 text-xs leading-relaxed text-ink-muted">
                                        Penyusunan draf, data master surat, persetujuan pimpinan, dan pengesahan tanda tangan digital.
                                    </p>
                                </div>
                            </li>

                            <li class="flex items-start gap-3.5 px-5 py-4">
                                <span class="squircle mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-soft text-brand-ink" aria-hidden="true">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.85" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <h2 class="text-sm font-semibold text-ink">Dokumen &amp; Aset</h2>
                                    <p class="mt-0.5 text-xs leading-relaxed text-ink-muted">
                                        Pencatatan surat masuk, penyimpanan arsip dokumen organisasi, dan inventaris aset.
                                    </p>
                                </div>
                            </li>

                            <li class="flex items-start gap-3.5 px-5 py-4">
                                <span class="squircle mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-soft text-brand-ink" aria-hidden="true">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.85" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <h2 class="text-sm font-semibold text-ink">Kegiatan</h2>
                                    <p class="mt-0.5 text-xs leading-relaxed text-ink-muted">
                                        Pengelolaan agenda organisasi, pendaftaran peserta, dan pencatatan kehadiran kegiatan.
                                    </p>
                                </div>
                            </li>
                        </ul>

                        <div class="border-t border-stone-line/80 bg-alabaster/70 px-5 py-3 text-xs text-ink-muted">
                            Akses menu menyesuaikan peran akun pada periode aktif.
                        </div>
                    </div>
                </aside>
            </div>
        </main>

        <footer class="border-t border-stone-line/80 px-5 py-5 sm:px-6">
            <div class="mx-auto flex max-w-5xl flex-col items-start justify-between gap-1 text-xs text-ink-muted sm:flex-row sm:items-center">
                <span>PD Pemuda Persis Kabupaten Bandung</span>
                <span>Sistem Informasi Organisasi</span>
            </div>
        </footer>
    </body>
</html>
