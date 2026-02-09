@extends('layouts.app')

@section('title', $metaTitle ?? 'Tahmini Maliyet - NakliyePark')
@section('meta_description', $metaDescription ?? 'Hacim ve mesafeye göre nakliyat maliyet aralığı hesaplayın. Kesin fiyat için ücretsiz ihale açıp teklif alın.')

@section('content')
<div class="px-4 py-6 max-w-lg mx-auto">
    <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">Tahmini Maliyet</h1>
    <p class="text-sm text-slate-500 mb-6">Hacim ve mesafeye göre kabaca nakliyat maliyet aralığı. Kesin fiyat için ihale açıp teklif alın.</p>

    <div class="space-y-4 mb-6">
        <div>
            <label for="volume_m3" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Taşınacak hacim (m³) *</label>
            <input type="number" id="volume_m3" min="1" step="0.5" value="20" class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl" placeholder="Örn. 20">
        </div>
        <div>
            <label for="distance_km" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Mesafe (km) *</label>
            <input type="number" id="distance_km" min="1" step="1" value="300" class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl" placeholder="Örn. 300">
        </div>
        <div class="flex flex-col gap-2">
            <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Ek hizmetler</span>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" id="extra_packing" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                <span class="text-sm text-slate-700 dark:text-slate-300">Paketleme / ambalaj</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" id="extra_insurance" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                <span class="text-sm text-slate-700 dark:text-slate-300">Sigorta</span>
            </label>
        </div>
        <button type="button" id="btn-calc" class="btn-primary w-full rounded-xl py-3">Hesapla</button>
    </div>

    <div id="result" class="card-touch bg-sky-50 dark:bg-sky-900/20 hidden">
        <p class="text-sm text-slate-500 dark:text-slate-400">Tahmini aralık</p>
        <p class="text-2xl font-bold text-sky-600 dark:text-sky-400 mt-1"><span id="result-min">0</span> – <span id="result-max">0</span> ₺</p>
        <p class="text-xs text-slate-500 mt-3">Fiyatlar firmaya ve hizmet detayına göre değişir. Kesin teklif için ücretsiz ihale açın.</p>
        <a href="{{ route('ihale.create') }}" class="btn-primary rounded-xl mt-4 inline-block">İhale başlat</a>
    </div>

    @if(!empty($toolContent))
    <section class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-600" aria-labelledby="nasil-calisir-cost">
        <h2 id="nasil-calisir-cost" class="text-base font-semibold text-slate-800 dark:text-slate-100 mb-3">Tahmini maliyet nasıl çalışır?</h2>
        <div class="prose prose-sm prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-slate-400">
            {!! $toolContent !!}
        </div>
    </section>
    @endif
</div>

@push('scripts')
<script>
(function() {
    const volumeEl = document.getElementById('volume_m3');
    const distanceEl = document.getElementById('distance_km');
    const extraPacking = document.getElementById('extra_packing');
    const extraInsurance = document.getElementById('extra_insurance');
    const btnCalc = document.getElementById('btn-calc');
    const resultBox = document.getElementById('result');
    const resultMin = document.getElementById('result-min');
    const resultMax = document.getElementById('result-max');

    function formatMoney(n) {
        return new Intl.NumberFormat('tr-TR').format(Math.round(n));
    }

    btnCalc.addEventListener('click', function() {
        const vol = parseFloat(volumeEl.value) || 0;
        const km = parseFloat(distanceEl.value) || 0;
        if (vol <= 0 || km <= 0) {
            alert('Hacim ve mesafe için geçerli değer girin.');
            return;
        }
        // Kabaca: taban (m³ * birim + km * birim), ek hizmetler yüzde artış
        const basePerM3 = 80;
        const basePerKm = 3.5;
        let base = vol * basePerM3 + km * basePerKm;
        if (extraPacking.checked) base *= 1.15;
        if (extraInsurance.checked) base *= 1.08;
        const min = base * 0.75;
        const max = base * 1.35;
        resultMin.textContent = formatMoney(min);
        resultMax.textContent = formatMoney(max);
        resultBox.classList.remove('hidden');
    });
})();
</script>
@endpush
@endsection
