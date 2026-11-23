@php
    $isReady = ! empty($hasSignature) && ! empty($signatureUrl);
    $isMissingFile = $signature !== null && ! $isReady;
    $uploadedAtLabel = $signature?->uploaded_at?->format('d/m/Y H:i');
    $historyCount = (int) ($signatureHistoryCount ?? 0);
@endphp

<section class="min-w-0 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <h2 class="text-base font-semibold text-gray-900">Status Tanda Tangan Digital</h2>
            <p class="mt-1 text-sm text-gray-600">
                Tanda tangan PNG transparan dipakai pada pengesahan surat keluar resmi organisasi.
            </p>
        </div>

        <div class="shrink-0">
            @if ($isReady)
                <span class="inline-flex items-center gap-1.5 rounded-md border border-green-700 bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-800">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Tanda tangan aktif
                </span>
            @elseif ($isMissingFile)
                <span class="inline-flex items-center gap-1.5 rounded-md border border-red-700 bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-800">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-7.5 13A1 1 0 003.66 18h16.68a1 1 0 00.87-1.5l-7.5-13a1 1 0 00-1.74 0z" />
                    </svg>
                    Berkas tidak ditemukan
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 rounded-md border border-amber-700 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Belum diunggah
                </span>
            @endif
        </div>
    </div>

    @if ($isReady)
        <div class="mt-4">
            <div class="signature-checkerboard flex min-h-[120px] items-center justify-center rounded-lg border border-gray-300 p-4">
                <img
                    src="{{ $signatureUrl }}"
                    alt="Pratinjau tanda tangan digital milik {{ $user->name }}"
                    width="240"
                    height="96"
                    fetchpriority="high"
                    class="max-h-24 w-auto object-contain"
                >
            </div>

            <dl class="mt-4 grid grid-cols-1 gap-3 border-t border-gray-200 pt-4 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium text-gray-600">Terakhir diunggah</dt>
                    <dd class="mt-0.5 font-medium text-gray-900">{{ $uploadedAtLabel ?? 'Tidak tercatat' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-600">Riwayat unggahan</dt>
                    <dd class="mt-0.5 font-medium text-gray-900">{{ $historyCount }} berkas</dd>
                </div>
            </dl>

            <div class="mt-5">
                <a
                    href="{{ route('shared.signature.edit') }}"
                    class="inline-flex min-h-[44px] items-center justify-center rounded-lg border border-green-700 bg-white px-4 py-2.5 text-sm font-semibold text-green-800 transition-colors hover:bg-green-50 active:bg-green-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2"
                >
                    Kelola Tanda Tangan
                </a>
            </div>
        </div>
    @elseif ($isMissingFile)
        <div role="alert" class="mt-4 rounded-lg border border-red-700 bg-red-50 p-4 text-sm text-red-800">
            <p class="font-semibold">Berkas tanda tangan tidak ditemukan di penyimpanan.</p>
            <p class="mt-1 leading-relaxed">
                Data tanda tangan tercatat di sistem, tetapi gambar PNG tidak dapat dimuat. Unggah ulang berkas PNG transparan agar siap dipakai saat pengesahan surat.
            </p>
        </div>

        <div class="mt-4">
            <a
                href="{{ route('shared.signature.edit') }}"
                class="inline-flex min-h-[44px] items-center justify-center rounded-lg bg-green-700 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-green-800 active:bg-green-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2"
            >
                Unggah Ulang Tanda Tangan PNG
            </a>
        </div>
    @else
        <div class="mt-4 rounded-lg border border-amber-700 bg-amber-50 p-4 text-sm text-amber-800">
            <p class="font-semibold text-amber-900">Tanda tangan digital Anda belum tersedia.</p>
            <p class="mt-1 leading-relaxed">
                Unggah gambar tanda tangan berformat PNG dengan latar transparan sekarang agar proses penandatanganan surat keluar berjalan tanpa hambatan.
            </p>
        </div>

        <div class="mt-4">
            <a
                href="{{ route('shared.signature.edit') }}"
                class="inline-flex min-h-[44px] items-center justify-center rounded-lg bg-green-700 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-green-800 active:bg-green-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2"
            >
                Unggah Tanda Tangan PNG
            </a>
        </div>
    @endif
</section>
