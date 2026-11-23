@props([
    'label',
    'value',
    'note' => null,
    'tone' => null,
    'color' => 'neutral',
])

@php
    $resolvedTone = $tone ?? match ($color) {
        'green' => 'accent',
        'yellow', 'orange', 'amber' => 'warning',
        'red' => 'danger',
        default => 'neutral',
    };

    $cardClasses = match ($resolvedTone) {
        'accent' => 'bg-green-50 border-green-700',
        'warning' => 'bg-amber-50 border-amber-700',
        'danger' => 'bg-red-50 border-red-700',
        default => 'bg-white border-gray-200',
    };

    $labelClasses = match ($resolvedTone) {
        'accent' => 'text-gray-700',
        'warning' => 'text-amber-800',
        'danger' => 'text-red-800',
        default => 'text-gray-600',
    };

    $valueClasses = match ($resolvedTone) {
        'accent' => 'text-green-800',
        'warning' => 'text-amber-900',
        'danger' => 'text-red-800',
        default => 'text-gray-900',
    };

    $noteClasses = match ($resolvedTone) {
        'accent' => 'text-gray-700',
        'warning' => 'text-amber-800',
        'danger' => 'text-red-800',
        default => 'text-gray-600',
    };
@endphp

<div {{ $attributes->merge(['class' => "min-w-0 rounded-xl border p-4 sm:p-5 {$cardClasses}"]) }}>
    <p class="text-xs font-semibold leading-snug {{ $labelClasses }}">{{ $label }}</p>
    <p class="mt-2 text-2xl font-bold tracking-tight break-words {{ $valueClasses }}">{{ $value }}</p>
    @if (filled($note))
        <p class="mt-1.5 text-xs leading-relaxed {{ $noteClasses }}">{{ $note }}</p>
    @endif
</div>
