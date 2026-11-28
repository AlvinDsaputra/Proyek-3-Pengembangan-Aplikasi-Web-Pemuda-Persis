@props(['title' => 'Kegiatan'])

@auth
    <x-app-layout>
        <x-slot name="header">{{ $title }}</x-slot>
        {{ $slot }}
    </x-app-layout>
@else
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <title>{{ $title }} - {{ config('app.name', 'PD Pemuda Persis Kab. Bandung') }}</title>

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

            @vite(['resources/css/app.css', 'resources/js/app.js'])
        </head>
        <body class="min-h-screen bg-alabaster font-sans text-ink antialiased flex flex-col">
            <header class="sticky top-0 z-30 px-4 pt-5 sm:px-6">
                <nav
                    aria-label="Navigasi utama"
                    class="mx-auto flex max-w-6xl items-center justify-between gap-4 rounded-full border border-stone-line bg-white/95 py-2 pl-3 pr-2.5 shadow-sm backdrop-blur-md sm:pl-4"
                >
                    <a href="{{ url('/') }}" class="flex min-w-0 items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-pine text-xs font-bold tracking-tight text-white">
                            PP
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold leading-tight text-ink">PD Pemuda Persis</p>
                            <p class="hidden truncate text-xs leading-tight text-ink-muted sm:block">Kabupaten Bandung</p>
                        </div>
                    </a>

                    <div class="flex shrink-0 items-center gap-2">
                        <a
                            href="{{ route('kegiatan.index') }}"
                            class="inline-flex min-h-[40px] items-center rounded-full px-4 text-sm font-semibold {{ request()->routeIs('kegiatan.*') ? 'bg-brand-soft text-brand-ink' : 'text-ink-muted hover:text-ink' }} transition-colors"
                        >
                            Katalog Kegiatan
                        </a>
                        @if (Route::has('login'))
                            <a
                                href="{{ route('login') }}"
                                class="inline-flex min-h-[40px] items-center gap-2 rounded-full bg-pine px-5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-pine-deep"
                            >
                                Masuk
                            </a>
                        @endif
                    </div>
                </nav>
            </header>

            <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8 sm:px-6">
                {{ $slot }}
            </main>

            <footer class="border-t border-stone-line/80 bg-white/60 py-6 text-center text-xs text-ink-muted">
                &copy; {{ date('Y') }} PD Pemuda Persis Kabupaten Bandung &mdash; Modul Manajemen Kegiatan
            </footer>
        </body>
    </html>
@endauth
