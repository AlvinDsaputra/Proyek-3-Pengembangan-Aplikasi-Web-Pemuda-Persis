<div class="mx-auto max-w-xl pb-8">
    <section aria-label="Status peran akun" class="surface-card overflow-hidden">
        <div class="flex items-center gap-2.5 border-b border-ochre-border/30 bg-ochre-bg px-6 py-4">
            <svg class="h-5 w-5 shrink-0 text-ochre" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <span class="text-sm font-semibold text-ochre">Peran Belum Ditetapkan</span>
        </div>

        <div class="px-6 py-6">
            <h2 class="text-lg font-bold tracking-tight text-ink">Assalamu'alaikum, {{ $user->name }}</h2>
            <p class="mt-2 text-sm leading-relaxed text-ink-muted">
                Akun Anda sudah aktif, namun belum terhubung ke peran kepengurusan pada periode berjalan. Hubungi Admin PD Pemuda Persis Kab. Bandung untuk penetapan peran.
            </p>

            <div class="mt-5">
                <a
                    href="{{ route('profile.edit') }}"
                    class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-white transition-[transform,background-color] duration-150 ease-spring hover:-translate-y-0.5 hover:bg-brand-hover active:translate-y-0 active:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2"
                >
                    Buka Profil Saya
                </a>
            </div>
        </div>
    </section>
</div>
