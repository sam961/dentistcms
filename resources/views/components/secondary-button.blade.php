@props([
    'href' => null,
    'icon' => null,
    'type' => 'button',
])

@php
$baseClasses = "inline-flex items-center px-4 py-2 bg-white border-2 border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 hover:border-gray-400 shadow-sm hover:shadow transition-all duration-200";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        @if($icon)
            <i class="fas fa-{{ $icon }} mr-2"></i>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        @if($icon)
            <i class="fas fa-{{ $icon }} mr-2"></i>
        @endif
        {{ $slot }}
    </button>
@endif
