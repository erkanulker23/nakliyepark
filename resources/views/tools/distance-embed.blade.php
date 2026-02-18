@extends('layouts.embed')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<style>
#map-container { border-radius: 1rem; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.08); }
#map-container .leaflet-control-zoom { border: none !important; }
#map-container .leaflet-control-zoom a { width: 36px; height: 36px; line-height: 36px; font-size: 18px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.15); }
.distance-marker-start .leaflet-marker-icon { border-radius: 50%; box-shadow: 0 3px 12px rgba(220,38,38,.4); }
.distance-marker-end .leaflet-marker-icon { border-radius: 50%; box-shadow: 0 3px 12px rgba(5,150,105,.4); }
#map-legend { background: rgba(255,255,255,.95); backdrop-filter: blur(8px); border-radius: 12px; padding: 10px 14px; box-shadow: 0 2px 12px rgba(0,0,0,.1); }
.dark #map-legend { background: rgba(24,24,27,.95); }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="card rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm p-5 sm:p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Başlangıç *</label>
                <select id="from-province" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                    <option value="">İl seçin</option>
                </select>
                <select id="from-district" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" disabled>
                    <option value="">Önce il seçin</option>
                </select>
                <select id="from-neighborhood" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" disabled>
                    <option value="">İlçe seçince mahalle (opsiyonel)</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Varış *</label>
                <select id="to-province" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                    <option value="">İl seçin</option>
                </select>
                <select id="to-district" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" disabled>
                    <option value="">Önce il seçin</option>
                </select>
                <select id="to-neighborhood" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl" disabled>
                    <option value="">İlçe seçince mahalle (opsiyonel)</option>
                </select>
            </div>
        </div>
    </div>

    <div id="map-wrapper" class="relative rounded-2xl overflow-hidden border border-zinc-200/80 dark:border-zinc-800 bg-zinc-100 dark:bg-zinc-800/50 hidden shadow-lg">
        <div id="map-container" class="w-full" style="height: 380px;">
            <div id="map" class="w-full h-full"></div>
        </div>
        <div id="map-legend" class="absolute bottom-4 left-4 right-4 sm:left-auto sm:right-4 sm:w-auto flex items-center justify-center gap-4 text-sm">
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500"></span> Başlangıç</span>
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Varış</span>
        </div>
    </div>

    <div id="result" class="rounded-2xl border border-emerald-200/80 dark:border-emerald-800/80 bg-emerald-50/80 dark:bg-emerald-950/30 p-5 sm:p-6 hidden">
        <p class="text-sm text-zinc-600 dark:text-zinc-400">Tahmini karayolu mesafesi</p>
        <p class="text-2xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-1"><span id="result-km">0</span> km</p>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1" id="result-route"></p>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function() {
    const provincesApiUrl = '{{ route("api.turkey.provinces") }}';
    const districtsApiUrl = '{{ route("api.turkey.districts") }}';
    const geocodeApiUrl = '{{ route("api.geocode") }}';
    const fromProvince = document.getElementById('from-province');
    const fromDistrict = document.getElementById('from-district');
    const fromNeighborhood = document.getElementById('from-neighborhood');
    const toProvince = document.getElementById('to-province');
    const toDistrict = document.getElementById('to-district');
    const toNeighborhood = document.getElementById('to-neighborhood');
    const mapWrapper = document.getElementById('map-wrapper');
    const resultBox = document.getElementById('result');
    const resultKm = document.getElementById('result-km');
    const resultRoute = document.getElementById('result-route');
    const OSRM_BASE = 'https://router.project-osrm.org/route/v1/driving';
    let map = null, fromMarker = null, toMarker = null, line = null, routeRequest = null;
    let provinces = [], fromDistrictsData = [], toDistrictsData = [];

    function fillProvinces() {
        const fromVal = fromProvince?.value || '', toVal = toProvince?.value || '';
        const opts = (p) => '<option value="' + p.id + '" data-lat="' + (p.latitude || '') + '" data-lng="' + (p.longitude || '') + '" data-name="' + (p.name || '').replace(/"/g, '&quot;') + '">' + (p.name || '') + '</option>';
        if (fromProvince) { fromProvince.innerHTML = '<option value="">İl seçin</option>' + provinces.map(opts).join(''); if (fromVal) fromProvince.value = fromVal; }
        if (toProvince) { toProvince.innerHTML = '<option value="">İl seçin</option>' + provinces.map(opts).join(''); if (toVal) toProvince.value = toVal; }
    }
    function loadDistricts(provinceSelect, districtSelect, neighborhoodSelect, storeKey) {
        const pid = provinceSelect?.value;
        if (!pid) {
            if (districtSelect) { districtSelect.innerHTML = '<option value="">Önce il seçin</option>'; districtSelect.disabled = true; }
            if (neighborhoodSelect) { neighborhoodSelect.innerHTML = '<option value="">İlçe seçince mahalle (opsiyonel)</option>'; neighborhoodSelect.disabled = true; }
            return Promise.resolve();
        }
        districtSelect.innerHTML = '<option value="">Yükleniyor...</option>'; districtSelect.disabled = false;
        return fetch(districtsApiUrl + '?province_id=' + pid).then(r => r.json()).then(data => {
            if (!data.data || !data.data.length) {
                districtSelect.innerHTML = '<option value="">İlçe yok</option>';
                if (neighborhoodSelect) { neighborhoodSelect.innerHTML = '<option value="">—</option>'; neighborhoodSelect.disabled = true; }
                if (storeKey === 'from') fromDistrictsData = []; else toDistrictsData = [];
                return;
            }
            if (storeKey === 'from') fromDistrictsData = data.data; else toDistrictsData = data.data;
            districtSelect.innerHTML = '<option value="">İlçe seçin</option>' + data.data.map(d => '<option value="' + d.id + '" data-name="' + (d.name || '').replace(/"/g, '&quot;') + '">' + (d.name || '') + '</option>').join('');
            if (neighborhoodSelect) { neighborhoodSelect.innerHTML = '<option value="">Mahalle (opsiyonel)</option>'; neighborhoodSelect.disabled = true; }
        }).catch(function() { districtSelect.innerHTML = '<option value="">Hata</option>'; });
    }
    function fillNeighborhoods(districtSelect, neighborhoodSelect, districtsData) {
        const did = districtSelect?.value;
        if (!did || !districtsData.length) { neighborhoodSelect.innerHTML = '<option value="">İlçe seçince mahalle (opsiyonel)</option>'; neighborhoodSelect.disabled = true; return; }
        const district = districtsData.find(d => String(d.id) === String(did));
        const neighborhoods = (district && district.neighborhoods) ? district.neighborhoods : [];
        neighborhoodSelect.disabled = false;
        neighborhoodSelect.innerHTML = '<option value="">Mahalle (opsiyonel)</option>' + neighborhoods.map(n => '<option value="' + (n.id || '') + '" data-name="' + (n.name || '').replace(/"/g, '&quot;') + '">' + (n.name || '') + '</option>').join('');
    }
    function getLocationName(provinceSelect, districtSelect, neighborhoodSelect) {
        const pOpt = provinceSelect?.options[provinceSelect.selectedIndex], dOpt = districtSelect?.options[districtSelect?.selectedIndex], nOpt = neighborhoodSelect?.options[neighborhoodSelect?.selectedIndex];
        const pName = pOpt?.text || '', dName = (dOpt && dOpt.value) ? dOpt.text : '', nName = (nOpt && nOpt.value) ? nOpt.text : '';
        if (nName) return nName + ', ' + dName + '/' + pName; if (dName) return dName + '/' + pName; return pName;
    }
    function getCoordsFor(side) {
        const provinceSelect = side === 'from' ? fromProvince : toProvince, districtSelect = side === 'from' ? fromDistrict : toDistrict, neighborhoodSelect = side === 'from' ? fromNeighborhood : toNeighborhood;
        const pOpt = provinceSelect?.options[provinceSelect?.selectedIndex];
        if (!pOpt || !pOpt.value) return Promise.resolve(null);
        const pName = pOpt.text || '', pLat = parseFloat(pOpt.dataset.lat), pLng = parseFloat(pOpt.dataset.lng);
        const dOpt = districtSelect?.options[districtSelect?.selectedIndex], nOpt = neighborhoodSelect?.options[neighborhoodSelect?.selectedIndex];
        const hasDistrict = dOpt && dOpt.value, hasNeighborhood = nOpt && nOpt.value, dName = hasDistrict ? dOpt.text : '', nName = hasNeighborhood ? nOpt.text : '';
        if (hasNeighborhood && hasDistrict) {
            const q = encodeURIComponent(nName + ', ' + dName + ', ' + pName);
            return fetch(geocodeApiUrl + '?q=' + q).then(r => r.json()).then(data => (data.lat != null && data.lng != null) ? { lat: data.lat, lng: data.lng, name: getLocationName(provinceSelect, districtSelect, neighborhoodSelect) } : { lat: pLat, lng: pLng, name: pName }).catch(() => ({ lat: pLat, lng: pLng, name: pName }));
        }
        if (hasDistrict) {
            const q = encodeURIComponent(dName + ', ' + pName);
            return fetch(geocodeApiUrl + '?q=' + q).then(r => r.json()).then(data => (data.lat != null && data.lng != null) ? { lat: data.lat, lng: data.lng, name: dName + '/' + pName } : { lat: pLat, lng: pLng, name: pName }).catch(() => ({ lat: pLat, lng: pLng, name: pName }));
        }
        if (!isNaN(pLat) && !isNaN(pLng)) return Promise.resolve({ lat: pLat, lng: pLng, name: pName });
        return Promise.resolve(null);
    }
    function fetchRoadRoute(from, to, onSuccess, onFallback) {
        const url = OSRM_BASE + '/' + from.lng + ',' + from.lat + ';' + to.lng + ',' + to.lat + '?overview=full&geometries=geojson';
        if (routeRequest) routeRequest.abort();
        routeRequest = new AbortController();
        fetch(url, { signal: routeRequest.signal }).then(r => r.json()).then(data => {
            if (data.code === 'Ok' && data.routes && data.routes[0]) {
                const route = data.routes[0];
                if (route.distance != null && resultKm) resultKm.textContent = Math.round(route.distance / 1000);
                if (route.geometry && route.geometry.coordinates && route.geometry.coordinates.length) onSuccess(route.geometry.coordinates.map(c => [c[1], c[0]]));
                else if (onFallback) onFallback();
            } else if (onFallback) onFallback();
        }).catch(() => { if (onFallback) onFallback(); });
    }
    function updateMapAndResult(from, to) {
        if (!from && !to) { mapWrapper.classList.add('hidden'); resultBox.classList.add('hidden'); return; }
        mapWrapper.classList.remove('hidden');
        if (!map) {
            const center = from || to;
            map = L.map('map').setView([center.lat, center.lng], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
        }
        if (fromMarker) map.removeLayer(fromMarker); if (toMarker) map.removeLayer(toMarker); if (line) map.removeLayer(line);
        fromMarker = null; toMarker = null; line = null;
        const bounds = [];
        if (from) { fromMarker = L.marker([from.lat, from.lng], { className: 'distance-marker-start' }).addTo(map).bindPopup('<b>Başlangıç</b><br>' + from.name); bounds.push([from.lat, from.lng]); }
        if (to) { toMarker = L.marker([to.lat, to.lng], { className: 'distance-marker-end' }).addTo(map).bindPopup('<b>Varış</b><br>' + to.name); bounds.push([to.lat, to.lng]); }
        if (from && to) {
            resultBox.classList.remove('hidden');
            if (resultRoute) resultRoute.textContent = from.name + ' → ' + to.name;
            fetchRoadRoute(from, to, function(coords) {
                if (line) map.removeLayer(line);
                line = L.polyline(coords, { color: '#059669', weight: 4, opacity: 0.9 }).addTo(map);
                map.fitBounds(L.latLngBounds(coords), { padding: [40, 40], maxZoom: 12 });
            }, function() {
                if (line) map.removeLayer(line);
                line = L.polyline([[from.lat, from.lng], [to.lat, to.lng]], { color: '#059669', weight: 4, opacity: 0.9 }).addTo(map);
                if (resultKm) resultKm.textContent = Math.round(Math.sqrt(Math.pow((to.lat - from.lat) * 111, 2) + Math.pow((to.lng - from.lng) * 85, 2)));
                map.fitBounds(bounds, { padding: [40, 40], maxZoom: 12 });
            });
        } else { resultBox.classList.add('hidden'); if (bounds.length) map.fitBounds(bounds, { padding: [40, 40], maxZoom: 12 }); }
    }
    function runUpdate() {
        if (!fromProvince?.value || !toProvince?.value) { mapWrapper.classList.add('hidden'); resultBox.classList.add('hidden'); return; }
        Promise.all([getCoordsFor('from'), getCoordsFor('to')]).then(function([from, to]) { updateMapAndResult(from, to); });
    }
    fromProvince?.addEventListener('change', function() { loadDistricts(fromProvince, fromDistrict, fromNeighborhood, 'from').then(runUpdate); });
    toProvince?.addEventListener('change', function() { loadDistricts(toProvince, toDistrict, toNeighborhood, 'to').then(runUpdate); });
    fromDistrict?.addEventListener('change', function() { fillNeighborhoods(fromDistrict, fromNeighborhood, fromDistrictsData); runUpdate(); });
    toDistrict?.addEventListener('change', function() { fillNeighborhoods(toDistrict, toNeighborhood, toDistrictsData); runUpdate(); });
    fromNeighborhood?.addEventListener('change', runUpdate);
    toNeighborhood?.addEventListener('change', runUpdate);
    fetch(provincesApiUrl).then(r => r.json()).then(data => {
        if (!data.data || !data.data.length) return;
        provinces = data.data.filter(p => p.latitude != null && p.longitude != null);
        fillProvinces();
    }).catch(() => {});
})();
</script>
@endpush
@endsection
