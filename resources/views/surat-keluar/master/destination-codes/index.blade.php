<x-app-layout>
    <x-slot name="header">Master Surat</x-slot>

    <div class="py-4">
        <div class="max-w-6xl mx-auto">
            @include('surat-keluar.master._tabs')

            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <h2 class="text-lg font-semibold text-ink">Indeks Kode Tujuan Surat</h2>
                @can('create', \Modules\SuratKeluar\Models\LetterDestinationCode::class)
                    <a href="{{ route('surat-keluar.master.destination-codes.create') }}"
                       class="px-4 py-2 bg-pine text-white text-sm font-medium rounded-xl hover:bg-pine-deep transition-colors">
                        + Tambah Kode Tujuan
                    </a>
                @endcan
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 text-sm rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" action="{{ route('surat-keluar.master.destination-codes.index') }}" class="mb-4 flex flex-wrap gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari kode (misal: PP, J.16) atau nama tujuan..."
                       class="w-full max-w-sm rounded-xl border-stone-line bg-white text-sm text-ink shadow-sm focus:border-pine focus:ring-pine">
                <button type="submit" class="px-4 py-2 bg-pine text-white text-sm font-medium rounded-xl hover:bg-pine-deep transition-colors">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('surat-keluar.master.destination-codes.index') }}"
                       class="px-4 py-2 bg-white border border-stone-line text-ink text-sm font-medium rounded-xl hover:bg-stone-soft transition-colors">
                        Reset
                    </a>
                @endif
            </form>

            <div class="bg-white shadow-sm border border-stone-line/80 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Tujuan / Instansi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($destinationCodes as $item)
                                <tr class="hover:bg-alabaster/60">
                                    <td class="px-6 py-4 font-mono font-semibold text-gray-900">{{ $item->code }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->name }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $item->description ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if ($item->is_active)
                                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Aktif</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded-full">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-3 whitespace-nowrap">
                                        @can('update', $item)
                                            <a href="{{ route('surat-keluar.master.destination-codes.edit', $item) }}"
                                               class="text-sm font-medium text-pine hover:text-pine-deep hover:underline">
                                                Edit
                                            </a>
                                        @endcan
                                        @can('delete', $item)
                                            <form method="POST" action="{{ route('surat-keluar.master.destination-codes.destroy', $item) }}" class="inline"
                                                  onsubmit="return confirm('Hapus kode tujuan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 hover:underline">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada data kode tujuan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">{{ $destinationCodes->links() }}</div>
        </div>
    </div>
</x-app-layout>