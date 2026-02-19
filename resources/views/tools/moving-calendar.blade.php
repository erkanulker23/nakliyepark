@extends('layouts.app')

@section('title', $metaTitle ?? 'Taşınma Takvimi - NakliyePark')
@section('meta_description', $metaDescription ?? 'Taşınma tarihinize göre planlayıcı. Hangi işlemler ne zaman yapılmalı?')

@section('content')
<div class="page-container py-6 sm:py-8">
    <header class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Taşınma Takvimi</h1>
        <p class="mt-1.5 text-sm text-zinc-500 dark:text-zinc-400">Taşınma tarihinizi girin; hangi işlemleri ne zaman yapmanız gerektiğini görün.</p>
        <p class="mt-3 text-zinc-600 dark:text-zinc-400 text-base">Taşınma takvimi, taşınma gününüze göre ev sahibine bildirim, abonelik iptali, adres güncellemesi ve paketleme gibi yapılacak işlemleri tarih bazlı planlamanıza yardımcı olur. Ücretsiz taşınma planlayıcı ile hiçbir adımı atlamayın.</p>
    </header>

    <div>
        <div class="card rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm p-5 sm:p-6 mb-6" id="move-date-card">
            <label for="move-date" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Taşınma tarihi *</label>
            <div class="flex flex-wrap items-center gap-3">
                <input type="date" id="move-date" class="input-touch w-full sm:w-auto min-h-[44px] border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl py-2.5 flex-1" style="min-width: 160px;">
                <button type="button" id="move-date-apply" class="btn-touch min-h-[44px] px-5 py-2.5 rounded-xl font-medium bg-emerald-500 hover:bg-emerald-600 text-white shadow-sm">Takvimi göster</button>
            </div>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2">Tarih seçip &quot;Takvimi göster&quot;e tıklayın veya takvimi otomatik görmek için bir tarih seçin.</p>
        </div>

        <div id="calendar-timeline" class="grid grid-cols-2 md:grid-cols-1 gap-4 hidden">
            @foreach($phases as $phase => $tasks)
                <div class="card rounded-2xl border border-zinc-200/80 dark:border-zinc-800 overflow-hidden" data-phase="{{ $phase }}">
                    <div class="p-4 sm:p-5 bg-gradient-to-r from-amber-50 to-white dark:from-amber-950/20 dark:to-zinc-900 border-b border-zinc-100 dark:border-zinc-800">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                            <div>
                                <h2 class="font-semibold text-zinc-900 dark:text-white phase-label">{{ $phase }}</h2>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 phase-date">—</p>
                            </div>
                        </div>
                    </div>
                    <ul class="p-4 sm:p-5 space-y-2">
                        @foreach($tasks as $task)
                            <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                <span class="w-5 h-5 shrink-0 rounded-full border-2 border-amber-300 dark:border-amber-600 mt-0.5"></span>
                                {{ $task }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div id="calendar-empty" class="card rounded-2xl border border-zinc-200/80 dark:border-zinc-800 p-12 text-center">
            <p class="text-zinc-500 dark:text-zinc-400">Taşınma tarihini seçtiğinizde takvim burada görünecektir.</p>
        </div>
    </div>

    <section class="mt-8 pt-6 border-t border-zinc-200 dark:border-zinc-800" aria-labelledby="nasil-calisir-calendar">
        <h2 id="nasil-calisir-calendar" class="text-lg font-semibold text-zinc-900 dark:text-white mb-3">Taşınma takvimi nasıl kullanılır?</h2>
        <div class="prose prose-sm prose-zinc dark:prose-invert max-w-none text-zinc-600 dark:text-zinc-400">
            @if(!empty($toolContent))
                {!! $toolContent !!}
            @else
                <p>Taşınma takvimi, taşınma gününüze göre hangi işlemleri ne zaman yapmanız gerektiğini adım adım gösterir. Böylece ev sahibine bildirim, abonelik iptali, adres güncellemesi ve paketleme gibi işlemleri kaçırmazsınız.</p>
                <p><strong>Kullanım:</strong> Yukarıdaki alandan taşınma tarihinizi seçin. Takvim otomatik olarak güncellenir ve her faz için (4 hafta önce, 3 hafta önce, 2 hafta önce, 1 hafta önce, 3 gün önce, 1 gün önce ve taşınma günü) tam tarihler ile yapılacak işlemler listelenir. Bu listeyi takip ederek taşınma sürecinizi stressiz planlayabilirsiniz.</p>
            @endif
        </div>
    </section>
</div>

@push('scripts')
<script>
(function() {
    const moveDateEl = document.getElementById('move-date');
    const timeline = document.getElementById('calendar-timeline');
    const emptyEl = document.getElementById('calendar-empty');
    const phaseOffsets = @json($phaseOffsets ?? []);

    function formatDate(d) {
        return d.toLocaleDateString('tr-TR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    }

    function update() {
        const val = moveDateEl.value;
        if (!val) {
            timeline.classList.add('hidden');
            emptyEl.classList.remove('hidden');
            return;
        }

        const moveDate = new Date(val + 'T12:00:00');
        if (isNaN(moveDate.getTime())) {
            timeline.classList.add('hidden');
            emptyEl.classList.remove('hidden');
            return;
        }

        timeline.classList.remove('hidden');
        emptyEl.classList.add('hidden');

        Object.keys(phaseOffsets).forEach(function(label) {
            const offset = phaseOffsets[label];
            const d = new Date(moveDate);
            d.setDate(d.getDate() + offset);
            const block = timeline.querySelector('[data-phase="' + label.replace(/"/g, '\\"') + '"]');
            if (block) {
                const dateEl = block.querySelector('.phase-date');
                if (dateEl) dateEl.textContent = formatDate(d);
            }
        });
    }

    if (moveDateEl) {
        moveDateEl.addEventListener('change', update);
        moveDateEl.addEventListener('input', update);
        moveDateEl.addEventListener('blur', update);
    }
    var applyBtn = document.getElementById('move-date-apply');
    if (applyBtn) {
        applyBtn.addEventListener('click', function() {
            if (moveDateEl) moveDateEl.focus();
            update();
        });
    }
    var dateCard = document.getElementById('move-date-card');
    if (dateCard && moveDateEl) {
        dateCard.addEventListener('click', function(ev) {
            if (ev.target === moveDateEl || ev.target.id === 'move-date-apply') return;
            moveDateEl.focus();
            try { moveDateEl.showPicker && moveDateEl.showPicker(); } catch (e) {}
        });
    }
})();
</script>
@endpush
@endsection
