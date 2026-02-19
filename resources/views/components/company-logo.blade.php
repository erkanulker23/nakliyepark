@props([
    'logoPath' => null,
    'alt' => '',
    'fallbackLetter' => '',
    'size' => 'md', // sm, md, lg, xl
])

@php
    $sizeClasses = [
        'sm' => 'w-12 h-12 rounded-xl',
        'md' => 'w-16 h-16 sm:w-20 sm:h-20 rounded-xl',
        'lg' => 'w-20 h-20 sm:w-24 sm:h-24 rounded-2xl',
        'xl' => 'w-28 h-28 sm:w-36 sm:h-36 rounded-2xl',
    ];
    $boxClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $wrapClass = $boxClass . ' shrink-0 overflow-hidden flex items-center justify-center bg-white dark:bg-zinc-800 border border-zinc-200/60 dark:border-zinc-700/60';
@endphp

@if($logoPath && trim($logoPath) !== '')
    <div class="{{ $wrapClass }} {{ $attributes->get('class') }}">
        <img src="{{ asset('storage/'.$logoPath) }}" alt="{{ $alt }}" class="w-full h-full object-contain p-0.5" {{ $attributes->except('class')->merge([]) }}>
    </div>
@else
    <div class="{{ $boxClass }} shrink-0 bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold shadow-sm {{ $size === 'sm' ? 'text-lg' : ($size === 'xl' ? 'text-3xl sm:text-4xl' : 'text-2xl') }} {{ $attributes->get('class') }}">
        {{ mb_substr($fallbackLetter ?: 'F', 0, 1) }}
    </div>
@endif
