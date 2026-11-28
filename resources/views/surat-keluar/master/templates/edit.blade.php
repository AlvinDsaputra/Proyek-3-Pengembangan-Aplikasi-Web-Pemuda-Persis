<x-app-layout>
    <x-slot name="header">Edit Template Surat</x-slot>

    <div class="py-4">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow-sm border border-stone-line/80 rounded-xl p-6">
                <form method="POST" action="{{ route('surat-keluar.master.templates.update', $template) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="letter_type_id" class="block text-sm font-medium text-ink">Jenis Surat <span class="text-red-600">*</span></label>
                        <select id="letter_type_id" name="letter_type_id" required
                                class="mt-1 w-full rounded-xl border-stone-line text-sm text-ink shadow-sm focus:border-pine focus:ring-pine">
                            @foreach ($letterTypes as $type)
                                <option value="{{ $type->id }}" {{ old('letter_type_id', $template->letter_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->code }} - {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('letter_type_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-ink">Nama Template <span class="text-red-600">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $template->name) }}" required
                               class="mt-1 w-full rounded-xl border-stone-line text-sm text-ink shadow-sm focus:border-pine focus:ring-pine">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="default_subject" class="block text-sm font-medium text-ink">Perihal Default</label>
                        <input type="text" id="default_subject" name="default_subject" value="{{ old('default_subject', $template->default_subject) }}"
                               class="mt-1 w-full rounded-xl border-stone-line text-sm text-ink shadow-sm focus:border-pine focus:ring-pine">
                        @error('default_subject') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="body_template" class="block text-sm font-medium text-ink">Isi Kerangka Template Surat <span class="text-red-600">*</span></label>
                        <textarea id="body_template" name="body_template" rows="10" required
                                  class="mt-1 w-full rounded-xl border-stone-line font-mono text-sm text-ink shadow-sm focus:border-pine focus:ring-pine">{{ old('body_template', $template->body_template) }}</textarea>
                        @error('body_template') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $template->is_active) ? 'checked' : '' }}
                               class="rounded border-stone-line text-pine focus:ring-pine">
                        <label for="is_active" class="ml-2 text-sm text-ink">Aktif digunakan</label>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <a href="{{ route('surat-keluar.master.templates.index') }}"
                           class="px-4 py-2 bg-white border border-stone-line text-ink text-sm font-medium rounded-xl hover:bg-stone-soft transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-pine text-white text-sm font-medium rounded-xl hover:bg-pine-deep transition-colors">
                            Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>