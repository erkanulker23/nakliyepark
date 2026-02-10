@extends('layouts.app')

@section('title', $metaTitle ?? 'Taşınma Kontrol Listesi - NakliyePark')
@section('meta_description', $metaDescription ?? 'Taşınma öncesi yapılacaklar listesi. Adres değişikliği, abonelik iptali ve daha fazlası.')

@section('content')
<div class="page-container py-6 sm:py-8">
    <header class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Taşınma Kontrol Listesi</h1>
        <p class="mt-1.5 text-sm text-zinc-500 dark:text-zinc-400">Taşınma öncesinde unutmamanız gereken işlemleri işaretleyin. İlerlemeniz tarayıcınızda saklanır.</p>
        <p class="mt-3 text-zinc-600 dark:text-zinc-400 text-base max-w-2xl">Taşınma kontrol listesi ile ev sahibine bildirim, adres değişikliği, abonelik iptali, okul nakli ve taşınma günü yapılacaklar gibi adımları takip edin. Her maddeyi işaretleyerek ilerleme kaydedilir; liste tarayıcıda saklanır.</p>
    </header>

    <div class="max-w-2xl">
        <div class="card rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 space-y-4">
                @foreach($items as $group => $groupItems)
                    <div class="border-b border-zinc-100 dark:border-zinc-800 pb-4 last:border-0 last:pb-0 last:mb-0">
                        <h2 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider mb-3">{{ $group }}</h2>
                        <ul class="space-y-2">
                            @foreach($groupItems as $key => $label)
                                <li class="flex items-center gap-3 py-2 px-3 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    <input type="checkbox" id="cl-{{ $key }}" data-key="{{ $key }}" class="checklist-item rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500 h-5 w-5 shrink-0">
                                    <label for="cl-{{ $key }}" class="text-sm text-zinc-700 dark:text-zinc-300 cursor-pointer flex-1">{{ $label }}</label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
            <div class="px-5 sm:px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 border-t border-zinc-100 dark:border-zinc-800">
                <p class="text-xs text-zinc-500 dark:text-zinc-400">İlerleme tarayıcıda saklanır. Listeyi sıfırlamak için: <button type="button" id="btn-reset" class="text-amber-600 dark:text-amber-400 hover:underline font-medium">Sıfırla</button></p>
            </div>
        </div>
    </div>

    <section class="mt-8 pt-6 border-t border-zinc-200 dark:border-zinc-800" aria-labelledby="nasil-calisir-checklist">
        <h2 id="nasil-calisir-checklist" class="text-lg font-semibold text-zinc-900 dark:text-white mb-3">Taşınma kontrol listesi nasıl kullanılır?</h2>
        <div class="prose prose-sm prose-zinc dark:prose-invert max-w-none text-zinc-600 dark:text-zinc-400">
            @if(!empty($toolContent))
                {!! $toolContent !!}
            @else
                <p>Taşınma kontrol listesi, taşınma öncesi ve taşınma günü yapılması gereken işlemleri zaman dilimine göre (1 ay önce, 2 hafta önce, 1 hafta önce, taşınma günü) gruplar.</p>
                <p><strong>Kullanım:</strong> Her maddeyi tamamladıkça yanındaki kutucuğu işaretleyin. İlerlemeniz tarayıcınızda (localStorage) saklanır; sayfayı kapatsanız bile işaretleriniz kalır. Listeyi sıfırlamak için alttaki &ldquo;Sıfırla&rdquo; butonunu kullanın. Ev sahibine bildirim, PTT posta yönlendirme, abonelik iptali, kimlik ve banka adres güncellemesi, sayaç okumaları ve nakliye firması koordinasyonu gibi önemli adımlar listede yer alır.</p>
            @endif
        </div>
    </section>
</div>

@push('scripts')
<script>
(function() {
    const STORAGE_KEY = 'nakliyepark_checklist';
    const items = document.querySelectorAll('.checklist-item');
    const btnReset = document.getElementById('btn-reset');

    function load() {
        try {
            const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
            items.forEach(el => {
                el.checked = !!saved[el.dataset.key];
            });
        } catch (e) {}
    }

    function save() {
        const obj = {};
        items.forEach(el => { obj[el.dataset.key] = el.checked; });
        localStorage.setItem(STORAGE_KEY, JSON.stringify(obj));
    }

    items.forEach(el => {
        el.addEventListener('change', save);
    });
    btnReset.addEventListener('click', () => {
        if (confirm('Tüm işaretleri sıfırlamak istediğinize emin misiniz?')) {
            items.forEach(el => { el.checked = false; });
            save();
        }
    });

    load();
})();
</script>
@endpush
@endsection
