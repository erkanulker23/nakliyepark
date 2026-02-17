@props([
    'href' => null,
    'ariaLabel' => 'Ana işlem',
])
@php
    $tag = $href ? 'a' : 'button';
@endphp
<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge([
        'class' => 'panel-fab',
        'aria-label' => $ariaLabel,
    ]) }}
>
    {{ $slot }}
</{{ $tag }}>
