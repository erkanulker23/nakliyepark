@extends('layouts.app')

@section('title', $metaTitle ?? 'Hacim Hesaplama - NakliyePark')
@section('meta_description', $metaDescription ?? 'Kayıtlı odalara göre taşınacak hacmi hesaplayın. Nakliye ihalesi için toplam m³ değerini kolayca bulun.')

@section('content')
<div class="page-container py-6 sm:py-8">
    <header class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Hacim Hesaplama</h1>
        <p class="mt-1.5 text-sm text-zinc-500 dark:text-zinc-400">Kayıtlı odalara göre taşınacak hacmi hesaplayın; tahmini araç ihtiyacını görün.</p>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
        {{-- Sol: Hacim Hesaplama --}}
        <section class="card rounded-2xl border-0 shadow-lg shadow-zinc-200/60 dark:shadow-none dark:bg-zinc-900/80 dark:border dark:border-zinc-800 overflow-hidden" aria-labelledby="hacim-baslik">
            <div class="p-5 sm:p-6 bg-gradient-to-br from-emerald-50/80 to-white dark:from-emerald-950/20 dark:to-zinc-900 border-b border-zinc-100 dark:border-zinc-800">
                <h2 id="hacim-baslik" class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-500/15 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    </span>
                    Hacim Hesaplama
                </h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Oda sayılarını girin, toplam m³ otomatik hesaplansın.</p>
            </div>
            <div class="p-5 sm:p-6 space-y-3">
                @foreach($rooms as $room)
                    <div class="flex items-center justify-between gap-4 py-3 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-700/50 transition-colors hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80">
                        <span class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $room->name }}</span>
                        <div class="flex items-center gap-2">
                            <button type="button" class="vol-minus w-9 h-9 rounded-xl flex items-center justify-center text-zinc-600 dark:text-zinc-300 bg-white dark:bg-zinc-700 border border-zinc-200 dark:border-zinc-600 hover:bg-zinc-100 dark:hover:bg-zinc-600 transition-colors font-medium text-lg leading-none select-none" data-m3="{{ $room->default_volume_m3 }}" aria-label="Azalt">−</button>
                            <span class="vol-display min-w-[2.5rem] text-center text-sm font-semibold text-zinc-900 dark:text-white tabular-nums" data-default="{{ $room->default_volume_m3 }}">0</span>
                            <span class="text-xs text-zinc-500 dark:text-zinc-400 w-6">m³</span>
                            <button type="button" class="vol-plus w-9 h-9 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-colors font-medium text-lg leading-none select-none" data-m3="{{ $room->default_volume_m3 }}" aria-label="Artır">+</button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-5 sm:p-6 pt-0">
                <div class="flex items-center justify-between py-4 px-5 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/15 border border-emerald-200/80 dark:border-emerald-800/80">
                    <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">Toplam hacim</span>
                    <span class="flex items-baseline gap-1">
                        <span id="total-volume" class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 tabular-nums">0</span>
                        <span class="text-sm font-medium text-emerald-600 dark:text-emerald-400">m³</span>
                    </span>
                </div>
            </div>
        </section>

        {{-- Sağ: Tahmini araç ihtiyacı --}}
        <section class="card rounded-2xl border-0 shadow-lg shadow-zinc-200/60 dark:shadow-none dark:bg-zinc-900/80 dark:border dark:border-zinc-800 overflow-hidden" aria-labelledby="arac-baslik">
            <div class="p-5 sm:p-6 bg-gradient-to-br from-sky-50/80 to-white dark:from-sky-950/20 dark:to-zinc-900 border-b border-zinc-100 dark:border-zinc-800">
                <h2 id="arac-baslik" class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-sky-500/15 dark:bg-sky-500/20 flex items-center justify-center text-sky-600 dark:text-sky-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 4h8m-3 5l3-3m0 0l3 3m-3-3v-6"/></svg>
                    </span>
                    Tahmini araç ihtiyacı
                </h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Hacme göre araçlar küçükten büyüğe (kamyonet → kamyon → tır) doldurulur.</p>
            </div>
            <div class="p-5 sm:p-6 space-y-3">
                <div class="flex items-center justify-between py-4 px-5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-700/50">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400 text-lg" aria-hidden="true">🚐</span>
                        <div>
                            <span class="text-sm font-medium text-zinc-800 dark:text-zinc-200 block">Kamyonet</span>
                            <span class="text-xs text-zinc-500 dark:text-zinc-400">~18 m³</span>
                        </div>
                    </div>
                    <span id="vol-kamyonet" class="text-base font-semibold text-zinc-900 dark:text-white tabular-nums">0 adet</span>
                </div>
                <div class="flex items-center justify-between py-4 px-5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-700/50">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-400 text-lg" aria-hidden="true">🚛</span>
                        <div>
                            <span class="text-sm font-medium text-zinc-800 dark:text-zinc-200 block">Kamyon</span>
                            <span class="text-xs text-zinc-500 dark:text-zinc-400">~50 m³</span>
                        </div>
                    </div>
                    <span id="vol-kamyon" class="text-base font-semibold text-zinc-900 dark:text-white tabular-nums">0 adet</span>
                </div>
                <div class="flex items-center justify-between py-4 px-5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-700/50">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center text-violet-600 dark:text-violet-400 text-lg" aria-hidden="true">🚚</span>
                        <div>
                            <span class="text-sm font-medium text-zinc-800 dark:text-zinc-200 block">Tır</span>
                            <span class="text-xs text-zinc-500 dark:text-zinc-400">~90 m³</span>
                        </div>
                    </div>
                    <span id="vol-tir" class="text-base font-semibold text-zinc-900 dark:text-white tabular-nums">0 adet</span>
                </div>
            </div>
            <div class="px-5 sm:px-6 pb-5 sm:pb-6">
                <p id="vehicle-remainder" class="text-xs text-zinc-500 dark:text-zinc-400 rounded-lg bg-zinc-50 dark:bg-zinc-800/50 px-4 py-2.5 hidden">
                    Son araçta <span id="remainder-m3" class="font-medium text-zinc-700 dark:text-zinc-300">0</span> m³ doluluk.
                </p>
            </div>
        </section>
    </div>

    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-6">Bu değeri nakliye ihalesi oluştururken kullanabilirsiniz.</p>

    @if(!empty($toolContent))
    <section class="mt-8 pt-6 border-t border-zinc-200 dark:border-zinc-800" aria-labelledby="nasil-calisir-vol">
        <h2 id="nasil-calisir-vol" class="text-lg font-semibold text-zinc-900 dark:text-white mb-3">Hacim hesaplama nasıl çalışır?</h2>
        <div class="prose prose-sm prose-zinc dark:prose-invert max-w-none text-zinc-600 dark:text-zinc-400">
            {!! $toolContent !!}
        </div>
    </section>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function() {
    const KAMYONET_M3 = 18;
    const KAMYON_M3 = 50;
    const TIR_M3 = 90;

    const totalEl = document.getElementById('total-volume');
    const displays = document.querySelectorAll('.vol-display');
    const volKamyonet = document.getElementById('vol-kamyonet');
    const volKamyon = document.getElementById('vol-kamyon');
    const volTir = document.getElementById('vol-tir');
    const remainderEl = document.getElementById('vehicle-remainder');
    const remainderM3 = document.getElementById('remainder-m3');

    let total = 0;

    function updateVehicleBreakdown() {
        let remaining = total;
        const nKamyonet = Math.floor(remaining / KAMYONET_M3);
        remaining -= nKamyonet * KAMYONET_M3;
        const nKamyon = Math.floor(remaining / KAMYON_M3);
        remaining -= nKamyon * KAMYON_M3;
        const nTirFull = Math.floor(remaining / TIR_M3);
        const lastTirM3 = remaining - nTirFull * TIR_M3;
        const nTir = nTirFull + (lastTirM3 > 0 ? 1 : 0);

        volKamyonet.textContent = nKamyonet + ' adet';
        volKamyon.textContent = nKamyon + ' adet';
        volTir.textContent = nTir + ' adet';

        if (nTir > 0 && lastTirM3 > 0) {
            remainderM3.textContent = lastTirM3.toFixed(1);
            remainderEl.classList.remove('hidden');
        } else {
            remainderEl.classList.add('hidden');
        }
    }

    document.querySelectorAll('.vol-plus').forEach((btn, i) => {
        btn.addEventListener('click', () => {
            const m3 = parseFloat(btn.dataset.m3);
            const disp = displays[i];
            disp.textContent = parseInt(disp.textContent || '0') + 1;
            total += m3;
            totalEl.textContent = total.toFixed(1);
            updateVehicleBreakdown();
        });
    });
    document.querySelectorAll('.vol-minus').forEach((btn, i) => {
        btn.addEventListener('click', () => {
            const disp = displays[i];
            const n = parseInt(disp.textContent || '0');
            if (n > 0) {
                const m3 = parseFloat(btn.dataset.m3);
                disp.textContent = n - 1;
                total = Math.max(0, total - m3);
                totalEl.textContent = total.toFixed(1);
                updateVehicleBreakdown();
            }
        });
    });

    updateVehicleBreakdown();
})();
</script>
@endpush
