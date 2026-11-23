<section class="min-w-0 rounded-xl border border-gray-200 bg-white p-5 sm:p-6">
    <h2 class="text-base font-semibold text-gray-900">{{ $title }}</h2>
    @if (! empty($description))
        <p class="mt-1 text-sm leading-relaxed text-gray-600">{{ $description }}</p>
    @endif

    @if (! empty($items))
        <ul class="mt-4 divide-y divide-gray-200 border-t border-gray-200">
            @foreach ($items as $item)
                <li class="py-3.5 first:pt-3.5 last:pb-0">
                    @if (is_array($item))
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="text-sm font-semibold text-gray-900">{{ $item['title'] }}</span>
                            <span class="shrink-0 rounded border border-gray-500 bg-gray-50 px-2 py-0.5 text-xs font-medium text-gray-700">
                                {{ $item['status'] ?? 'Segera' }}
                            </span>
                        </div>
                        @if (! empty($item['detail']))
                            <p class="mt-1 text-sm leading-relaxed text-gray-600">{{ $item['detail'] }}</p>
                        @endif
                    @else
                        <p class="text-sm leading-relaxed text-gray-700">{{ $item }}</p>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</section>
