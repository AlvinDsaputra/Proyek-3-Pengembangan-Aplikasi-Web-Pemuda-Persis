@props([
    'label',
    'value',
    'note' => null,
    'tone' => null,
    'color' => 'neutral',
    'icon' => null,
])

@php
    $resolvedTone = $tone ?? match ($color) {
        'green' => 'accent',
        'yellow', 'orange', 'amber' => 'warning',
        'red' => 'danger',
        default => 'neutral',
    };

    $iconBoxClass = match ($resolvedTone) {
        'accent' => 'bg-brand text-white',
        'warning' => 'bg-ochre-bg text-ochre border border-ochre-border/40',
        'danger' => 'bg-danger-bg text-danger border border-danger-border/40',
        default => 'bg-brand-soft text-brand-ink',
    };

    $valueClass = match ($resolvedTone) {
        'accent' => 'text-pine',
        'warning' => 'text-ochre',
        'danger' => 'text-danger',
        default => 'text-ink',
    };
@endphp

<div {{ $attributes->merge(['class' => 'surface-card flex min-w-0 items-center gap-4 p-5']) }}>
    @if ($icon)
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl squircle {{ $iconBoxClass }}" aria-hidden="true">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" focusable="false">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
            </svg>
        </div>
    @endif

    <div class="min-w-0 flex-1">
        <p class="truncate text-xs font-medium text-ink-muted">{{ $label }}</p>
        <p class="mt-0.5 truncate text-2xl font-bold tabular-nums tracking-tight {{ $valueClass }}">{{ $value }}</p>
        @if (filled($note))
            <p class="mt-1 truncate text-xs text-ink-muted">{{ $note }}</p>
        @endif
    </div>
</div>
