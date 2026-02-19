@extends('layouts.app')

@section('title', $metaTitle ?? 'Mesafe Hesaplama - NakliyePark')
@section('meta_description', $metaDescription ?? 'Başlangıç ve varış ili seçerek nakliye mesafesini tahmini olarak hesaplayın. Harita üzerinde kuş uçuşu km görüntüleyin.')

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
<div class="page-container py-6 sm:py-8">
    <header class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Mesafe Hesaplama</h1>
        <p class="mt-1.5 text-sm text-zinc-500 dark:text-zinc-400">Başlangıç ve varış için il, ilçe ve isteğe bağlı mahalle seçin; karayolu mesafesi hesaplansın.</p>
        <p class="mt-3 text-zinc-600 dark:text-zinc-400 text-base max-w-3xl">Aynı il içinde farklı ilçeler arasında da mesafe hesaplanır. İl + ilçe (ve isteğe bağlı mahalle) seçerek nakliye km tahmini alın, harita üzerinde güzergahı görün.</p>
    </header>

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
            <div class="mt-4">
                <button type="button" id="btn-calc-distance" class="btn-primary gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Mesafeyi hesapla
                </button>
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
            <p class="text-sm text-zinc-600 dark:text-zinc-400" id="result-label">Tahmini karayolu mesafesi</p>
            <p class="text-2xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-1"><span id="result-km">0</span> km</p>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1" id="result-route"></p>
        </div>

        <section class="mt-8 pt-6 border-t border-zinc-200 dark:border-zinc-800" aria-labelledby="son-mesafe-baslik">
            <h2 id="son-mesafe-baslik" class="text-lg font-semibold text-zinc-900 dark:text-white mb-1">Son 10 mesafe ölçümü</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-3">Sitede yapılan son hesaplamalar; herkes aynı listeyi görür.</p>
            <div id="distance-history-list" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800/30 divide-y divide-zinc-200 dark:divide-zinc-700 overflow-hidden">
                <p class="p-4 text-sm text-zinc-500 dark:text-zinc-400" id="distance-history-empty">Henüz mesafe hesaplaması yapılmadı. Başlangıç ve varış seçip "Mesafeyi hesapla" butonuna tıklayın.</p>
            </div>
        </section>
    </div>

    <section class="mt-8 pt-8 border-t border-zinc-200 dark:border-zinc-800 max-w-3xl mx-auto" aria-labelledby="embed-baslik-dist">
        <h2 id="embed-baslik-dist" class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Bu aracı sitenize ekleyin</h2>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-3">Aşağıdaki iframe kodunu kendi sitenize yapıştırarak mesafe hesaplama aracını gösterebilirsiniz.</p>
        <div class="rounded-xl bg-zinc-900 dark:bg-zinc-950 p-4 overflow-x-auto">
            <code class="text-sm text-emerald-300 font-mono whitespace-pre break-all">&lt;iframe src="{{ $embedUrl ?? url(route('tools.distance.embed')) }}" width="100%" height="520" frameborder="0" scrolling="no" title="Mesafe Hesaplama - NakliyePark"&gt;&lt;/iframe&gt;</code>
        </div>
        <p class="text-xs text-zinc-500 dark:text-zinc-500 mt-2">İsterseniz <code class="px-1.5 py-0.5 rounded bg-zinc-200 dark:bg-zinc-700 text-xs">width</code> ve <code class="px-1.5 py-0.5 rounded bg-zinc-200 dark:bg-zinc-700 text-xs">height</code> değerlerini ihtiyacınıza göre değiştirin.</p>
    </section>

    <section class="mt-8 pt-6 border-t border-zinc-200 dark:border-zinc-800" aria-labelledby="nasil-calisir-dist">
        <h2 id="nasil-calisir-dist" class="text-lg font-semibold text-zinc-900 dark:text-white mb-3">Mesafe hesaplama nasıl çalışır?</h2>
        <div class="prose prose-sm prose-zinc dark:prose-invert max-w-none text-zinc-600 dark:text-zinc-400">
            @if(!empty($toolContent))
                {!! $toolContent !!}
            @else
                <p>Mesafe hesaplama aracı, başlangıç ve varış için seçtiğiniz il, ilçe ve isteğe bağlı mahalle bilgisiyle tahmini karayolu mesafesini hesaplar ve harita üzerinde gösterir.</p>
                <p><strong>Kullanım:</strong> Başlangıç ve varış için önce ili, sonra ilçeyi seçin. İsterseniz mahalle de seçerek daha hassas mesafe alabilirsiniz. Aynı il içinde farklı ilçeler (örn. Adana Çukurova → Adana Seyhan) seçildiğinde de ilçeler arası km hesaplanır. Sonuç kutusunda tahmini karayolu mesafesi (km) görüntülenir.</p>
            @endif
        </div>
    </section>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function() {
    const csrfToken = '{{ csrf_token() }}';
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
    const resultLabel = document.getElementById('result-label');
    const resultRoute = document.getElementById('result-route');

    const OSRM_BASE = 'https://router.project-osrm.org/route/v1/driving';
    let map = null;
    let fromMarker = null;
    let toMarker = null;
    let line = null;
    let routeRequest = null;
    let provinces = [];
    let fromDistrictsData = [];
    let toDistrictsData = [];

    function fillProvinces() {
        const fromVal = fromProvince?.value || '';
        const toVal = toProvince?.value || '';
        const opts = (p) => '<option value="' + p.id + '" data-lat="' + (p.latitude || '') + '" data-lng="' + (p.longitude || '') + '" data-name="' + (p.name || '').replace(/"/g, '&quot;') + '">' + (p.name || '') + '</option>';
        if (fromProvince) {
            fromProvince.innerHTML = '<option value="">İl seçin</option>' + provinces.map(opts).join('');
            if (fromVal) fromProvince.value = fromVal;
        }
        if (toProvince) {
            toProvince.innerHTML = '<option value="">İl seçin</option>' + provinces.map(opts).join('');
            if (toVal) toProvince.value = toVal;
        }
    }

    function loadDistricts(provinceSelect, districtSelect, neighborhoodSelect, storeKey) {
        const pid = provinceSelect?.value;
        if (!pid) {
            if (districtSelect) { districtSelect.innerHTML = '<option value="">Önce il seçin</option>'; districtSelect.disabled = true; }
            if (neighborhoodSelect) { neighborhoodSelect.innerHTML = '<option value="">İlçe seçince mahalle (opsiyonel)</option>'; neighborhoodSelect.disabled = true; }
            return Promise.resolve();
        }
        districtSelect.innerHTML = '<option value="">Yükleniyor...</option>';
        districtSelect.disabled = false;
        return fetch(districtsApiUrl + '?province_id=' + pid).then(r => r.json()).then(data => {
            if (!data.data || !data.data.length) {
                districtSelect.innerHTML = '<option value="">İlçe yok</option>';
                if (neighborhoodSelect) { neighborhoodSelect.innerHTML = '<option value="">—</option>'; neighborhoodSelect.disabled = true; }
                if (storeKey === 'from') fromDistrictsData = []; else toDistrictsData = [];
                return;
            }
            if (storeKey === 'from') fromDistrictsData = data.data; else toDistrictsData = data.data;
            districtSelect.innerHTML = '<option value="">İlçe seçin</option>' + data.data.map(d =>
                '<option value="' + d.id + '" data-name="' + (d.name || '').replace(/"/g, '&quot;') + '">' + (d.name || '') + '</option>'
            ).join('');
            if (neighborhoodSelect) { neighborhoodSelect.innerHTML = '<option value="">Mahalle (opsiyonel)</option>'; neighborhoodSelect.disabled = true; }
        }).catch(function() {
            districtSelect.innerHTML = '<option value="">Hata</option>';
        });
    }

    function fillNeighborhoods(districtSelect, neighborhoodSelect, districtsData) {
        const did = districtSelect?.value;
        if (!did || !districtsData.length) {
            neighborhoodSelect.innerHTML = '<option value="">İlçe seçince mahalle (opsiyonel)</option>';
            neighborhoodSelect.disabled = true;
            return;
        }
        const district = districtsData.find(d => String(d.id) === String(did));
        const neighborhoods = (district && district.neighborhoods) ? district.neighborhoods : [];
        neighborhoodSelect.disabled = false;
        neighborhoodSelect.innerHTML = '<option value="">Mahalle (opsiyonel)</option>' + neighborhoods.map(n =>
            '<option value="' + (n.id || '') + '" data-name="' + (n.name || '').replace(/"/g, '&quot;') + '">' + (n.name || '') + '</option>'
        ).join('');
    }

    function getLocationName(provinceSelect, districtSelect, neighborhoodSelect) {
        const pOpt = provinceSelect?.options[provinceSelect.selectedIndex];
        const dOpt = districtSelect?.options[districtSelect?.selectedIndex];
        const nOpt = neighborhoodSelect?.options[neighborhoodSelect?.selectedIndex];
        const pName = pOpt?.text || '';
        const dName = (dOpt && dOpt.value) ? dOpt.text : '';
        const nName = (nOpt && nOpt.value) ? nOpt.text : '';
        if (nName) return nName + ', ' + dName + '/' + pName;
        if (dName) return dName + '/' + pName;
        return pName;
    }

    function getCoordsFor(side) {
        const provinceSelect = side === 'from' ? fromProvince : toProvince;
        const districtSelect = side === 'from' ? fromDistrict : toDistrict;
        const neighborhoodSelect = side === 'from' ? fromNeighborhood : toNeighborhood;
        const pOpt = provinceSelect?.options[provinceSelect?.selectedIndex];
        if (!pOpt || !pOpt.value) return Promise.resolve(null);
        const pName = pOpt.text || '';
        const pLat = parseFloat(pOpt.dataset.lat);
        const pLng = parseFloat(pOpt.dataset.lng);
        const dOpt = districtSelect?.options[districtSelect?.selectedIndex];
        const nOpt = neighborhoodSelect?.options[neighborhoodSelect?.selectedIndex];
        const hasDistrict = dOpt && dOpt.value;
        const hasNeighborhood = nOpt && nOpt.value;
        const dName = hasDistrict ? dOpt.text : '';
        const nName = hasNeighborhood ? nOpt.text : '';

        if (hasNeighborhood && hasDistrict) {
            const q = encodeURIComponent(nName + ', ' + dName + ', ' + pName);
            return fetch(geocodeApiUrl + '?q=' + q).then(r => r.json()).then(data => {
                if (data.lat != null && data.lng != null) return { lat: data.lat, lng: data.lng, name: getLocationName(provinceSelect, districtSelect, neighborhoodSelect) };
                return { lat: pLat, lng: pLng, name: pName };
            }).catch(() => ({ lat: pLat, lng: pLng, name: pName }));
        }
        if (hasDistrict) {
            const q = encodeURIComponent(dName + ', ' + pName);
            return fetch(geocodeApiUrl + '?q=' + q).then(r => r.json()).then(data => {
                if (data.lat != null && data.lng != null) return { lat: data.lat, lng: data.lng, name: dName + '/' + pName };
                return { lat: pLat, lng: pLng, name: pName };
            }).catch(() => ({ lat: pLat, lng: pLng, name: pName }));
        }
        if (!isNaN(pLat) && !isNaN(pLng)) return Promise.resolve({ lat: pLat, lng: pLng, name: pName });
        return Promise.resolve(null);
    }

    const distanceHistoryApiUrl = '{{ route("api.tools.distance-history") }}';
    const distanceHistoryStoreUrl = '{{ route("api.tools.distance-history.store") }}';

    function saveDistanceHistory(fromName, toName, km) {
        const route = fromName + ' → ' + toName;
        fetch(distanceHistoryStoreUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ from: fromName, to: toName, km: km, route: route })
        }).then(function() { loadDistanceHistory(); }).catch(function() {});
    }

    function loadDistanceHistory() {
        fetch(distanceHistoryApiUrl, { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(res) { renderDistanceHistory(res.data || []); })
            .catch(function() { renderDistanceHistory([]); });
    }

    function renderDistanceHistory(list) {
        const container = document.getElementById('distance-history-list');
        const emptyEl = document.getElementById('distance-history-empty');
        if (!container) return;
        if (!list || list.length === 0) {
            if (emptyEl) emptyEl.classList.remove('hidden');
            container.querySelectorAll('.distance-history-item').forEach(function(el) { el.remove(); });
            return;
        }
        if (emptyEl) emptyEl.classList.add('hidden');
        container.querySelectorAll('.distance-history-item').forEach(function(el) { el.remove(); });
        list.forEach(function(item) {
            const div = document.createElement('div');
            div.className = 'distance-history-item px-4 py-3 flex items-center justify-between gap-3 text-sm';
            div.innerHTML = '<span class="text-zinc-600 dark:text-zinc-300 truncate">' + (item.route || item.from + ' → ' + item.to) + '</span><span class="font-semibold text-emerald-600 dark:text-emerald-400 shrink-0">' + (item.km || 0) + ' km</span>';
            container.appendChild(div);
        });
    }

    function fetchRoadRoute(from, to, onSuccess, onFallback) {
        const url = OSRM_BASE + '/' + from.lng + ',' + from.lat + ';' + to.lng + ',' + to.lat + '?overview=full&geometries=geojson';
        if (routeRequest) routeRequest.abort();
        routeRequest = new AbortController();
        fetch(url, { signal: routeRequest.signal })
            .then(r => r.json())
            .then(data => {
                if (data.code === 'Ok' && data.routes && data.routes[0]) {
                    const route = data.routes[0];
                    const roadKm = route.distance != null ? Math.round(route.distance / 1000) : null;
                    if (roadKm != null && resultKm) resultKm.textContent = roadKm;
                    if (roadKm != null) saveDistanceHistory(from.name, to.name, roadKm);
                    if (route.geometry && route.geometry.coordinates && route.geometry.coordinates.length) {
                        const coords = route.geometry.coordinates.map(c => [c[1], c[0]]);
                        onSuccess(coords);
                    } else if (onFallback) onFallback();
                } else if (onFallback) onFallback();
            })
            .catch(() => { if (onFallback) onFallback(); });
    }

    function updateMapAndResult(from, to) {
        if (!from && !to) {
            mapWrapper.classList.add('hidden');
            resultBox.classList.add('hidden');
            return;
        }
        mapWrapper.classList.remove('hidden');
        if (!map) {
            const center = from || to;
            map = L.map('map').setView([center.lat, center.lng], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
        }
        if (fromMarker) map.removeLayer(fromMarker);
        if (toMarker) map.removeLayer(toMarker);
        if (line) map.removeLayer(line);
        fromMarker = null;
        toMarker = null;
        line = null;
        const bounds = [];
        if (from) {
            fromMarker = L.marker([from.lat, from.lng], { className: 'distance-marker-start' }).addTo(map).bindPopup('<b>Başlangıç</b><br>' + from.name);
            bounds.push([from.lat, from.lng]);
        }
        if (to) {
            toMarker = L.marker([to.lat, to.lng], { className: 'distance-marker-end' }).addTo(map).bindPopup('<b>Varış</b><br>' + to.name);
            bounds.push([to.lat, to.lng]);
        }
        if (from && to) {
            resultBox.classList.remove('hidden');
            if (resultLabel) resultLabel.textContent = 'Tahmini karayolu mesafesi';
            if (resultRoute) resultRoute.textContent = from.name + ' → ' + to.name;
            fetchRoadRoute(from, to,
                function(coords) {
                    if (line) map.removeLayer(line);
                    line = L.polyline(coords, { color: '#059669', weight: 4, opacity: 0.9 }).addTo(map);
                    map.fitBounds(L.latLngBounds(coords), { padding: [40, 40], maxZoom: 12 });
                },
                function() {
                    if (line) map.removeLayer(line);
                    var fallbackKm = Math.round(Math.sqrt(Math.pow((to.lat - from.lat) * 111, 2) + Math.pow((to.lng - from.lng) * 85, 2)));
                    if (resultKm) resultKm.textContent = fallbackKm;
                    saveDistanceHistory(from.name, to.name, fallbackKm);
                    line = L.polyline([[from.lat, from.lng], [to.lat, to.lng]], { color: '#059669', weight: 4, opacity: 0.9 }).addTo(map);
                    map.fitBounds(bounds, { padding: [40, 40], maxZoom: 12 });
                }
            );
        } else {
            resultBox.classList.add('hidden');
            if (bounds.length) map.fitBounds(bounds, { padding: [40, 40], maxZoom: 12 });
        }
    }

    function runUpdate() {
        if (!fromProvince?.value || !toProvince?.value) {
            mapWrapper.classList.add('hidden');
            resultBox.classList.add('hidden');
            return;
        }
        Promise.all([getCoordsFor('from'), getCoordsFor('to')]).then(function([from, to]) {
            updateMapAndResult(from, to);
        });
    }

    fromProvince?.addEventListener('change', function() {
        loadDistricts(fromProvince, fromDistrict, fromNeighborhood, 'from');
    });
    toProvince?.addEventListener('change', function() {
        loadDistricts(toProvince, toDistrict, toNeighborhood, 'to');
    });
    fromDistrict?.addEventListener('change', function() {
        fillNeighborhoods(fromDistrict, fromNeighborhood, fromDistrictsData);
    });
    toDistrict?.addEventListener('change', function() {
        fillNeighborhoods(toDistrict, toNeighborhood, toDistrictsData);
    });

    document.getElementById('btn-calc-distance')?.addEventListener('click', runUpdate);

    loadDistanceHistory();

    fetch(provincesApiUrl)
        .then(r => r.json())
        .then(data => {
            if (!data.data || !data.data.length) return;
            provinces = data.data.filter(p => p.latitude != null && p.longitude != null);
            fillProvinces();
        })
        .catch(() => {});
})();
</script>
@endpush
@endsection
