<section class="min-w-0 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
    <h2 class="text-base font-semibold text-gray-900">{{ $title ?? 'Akses Cepat' }}</h2>
    @if (! empty($subtitle))
        <p class="mt-1 text-sm text-gray-600">{{ $subtitle }}</p>
    @endif

    <div class="mt-4 space-y-3">
        @foreach ($actions as $action)
            @if (! empty($action['active']) && ! empty($action['href']))
                <a
                    href="{{ $action['href'] }}"
                    class="block min-h-[44px] rounded-lg border border-green-700 bg-green-50 px-4 py-3.5 transition-colors hover:bg-green-100 active:bg-green-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2"
                >
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-sm font-semibold text-green-800">{{ $action['label'] }}</span>
                        <span class="shrink-0 rounded bg-green-800 px-2 py-0.5 text-xs font-medium text-white">
                            {{ $action['badge'] ?? 'Aktif' }}
                        </span>
                    </div>
                    @if (! empty($action['description']))
                        <p class="mt-1 text-xs leading-relaxed text-gray-700 sm:text-sm">
                            {{ $action['description'] }}
                        </p>
                    @endif
                </a>
            @else
                {{-- TODO: ubah otomatis menjadi tautan aktif ketika route modul terdaftar --}}
                <div
                    aria-disabled="true"
                    class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3.5"
                >
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-sm font-semibold text-gray-700">{{ $action['label'] }}</span>
                        <span class="shrink-0 rounded border border-gray-500 bg-white px-2 py-0.5 text-xs font-medium text-gray-700">
                            {{ $action['badge'] ?? 'Segera' }}
                        </span>
                    </div>
                    @if (! empty($action['description']))
                        <p class="mt-1 text-xs leading-relaxed text-gray-600 sm:text-sm">
                            {{ $action['description'] }}
                        </p>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</section>
