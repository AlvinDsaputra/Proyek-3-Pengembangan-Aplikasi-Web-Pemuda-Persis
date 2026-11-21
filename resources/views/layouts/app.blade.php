<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PD Pemuda Persis') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        @auth
            @php $userRole = Auth::user()->roles()->first(); @endphp
        @endauth

        <div class="flex h-screen overflow-hidden">

            {{-- Sidebar --}}
            @include('layouts.navigation')

            {{-- Area utama --}}
            <div class="flex flex-col flex-1 overflow-hidden">

                {{-- Top bar --}}
                <header class="bg-white border-b border-gray-200 h-14 flex items-center justify-between px-6 shrink-0">
                    <div class="text-gray-700 font-medium text-sm">
                        @isset($header)
                            {{ $header }}
                        @endisset
                    </div>

                    <div class="flex items-center gap-4">
                        @auth
                            @if(isset($userRole))
                                <span class="text-xs text-gray-500">
                                    Peran: <span class="font-semibold text-gray-700">{{ $userRole->name }}</span>
                                </span>
                            @endif

                            {{-- Notifikasi (placeholder) --}}
                            <button class="relative text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>

                            {{-- Dropdown profil --}}
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center gap-2 px-2 py-1.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">
                                        <div class="w-7 h-7 rounded-full bg-green-700 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                            {{ mb_substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                        <span class="max-w-[8rem] truncate">{{ Auth::user()->name }}</span>
                                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        Profil Saya
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                            Keluar
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @endauth
                    </div>
                </header>

                {{-- Konten halaman --}}
                <main class="flex-1 overflow-y-auto p-6">
                    {{ $slot }}
                </main>

            </div>
        </div>
    </body>
</html>
