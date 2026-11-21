@props(['label', 'value', 'note' => null, 'color' => 'green'])

@php
$border = match($color) {
    'blue'   => 'border-blue-400',
    'yellow' => 'border-yellow-400',
    'orange' => 'border-orange-400',
    'gray'   => 'border-gray-300',
    default  => 'border-green-500',
};
$valueColor = match($color) {
    'blue'   => 'text-blue-600',
    'yellow' => 'text-yellow-600',
    'orange' => 'text-orange-600',
    'gray'   => 'text-gray-600',
    default  => 'text-green-700',
};
@endphp

<div class="bg-white rounded-xl shadow-sm p-4 border-l-4 {{ $border }}">
    <p class="text-xs text-gray-500 font-medium leading-tight">{{ $label }}</p>
    <p class="text-2xl font-bold {{ $valueColor }} mt-1">{{ $value }}</p>
    @if($note)
        <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $note }}</p>
    @endif
</div>
