<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($header) ? trim(strip_tags($header)) . ' - ' : '' }}{{ config('app.name', 'PD Pemuda Persis') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-900">
        @auth
            @php
                $userRole = Auth::user()->primaryRole();
            @endphp
        @endauth

        <a
            href="#main-content"
            class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:rounded-lg focus:bg-green-800 focus:px-4 focus:py-2.5 focus:text-sm focus:font-semibold focus:text-white focus:outline-none focus:ring-2 focus:ring-green-700 focus:ring-offset-2"
        >
            Langsung ke konten utama
        </a>

        <div
            x-data="{
                sidebarOpen: false,
                openSidebar() {
                    this.sidebarOpen = true;
                    this.$nextTick(() => this.$refs.closeSidebarBtn?.focus());
                },
                closeSidebar() {
                    this.sidebarOpen = false;
                    this.$nextTick(() => this.$refs.menuTrigger?.focus());
                }
            }"
            @keydown.escape.window="if (sidebarOpen) closeSidebar()"
            class="min-h-screen flex bg-gray-100 lg:h-screen lg:overflow-hidden"
        >
            <div
                x-show="sidebarOpen"
                x-cloak
                @click="closeSidebar()"
                class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden"
                aria-hidden="true"
            ></div>

            @include('layouts.navigation')

            <div class="flex flex-col flex-1 min-w-0 lg:overflow-hidden">
                <header class="sticky top-0 z-20 bg-white border-b border-gray-200 min-h-[56px] flex items-center justify-between gap-3 px-4 sm:px-6 shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <button
                            type="button"
                            x-ref="menuTrigger"
                            @click="sidebarOpen ? closeSidebar() : openSidebar()"
                            :aria-expanded="sidebarOpen ? 'true' : 'false'"
                            aria-controls="app-sidebar"
                            class="lg:hidden inline-flex min-h-[44px] items-center gap-2 rounded-lg border border-gray-500 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-100 hover:text-gray-900 active:bg-gray-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2 shrink-0"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <span>Menu</span>
                        </button>

                        <div class="min-w-0 text-gray-800 font-semibold text-sm sm:text-base truncate">
                            @isset($header)
                                {{ $header }}
                            @endisset
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        @auth
                            @if (isset($userRole) && $userRole)
                                <span class="hidden md:inline-flex items-center text-xs text-gray-600">
                                    Peran:
                                    <span class="ml-1 font-semibold text-gray-800">{{ $userRole->name }}</span>
                                </span>
                            @endif

                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        type="button"
                                        aria-label="Menu akun {{ Auth::user()->name }}"
                                        class="inline-flex min-h-[44px] items-center gap-2 rounded-lg px-2.5 py-1.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 hover:text-gray-900 active:bg-gray-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2"
                                    >
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-green-800 text-xs font-bold text-white shrink-0" aria-hidden="true">
                                            {{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                                        </span>
                                        <span class="hidden sm:inline max-w-[9rem] truncate">{{ Auth::user()->name }}</span>
                                        <svg class="h-4 w-4 text-gray-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')" class="min-h-[44px] flex items-center">
                                        Profil Saya
                                    </x-dropdown-link>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="flex min-h-[44px] w-full items-center px-4 py-2 text-start text-sm leading-5 text-gray-700 transition-colors hover:bg-gray-100 hover:text-gray-900 focus-visible:outline-none focus-visible:bg-gray-100"
                                        >
                                            Keluar
                                        </button>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @endauth
                    </div>
                </header>

                <main id="main-content" tabindex="-1" class="flex-1 min-w-0 lg:overflow-y-auto px-4 py-5 sm:px-6 sm:py-6 focus:outline-none">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
