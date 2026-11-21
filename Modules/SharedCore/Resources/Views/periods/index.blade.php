<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Periode Kepengurusan
            </h2>
            <a href="{{ route('admin.periods.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                + Tambah Periode
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mulai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Selesai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($periods as $period)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $period->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $period->start_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $period->end_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    @if ($period->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Aktif</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded-full">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @unless ($period->is_active)
                                        <form method="POST" action="{{ route('admin.periods.activate', $period) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="text-sm text-indigo-600 hover:text-indigo-900"
                                                    onclick="return confirm('Aktifkan periode ini?')">
                                                Aktifkan
                                            </button>
                                        </form>
                                    @endunless
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada periode kepengurusan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
