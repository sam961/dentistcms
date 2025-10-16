@props([
    'href' => null,
    'icon' => null,
    'color' => 'blue', // blue, green, purple, orange, red, indigo, teal
    'type' => 'button',
])

@php
$colorClasses = [
    'blue' => 'from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800',
    'green' => 'from-green-600 to-green-700 hover:from-green-700 hover:to-green-800',
    'purple' => 'from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800',
    'orange' => 'from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700',
    'red' => 'from-red-600 to-red-700 hover:from-red-700 hover:to-red-800',
    'indigo' => 'from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800',
    'teal' => 'from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800',
    'pink' => 'from-pink-600 to-pink-700 hover:from-pink-700 hover:to-pink-800',
];

$gradientClass = $colorClasses[$color] ?? $colorClasses['blue'];
$baseClasses = "inline-flex items-center px-4 py-2.5 bg-gradient-to-r {$gradientClass} border border-transparent rounded-xl font-semibold text-sm text-white shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5";
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
