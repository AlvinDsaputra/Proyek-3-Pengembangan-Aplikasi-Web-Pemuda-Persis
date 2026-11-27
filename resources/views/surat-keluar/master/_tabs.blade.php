<div class="mb-5 border-b border-stone-line">
    <nav class="-mb-px flex flex-wrap gap-6" aria-label="Tab Master Surat">
        <a href="{{ route('surat-keluar.master.letter-types.index') }}"
           class="whitespace-nowrap border-b-2 py-3 px-1 text-sm font-semibold transition-colors {{ request()->routeIs('surat-keluar.master.letter-types.*') ? 'border-pine text-pine' : 'border-transparent text-ink-muted hover:border-stone-line hover:text-ink' }}">
            Jenis Surat
        </a>
        <a href="{{ route('surat-keluar.master.destination-codes.index') }}"
           class="whitespace-nowrap border-b-2 py-3 px-1 text-sm font-semibold transition-colors {{ request()->routeIs('surat-keluar.master.destination-codes.*') ? 'border-pine text-pine' : 'border-transparent text-ink-muted hover:border-stone-line hover:text-ink' }}">
            Indeks Kode Tujuan
        </a>
        <a href="{{ route('surat-keluar.master.templates.index') }}"
           class="whitespace-nowrap border-b-2 py-3 px-1 text-sm font-semibold transition-colors {{ request()->routeIs('surat-keluar.master.templates.*') ? 'border-pine text-pine' : 'border-transparent text-ink-muted hover:border-stone-line hover:text-ink' }}">
            Template Surat
        </a>
    </nav>
</div>