@props([
    'id' => 'panel-bottom-sheet',
    'title' => '',
])
<div
    id="{{ $id }}-backdrop"
    class="panel-bottom-sheet-backdrop"
    role="button"
    tabindex="-1"
    aria-label="Kapat"
    data-panel-sheet-backdrop
></div>
<div
    id="{{ $id }}"
    class="panel-bottom-sheet"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
    data-panel-sheet
>
    <div class="panel-bottom-sheet-handle"></div>
    @if($title)
        <h2 id="{{ $id }}-title" class="px-5 pb-2 text-lg font-semibold text-[var(--panel-text)]">{{ $title }}</h2>
    @endif
    <div class="panel-bottom-sheet-body">
        {{ $slot }}
    </div>
</div>
