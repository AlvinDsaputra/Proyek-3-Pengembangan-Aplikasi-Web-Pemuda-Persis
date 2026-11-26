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
        class="mx-auto max-w-6xl space-y-5 pb-8"
    >
        @if (session('success'))
            <div
                role="status"
                aria-live="polite"
                class="squircle flex items-center gap-2.5 rounded-2xl border border-brand/20 bg-brand-soft px-4 py-3 text-sm font-medium text-brand-ink"
            >
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div
                role="alert"
                aria-live="assertive"
                class="squircle flex items-center gap-2.5 rounded-2xl border border-danger-border/35 bg-danger-bg px-4 py-3 text-sm font-medium text-danger"
            >
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
            <section class="surface-card min-w-0 divide-y divide-stone-line/80 lg:col-span-7">
                <div class="p-5 sm:p-6">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <h2 class="text-sm font-semibold text-ink">Status Tanda Tangan</h2>
                            @if ($hasActiveSignature)
                                <span class="rounded-full bg-brand-soft px-2.5 py-0.5 text-xs font-semibold text-brand-ink">Aktif</span>
                            @elseif ($hasMissingFile)
                                <span class="rounded-full bg-danger-bg px-2.5 py-0.5 text-xs font-semibold text-danger">File hilang</span>
                            @else
                                <span class="rounded-full bg-ochre-bg px-2.5 py-0.5 text-xs font-semibold text-ochre">Belum ada</span>
                            @endif
                        </div>

                        @if ($signature)
                            <button
                                type="button"
                                x-ref="deleteTrigger"
                                @click="openDeleteModal()"
                                class="inline-flex min-h-[44px] items-center gap-1.5 rounded-xl px-3 py-2 text-sm font-medium text-danger transition-colors duration-150 ease-spring hover:bg-danger-bg active:bg-danger-bg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-danger-border focus-visible:ring-offset-2"
                            >
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        @endif
                    </div>

                    @if ($hasActiveSignature)
                        <div class="signature-checkerboard squircle mt-4 flex min-h-[116px] items-center justify-center rounded-xl border border-stone-line/80 p-4">
                            <img
                                src="{{ $signatureUrl }}"
                                alt="Tanda tangan digital aktif milik {{ $user->name }}"
                                width="320"
                                height="128"
                                fetchpriority="high"
                                decoding="async"
                                class="max-h-24 max-w-full w-auto object-contain"
                            >
                        </div>

                        <div class="mt-3 flex flex-wrap gap-x-5 gap-y-1 text-xs tabular-nums text-ink-muted">
                            @if ($uploadedAtLabel)
                                <span>Terakhir diunggah: <strong class="font-medium text-ink">{{ $uploadedAtLabel }}</strong></span>
                            @endif
                            <span>Riwayat unggahan: <strong class="font-medium text-ink">{{ $historyCount }} kali</strong></span>
                        </div>
                    @elseif ($hasMissingFile)
                        <p role="alert" class="mt-3 rounded-xl border border-danger-border/30 bg-danger-bg px-3.5 py-2.5 text-sm font-medium text-danger">
                            Berkas tanda tangan aktif tidak ditemukan di penyimpanan. Unggah ulang file PNG di bawah.
                        </p>
                    @else
                        <p class="mt-2.5 text-sm text-ink-muted">
                            Belum ada tanda tangan aktif tersimpan. Unggah berkas PNG transparan di bawah agar siap digunakan saat pengesahan surat.@if ($historyCount > 0) ({{ $historyCount }} riwayat nonaktif.)@endif
                        </p>
                    @endif
                </div>

                <div class="p-5 sm:p-6">
                    <h2 class="text-sm font-semibold text-ink">
                        {{ $hasActiveSignature ? 'Ganti Tanda Tangan' : 'Unggah Tanda Tangan' }}
                    </h2>

                    <form
                        method="POST"
                        action="{{ route('shared.signature.store') }}"
                        enctype="multipart/form-data"
                        @submit="handleSubmit($event)"
                        novalidate
                        class="mt-3.5 space-y-4"
                    >
                        @csrf

                        <div>
                            <label
                                for="signature"
                                :aria-disabled="submitting ? 'true' : 'false'"
                                @click="if (submitting) $event.preventDefault()"
                                class="sr-only"
                            >
                                Pilih file tanda tangan PNG
                            </label>

                            <div
                                @dragenter.prevent="if (!submitting) { dragDepth++; isDragging = true; }"
                                @dragover.prevent="if (!submitting) isDragging = true"
                                @dragleave.prevent="dragDepth = Math.max(0, dragDepth - 1); if (dragDepth === 0) isDragging = false"
                                @drop.prevent="handleDrop($event)"
                                :class="isDragging
                                    ? 'border-brand bg-brand-soft/70'
                                    : ((clientError || hasServerError)
                                        ? 'border-danger-border bg-danger-bg/40'
                                        : (previewUrl
                                            ? 'border-brand/50 bg-white'
                                            : 'border-stone-line bg-alabaster/70 hover:border-brand/55 hover:bg-brand-soft/20'))"
                                class="squircle relative flex min-h-[220px] flex-col overflow-hidden rounded-2xl border-2 border-dashed transition-colors duration-200 ease-spring focus-within:border-brand focus-within:ring-2 focus-within:ring-brand/25"
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

                                <div
                                    x-show="!previewUrl"
                                    class="flex flex-1 flex-col items-center justify-center px-5 py-7 text-center"
                                >
                                    <div
                                        aria-hidden="true"
                                        class="squircle mb-3 flex h-11 w-11 items-center justify-center rounded-xl border border-stone-line bg-white text-brand shadow-sm"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                    </div>

                                    <p class="text-sm font-semibold text-ink">
                                        Seret file PNG ke area ini
                                    </p>
                                    <p id="signature-help" class="mt-1 max-w-sm text-xs leading-relaxed text-ink-muted">
                                        PNG transparan, maks. 2 MB, dimensi 80 x 40 sampai 3000 x 3000 px.
                                    </p>

                                    <button
                                        type="button"
                                        :disabled="submitting"
                                        @click="if (!submitting) $refs.fileInput.click()"
                                        :class="submitting
                                            ? 'border-stone-line bg-stone-soft text-ink-muted'
                                            : 'border-stone-line bg-white text-ink hover:border-brand/40 hover:bg-stone-soft/60 active:bg-stone-soft'"
                                        class="mt-4 inline-flex min-h-[44px] items-center justify-center gap-2 rounded-xl border px-4 py-2 text-sm font-medium shadow-sm transition-colors duration-150 ease-spring focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2"
                                    >
                                        <svg class="h-4 w-4 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Pilih File PNG</span>
                                    </button>
                                </div>

                                <div x-show="previewUrl" x-cloak class="relative flex flex-1 flex-col">
                                    <div class="absolute right-3 top-3 z-10 inline-flex items-center gap-1 rounded-full border border-stone-line bg-white/95 p-1 shadow-sm backdrop-blur-sm">
                                        <button
                                            type="button"
                                            :disabled="submitting"
                                            @click="if (!submitting) $refs.fileInput.click()"
                                            class="inline-flex min-h-[44px] items-center rounded-full px-3.5 text-xs font-semibold text-ink transition-colors duration-150 hover:bg-stone-soft focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand disabled:opacity-50"
                                        >
                                            Ganti
                                        </button>
                                        <button
                                            type="button"
                                            @click="clearSelection()"
                                            :disabled="submitting"
                                            class="inline-flex min-h-[44px] items-center rounded-full px-3.5 text-xs font-semibold text-danger transition-colors duration-150 hover:bg-danger-bg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-danger-border disabled:opacity-50"
                                        >
                                            Batal
                                        </button>
                                    </div>

                                    <div class="signature-checkerboard flex min-h-[176px] flex-1 items-center justify-center px-6 pb-5 pt-16">
                                        <img
                                            :src="previewUrl"
                                            alt="Pratinjau lokal tanda tangan PNG baru"
                                            width="320"
                                            height="128"
                                            class="max-h-28 max-w-full w-auto object-contain"
                                        >
                                    </div>

                                    <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-1 border-t border-stone-line/80 bg-alabaster/90 px-4 py-2.5 text-xs">
                                        <span class="min-w-0 truncate font-medium text-ink" x-text="selectedFileName"></span>
                                        <span class="shrink-0 tabular-nums text-ink-muted" x-text="selectedFileMeta"></span>
                                    </div>
                                </div>
                            </div>

                            <p
                                id="signature-client-error"
                                x-show="clientError"
                                x-cloak
                                role="alert"
                                aria-live="assertive"
                                class="mt-2 text-sm font-medium text-danger"
                                x-text="clientError"
                            ></p>

                            @error('signature')
                                <p
                                    id="signature-server-error"
                                    x-show="hasServerError"
                                    role="alert"
                                    class="mt-2 text-sm font-medium text-danger"
                                >
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end pt-1">
                            <button
                                type="submit"
                                :disabled="submitting"
                                :aria-busy="submitting ? 'true' : 'false'"
                                class="inline-flex min-h-[44px] w-full items-center justify-center gap-2 rounded-xl bg-brand px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-[transform,background-color,box-shadow] duration-200 ease-spring hover:-translate-y-0.5 hover:bg-brand-hover active:translate-y-0 active:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 disabled:opacity-60 disabled:hover:translate-y-0 sm:w-auto"
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
                                <span x-text="submitting ? 'Menyimpan...' : (hasActiveSignature ? 'Simpan Perubahan' : 'Simpan Tanda Tangan')">{{ $hasActiveSignature ? 'Simpan Perubahan' : 'Simpan Tanda Tangan' }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <aside class="min-w-0 space-y-5 lg:col-span-5">
                <div class="surface-card overflow-hidden">
                    <div class="h-1.5 w-full bg-pine" aria-hidden="true"></div>

                    <div class="flex items-center justify-between gap-2 border-b border-stone-line/80 px-6 py-4">
                        <h2 class="text-sm font-semibold text-ink">Pratinjau di Surat</h2>
                        <span
                            x-show="previewUrl"
                            x-cloak
                            class="rounded-full bg-brand-soft px-2.5 py-0.5 text-xs font-semibold text-brand-ink"
                        >
                            Pratinjau baru
                        </span>
                    </div>

                    <div class="px-6 py-7 sm:px-7">
                        <div class="border-b border-stone-line pb-4 text-center">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-ink-muted">Pimpinan Daerah</p>
                            <p class="mt-0.5 text-xs font-bold uppercase tracking-wider text-pine">Pemuda Persis Kab. Bandung</p>
                        </div>

                        <div class="mt-5 space-y-2" aria-hidden="true">
                            <div class="h-2 w-full rounded-full bg-stone-soft"></div>
                            <div class="h-2 w-11/12 rounded-full bg-stone-soft"></div>
                            <div class="h-2 w-3/5 rounded-full bg-stone-soft"></div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <div class="w-full max-w-[230px] text-center">
                                <p class="text-xs font-medium text-ink-muted">{{ $userRoleName }},</p>

                                <div class="my-2.5 flex h-24 w-full items-center justify-center">
                                    <img
                                        x-show="activePreviewUrl"
                                        @if ($hasActiveSignature) src="{{ $signatureUrl }}" @endif
                                        :src="activePreviewUrl"
                                        alt="Simulasi tanda tangan {{ $user->name }} pada surat resmi"
                                        width="220"
                                        height="88"
                                        class="max-h-20 max-w-full w-auto object-contain"
                                        @if (! $hasActiveSignature) x-cloak @endif
                                    >
                                    <span
                                        x-show="!activePreviewUrl"
                                        @if ($hasActiveSignature) x-cloak @endif
                                        class="text-xs text-ink-muted"
                                    >
                                        (Area tanda tangan)
                                    </span>
                                </div>

                                <p class="inline-block border-b border-ink pb-0.5 text-sm font-semibold text-ink">
                                    {{ $user->name }}
                                </p>
                                <p class="mt-1 text-xs tabular-nums text-ink-muted">NPA: {{ $displayNpa }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-1">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-ink-muted">Catatan Berkas</h2>
                    <ul class="mt-2.5 space-y-2 text-xs leading-relaxed text-ink-muted">
                        <li class="flex items-start gap-2.5">
                            <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand" aria-hidden="true"></span>
                            <span>Gunakan PNG berlatar transparan agar menyatu dengan kertas surat.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand" aria-hidden="true"></span>
                            <span>Potong area kosong berlebih di sekitar goresan tanda tangan.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand" aria-hidden="true"></span>
                            <span>File disimpan privat dan hanya dapat diakses oleh akun Anda.</span>
                        </li>
                    </ul>
                </div>
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
                    class="fixed inset-0 bg-pine-deep/55 backdrop-blur-[2px]"
                    aria-hidden="true"
                ></div>

                <div
                    x-ref="deleteDialogPanel"
                    class="squircle relative z-10 my-auto w-full max-w-md rounded-2xl border border-stone-line bg-white p-6 shadow-xl"
                >
                    <h2 id="delete-signature-title" class="text-base font-semibold text-ink">
                        Hapus tanda tangan aktif?
                    </h2>
                    <p id="delete-signature-description" class="mt-2 text-sm leading-relaxed text-ink-muted">
                        Tanda tangan aktif akan dinonaktifkan dari akun Anda hingga Anda mengunggah file PNG baru. Riwayat arsip lama tetap tersimpan.
                    </p>

                    <div class="mt-6 flex flex-col-reverse gap-2.5 sm:flex-row sm:justify-end">
                        <button
                            type="button"
                            x-ref="cancelDeleteBtn"
                            @click="closeDeleteModal()"
                            :disabled="deleting"
                            class="inline-flex min-h-[44px] w-full items-center justify-center rounded-xl border border-stone-line bg-white px-4 py-2 text-sm font-medium text-ink transition-colors duration-150 ease-spring hover:bg-alabaster active:bg-stone-soft focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 disabled:opacity-60 sm:w-auto"
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
                                class="inline-flex min-h-[44px] w-full items-center justify-center gap-2 rounded-xl bg-danger px-4 py-2 text-sm font-semibold text-white transition-colors duration-150 ease-spring hover:bg-[#7F1D1D] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-danger-border focus-visible:ring-offset-2 disabled:opacity-60 sm:w-auto"
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
                                <span x-text="deleting ? 'Menghapus...' : 'Ya, Hapus'">Ya, Hapus</span>
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
                dragDepth: 0,
                validationToken: 0,
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
                    this.dragDepth = 0;
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
                        this.clientError = 'Browser ini tidak mendukung lepas berkas langsung. Klik tombol Pilih File PNG.';
                    }
                },

                validateAndPreview(file) {
                    const token = ++this.validationToken;
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
                        this.rejectFile('Format file tidak didukung. Pilih gambar PNG (.png).');
                        return;
                    }

                    if (file.size > this.maxBytes) {
                        this.rejectFile('Ukuran file melebihi 2 MB.');
                        return;
                    }

                    const objectUrl = URL.createObjectURL(file);
                    const probe = new Image();

                    probe.onload = () => {
                        if (token !== this.validationToken) {
                            URL.revokeObjectURL(objectUrl);
                            return;
                        }

                        const width = probe.naturalWidth;
                        const height = probe.naturalHeight;

                        if (width < this.minWidth || height < this.minHeight) {
                            URL.revokeObjectURL(objectUrl);
                            this.rejectFile('Dimensi terlalu kecil. Minimal 80 x 40 piksel.');
                            return;
                        }

                        if (width > this.maxWidth || height > this.maxHeight) {
                            URL.revokeObjectURL(objectUrl);
                            this.rejectFile('Dimensi terlalu besar. Maksimal 3000 x 3000 piksel.');
                            return;
                        }

                        this.revokePreviewUrl();
                        this.previewUrl = objectUrl;
                        this.selectedFileName = file.name;
                        this.selectedFileMeta = `${this.formatFileSize(file.size)}, ${width} x ${height} px`;
                    };

                    probe.onerror = () => {
                        URL.revokeObjectURL(objectUrl);
                        if (token !== this.validationToken) {
                            return;
                        }
                        this.rejectFile('File PNG tidak dapat dibaca atau rusak.');
                    };

                    probe.src = objectUrl;
                },

                rejectFile(message) {
                    this.clearSelection();
                    this.clientError = message;
                },

                clearSelection() {
                    this.validationToken++;
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
                            this.clientError = 'Pilih file PNG terlebih dahulu sebelum menyimpan.';
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
