@php
    $config = $config ?? config('price_estimator');
    $showEmbedLink = $showEmbedLink ?? false;
    $ihaleCreateUrl = $ihaleCreateUrl ?? route('ihale.create');
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
<div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-lg overflow-hidden" id="price-estimator">
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

        {{-- Oda tipi (evden eve) veya Hacim (diğer) --}}
        <div id="room-type-wrap">
            <label for="room_type" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Eşya durumu (oda tipi)</label>
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
            <label for="volume_m3" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Tahmini hacim (m³)</label>
            <input type="number" id="volume_m3" min="1" max="500" step="1" value="30" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
        </div>
        <div id="esya-durumu-wrap" class="hidden">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Eşya durumu</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                @foreach($config['esya_durumu'] ?? [] as $key => $mult)
                    <label class="flex items-center gap-2 min-h-[44px] px-3 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 cursor-pointer hover:border-emerald-400/80 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/80 dark:has-[:checked]:bg-emerald-900/20">
                        <input type="radio" name="esya_durumu_alt" value="{{ $key }}" {{ $key === 'normal' ? 'checked' : '' }} class="w-4 h-4 text-emerald-500 accent-emerald-500">
                        <span class="text-sm font-medium text-zinc-800 dark:text-zinc-100">
                            @if($key === 'basit') Az eşya
                            @elseif($key === 'normal') Normal
                            @elseif($key === 'agir') Çok eşya
                            @else Özel
                            @endif
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Nereden / Nereye - İl + İlçe, mesafe otomatik hesaplanır --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label for="from_province" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nereden (ilçe)</label>
                <select id="from_province" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                    <option value="">İl seçin</option>
                </select>
                <select id="from_district" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" disabled>
                    <option value="">Önce il seçin</option>
                </select>
            </div>
            <div class="space-y-2">
                <label for="to_province" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nereye (ilçe)</label>
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
    </div>
</div>

@push('scripts')
<script>
(function() {
    const config = @json($config);
    const ROAD_FACTOR = 1.25;
    const OSRM_BASE = 'https://router.project-osrm.org/route/v1/driving';
    const provincesApiUrl = '{{ route("api.turkey.provinces") }}';
    const districtsApiUrl = '{{ route("api.turkey.districts") }}';
    const ihaleCreateUrl = '{{ $ihaleCreateUrl }}';

    const serviceTypeEl = document.getElementById('service_type');
    const roomTypeWrap = document.getElementById('room-type-wrap');
    const volumeWrap = document.getElementById('volume-wrap');
    const esyaDurumuWrap = document.getElementById('esya-durumu-wrap');
    const roomTypeEl = document.getElementById('room_type');
    const volumeEl = document.getElementById('volume_m3');
    const fromProvince = document.getElementById('from_province');
    const toProvince = document.getElementById('to_province');
    const fromDistrict = document.getElementById('from_district');
    const toDistrict = document.getElementById('to_district');
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

    function getDistance() {
        return Math.max(0, Math.round(parseFloat(distanceEl?.value || 0) || 0));
    }

    function getRoomFactor() {
        if (serviceTypeEl?.value !== 'evden_eve_nakliyat') return 1;
        const opt = roomTypeEl?.options[roomTypeEl.selectedIndex];
        return parseFloat(opt?.dataset?.factor || 1) || 1;
    }

    function getEsyaDurumuMult() {
        const r = document.querySelector('input[name="esya_durumu_alt"]:checked');
        const key = r ? r.value : 'normal';
        const map = config.esya_durumu || {};
        return map[key] ?? 1;
    }

    function getFloorSurcharge() {
        const fromFloor = parseInt(fromFloorEl?.value || 0, 10) || 0;
        const toFloor = parseInt(toFloorEl?.value || 0, 10) || 0;
        const fromElev = document.querySelector('input[name="from_elevator"]:checked')?.value === '1';
        const toElev = document.querySelector('input[name="to_elevator"]:checked')?.value === '1';
        const perNo = config.per_floor_no_elevator || 150;
        const perYes = config.per_floor_with_elevator || 30;
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

        if (serviceTypeEl?.value === 'evden_eve_nakliyat') {
            fiyat *= getRoomFactor();
        } else if (esyaDurumuWrap && !esyaDurumuWrap.classList.contains('hidden')) {
            fiyat *= getEsyaDurumuMult();
        }

        fiyat += getFloorSurcharge();
        fiyat = Math.round(fiyat);

        const formatted = fiyat.toLocaleString('tr-TR');
        if (priceDisplayEl) priceDisplayEl.textContent = formatted;

        const fromName = fromDistrict?.options[fromDistrict?.selectedIndex]?.text || fromProvince?.options[fromProvince?.selectedIndex]?.text || '';
        const toName = toDistrict?.options[toDistrict?.selectedIndex]?.text || toProvince?.options[toProvince?.selectedIndex]?.text || '';
        const roomLabel = roomTypeEl?.options[roomTypeEl?.selectedIndex]?.text ?? '';
        const details = [fromName && toName ? fromName + ' → ' + toName : '', 'Mesafe: ' + km + ' km', 'Eşya durumu: ' + roomLabel].filter(Boolean);
        if (priceDetailsEl) priceDetailsEl.innerHTML = details.map(d => '<div>' + d + '</div>').join('');
    }

    function toggleInputs() {
        const isEvdenEve = serviceTypeEl?.value === 'evden_eve_nakliyat';
        if (roomTypeWrap) roomTypeWrap.classList.toggle('hidden', !isEvdenEve);
        if (volumeWrap) volumeWrap.classList.toggle('hidden', isEvdenEve);
        if (esyaDurumuWrap) esyaDurumuWrap.classList.toggle('hidden', isEvdenEve);
    }

    function fillProvinces() {
        const opts = '<option value="">İl seçin</option>' + provinces.map(p =>
            '<option value="' + p.id + '" data-lat="' + (p.latitude || '') + '" data-lng="' + (p.longitude || '') + '" data-name="' + (p.name || '').replace(/"/g, '&quot;') + '">' + p.name + '</option>'
        ).join('');
        if (fromProvince) fromProvince.innerHTML = opts;
        if (toProvince) toProvince.innerHTML = opts;
    }

    function haversine(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2)**2 + Math.cos(lat1 * Math.PI/180) * Math.cos(lat2 * Math.PI/180) * Math.sin(dLon/2)**2;
        return 2 * R * Math.asin(Math.sqrt(a));
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

    function getFromToCoords() {
        const fromOpt = fromProvince?.options[fromProvince?.selectedIndex];
        const toOpt = toProvince?.options[toProvince?.selectedIndex];
        if (!fromOpt || !toOpt || !fromOpt.value || !toOpt.value || fromOpt.value === toOpt.value) return null;
        const lat1 = parseFloat(fromOpt.dataset.lat), lng1 = parseFloat(fromOpt.dataset.lng);
        const lat2 = parseFloat(toOpt.dataset.lat), lng2 = parseFloat(toOpt.dataset.lng);
        if (isNaN(lat1) || isNaN(lng1) || isNaN(lat2) || isNaN(lng2)) return null;
        return { from: { lat: lat1, lng: lng1, name: fromOpt.dataset.name || fromOpt.text }, to: { lat: lat2, lng: lng2, name: toOpt.dataset.name || toOpt.text } };
    }

    function updatePriceEstimatorMap() {
        const coords = getFromToCoords();
        if (!coords || !mapWrapper) {
            if (mapWrapper) mapWrapper.classList.add('hidden');
            return;
        }
        mapWrapper.classList.remove('hidden');
        if (typeof L === 'undefined') return;
        if (!priceEstimatorMap) {
            priceEstimatorMap = L.map('price-estimator-map').setView([coords.from.lat, coords.from.lng], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(priceEstimatorMap);
        }
        if (priceEstimatorFromMarker) priceEstimatorMap.removeLayer(priceEstimatorFromMarker);
        if (priceEstimatorToMarker) priceEstimatorMap.removeLayer(priceEstimatorToMarker);
        if (priceEstimatorLine) priceEstimatorMap.removeLayer(priceEstimatorLine);
        priceEstimatorFromMarker = L.marker([coords.from.lat, coords.from.lng]).addTo(priceEstimatorMap).bindPopup('<b>Nereden</b><br>' + (coords.from.name || ''));
        priceEstimatorToMarker = L.marker([coords.to.lat, coords.to.lng]).addTo(priceEstimatorMap).bindPopup('<b>Nereye</b><br>' + (coords.to.name || ''));
        const bounds = [[coords.from.lat, coords.from.lng], [coords.to.lat, coords.to.lng]];
        if (priceEstimatorRouteRequest) priceEstimatorRouteRequest.abort();
        priceEstimatorRouteRequest = new AbortController();
        const url = OSRM_BASE + '/' + coords.from.lng + ',' + coords.from.lat + ';' + coords.to.lng + ',' + coords.to.lat + '?overview=full&geometries=geojson';
        fetch(url, { signal: priceEstimatorRouteRequest.signal })
            .then(r => r.json())
            .then(data => {
                if (data.code === 'Ok' && data.routes && data.routes[0]) {
                    const route = data.routes[0];
                    const roadKm = route.distance != null ? Math.round(route.distance / 1000) : null;
                    if (roadKm != null && distanceEl) {
                        distanceEl.value = roadKm;
                        const fromName = fromProvince?.options[fromProvince?.selectedIndex]?.text || '';
                        const toName = toProvince?.options[toProvince?.selectedIndex]?.text || '';
                        if (distanceLabel) distanceLabel.textContent = 'Mesafe: ' + roadKm + ' km (' + fromName + ' → ' + toName + ')';
                        calculate();
                    }
                    if (route.geometry && route.geometry.coordinates && route.geometry.coordinates.length) {
                        const lineCoords = route.geometry.coordinates.map(c => [c[1], c[0]]);
                        priceEstimatorLine = L.polyline(lineCoords, { color: '#059669', weight: 4, opacity: 0.9 }).addTo(priceEstimatorMap);
                        priceEstimatorMap.fitBounds(L.latLngBounds(lineCoords), { padding: [30, 30], maxZoom: 12 });
                        return;
                    }
                }
                priceEstimatorLine = L.polyline([[coords.from.lat, coords.from.lng], [coords.to.lat, coords.to.lng]], { color: '#059669', weight: 4, opacity: 0.9 }).addTo(priceEstimatorMap);
                priceEstimatorMap.fitBounds(bounds, { padding: [30, 30], maxZoom: 12 });
            })
            .catch(function() {
                priceEstimatorLine = L.polyline([[coords.from.lat, coords.from.lng], [coords.to.lat, coords.to.lng]], { color: '#059669', weight: 4, opacity: 0.9 }).addTo(priceEstimatorMap);
                priceEstimatorMap.fitBounds(bounds, { padding: [30, 30], maxZoom: 12 });
            });
    }

    function calcDistanceAuto() {
        const fromOpt = fromProvince?.options[fromProvince?.selectedIndex];
        const toOpt = toProvince?.options[toProvince?.selectedIndex];
        if (!fromOpt || !toOpt || !fromOpt.value || !toOpt.value) {
            if (distanceEl) distanceEl.value = '0';
            if (distanceLabel) distanceLabel.textContent = 'Mesafe: İl ve ilçe seçin, otomatik hesaplanacak';
            if (mapWrapper) mapWrapper.classList.add('hidden');
            calculate();
            return;
        }
        const lat1 = parseFloat(fromOpt.dataset.lat), lng1 = parseFloat(fromOpt.dataset.lng);
        const lat2 = parseFloat(toOpt.dataset.lat), lng2 = parseFloat(toOpt.dataset.lng);
        if (isNaN(lat1) || isNaN(lng1) || isNaN(lat2) || isNaN(lng2)) {
            if (distanceEl) distanceEl.value = '0';
            updatePriceEstimatorMap();
            calculate();
            return;
        }
        const airKm = haversine(lat1, lng1, lat2, lng2);
        const roadKm = Math.round(airKm * ROAD_FACTOR);
        if (distanceEl) distanceEl.value = roadKm;
        const fromName = fromProvince?.options[fromProvince?.selectedIndex]?.text || '';
        const toName = toProvince?.options[toProvince?.selectedIndex]?.text || '';
        if (distanceLabel) distanceLabel.textContent = 'Mesafe: ' + roadKm + ' km (' + fromName + ' → ' + toName + ')';
        updatePriceEstimatorMap();
        calculate();
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
        calculate();
    });

    roomTypeEl?.addEventListener('change', calculate);
    volumeEl?.addEventListener('input', calculate);
    fromFloorEl?.addEventListener('change', calculate);
    toFloorEl?.addEventListener('change', calculate);

    document.querySelectorAll('input[name="esya_durumu_alt"]').forEach(function(r) {
        r.addEventListener('change', calculate);
    });
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
})();
</script>
@endpush
