@props([
    'status' => 'neutral', // pending | approved | rejected | success | error | neutral
])
@php
    $class = match($status) {
        'pending' => 'panel-badge panel-badge-pending',
        'approved', 'success' => 'panel-badge panel-badge-approved',
        'rejected', 'error' => 'panel-badge panel-badge-rejected',
        default => 'panel-badge panel-badge-neutral',
    };
@endphp
<span {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</span>
