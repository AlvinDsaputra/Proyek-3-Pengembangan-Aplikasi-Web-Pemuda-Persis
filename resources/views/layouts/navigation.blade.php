<aside class="w-60 bg-green-800 flex flex-col shrink-0 h-full overflow-y-auto">

    {{-- Brand --}}
    <div class="px-4 py-4 border-b border-green-700">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 bg-white rounded-full flex items-center justify-center shrink-0">
                <span class="text-green-800 font-bold text-xs leading-none">PP</span>
            </div>
            <div class="min-w-0">
                <div class="text-white font-semibold text-sm leading-tight">PD Pemuda Persis</div>
                <div class="text-green-300 text-xs">Kab. Bandung</div>
            </div>
        </a>
    </div>

    {{-- User card --}}
    @auth
    <div class="px-4 py-3 border-b border-green-700">
        <div class="text-white font-medium text-sm truncate">{{ Auth::user()->name }}</div>
        <div class="text-green-300 text-xs mt-0.5">{{ $userRole?->name ?? 'Pengguna' }}</div>
    </div>
    @endauth

    {{-- Navigasi --}}
    <nav class="flex-1 px-3 py-3 space-y-0.5">

        {{-- Beranda --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Beranda
        </a>

        {{-- Surat Keluar --}}
        @can('surat.view')
        <a href="{{ Route::has('surat-keluar.index') ? route('surat-keluar.index') : '#' }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('surat-keluar.*') ? 'bg-white/20 text-white' : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Surat Keluar
        </a>
        @endcan

        {{-- Surat Masuk (hanya Sekretaris) --}}
        @can('surat-masuk.create')
        <a href="{{ Route::has('dokumen-aset.surat-masuk.index') ? route('dokumen-aset.surat-masuk.index') : '#' }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('dokumen-aset.surat-masuk.*') ? 'bg-white/20 text-white' : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Surat Masuk
        </a>
        @endcan

        {{-- Dokumen & Arsip --}}
        @canany(['dokumen.view', 'dokumen.create'])
        <a href="{{ Route::has('dokumen-aset.dokumen.index') ? route('dokumen-aset.dokumen.index') : '#' }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('dokumen-aset.dokumen.*') ? 'bg-white/20 text-white' : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
            </svg>
            Dokumen &amp; Arsip
        </a>
        @endcanany

        {{-- Aset Organisasi --}}
        @canany(['aset.view', 'aset.create'])
        <a href="{{ Route::has('dokumen-aset.aset.index') ? route('dokumen-aset.aset.index') : '#' }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('dokumen-aset.aset.*') ? 'bg-white/20 text-white' : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
            </svg>
            Aset Organisasi
        </a>
        @endcanany

        {{-- Kegiatan --}}
        @can('kegiatan.view')
        <a href="{{ Route::has('kegiatan.index') ? route('kegiatan.index') : '#' }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('kegiatan.*') ? 'bg-white/20 text-white' : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Kegiatan &amp; Hadiri
        </a>
        @endcan

        {{-- Tanda Tangan Saya --}}
        @can('shared.signature.upload')
        <a href="{{ Route::has('shared.signature.edit') ? route('shared.signature.edit') : '#' }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('shared.signature.*') ? 'bg-white/20 text-white' : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            Tanda Tangan Saya
        </a>
        @endcan

        {{-- Pemisah --}}
        @canany(['shared.periods.manage', 'shared.users.view'])
        <div class="pt-2 pb-1">
            <div class="border-t border-green-700"></div>
        </div>

        {{-- Admin --}}
        <a href="{{ route('admin.periods.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.*') ? 'bg-white/20 text-white' : 'text-green-100 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Panel Admin
        </a>
        @endcanany

    </nav>


</aside>
