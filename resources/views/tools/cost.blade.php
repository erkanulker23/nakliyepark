@extends('layouts.app')

@section('title', $metaTitle ?? 'Tahmini Maliyet - NakliyePark')
@section('meta_description', $metaDescription ?? 'Hacim ve mesafeye göre nakliyat maliyet aralığı hesaplayın. Kesin fiyat için ücretsiz ihale açıp teklif alın.')

@section('content')
<div class="page-container py-6 sm:py-8">
    <header class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Tahmini Maliyet</h1>
        <p class="mt-1.5 text-sm text-zinc-500 dark:text-zinc-400">Nereden nereye taşınacağınızı ve eşya bilgisini girin; tahmini fiyat aralığı çıksın. Kesin fiyat için ihale açıp teklif alın.</p>
    </header>

    <div class="max-w-2xl">
        <div class="card rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="from_province" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Nereden (il) *</label>
                        <select id="from_province" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                            <option value="">İl seçin</option>
                        </select>
                    </div>
                    <div>
                        <label for="to_province" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Nereye (il) *</label>
                        <select id="to_province" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                            <option value="">İl seçin</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="item_type" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Ne taşınacak (eşya türü) *</label>
                    <select id="item_type" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                        <option value="ev_esyasi">Ev eşyası (mobilya, eşya)</option>
                        <option value="ofis_esyasi">Ofis eşyası</option>
                        <option value="parca_yuk">Parça yük / palet</option>
                        <option value="karisik">Karışık</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Eşya / hacim (m³)</label>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-2">Hızlı seçim veya toplam m³ girin.</p>
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach(['1+1' => 15, '2+1' => 25, '3+1' => 40, '4+1' => 55, '5+1' => 70, 'Daha büyük' => 90] as $label => $m3)
                            <button type="button" class="cost-room-btn px-3 py-1.5 rounded-xl text-sm font-medium border border-zinc-200 dark:border-zinc-600 text-zinc-600 dark:text-zinc-400 hover:border-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 dark:hover:border-emerald-600 transition-colors" data-m3="{{ $m3 }}">{{ $label }} ({{ $m3 }} m³)</button>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="number" id="volume_m3" min="1" step="0.5" value="25" placeholder="m³" class="input-touch w-28 border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">m³ taşınacak</span>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Ek hizmetler</span>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="extra_packing" class="rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-zinc-700 dark:text-zinc-300">Paketleme / ambalaj</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="extra_insurance" class="rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-zinc-700 dark:text-zinc-300">Sigorta</span>
                    </label>
                </div>

                <button type="button" id="btn-calc" class="btn-primary w-full rounded-xl py-3">Tahmini fiyat hesapla</button>
            </div>
        </div>

        <div id="result" class="mt-6 rounded-2xl border border-emerald-200/80 dark:border-emerald-800/80 bg-emerald-50/80 dark:bg-emerald-950/30 p-6 hidden">
            <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Nereden → Nereye</p>
            <p id="result-route" class="text-base font-semibold text-zinc-900 dark:text-white mt-0.5">—</p>
            <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mt-3">Eşya türü</p>
            <p id="result-item-type" class="text-sm text-zinc-600 dark:text-zinc-400">—</p>
            <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mt-1">Hacim</p>
            <p id="result-volume" class="text-sm text-zinc-600 dark:text-zinc-400">— m³</p>
            <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-3">Tahmini fiyat aralığı</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1"><span id="result-min">0</span> – <span id="result-max">0</span> ₺</p>
            <p id="result-detail" class="text-sm text-zinc-500 dark:text-zinc-400 mt-2"></p>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-3">Fiyatlar firmaya ve hizmet detayına göre değişir. Kesin teklif için ücretsiz ihale açın.</p>
            <a href="{{ route('ihale.create') }}" class="btn-primary rounded-xl mt-4 inline-block">İhale başlat</a>
        </div>
    </div>

    @if(!empty($toolContent))
    <section class="mt-8 pt-6 border-t border-zinc-200 dark:border-zinc-800" aria-labelledby="nasil-calisir-cost">
        <h2 id="nasil-calisir-cost" class="text-lg font-semibold text-zinc-900 dark:text-white mb-3">Tahmini maliyet nasıl çalışır?</h2>
        <div class="prose prose-sm prose-zinc dark:prose-invert max-w-none text-zinc-600 dark:text-zinc-400">
            {!! $toolContent !!}
        </div>
    </section>
    @endif
</div>

@push('scripts')
<script>
(function() {
    const fromSelect = document.getElementById('from_province');
    const toSelect = document.getElementById('to_province');
    const itemTypeSelect = document.getElementById('item_type');
    const volumeEl = document.getElementById('volume_m3');
    const extraPacking = document.getElementById('extra_packing');
    const extraInsurance = document.getElementById('extra_insurance');
    const btnCalc = document.getElementById('btn-calc');
    const resultBox = document.getElementById('result');
    const resultRoute = document.getElementById('result-route');
    const resultItemType = document.getElementById('result-item-type');
    const resultVolume = document.getElementById('result-volume');
    const resultMin = document.getElementById('result-min');
    const resultMax = document.getElementById('result-max');
    const resultDetail = document.getElementById('result-detail');

    const itemTypeLabels = { ev_esyasi: 'Ev eşyası', ofis_esyasi: 'Ofis eşyası', parca_yuk: 'Parça yük / palet', karisik: 'Karışık' };
    const itemTypeMultipliers = { ev_esyasi: 1, ofis_esyasi: 1.25, parca_yuk: 1.1, karisik: 1.15 };

    let provinces = [];

    function haversine(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2)**2 + Math.cos(lat1 * Math.PI/180) * Math.cos(lat2 * Math.PI/180) * Math.sin(dLon/2)**2;
        return 2 * R * Math.asin(Math.sqrt(a));
    }

    function fillProvinces() {
        const opts = '<option value="">İl seçin</option>' + provinces.map(p => '<option value="' + p.id + '" data-lat="' + (p.latitude || '') + '" data-lng="' + (p.longitude || '') + '" data-name="' + (p.name || '').replace(/"/g, '&quot;') + '">' + p.name + '</option>').join('');
        fromSelect.innerHTML = opts;
        toSelect.innerHTML = opts;
    }

    document.querySelectorAll('.cost-room-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            volumeEl.value = this.dataset.m3;
            document.querySelectorAll('.cost-room-btn').forEach(b => b.classList.remove('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20', 'dark:border-emerald-600', 'text-emerald-700', 'dark:text-emerald-300'));
            this.classList.add('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20', 'dark:border-emerald-600', 'text-emerald-700', 'dark:text-emerald-300');
        });
    });

    function formatMoney(n) {
        return new Intl.NumberFormat('tr-TR').format(Math.round(n));
    }

    btnCalc.addEventListener('click', function() {
        const fromOpt = fromSelect.options[fromSelect.selectedIndex];
        const toOpt = toSelect.options[toSelect.selectedIndex];
        if (!fromOpt || !fromOpt.value || !toOpt || !toOpt.value) {
            alert('Nereden ve nereye için il seçin.');
            return;
        }
        if (fromOpt.value === toOpt.value) {
            alert('Farklı iller seçin.');
            return;
        }
        const vol = parseFloat(volumeEl.value) || 0;
        if (vol <= 0) {
            alert('Geçerli bir hacim (m³) girin.');
            return;
        }
        const fromLat = parseFloat(fromOpt.dataset.lat);
        const fromLng = parseFloat(fromOpt.dataset.lng);
        const toLat = parseFloat(toOpt.dataset.lat);
        const toLng = parseFloat(toOpt.dataset.lng);
        const fromName = fromOpt.dataset.name || fromOpt.textContent;
        const toName = toOpt.dataset.name || toOpt.textContent;
        const km = (fromLat && fromLng && toLat && toLng) ? Math.round(haversine(fromLat, fromLng, toLat, toLng)) : 0;

        const itemType = itemTypeSelect?.value || 'ev_esyasi';
        const itemMult = itemTypeMultipliers[itemType] || 1;

        const basePerM3 = 195;
        const basePerKm = 6.2;
        let base = (vol * basePerM3 + (km || 50) * basePerKm) * itemMult;
        if (extraPacking.checked) base *= 1.18;
        if (extraInsurance.checked) base *= 1.1;
        const min = base * 0.78;
        const max = base * 1.4;

        resultRoute.textContent = fromName + ' → ' + toName;
        resultItemType.textContent = itemTypeLabels[itemType] || itemType;
        resultVolume.textContent = vol + ' m³';
        resultMin.textContent = formatMoney(min);
        resultMax.textContent = formatMoney(max);
        resultDetail.textContent = (km ? 'Yaklaşık ' + km + ' km. ' : '') + 'Fiyatlar firmaya göre değişir.';
        resultBox.classList.remove('hidden');
    });

    fetch('{{ route("api.turkey.provinces") }}')
        .then(r => r.json())
        .then(data => {
            if (data.data && data.data.length) {
                provinces = data.data.filter(p => p.latitude != null && p.longitude != null);
                fillProvinces();
            }
        })
        .catch(() => {});
})();
</script>
@endpush
@endsection
