<section class="rounded-xl bg-green-800 px-5 py-5 text-white sm:px-6 sm:py-6">
    <p class="text-xs font-medium text-green-100">
        @if ($activePeriod)
            Periode Aktif: {{ $activePeriod->name }}
            @if ($activePeriod->start_date && $activePeriod->end_date)
                ({{ $activePeriod->start_date->format('d/m/Y') }} s.d. {{ $activePeriod->end_date->format('d/m/Y') }})
            @endif
        @else
            Belum ada periode kepengurusan aktif
        @endif
    </p>

    <h1 class="mt-1.5 text-xl font-semibold tracking-tight text-white sm:text-2xl">
        Assalamu'alaikum, {{ $user->name }}
    </h1>

    <p class="mt-1 text-sm text-green-100">
        Masuk sebagai <span class="font-semibold text-white">{{ $userRole?->name ?? 'Belum ditetapkan' }}</span>, PD Pemuda Persis Kab. Bandung.
    </p>

    @if (! empty($roleFocus))
        <p class="mt-3 max-w-2xl border-t border-green-700 pt-3 text-sm leading-relaxed text-green-100">
            {{ $roleFocus }}
        </p>
    @endif
</section>
