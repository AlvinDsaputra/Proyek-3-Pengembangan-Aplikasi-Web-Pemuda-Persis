@php
    $isReady = ! empty($hasSignature) && ! empty($signatureUrl);
    $isMissingFile = $signature !== null && ! $isReady;
    $uploadedAtLabel = $signature?->uploaded_at?->format('d/m/Y H:i');
    $historyCount = (int) ($signatureHistoryCount ?? 0);
    $isCompact = ! empty($compact);
@endphp

<section class="surface-card min-w-0 overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-stone-line px-5 py-4">
        <h2 class="text-sm font-semibold text-ink">{{ $cardTitle ?? 'Tanda Tangan Saya' }}</h2>

        @if ($isReady)
            <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-soft px-2.5 py-1 text-xs font-semibold text-brand-ink">
                <span class="h-1.5 w-1.5 rounded-full bg-brand" aria-hidden="true"></span>
                Aktif
            </span>
        @elseif ($isMissingFile)
            <span class="inline-flex items-center gap-1.5 rounded-full bg-danger-bg px-2.5 py-1 text-xs font-semibold text-danger">
                <span class="h-1.5 w-1.5 rounded-full bg-danger" aria-hidden="true"></span>
                File hilang
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 rounded-full bg-ochre-bg px-2.5 py-1 text-xs font-semibold text-ochre">
                <span class="h-1.5 w-1.5 rounded-full bg-ochre-border" aria-hidden="true"></span>
                Belum diunggah
            </span>
        @endif
    </div>

    <div class="{{ $isCompact ? 'p-5' : 'p-5 sm:p-6' }}">
        @if ($isReady)
            <div class="signature-checkerboard flex {{ $isCompact ? 'min-h-[116px]' : 'min-h-[144px]' }} items-center justify-center rounded-xl squircle border border-stone-line p-4">
                <img
                    src="{{ $signatureUrl }}"
                    alt="Pratinjau tanda tangan {{ $user->name }}"
                    width="240"
                    height="96"
                    fetchpriority="high"
                    class="{{ $isCompact ? 'max-h-20' : 'max-h-24' }} w-auto object-contain"
                >
            </div>

            <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                <p class="text-xs tabular-nums text-ink-muted">
                    Diperbarui {{ $uploadedAtLabel ?? '-' }} ({{ $historyCount }} riwayat)
                </p>

                <a
                    href="{{ route('shared.signature.edit') }}"
                    class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-stone-line bg-white px-4 py-2 text-sm font-semibold text-brand-ink transition-[transform,background-color,border-color] duration-150 ease-spring hover:-translate-y-0.5 hover:border-brand/40 hover:bg-brand-soft active:translate-y-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2"
                >
                    Ubah Tanda Tangan
                </a>
            </div>
        @elseif ($isMissingFile)
            <p role="alert" class="rounded-xl border border-danger-border/30 bg-danger-bg px-4 py-3 text-sm leading-relaxed text-danger">
                File tanda tangan tidak ditemukan di penyimpanan. Unggah ulang berkas PNG agar pengesahan surat dapat berjalan.
            </p>

            <div class="mt-4">
                <a
                    href="{{ route('shared.signature.edit') }}"
                    class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-white transition-[transform,background-color] duration-150 ease-spring hover:-translate-y-0.5 hover:bg-brand-hover active:translate-y-0 active:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2"
                >
                    Unggah Ulang PNG
                </a>
            </div>
        @else
            <p class="text-sm leading-relaxed text-ink-muted">
                Belum ada tanda tangan aktif. Unggah berkas PNG transparan agar siap dibubuhkan pada surat keluar.
            </p>

            <div class="mt-4">
                <a
                    href="{{ route('shared.signature.edit') }}"
                    class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-white transition-[transform,background-color] duration-150 ease-spring hover:-translate-y-0.5 hover:bg-brand-hover active:translate-y-0 active:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2"
                >
                    Unggah Tanda Tangan
                </a>
            </div>
        @endif
    </div>
</section>
