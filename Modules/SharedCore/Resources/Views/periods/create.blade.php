<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Periode Kepengurusan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">

                <form method="POST" action="{{ route('admin.periods.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="name" value="Nama Periode" />
                        <x-text-input id="name" name="name" class="block mt-1 w-full"
                            value="{{ old('name') }}" placeholder="cth. 2024–2026" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="start_date" value="Tanggal Mulai" />
                        <x-text-input id="start_date" name="start_date" type="date"
                            class="block mt-1 w-full" value="{{ old('start_date') }}" required />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="end_date" value="Tanggal Selesai" />
                        <x-text-input id="end_date" name="end_date" type="date"
                            class="block mt-1 w-full" value="{{ old('end_date') }}" required />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('admin.periods.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
