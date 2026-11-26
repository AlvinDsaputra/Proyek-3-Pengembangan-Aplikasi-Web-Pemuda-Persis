<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($header) ? trim(strip_tags($header)) . ' - ' : '' }}{{ config('app.name', 'PD Pemuda Persis') }}</title>

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

        <script>
            try {
                if (window.localStorage.getItem('pp_sidebar_collapsed') === 'true') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) {}
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-alabaster text-ink lg:bg-pine">
        @auth
            @php
                $authUser = Auth::user();
                $userRole = $userRole ?? $authUser->roles()->first();
            @endphp
        @endauth

        <a
            href="#main-content"
            class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:rounded-xl focus:bg-brand focus:px-4 focus:py-2.5 focus:text-sm focus:font-semibold focus:text-white focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2"
        >
            Langsung ke konten utama
        </a>

        <div
            x-data="{
                sidebarOpen: false,
                sidebarCollapsed: document.documentElement.classList.contains('sidebar-collapsed'),
                sidebarReady: false,
                init() {
                    this.syncCollapseFromStorage();
                    window.requestAnimationFrame(() => {
                        window.requestAnimationFrame(() => {
                            this.sidebarReady = true;
                        });
                    });
                },
                syncCollapseFromStorage() {
                    let collapsed = false;
                    try {
                        collapsed = window.localStorage.getItem('pp_sidebar_collapsed') === 'true';
                    } catch (e) {}
                    this.sidebarCollapsed = collapsed;
                    document.documentElement.classList.toggle('sidebar-collapsed', collapsed);
                },
                handlePageShow(event) {
                    if (!event.persisted) return;
                    this.sidebarReady = false;
                    this.syncCollapseFromStorage();
                    window.requestAnimationFrame(() => {
                        window.requestAnimationFrame(() => {
                            this.sidebarReady = true;
                        });
                    });
                },
                openSidebar() {
                    this.sidebarOpen = true;
                    this.$nextTick(() => this.$refs.closeSidebarBtn?.focus());
                },
                closeSidebar() {
                    this.sidebarOpen = false;
                    this.$nextTick(() => this.$refs.menuTrigger?.focus());
                },
                toggleCollapse() {
                    this.sidebarCollapsed = !this.sidebarCollapsed;
                    document.documentElement.classList.toggle('sidebar-collapsed', this.sidebarCollapsed);
                    try {
                        window.localStorage.setItem('pp_sidebar_collapsed', this.sidebarCollapsed ? 'true' : 'false');
                    } catch (e) {}
                }
            }"
            @keydown.escape.window="if (sidebarOpen) closeSidebar()"
            @pageshow.window="handlePageShow($event)"
            @resize.window="if (sidebarOpen && window.innerWidth >= 1024) sidebarOpen = false"
            class="flex min-h-screen bg-alabaster lg:h-screen lg:overflow-hidden lg:bg-pine"
        >
            <div
                x-show="sidebarOpen"
                x-transition.opacity.duration.200ms
                x-cloak
                @click="closeSidebar()"
                class="fixed inset-0 z-30 bg-pine-deep/60 backdrop-blur-[2px] lg:hidden"
                aria-hidden="true"
            ></div>

            @include('layouts.navigation')

            <div class="flex min-w-0 flex-1 flex-col bg-alabaster lg:my-2.5 lg:mr-2.5 lg:overflow-hidden lg:rounded-[22px] lg:shadow-[0_0_0_1px_rgba(255,255,255,0.08),0_20px_40px_-20px_rgba(0,0,0,0.35)]">
                <header class="sticky top-0 z-20 flex h-16 shrink-0 items-center justify-between gap-4 border-b border-stone-line/80 bg-alabaster/95 px-5 backdrop-blur-sm sm:px-7">
                    <div class="flex min-w-0 items-center gap-3">
                        <button
                            type="button"
                            x-ref="menuTrigger"
                            @click="sidebarOpen ? closeSidebar() : openSidebar()"
                            :aria-expanded="sidebarOpen ? 'true' : 'false'"
                            aria-controls="app-sidebar"
                            class="inline-flex min-h-[44px] shrink-0 items-center gap-2 rounded-xl border border-stone-line bg-white px-3 py-2 text-sm font-medium text-ink shadow-sm transition-colors duration-150 ease-spring hover:bg-stone-soft active:bg-stone-line/60 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 focus-visible:ring-offset-alabaster lg:hidden"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <span>Menu</span>
                        </button>

                        <h1 class="min-w-0 truncate text-base font-semibold tracking-tight text-ink sm:text-lg">
                            @isset($header)
                                {{ $header }}
                            @endisset
                        </h1>
                    </div>

                    <div class="flex shrink-0 items-center">
                        @auth
                            <x-dropdown align="right" width="48" contentClasses="py-1.5 bg-white border border-stone-line rounded-xl">
                                <x-slot name="trigger">
                                    <button
                                        type="button"
                                        aria-label="Menu akun {{ $authUser->name }}"
                                        class="inline-flex min-h-[44px] items-center gap-2.5 rounded-xl px-2 py-1.5 text-left transition-colors duration-150 ease-spring hover:bg-stone-soft/80 active:bg-stone-soft focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 focus-visible:ring-offset-alabaster"
                                    >
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-pine text-xs font-semibold text-white" aria-hidden="true">
                                            {{ mb_strtoupper(mb_substr($authUser->name, 0, 1)) }}
                                        </span>
                                        <span class="hidden min-w-0 sm:block">
                                            <span class="block max-w-[10rem] truncate text-sm font-semibold leading-tight text-ink">{{ $authUser->name }}</span>
                                            <span class="block max-w-[10rem] truncate text-xs leading-tight text-ink-muted">{{ $userRole?->name ?? 'Pengguna' }}</span>
                                        </span>
                                        <svg class="h-4 w-4 shrink-0 text-ink-muted" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')" class="flex min-h-[44px] items-center text-ink transition-colors duration-150 hover:bg-alabaster focus:bg-alabaster">
                                        Profil Saya
                                    </x-dropdown-link>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="flex min-h-[44px] w-full items-center px-4 py-2 text-start text-sm leading-5 text-ink transition-colors duration-150 hover:bg-alabaster focus-visible:bg-alabaster focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-brand"
                                        >
                                            Keluar
                                        </button>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @endauth
                    </div>
                </header>

                <main id="main-content" tabindex="-1" class="min-w-0 flex-1 px-5 py-6 sm:px-7 sm:py-7 lg:overflow-y-auto focus:outline-none">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
