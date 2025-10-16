@props(['href', 'text' => 'Back'])

<div>
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'group inline-flex items-center px-4 py-2 bg-white border-2 border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:border-gray-300 hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow']) }}>
        <svg class="w-4 h-4 mr-2 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        {{ $text }}
    </a>
</div>
