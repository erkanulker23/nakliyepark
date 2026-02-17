@props([
    'type' => 'text', // text | title | avatar | card | line
])
@php
    $class = 'panel-skeleton ' . match($type) {
        'title' => 'panel-skeleton-title',
        'avatar' => 'panel-skeleton-avatar',
        'card' => 'panel-skeleton-card',
        'line' => 'panel-skeleton-text w-full',
        default => 'panel-skeleton-text',
    };
@endphp
<div {{ $attributes->merge(['class' => $class]) }} aria-hidden="true"></div>
