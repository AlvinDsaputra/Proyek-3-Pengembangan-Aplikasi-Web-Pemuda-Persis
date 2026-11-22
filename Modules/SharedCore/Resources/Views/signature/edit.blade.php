<x-app-layout>
    <x-slot name="header">Tanda Tangan Saya</x-slot>

    @php
        $hasActiveSignature = $signature !== null && ! empty($signatureUrl);
        $hasMissingFile = $signature !== null && empty($signatureUrl);
        $uploadedAtLabel = $signature?->uploaded_at?->format('d/m/Y H:i');
        $displayNpa = filled($user->npa) ? $user->npa : 'Belum diisi';
    @endphp

    <div
        x-data="signatureManager({
            savedUrl: @js($signatureUrl),
            hasActiveSignature: @js($hasActiveSignature),
            hasServerError: @js($errors->has('signature')),
            maxBytes: 2097152,
            minWidth: 80,
            minHeight: 40,
            maxWidth: 3000,
            maxHeight: 3000
        })"
        class="max-w-6xl mx-auto space-y-5 sm:space-y-6 pb-8"
    >
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between border-b border-gray-200 pb-4">
            <div class="min-w-0">
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 tracking-tight">
                    Tanda Tangan Digital Resmi
                </h1>
                <p class="mt-1 text-sm text-gray-600 max-w-2xl">
                    Kelola berkas tanda tangan PNG yang dibubuhkan pada dokumen surat keluar resmi PD Pemuda Persis Kab. Bandung.
                </p>
            </div>

            <div class="shrink-0">
                @if ($hasActiveSignature)
                    <span class="inline-flex items-center gap-2 rounded-md border border-green-700 bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-800">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Tanda tangan aktif
                    </span>
                @elseif ($hasMissingFile)
                    <span class="inline-flex items-center gap-2 rounded-md border border-red-700 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-800">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-7.5 13A1 1 0 003.66 18h16.68a1 1 0 00.87-1.5l-7.5-13a1 1 0 00-1.74 0z" />
                        </svg>
                        Berkas tidak ditemukan
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 rounded-md border border-gray-500 bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Belum ada tanda tangan
                    </span>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div
                role="status"
                aria-live="polite"
                class="flex items-start gap-3 rounded-lg border border-green-700 bg-green-50 px-4 py-3.5 text-sm text-green-800"
            >
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-green-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div
                role="alert"
                aria-live="assertive"
                class="flex items-start gap-3 rounded-lg border border-red-700 bg-red-50 px-4 py-3.5 text-sm text-red-800"
            >
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-12 lg:gap-6 lg:items-start">
            <section class="min-w-0 lg:col-span-7 bg-white border border-gray-200 rounded-xl shadow-sm divide-y divide-gray-200">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <h2 class="text-base font-semibold text-gray-900">Tanda Tangan Tersimpan</h2>
                            <p class="mt-0.5 text-sm text-gray-600">
                                Latar kotak abu-abu menandakan area transparan pada gambar PNG.
                            </p>
                        </div>

                        @if ($signature)
                            <button
                                type="button"
                                x-ref="deleteTrigger"
                                @click="openDeleteModal()"
                                class="inline-flex min-h-[44px] items-center justify-center gap-2 self-start rounded-lg border border-red-700 bg-red-50 px-3.5 py-2 text-sm font-medium text-red-700 transition-colors hover:bg-red-700 hover:text-white active:bg-red-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-700 focus-visible:ring-offset-2"
                            >
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Tanda Tangan
                            </button>
                        @endif
                    </div>

                    @if ($hasActiveSignature)
                        <div class="mt-4">
                            <div class="signature-checkerboard flex min-h-[144px] items-center justify-center rounded-lg border border-gray-300 p-4">
                                <img
                                    src="{{ $signatureUrl }}"
                                    alt="Tanda tangan digital aktif milik {{ $user->name }}"
                                    width="320"
                                    height="128"
                                    fetchpriority="high"
                                    decoding="async"
                                    class="max-h-32 max-w-full w-auto object-contain"
                                >
                            </div>

                            <dl class="mt-3 flex flex-wrap gap-x-6 gap-y-2 text-xs text-gray-600">
                                <div>
                                    <dt class="inline font-medium text-gray-700">Status:</dt>
                                    <dd class="inline font-semibold text-green-800">Aktif digunakan</dd>
                                </div>
                                @if ($uploadedAtLabel)
                                    <div>
                                        <dt class="inline font-medium text-gray-700">Terakhir diunggah:</dt>
                                        <dd class="inline">{{ $uploadedAtLabel }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="inline font-medium text-gray-700">Riwayat unggahan:</dt>
                                    <dd class="inline">{{ $historyCount }} kali</dd>
                                </div>
                            </dl>
                        </div>
                    @elseif ($hasMissingFile)
                        <div
                            role="alert"
                            class="mt-4 rounded-lg border border-red-700 bg-red-50 p-4 text-sm text-red-800"
                        >
                            <p class="font-semibold">Berkas tanda tangan aktif tidak ditemukan di penyimpanan.</p>
                            <p class="mt-1 text-red-700">
                                Unggah ulang berkas tanda tangan PNG di bawah ini agar surat keluar resmi tetap dapat dibubuhi tanda tangan Anda.
                            </p>
                        </div>
                    @else
                        <div class="mt-4 rounded-lg border border-dashed border-gray-500 bg-gray-50 px-4 py-6 sm:px-6">
                            <div class="flex flex-col sm:flex-row sm:items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">
                                        Belum ada tanda tangan yang tersimpan
                                    </p>
                                    <p class="mt-1 text-sm text-gray-600">
                                        Surat keluar yang disetujui memerlukan tanda tangan PNG agar dapat dibubuhkan otomatis di PDF resmi. Pilih berkas tanda tangan PNG pada formulir di bawah untuk mulai mengunggah.
                                    </p>
                                    @if ($historyCount > 0)
                                        <p class="mt-2 text-xs text-gray-600">
                                            Riwayat unggahan sebelumnya: {{ $historyCount }} kali (nonaktif).
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="p-4 sm:p-6">
                    <h2 class="text-base font-semibold text-gray-900">
                        {{ $hasActiveSignature ? 'Ganti Tanda Tangan' : 'Unggah Tanda Tangan Baru' }}
                    </h2>
                    <p class="mt-0.5 text-sm text-gray-600">
                        {{ $hasActiveSignature
                            ? 'Mengunggah berkas baru akan menggantikan tanda tangan aktif saat ini dan menyimpan versi lama di riwayat.'
                            : 'Siapkan berkas tanda tangan berformat PNG dengan latar belakang transparan.' }}
                    </p>

                    <form
                        method="POST"
                        action="{{ route('shared.signature.store') }}"
                        enctype="multipart/form-data"
                        @submit="handleSubmit($event)"
                        novalidate
                        class="mt-4 space-y-4"
                    >
                        @csrf

                        <div>
                            <label
                                for="signature"
                                :aria-disabled="submitting ? 'true' : 'false'"
                                @click="if (submitting) $event.preventDefault()"
                                class="block text-sm font-medium text-gray-900"
                            >
                                Berkas tanda tangan (PNG)
                            </label>

                            <div
                                @dragover.prevent="if (!submitting) isDragging = true"
                                @dragleave.prevent="isDragging = false"
                                @drop.prevent="handleDrop($event)"
                                :class="isDragging
                                    ? 'border-green-700 bg-green-50'
                                    : ((clientError || hasServerError)
                                        ? 'border-red-700 bg-red-50'
                                        : 'border-gray-500 bg-gray-50')"
                                class="mt-2 rounded-lg border-2 border-dashed p-4 sm:p-5 transition-colors focus-within:border-green-700 focus-within:ring-2 focus-within:ring-green-700 focus-within:ring-offset-2"
                            >
                                <input
                                    id="signature"
                                    name="signature"
                                    type="file"
                                    accept=".png,image/png"
                                    x-ref="fileInput"
                                    :aria-disabled="submitting ? 'true' : 'false'"
                                    @click="if (submitting) $event.preventDefault()"
                                    @change="handleInputChange($event)"
                                    aria-describedby="signature-help @error('signature') signature-server-error @enderror signature-client-error"
                                    :aria-invalid="Boolean(clientError) || hasServerError"
                                    class="sr-only"
                                >

                                <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-green-800">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-900">
                                                <span x-show="!selectedFileName">Seret berkas PNG ke kotak ini atau pilih dari perangkat</span>
                                                <span x-show="selectedFileName" x-cloak class="break-all" x-text="selectedFileName"></span>
                                            </p>
                                            <p id="signature-help" class="mt-0.5 text-xs text-gray-600">
                                                <span x-show="!selectedFileName">Format PNG saja, ukuran maksimal 2 MB, dimensi 80 x 40 hingga 3000 x 3000 piksel.</span>
                                                <span x-show="selectedFileName" x-cloak x-text="selectedFileMeta"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <label
                                        for="signature"
                                        :aria-disabled="submitting ? 'true' : 'false'"
                                        @click="if (submitting) $event.preventDefault()"
                                        :class="submitting
                                            ? 'border-gray-500 bg-gray-200 text-gray-600'
                                            : 'border-gray-500 bg-white text-gray-900 hover:bg-gray-100 active:bg-gray-200'"
                                        class="inline-flex min-h-[44px] w-full sm:w-auto shrink-0 items-center justify-center rounded-lg border px-4 py-2 text-sm font-medium transition-colors"
                                    >
                                        <span x-text="selectedFileName ? 'Ganti Pilihan' : 'Pilih Berkas PNG'"></span>
                                    </label>
                                </div>
                            </div>

                            <div
                                id="signature-client-error"
                                x-show="clientError"
                                x-cloak
                                role="alert"
                                aria-live="assertive"
                                class="mt-2.5 flex items-start gap-2 rounded-md border border-red-700 bg-red-50 px-3 py-2.5 text-sm text-red-800"
                            >
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p x-text="clientError"></p>
                            </div>

                            @error('signature')
                                <div
                                    id="signature-server-error"
                                    x-show="hasServerError"
                                    role="alert"
                                    class="mt-2.5 flex items-start gap-2 rounded-md border border-red-700 bg-red-50 px-3 py-2.5 text-sm text-red-800"
                                >
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p>{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        <div x-show="previewUrl" x-cloak class="space-y-2.5">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="text-sm font-medium text-gray-900">Pratinjau berkas yang dipilih</p>
                                <span class="rounded-md border border-green-700 bg-green-50 px-2 py-0.5 text-xs font-medium text-green-800">
                                    Siap diunggah
                                </span>
                            </div>

                            <div class="signature-checkerboard flex min-h-[144px] items-center justify-center rounded-lg border border-green-700 p-4">
                                <img
                                    :src="previewUrl"
                                    alt="Pratinjau lokal berkas tanda tangan PNG yang baru dipilih"
                                    width="320"
                                    height="128"
                                    class="max-h-32 max-w-full w-auto object-contain"
                                >
                            </div>
                        </div>

                        <div class="flex flex-col-reverse gap-3 pt-1 sm:flex-row sm:items-center sm:justify-end">
                            <button
                                type="button"
                                x-show="previewUrl"
                                x-cloak
                                @click="clearSelection()"
                                :disabled="submitting"
                                class="inline-flex min-h-[44px] w-full sm:w-auto items-center justify-center rounded-lg border border-gray-500 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 active:bg-gray-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2 disabled:bg-gray-200 disabled:text-gray-600"
                            >
                                Batal Pilih
                            </button>

                            <button
                                type="submit"
                                :disabled="submitting"
                                :aria-busy="submitting ? 'true' : 'false'"
                                class="inline-flex min-h-[44px] w-full sm:w-auto items-center justify-center gap-2 rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-green-800 active:bg-green-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2 disabled:border disabled:border-gray-500 disabled:bg-gray-200 disabled:text-gray-600"
                            >
                                <svg
                                    x-show="submitting"
                                    x-cloak
                                    class="h-4 w-4 shrink-0 motion-safe:animate-spin"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                    focusable="false"
                                >
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span x-text="submitting ? 'Menyimpan tanda tangan...' : (hasActiveSignature ? 'Simpan Tanda Tangan Baru' : 'Simpan Tanda Tangan')"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <aside class="min-w-0 lg:col-span-5 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-1">
                <section class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <h2 class="text-base font-semibold text-gray-900">Simulasi di Surat Resmi</h2>
                            <p class="mt-0.5 text-xs text-gray-600">
                                Gambaran posisi tanda tangan Anda pada bagian pengesahan surat keluar PDF.
                            </p>
                        </div>
                        <span
                            x-show="previewUrl"
                            x-cloak
                            class="shrink-0 rounded-md border border-green-700 bg-green-50 px-2 py-0.5 text-xs font-medium text-green-800"
                        >
                            Pratinjau baru
                        </span>
                    </div>

                    <div class="mt-4 rounded-lg border border-gray-300 bg-white px-5 py-6">
                        <div class="border-b border-gray-200 pb-3 text-center">
                            <p class="text-sm font-semibold text-green-800">
                                PD Pemuda Persis Kab. Bandung
                            </p>
                            <p class="mt-0.5 text-xs text-gray-600">
                                Blok pengesahan surat resmi
                            </p>
                        </div>

                        <div class="mt-5 flex flex-col items-center text-center">
                            <p class="text-sm font-medium text-gray-700">{{ $userRoleName }}</p>

                            <div class="my-3 flex h-24 w-full max-w-[240px] items-center justify-center">
                                <img
                                    x-show="activePreviewUrl"
                                    @if ($hasActiveSignature) src="{{ $signatureUrl }}" @endif
                                    :src="activePreviewUrl"
                                    alt="Simulasi tampilan tanda tangan {{ $user->name }} pada surat resmi"
                                    width="220"
                                    height="88"
                                    class="max-h-20 max-w-full w-auto object-contain"
                                    @if (! $hasActiveSignature) x-cloak @endif
                                >
                                <div
                                    x-show="!activePreviewUrl"
                                    @if ($hasActiveSignature) x-cloak @endif
                                    class="flex h-20 w-full items-center justify-center rounded-md border border-dashed border-gray-500 bg-gray-50 px-3 text-xs font-medium text-gray-600"
                                >
                                    Belum diunggah
                                </div>
                            </div>

                            <p class="border-b border-gray-900 pb-0.5 text-sm font-semibold text-gray-900">
                                {{ $user->name }}
                            </p>
                            <p class="mt-1 text-xs text-gray-600">
                                NPA: {{ $displayNpa }}
                            </p>
                        </div>
                    </div>

                    <p x-show="previewUrl" x-cloak class="mt-3 text-xs text-gray-600">
                        Pratinjau di atas berasal dari berkas lokal yang baru Anda pilih dan belum tersimpan ke server.
                    </p>
                </section>

                <section class="rounded-xl border border-gray-200 bg-gray-50 p-4 sm:p-5">
                    <h2 class="text-base font-semibold text-gray-900">Ketentuan Berkas PNG</h2>
                    <ul class="mt-3 space-y-2.5 text-sm text-gray-700">
                        <li class="flex items-start gap-2.5">
                            <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-green-700" aria-hidden="true"></span>
                            <span>Gunakan berkas gambar berformat PNG asli dengan latar belakang transparan agar menyatu rapi di atas kertas surat.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-green-700" aria-hidden="true"></span>
                            <span>Ukuran berkas maksimal 2 MB dengan dimensi minimal 80 x 40 piksel dan maksimal 3000 x 3000 piksel.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-green-700" aria-hidden="true"></span>
                            <span>Potong area kosong berlebih di sekeliling goresan tanda tangan supaya proporsinya pas di atas nama dan NPA.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-green-700" aria-hidden="true"></span>
                            <span>Berkas yang diunggah disimpan di penyimpanan privat aplikasi dan hanya dapat diakses oleh pemilik akun.</span>
                        </li>
                    </ul>
                </section>
            </aside>
        </div>

        @if ($signature)
            <div
                x-show="confirmDeleteOpen"
                x-cloak
                @keydown.escape.window="closeDeleteModal()"
                @keydown.tab="trapModalFocus($event)"
                role="dialog"
                aria-modal="true"
                aria-labelledby="delete-signature-title"
                aria-describedby="delete-signature-description"
                class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4"
            >
                <div
                    @click="closeDeleteModal()"
                    class="fixed inset-0 bg-gray-900/60"
                    aria-hidden="true"
                ></div>

                <div
                    x-ref="deleteDialogPanel"
                    class="relative z-10 my-auto w-full max-w-md rounded-xl border border-gray-200 bg-white p-5 sm:p-6 shadow-lg"
                >
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-red-700 bg-red-50 text-red-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-7.5 13A1 1 0 003.66 18h16.68a1 1 0 00.87-1.5l-7.5-13a1 1 0 00-1.74 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h2 id="delete-signature-title" class="text-base font-semibold text-gray-900">
                                Hapus tanda tangan aktif?
                            </h2>
                            <p id="delete-signature-description" class="mt-1.5 text-sm text-gray-600">
                                Tanda tangan aktif akan dinonaktifkan dari akun Anda. Surat keluar yang disetujui tidak dapat dibubuhi tanda tangan hingga Anda mengunggah berkas PNG baru. Riwayat arsip sebelumnya tetap terjaga.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <button
                            type="button"
                            x-ref="cancelDeleteBtn"
                            @click="closeDeleteModal()"
                            :disabled="deleting"
                            class="inline-flex min-h-[44px] w-full sm:w-auto items-center justify-center rounded-lg border border-gray-500 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 active:bg-gray-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2 disabled:bg-gray-200 disabled:text-gray-600"
                        >
                            Batal
                        </button>

                        <form
                            method="POST"
                            action="{{ route('shared.signature.destroy') }}"
                            @submit="deleting = true"
                            class="w-full sm:w-auto"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                :disabled="deleting"
                                :aria-busy="deleting ? 'true' : 'false'"
                                class="inline-flex min-h-[44px] w-full sm:w-auto items-center justify-center gap-2 rounded-lg bg-red-700 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-800 active:bg-red-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-700 focus-visible:ring-offset-2 disabled:border disabled:border-gray-500 disabled:bg-gray-200 disabled:text-gray-600"
                            >
                                <svg
                                    x-show="deleting"
                                    x-cloak
                                    class="h-4 w-4 shrink-0 motion-safe:animate-spin"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                    focusable="false"
                                >
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span x-text="deleting ? 'Menghapus tanda tangan...' : 'Ya, Hapus Tanda Tangan'"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        function registerSignatureManager() {
            window.Alpine.data('signatureManager', (config) => ({
                savedUrl: config.savedUrl || null,
                hasActiveSignature: Boolean(config.hasActiveSignature),
                hasServerError: Boolean(config.hasServerError),
                maxBytes: config.maxBytes,
                minWidth: config.minWidth,
                minHeight: config.minHeight,
                maxWidth: config.maxWidth,
                maxHeight: config.maxHeight,

                previewUrl: null,
                selectedFileName: '',
                selectedFileMeta: '',
                clientError: '',
                isDragging: false,
                submitting: false,
                confirmDeleteOpen: false,
                deleting: false,
                lastFocusedElement: null,

                get activePreviewUrl() {
                    return this.previewUrl || this.savedUrl;
                },

                handleInputChange(event) {
                    if (this.submitting) {
                        return;
                    }
                    const file = event.target.files?.[0] || null;
                    this.validateAndPreview(file);
                },

                handleDrop(event) {
                    this.isDragging = false;
                    if (this.submitting) {
                        return;
                    }
                    const file = event.dataTransfer?.files?.[0] || null;
                    if (!file) {
                        return;
                    }

                    if (typeof DataTransfer !== 'undefined') {
                        const transfer = new DataTransfer();
                        transfer.items.add(file);
                        this.$refs.fileInput.files = transfer.files;
                        this.validateAndPreview(file);
                    } else {
                        this.clientError = 'Browser ini tidak mendukung lepas berkas langsung. Klik tombol Pilih Berkas PNG.';
                    }
                },

                validateAndPreview(file) {
                    this.clientError = '';
                    this.hasServerError = false;

                    if (!file) {
                        this.clearSelection();
                        return;
                    }

                    const lowerName = file.name.toLowerCase();
                    const isPngMime = file.type === 'image/png';
                    const isPngExt = lowerName.endsWith('.png');
                    if (!isPngMime && !(file.type === '' && isPngExt)) {
                        this.rejectFile('Format berkas tidak didukung. Pilih gambar berformat PNG (.png).');
                        return;
                    }

                    if (file.size > this.maxBytes) {
                        this.rejectFile('Ukuran berkas melebihi batas 2 MB. Perkecil ukuran berkas PNG sebelum mengunggah.');
                        return;
                    }

                    const objectUrl = URL.createObjectURL(file);
                    const probe = new Image();

                    probe.onload = () => {
                        const width = probe.naturalWidth;
                        const height = probe.naturalHeight;

                        if (width < this.minWidth || height < this.minHeight) {
                            URL.revokeObjectURL(objectUrl);
                            this.rejectFile('Dimensi gambar terlalu kecil. Minimal 80 x 40 piksel agar tanda tangan terbaca jelas.');
                            return;
                        }

                        if (width > this.maxWidth || height > this.maxHeight) {
                            URL.revokeObjectURL(objectUrl);
                            this.rejectFile('Dimensi gambar terlalu besar. Maksimal 3000 x 3000 piksel.');
                            return;
                        }

                        this.revokePreviewUrl();
                        this.previewUrl = objectUrl;
                        this.selectedFileName = file.name;
                        this.selectedFileMeta = `${this.formatFileSize(file.size)}, ${width} x ${height} piksel`;
                    };

                    probe.onerror = () => {
                        URL.revokeObjectURL(objectUrl);
                        this.rejectFile('Berkas tidak dapat dibaca sebagai gambar PNG yang valid. Pastikan berkas tidak rusak.');
                    };

                    probe.src = objectUrl;
                },

                rejectFile(message) {
                    this.clearSelection();
                    this.clientError = message;
                },

                clearSelection() {
                    this.revokePreviewUrl();
                    this.selectedFileName = '';
                    this.selectedFileMeta = '';
                    if (this.$refs.fileInput) {
                        this.$refs.fileInput.value = '';
                    }
                },

                revokePreviewUrl() {
                    if (this.previewUrl) {
                        URL.revokeObjectURL(this.previewUrl);
                        this.previewUrl = null;
                    }
                },

                formatFileSize(bytes) {
                    if (bytes < 1024) {
                        return `${bytes} B`;
                    }
                    if (bytes < 1024 * 1024) {
                        return `${(bytes / 1024).toFixed(1)} KB`;
                    }
                    return `${(bytes / (1024 * 1024)).toFixed(2)} MB`;
                },

                handleSubmit(event) {
                    if (this.submitting) {
                        event.preventDefault();
                        return;
                    }

                    const hasFile = Boolean(this.$refs.fileInput?.files?.length);
                    if (!hasFile || this.clientError) {
                        event.preventDefault();
                        if (!this.clientError) {
                            this.clientError = 'Pilih berkas tanda tangan PNG terlebih dahulu sebelum menyimpan.';
                        }
                        this.$refs.fileInput?.focus();
                        return;
                    }

                    this.submitting = true;
                },

                openDeleteModal() {
                    this.lastFocusedElement = document.activeElement;
                    this.confirmDeleteOpen = true;
                    this.$nextTick(() => {
                        this.$refs.cancelDeleteBtn?.focus();
                    });
                },

                closeDeleteModal() {
                    if (!this.confirmDeleteOpen || this.deleting) {
                        return;
                    }
                    this.confirmDeleteOpen = false;
                    this.$nextTick(() => {
                        this.lastFocusedElement?.focus();
                    });
                },

                trapModalFocus(event) {
                    if (!this.confirmDeleteOpen || !this.$refs.deleteDialogPanel) {
                        return;
                    }

                    const focusable = Array.from(
                        this.$refs.deleteDialogPanel.querySelectorAll(
                            'button:not([disabled]), [href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
                        )
                    );
                    if (focusable.length === 0) {
                        return;
                    }

                    const first = focusable[0];
                    const last = focusable[focusable.length - 1];

                    if (event.shiftKey && document.activeElement === first) {
                        event.preventDefault();
                        last.focus();
                    } else if (!event.shiftKey && document.activeElement === last) {
                        event.preventDefault();
                        first.focus();
                    }
                },

                destroy() {
                    this.revokePreviewUrl();
                },
            }));
        }

        if (window.Alpine) {
            registerSignatureManager();
        } else {
            document.addEventListener('alpine:init', registerSignatureManager);
        }
    </script>
</x-app-layout>
