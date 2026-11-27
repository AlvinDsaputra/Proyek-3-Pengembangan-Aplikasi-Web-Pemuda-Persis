<x-app-layout>
    <x-slot name="header">Tambah Indeks Kode Tujuan</x-slot>

    <div class="py-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-sm border border-stone-line/80 rounded-xl p-6">
                <form method="POST" action="{{ route('surat-keluar.master.destination-codes.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="code" class="block text-sm font-medium text-ink">Kode Tujuan <span class="text-red-600">*</span></label>
                        <input type="text" id="code" name="code" value="{{ old('code') }}" placeholder="Contoh: J.200" required
                               class="mt-1 w-full rounded-xl border-stone-line text-sm text-ink shadow-sm focus:border-pine focus:ring-pine">
                        @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-ink">Nama Tujuan / Instansi <span class="text-red-600">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: PC Pemuda Persis Pangalengan" required
                               class="mt-1 w-full rounded-xl border-stone-line text-sm text-ink shadow-sm focus:border-pine focus:ring-pine">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-ink">Keterangan</label>
                        <textarea id="description" name="description" rows="3"
                                  class="mt-1 w-full rounded-xl border-stone-line text-sm text-ink shadow-sm focus:border-pine focus:ring-pine">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded border-stone-line text-pine focus:ring-pine">
                        <label for="is_active" class="ml-2 text-sm text-ink">Aktif digunakan</label>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <a href="{{ route('surat-keluar.master.destination-codes.index') }}"
                           class="px-4 py-2 bg-white border border-stone-line text-ink text-sm font-medium rounded-xl hover:bg-stone-soft transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-pine text-white text-sm font-medium rounded-xl hover:bg-pine-deep transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>