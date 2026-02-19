@php
    $config = $config ?? config('price_estimator');
    $showEmbedLink = $showEmbedLink ?? false;
    $ihaleCreateUrl = $ihaleCreateUrl ?? route('ihale.create');
    $priceHistoryLast10 = $priceHistoryLast10 ?? collect();
@endphp
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<style>
#price-estimator-map-container { border-radius: 1rem; overflow: hidden; }
#price-estimator-map-container .leaflet-control-zoom a { border-radius: 8px; }
</style>
@endpush
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush
<div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-lg overflow-visible" id="price-estimator">
    <div class="p-4 sm:p-5 border-b border-zinc-200 dark:border-zinc-800 bg-gradient-to-r from-emerald-50/80 to-teal-50/50 dark:from-emerald-950/20 dark:to-zinc-900">
        <h2 class="text-lg font-bold text-zinc-900 dark:text-white flex items-center gap-2">
            <span class="w-9 h-9 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
            Nakliye Bilgileri
        </h2>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Tüm alanları doldurun; fiyat tahmini otomatik güncellenir.</p>
    </div>

    <div class="p-4 sm:p-6 space-y-5">
        {{-- Hizmet tipi --}}
        <div>
            <label for="service_type" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Hizmet tipi</label>
            <select id="service_type" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                @foreach(\App\Models\Ihale::serviceTypeLabels() as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- Eşya durumu (oda tipi) — ihale gibi; sadece uluslararasıda hacim (m³) --}}
        <div id="room-type-wrap">
            <label for="room_type" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Eşya durumu (oda tipi) *</label>
            <select id="room_type" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                @foreach(array_keys($config['room_type_factors'] ?? []) as $room)
                    @php
                        $factors = $config['room_type_factors'] ?? [];
                        $factor = $factors[$room] ?? 1;
                    @endphp
                    <option value="{{ $room }}" data-factor="{{ $factor }}">{{ $room }}</option>
                @endforeach
            </select>
        </div>
        <div id="volume-wrap" class="hidden">
            <label for="volume_m3" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Tahmini hacim (m³) *</label>
            <input type="number" id="volume_m3" min="1" max="500" step="1" value="30" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="Örn. 40">
        </div>

        {{-- Türkiye: İl + İlçe, mesafe otomatik --}}
        <div id="turkey-location-wrap" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="from_province" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nereden (il / ilçe)</label>
                    <select id="from_province" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                        <option value="">İl seçin</option>
                    </select>
                    <select id="from_district" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" disabled>
                        <option value="">Önce il seçin</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label for="to_province" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nereye (il / ilçe)</label>
                    <select id="to_province" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                        <option value="">İl seçin</option>
                    </select>
                    <select id="to_district" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" disabled>
                        <option value="">Önce il seçin</option>
                    </select>
                </div>
            </div>
            <div id="distance-display" class="rounded-xl bg-zinc-100 dark:bg-zinc-800/50 px-4 py-2 text-sm text-zinc-600 dark:text-zinc-400">
                <span id="distance-label">Mesafe: İl ve ilçe seçin, otomatik hesaplanacak</span>
            </div>
        </div>

        {{-- Uluslararası: Ülke + şehir, mesafe elle (km) --}}
        <div id="international-location-wrap" class="hidden space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="from_country" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Çıkış ülkesi</label>
                    <select id="from_country" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                        <option value="">Ülke seçin</option>
                        @foreach($config['countries'] ?? ['Türkiye', 'Almanya', 'Hollanda', 'Fransa', 'İngiltere', 'ABD', 'Diğer'] as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                    <input type="text" id="from_city_int" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="Şehir (opsiyonel)">
                </div>
                <div class="space-y-2">
                    <label for="to_country" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Varış ülkesi</label>
                    <select id="to_country" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                        <option value="">Ülke seçin</option>
                        @foreach($config['countries'] ?? ['Türkiye', 'Almanya', 'Hollanda', 'Fransa', 'İngiltere', 'ABD', 'Diğer'] as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                    <input type="text" id="to_city_int" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="Şehir (opsiyonel)">
                </div>
            </div>
            <div>
                <label for="distance_km_manual" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Tahmini mesafe (km)</label>
                <input type="number" id="distance_km_manual" min="0" max="20000" step="1" value="0" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" placeholder="Örn. 2500">
            </div>
        </div>

        <input type="hidden" id="distance_km" value="0">

        {{-- Harita: iller seçildikten sonra karayolu rotası --}}
        <div id="price-estimator-map-wrapper" class="relative rounded-2xl overflow-hidden border border-zinc-200/80 dark:border-zinc-800 bg-zinc-100 dark:bg-zinc-800/50 hidden shadow-lg">
            <div id="price-estimator-map-container" class="w-full" style="height: 320px;">
                <div id="price-estimator-map" class="w-full h-full"></div>
            </div>
            <div id="price-estimator-map-legend" class="absolute bottom-3 left-3 right-3 sm:left-auto sm:right-3 sm:w-auto flex items-center justify-center gap-4 text-sm bg-white/95 dark:bg-zinc-900/95 backdrop-blur rounded-lg px-3 py-2 shadow">
                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500"></span> Nereden</span>
                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Nereye</span>
            </div>
        </div>

        {{-- Kat bilgisi --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="from_floor" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Çıkış katı</label>
                <select id="from_floor" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                    <option value="0">Zemin</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">{{ $i }}. kat</option>
                    @endfor
                    <option value="15">11+ kat</option>
                </select>
            </div>
            <div>
                <label for="to_floor" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Varış katı</label>
                <select id="to_floor" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                    <option value="0">Zemin</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">{{ $i }}. kat</option>
                    @endfor
                    <option value="15">11+ kat</option>
                </select>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Çıkışta asansör var mı?</label>
                <div class="flex gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="from_elevator" value="1" class="w-4 h-4 text-emerald-500 accent-emerald-500">
                        <span class="text-sm">Evet</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="from_elevator" value="0" checked class="w-4 h-4 text-emerald-500 accent-emerald-500">
                        <span class="text-sm">Hayır</span>
                    </label>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Varışta asansör var mı?</label>
                <div class="flex gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="to_elevator" value="1" class="w-4 h-4 text-emerald-500 accent-emerald-500">
                        <span class="text-sm">Evet</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="to_elevator" value="0" checked class="w-4 h-4 text-emerald-500 accent-emerald-500">
                        <span class="text-sm">Hayır</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- Sonuç --}}
    <div class="p-4 sm:p-6 border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/30">
        <div id="result-box" class="rounded-xl border-2 border-emerald-200/80 dark:border-emerald-800/80 bg-emerald-50/80 dark:bg-emerald-950/30 p-5 sm:p-6">
            <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Tahmini fiyat</p>
            <p class="text-2xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                <span id="price-display">0</span> ₺
            </p>
            <div id="price-details" class="mt-2 text-xs text-zinc-500 dark:text-zinc-400 space-y-0.5"></div>
            @if($showEmbedLink)
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2">Bu tahmin referans amaçlıdır. Kesin fiyat için <a href="{{ $ihaleCreateUrl }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">ihale açın</a> ve firmalardan teklif alın.</p>
            @endif
        </div>
        <div id="local-message" class="hidden mt-3 rounded-xl border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/30 p-4">
            <p class="text-sm text-amber-800 dark:text-amber-200">{{ $config['local_transport_message'] ?? 'Lütfen çağrı merkezimizden şehir içi nakliye fiyatlarını öğreniniz.' }}</p>
            <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">Please obtain the prices for local transportation from our call center.</p>
        </div>

        <section id="son-10-fiyat-section" class="mt-6 pt-5 border-t-2 border-zinc-200 dark:border-zinc-700" aria-labelledby="son-fiyat-baslik">
            <h3 id="son-fiyat-baslik" class="text-base font-semibold text-zinc-900 dark:text-white mb-1">Son 10 tahmini fiyat hesaplaması</h3>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-3">Site genelinde yapılan son hesaplamalar; detaylar hemen altında listelenir.</p>
            <div id="price-history-list" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800/30 divide-y divide-zinc-200 dark:divide-zinc-700 overflow-hidden min-h-[120px]">
                <p class="p-4 text-sm text-zinc-500 dark:text-zinc-400 {{ $priceHistoryLast10->isNotEmpty() ? 'hidden' : '' }}" id="price-history-empty">Henüz fiyat hesaplaması yapılmadı. Mesafe ve diğer bilgileri girince tahmin otomatik hesaplanır ve burada listelenir.</p>
                @foreach($priceHistoryLast10 as $item)
                        <div class="price-history-item px-4 py-3 text-sm">
                            <div class="flex items-center justify-between gap-3 flex-wrap">
                                <span class="text-zinc-600 dark:text-zinc-300 truncate">{{ $item->route_label ?: ($item->from_label && $item->to_label ? $item->from_label . ' → ' . $item->to_label : '—') }}</span>
                                <span class="font-semibold text-emerald-600 dark:text-emerald-400 shrink-0">{{ number_format($item->price, 0, ',', '.') }} ₺</span>
                            </div>
                            @if($item->km || $item->room_label || $item->service_type)
                                <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ implode(' · ', array_filter([$item->km ? $item->km . ' km' : null, $item->room_label, $item->service_type])) }}</div>
                            @endif
                        </div>
                @endforeach
            </div>
        </section>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const config = @json($config);
    const csrfToken = '{{ csrf_token() }}';
    const OSRM_BASE = 'https://router.project-osrm.org/route/v1/driving';
    const provincesApiUrl = '{{ route("api.turkey.provinces") }}';
    const districtsApiUrl = '{{ route("api.turkey.districts") }}';
    const geocodeApiUrl = '{{ route("api.geocode") }}';
    const ihaleCreateUrl = '{{ $ihaleCreateUrl }}';
    const priceHistoryApiUrl = '{{ route("api.tools.price-history") }}';
    const priceHistoryStoreUrl = '{{ route("api.tools.price-history.store") }}';

    const serviceTypeEl = document.getElementById('service_type');
    const roomTypeWrap = document.getElementById('room-type-wrap');
    const volumeWrap = document.getElementById('volume-wrap');
    const roomTypeEl = document.getElementById('room_type');
    const volumeEl = document.getElementById('volume_m3');
    const turkeyLocationWrap = document.getElementById('turkey-location-wrap');
    const internationalLocationWrap = document.getElementById('international-location-wrap');
    const fromProvince = document.getElementById('from_province');
    const toProvince = document.getElementById('to_province');
    const fromDistrict = document.getElementById('from_district');
    const toDistrict = document.getElementById('to_district');
    const fromCountry = document.getElementById('from_country');
    const toCountry = document.getElementById('to_country');
    const distanceKmManual = document.getElementById('distance_km_manual');
    const distanceEl = document.getElementById('distance_km');
    const distanceLabel = document.getElementById('distance-label');
    const fromFloorEl = document.getElementById('from_floor');
    const toFloorEl = document.getElementById('to_floor');
    const priceDisplayEl = document.getElementById('price-display');
    const priceDetailsEl = document.getElementById('price-details');
    const resultBox = document.getElementById('result-box');
    const localMessage = document.getElementById('local-message');
    const mapWrapper = document.getElementById('price-estimator-map-wrapper');

    let provinces = [];
    let priceEstimatorMap = null;
    let priceEstimatorFromMarker = null;
    let priceEstimatorToMarker = null;
    let priceEstimatorLine = null;
    let priceEstimatorRouteRequest = null;

    function isInternational() {
        return serviceTypeEl?.value === 'uluslararasi_nakliyat';
    }

    function getDistance() {
        if (isInternational() && distanceKmManual) {
            const v = parseInt(distanceKmManual.value, 10);
            return Math.max(0, isNaN(v) ? 0 : v);
        }
        return Math.max(0, Math.round(parseFloat(distanceEl?.value || 0) || 0));
    }

    function getRoomFactor() {
        if (isInternational()) {
            var vol = parseFloat(volumeEl?.value || 0) || 30;
            return Math.max(0.5, Math.min(3, vol / 40));
        }
        const opt = roomTypeEl?.options[roomTypeEl?.selectedIndex];
        return parseFloat(opt?.dataset?.factor || 1) || 1;
    }

    function getFloorSurcharge() {
        const fromFloor = parseInt(fromFloorEl?.value || 0, 10) || 0;
        const toFloor = parseInt(toFloorEl?.value || 0, 10) || 0;
        const fromElev = document.querySelector('input[name="from_elevator"]:checked')?.value === '1';
        const toElev = document.querySelector('input[name="to_elevator"]:checked')?.value === '1';
        const perNo = config.per_floor_no_elevator || 280;
        const perYes = config.per_floor_with_elevator || 40;
        let total = 0;
        total += fromFloor > 0 ? (fromElev ? fromFloor * perYes : fromFloor * perNo) : 0;
        total += toFloor > 0 ? (toElev ? toFloor * perYes : toFloor * perNo) : 0;
        return total;
    }

    function getPricePerKm(mesafe) {
        const tiers = config.distance_tiers || {};
        const sorted = Object.keys(tiers).filter(k => k !== 'default').map(Number).sort((a,b)=>a-b);
        for (let i = 0; i < sorted.length; i++) {
            if (mesafe <= sorted[i]) return tiers[sorted[i]];
        }
        return tiers.default ?? 20;
    }

    function calculate() {
        const km = getDistance();

        if (km === 0) {
            if (resultBox) resultBox.classList.add('hidden');
            if (localMessage) localMessage.classList.remove('hidden');
            return;
        }

        if (resultBox) resultBox.classList.remove('hidden');
        if (localMessage) localMessage.classList.add('hidden');

        const pricePerKm = getPricePerKm(km);
        let fiyat = km * pricePerKm;

        fiyat *= getRoomFactor();

        fiyat += getFloorSurcharge();
        fiyat = Math.round(fiyat);

        const formatted = fiyat.toLocaleString('tr-TR');
        if (priceDisplayEl) priceDisplayEl.textContent = formatted;

        var fromName = '';
        var toName = '';
        if (isInternational()) {
            fromName = (fromCountry?.options[fromCountry?.selectedIndex]?.text || '') + (document.getElementById('from_city_int')?.value ? ', ' + document.getElementById('from_city_int').value : '');
            toName = (toCountry?.options[toCountry?.selectedIndex]?.text || '') + (document.getElementById('to_city_int')?.value ? ', ' + document.getElementById('to_city_int').value : '');
        } else {
            fromName = fromDistrict?.options[fromDistrict?.selectedIndex]?.text || fromProvince?.options[fromProvince?.selectedIndex]?.text || '';
            toName = toDistrict?.options[toDistrict?.selectedIndex]?.text || toProvince?.options[toProvince?.selectedIndex]?.text || '';
        }
        const roomLabel = isInternational() ? (volumeEl?.value ? volumeEl.value + ' m³' : '') : (roomTypeEl?.options[roomTypeEl?.selectedIndex]?.text ?? '');
        const details = [fromName && toName ? fromName + ' → ' + toName : '', 'Mesafe: ' + km + ' km', roomLabel ? 'Eşya/hacim: ' + roomLabel : ''].filter(Boolean);
        if (priceDetailsEl) priceDetailsEl.innerHTML = details.map(d => '<div>' + d + '</div>').join('');

        var routeLabel = (fromName && toName) ? fromName + ' → ' + toName : '';
        savePriceHistory(fromName, toName, km, fiyat, roomLabel, routeLabel);
    }

    function savePriceHistory(fromName, toName, km, price, roomLabel, routeLabel) {
        var payload = { from: fromName || '', to: toName || '', km: km, price: price, room: roomLabel || '', route: routeLabel || '', service_type: serviceTypeEl ? serviceTypeEl.value : '' };
        fetch(priceHistoryStoreUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(payload)
        }).then(function() { loadPriceHistory(); }).catch(function() {});
    }

    function loadPriceHistory() {
        fetch(priceHistoryApiUrl, { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(res) { renderPriceHistory(res.data || []); })
            .catch(function() { renderPriceHistory([]); });
    }

    function renderPriceHistory(list) {
        var container = document.getElementById('price-history-list');
        var emptyEl = document.getElementById('price-history-empty');
        if (!container) return;
        if (!list || list.length === 0) {
            if (emptyEl) emptyEl.classList.remove('hidden');
            container.querySelectorAll('.price-history-item').forEach(function(el) { el.remove(); });
            return;
        }
        if (emptyEl) emptyEl.classList.add('hidden');
        container.querySelectorAll('.price-history-item').forEach(function(el) { el.remove(); });
        list.forEach(function(item) {
            var div = document.createElement('div');
            div.className = 'price-history-item px-4 py-3 text-sm';
            var route = item.route || (item.from && item.to ? item.from + ' → ' + item.to : '') || '—';
            var priceStr = (item.price != null) ? Number(item.price).toLocaleString('tr-TR') + ' ₺' : '';
            var detay = [];
            if (item.km) detay.push(item.km + ' km');
            if (item.room) detay.push(item.room);
            if (item.service_type) detay.push(item.service_type);
            var detayStr = detay.length ? detay.join(' · ') : '';
            div.innerHTML = '<div class="flex items-center justify-between gap-3 flex-wrap"><span class="text-zinc-600 dark:text-zinc-300 truncate">' + route + '</span><span class="font-semibold text-emerald-600 dark:text-emerald-400 shrink-0">' + priceStr + '</span></div>' + (detayStr ? '<div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">' + detayStr + '</div>' : '');
            container.appendChild(div);
        });
    }

    function toggleInputs() {
        const international = isInternational();
        if (roomTypeWrap) roomTypeWrap.classList.toggle('hidden', international);
        if (volumeWrap) volumeWrap.classList.toggle('hidden', !international);
        if (turkeyLocationWrap) turkeyLocationWrap.classList.toggle('hidden', international);
        if (internationalLocationWrap) internationalLocationWrap.classList.toggle('hidden', !international);
        if (mapWrapper && international) mapWrapper.classList.add('hidden');
        if (international && distanceKmManual) {
            distanceEl.value = Math.max(0, parseInt(distanceKmManual.value, 10) || 0);
            if (distanceLabel) distanceLabel.textContent = 'Mesafe: ' + (distanceEl.value || '0') + ' km (elle girildi)';
        }
    }

    function fillProvinces() {
        const opts = '<option value="">İl seçin</option>' + provinces.map(p =>
            '<option value="' + p.id + '" data-lat="' + (p.latitude || '') + '" data-lng="' + (p.longitude || '') + '" data-name="' + (p.name || '').replace(/"/g, '&quot;') + '">' + p.name + '</option>'
        ).join('');
        if (fromProvince) fromProvince.innerHTML = opts;
        if (toProvince) toProvince.innerHTML = opts;
    }

    function loadDistricts(provinceSelect, districtSelect) {
        const pid = provinceSelect?.value;
        if (!pid) {
            if (districtSelect) {
                districtSelect.innerHTML = '<option value="">Önce il seçin</option>';
                districtSelect.disabled = true;
            }
            return;
        }
        districtSelect.innerHTML = '<option value="">Yükleniyor...</option>';
        districtSelect.disabled = false;
        fetch(districtsApiUrl + '?province_id=' + pid)
            .then(r => r.json())
            .then(data => {
                if (data.data && data.data.length) {
                    const opts = '<option value="">İlçe seçin</option>' + data.data.map(d =>
                        '<option value="' + d.id + '">' + (d.name || '').replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</option>'
                    ).join('');
                    districtSelect.innerHTML = opts;
                } else {
                    districtSelect.innerHTML = '<option value="">İlçe yok</option>';
                }
            })
            .catch(function() {
                districtSelect.innerHTML = '<option value="">Hata</option>';
            });
    }

    function getCoordsFor(side) {
        const provinceSelect = side === 'from' ? fromProvince : toProvince;
        const districtSelect = side === 'from' ? fromDistrict : toDistrict;
        const pOpt = provinceSelect?.options[provinceSelect?.selectedIndex];
        if (!pOpt || !pOpt.value) return Promise.resolve(null);
        const pName = (pOpt.dataset.name || pOpt.text || '').trim();
        const pLat = parseFloat(pOpt.dataset.lat);
        const pLng = parseFloat(pOpt.dataset.lng);
        const dOpt = districtSelect?.options[districtSelect?.selectedIndex];
        const hasDistrict = dOpt && dOpt.value;
        const dName = hasDistrict ? (dOpt.text || '').trim() : '';
        if (hasDistrict && dName) {
            const q = encodeURIComponent(dName + ', ' + pName);
            return fetch(geocodeApiUrl + '?q=' + q).then(r => r.json()).then(data => {
                if (data.lat != null && data.lng != null) return { lat: data.lat, lng: data.lng, name: dName + '/' + pName };
                return { lat: pLat, lng: pLng, name: pName };
            }).catch(function() { return { lat: pLat, lng: pLng, name: pName }; });
        }
        if (!isNaN(pLat) && !isNaN(pLng)) return Promise.resolve({ lat: pLat, lng: pLng, name: pName });
        return Promise.resolve(null);
    }

    function updatePriceEstimatorMap() {
        if (isInternational() || !fromProvince?.value || !toProvince?.value) {
            if (mapWrapper) mapWrapper.classList.add('hidden');
            return;
        }
        Promise.all([getCoordsFor('from'), getCoordsFor('to')]).then(function([from, to]) {
            if (!from || !to || !mapWrapper) {
                if (mapWrapper) mapWrapper.classList.add('hidden');
                return;
            }
            mapWrapper.classList.remove('hidden');
            if (typeof L === 'undefined') return;
            if (!priceEstimatorMap) {
                priceEstimatorMap = L.map('price-estimator-map').setView([from.lat, from.lng], 6);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(priceEstimatorMap);
            }
            if (priceEstimatorFromMarker) priceEstimatorMap.removeLayer(priceEstimatorFromMarker);
            if (priceEstimatorToMarker) priceEstimatorMap.removeLayer(priceEstimatorToMarker);
            if (priceEstimatorLine) priceEstimatorMap.removeLayer(priceEstimatorLine);
            priceEstimatorFromMarker = L.marker([from.lat, from.lng]).addTo(priceEstimatorMap).bindPopup('<b>Nereden</b><br>' + (from.name || ''));
            priceEstimatorToMarker = L.marker([to.lat, to.lng]).addTo(priceEstimatorMap).bindPopup('<b>Nereye</b><br>' + (to.name || ''));
            const bounds = [[from.lat, from.lng], [to.lat, to.lng]];
            if (priceEstimatorRouteRequest) priceEstimatorRouteRequest.abort();
            priceEstimatorRouteRequest = new AbortController();
            const url = OSRM_BASE + '/' + from.lng + ',' + from.lat + ';' + to.lng + ',' + to.lat + '?overview=full&geometries=geojson';
            fetch(url, { signal: priceEstimatorRouteRequest.signal })
                .then(r => r.json())
                .then(data => {
                    if (data.code === 'Ok' && data.routes && data.routes[0]) {
                        const route = data.routes[0];
                        const roadKm = route.distance != null ? Math.round(route.distance / 1000) : null;
                        if (roadKm != null && distanceEl) {
                            distanceEl.value = roadKm;
                            if (distanceLabel) distanceLabel.textContent = 'Mesafe: ' + roadKm + ' km (' + (from.name || '') + ' → ' + (to.name || '') + ')';
                            calculate();
                        }
                        if (route.geometry && route.geometry.coordinates && route.geometry.coordinates.length) {
                            const lineCoords = route.geometry.coordinates.map(c => [c[1], c[0]]);
                            priceEstimatorLine = L.polyline(lineCoords, { color: '#059669', weight: 4, opacity: 0.9 }).addTo(priceEstimatorMap);
                            priceEstimatorMap.fitBounds(L.latLngBounds(lineCoords), { padding: [30, 30], maxZoom: 12 });
                            return;
                        }
                    }
                    priceEstimatorLine = L.polyline([[from.lat, from.lng], [to.lat, to.lng]], { color: '#059669', weight: 4, opacity: 0.9 }).addTo(priceEstimatorMap);
                    priceEstimatorMap.fitBounds(bounds, { padding: [30, 30], maxZoom: 12 });
                })
                .catch(function() {
                    priceEstimatorLine = L.polyline([[from.lat, from.lng], [to.lat, to.lng]], { color: '#059669', weight: 4, opacity: 0.9 }).addTo(priceEstimatorMap);
                    priceEstimatorMap.fitBounds(bounds, { padding: [30, 30], maxZoom: 12 });
                });
        });
    }

    function calcDistanceAuto() {
        if (isInternational()) {
            if (distanceKmManual) distanceEl.value = Math.max(0, parseInt(distanceKmManual.value, 10) || 0);
            toggleInputs();
            calculate();
            return;
        }
        if (!fromProvince?.value || !toProvince?.value) {
            if (distanceEl) distanceEl.value = '0';
            if (distanceLabel) distanceLabel.textContent = 'Mesafe: İl ve ilçe seçin, otomatik hesaplanacak';
            if (mapWrapper) mapWrapper.classList.add('hidden');
            calculate();
            return;
        }
        updatePriceEstimatorMap();
    }

    function onFromProvinceChange() {
        loadDistricts(fromProvince, fromDistrict);
        calcDistanceAuto();
    }

    function onToProvinceChange() {
        loadDistricts(toProvince, toDistrict);
        calcDistanceAuto();
    }

    serviceTypeEl?.addEventListener('change', function() {
        toggleInputs();
        calcDistanceAuto();
        calculate();
    });

    roomTypeEl?.addEventListener('change', calculate);
    volumeEl?.addEventListener('input', function() { toggleInputs(); calculate(); });
    volumeEl?.addEventListener('change', calculate);
    fromFloorEl?.addEventListener('change', calculate);
    toFloorEl?.addEventListener('change', calculate);

    document.querySelectorAll('input[name="from_elevator"]').forEach(function(r) {
        r.addEventListener('change', calculate);
    });
    document.querySelectorAll('input[name="to_elevator"]').forEach(function(r) {
        r.addEventListener('change', calculate);
    });

    fromProvince?.addEventListener('change', onFromProvinceChange);
    toProvince?.addEventListener('change', onToProvinceChange);
    fromDistrict?.addEventListener('change', calcDistanceAuto);
    toDistrict?.addEventListener('change', calcDistanceAuto);

    fromCountry?.addEventListener('change', function() { calcDistanceAuto(); calculate(); });
    toCountry?.addEventListener('change', function() { calcDistanceAuto(); calculate(); });
    if (distanceKmManual) {
        distanceKmManual.addEventListener('input', function() {
            if (distanceEl) distanceEl.value = Math.max(0, parseInt(this.value, 10) || 0);
            if (distanceLabel) distanceLabel.textContent = 'Mesafe: ' + (distanceEl?.value || '0') + ' km (elle girildi)';
            calculate();
        });
        distanceKmManual.addEventListener('change', calcDistanceAuto);
    }

    fetch(provincesApiUrl)
        .then(r => r.json())
        .then(data => {
            if (data.data && data.data.length) {
                provinces = data.data.filter(p => p.latitude != null && p.longitude != null);
                fillProvinces();
            }
        })
        .catch(() => {});

    toggleInputs();
    calculate();
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() { loadPriceHistory(); });
    } else {
        loadPriceHistory();
    }
})();
</script>
@endpush
