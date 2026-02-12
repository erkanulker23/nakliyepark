@extends('layouts.admin')

@section('title', 'Anasayfa Düzenleyici')
@section('page_heading', 'Anasayfa Düzenleyici')
@section('page_subtitle', 'Bölümleri sürükleyerek sıralayın; işaretleyerek anasayfada gösterip gizleyin.')

@push('styles')
<style>
#homepage-sections-list.sortable-dragging { overflow: visible; }
.sortable-handle { -webkit-user-select: none; user-select: none; pointer-events: auto; }
.homepage-sortable-ghost { opacity: 0.4; background: #e2e8f0; }
.dark .homepage-sortable-ghost { background: #475569; }
.homepage-sortable-chosen { outline: 2px solid #10b981; outline-offset: -2px; }
.homepage-sortable-drag { opacity: 0.95; }
</style>
@endpush
@section('content')
<div class="w-full max-w-2xl">
    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-card p-6">
        <form method="POST" action="{{ route('admin.homepage-editor.update') }}" id="homepage-editor-form" class="space-y-1">
            @csrf
            <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">Sıralamayı değiştirmek için satırı sürükleyin. Gösterilmesini istediğiniz bölümleri işaretleyin.</p>

            <div id="homepage-sections-list" class="space-y-0 rounded-xl border border-slate-200 dark:border-slate-600 overflow-hidden">
                @foreach($order as $key)
                    @if(isset($labels[$key]))
                    <div class="homepage-section-row flex items-center gap-3 py-3 px-4 bg-white dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700 last:border-b-0 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors" data-section-key="{{ $key }}">
                        <span class="sortable-handle cursor-grab active:cursor-grabbing touch-none text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 shrink-0 inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 select-none" title="Sürükleyerek sırala" aria-label="Sırala">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                        </span>
                        <label for="{{ $key }}" class="font-medium text-slate-800 dark:text-slate-200 cursor-pointer flex-1 min-w-0">{{ $labels[$key] }}</label>
                        <input type="hidden" name="section_order[]" value="{{ $key }}">
                        <input type="checkbox" name="{{ $key }}" id="{{ $key }}" value="1"
                            class="rounded border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 shrink-0"
                            {{ ($sections[$key] ?? true) ? 'checked' : '' }}>
                    </div>
                    @endif
                @endforeach
            </div>

            <div class="pt-6 mt-4 border-t border-slate-200 dark:border-slate-600">
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-xl transition-colors">
                    Kaydet
                </button>
                <a href="{{ url('/') }}" target="_blank" class="ml-3 text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 text-sm font-medium">Anasayfayı önizle →</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js" crossorigin="anonymous" id="sortable-js"></script>
<script>
(function initHomepageSortable() {
    function run() {
        var list = document.getElementById('homepage-sections-list');
        if (!list) return;
        if (typeof Sortable === 'undefined') {
            setTimeout(run, 50);
            return;
        }
        new Sortable(list, {
            animation: 150,
            handle: '.sortable-handle',
            draggable: '.homepage-section-row',
            direction: 'vertical',
            ghostClass: 'homepage-sortable-ghost',
            chosenClass: 'homepage-sortable-chosen',
            dragClass: 'homepage-sortable-drag',
            forceFallback: false,
            filter: 'input, label',
            preventOnFilter: true,
            onStart: function() { list.classList.add('sortable-dragging'); },
            onEnd: function() { list.classList.remove('sortable-dragging'); }
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', run);
    } else {
        run();
    }
})();
</script>
@endpush
@endsection
