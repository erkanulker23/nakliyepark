@props([
    'elevated' => false,
    'touch' => false,
    'padding' => true,
])
<div
    {{ $attributes->merge([
        'class' => 'panel-card ' .
            ($elevated ? 'panel-card-elevated ' : '') .
            ($touch ? 'panel-card-touch ' : '') .
            ($padding && !$touch ? 'p-4 sm:p-5' : ''),
    ]) }}
>
    {{ $slot }}
</div>
