@php
    $brandRoute = Auth::check() ? route(Auth::user()->dashboardRouteName()) : route('dashboard');
@endphp

<div class="flex items-center justify-between gap-2 border-b border-green-700 px-4 py-4">
    <a
        href="{{ $brandRoute }}"
        @click="sidebarOpen = false"
        class="flex min-h-[44px] min-w-0 flex-1 items-center gap-3 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white"
    >
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white" aria-hidden="true">
            <span class="text-xs font-bold leading-none text-green-800">PP</span>
        </div>
        <div class="min-w-0">
            <div class="truncate text-sm font-semibold leading-tight text-white">PD Pemuda Persis</div>
            <div class="truncate text-xs text-green-100">Kab. Bandung</div>
        </div>
    </a>

    <button
        type="button"
        x-ref="closeSidebarBtn"
        @click="closeSidebar()"
        aria-label="Tutup menu navigasi"
        class="lg:hidden inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-lg text-white transition-colors hover:bg-white/10 active:bg-green-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white"
    >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

@auth
    <div class="border-b border-green-700 px-4 py-3">
        <div class="truncate text-sm font-medium text-white">{{ Auth::user()->name }}</div>
        <div class="mt-0.5 truncate text-xs text-green-100">{{ $userRole?->name ?? 'Belum memiliki peran' }}</div>
    </div>
@endauth
