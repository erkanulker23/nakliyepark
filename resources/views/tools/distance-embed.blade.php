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
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Başlangıç ili *</label>
                <select id="from-province" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                    <option value="">İl seçin</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Varış ili *</label>
                <select id="to-province" class="input-touch w-full border border-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 rounded-xl">
                    <option value="">İl seçin</option>
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
        <p class="text-sm text-zinc-600 dark:text-zinc-400">Tahmini mesafe (kuş uçuşu)</p>
        <p class="text-2xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-1"><span id="result-km">0</span> km</p>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function() {
    const provincesApiUrl = '{{ route("api.turkey.provinces") }}';
    const fromSelect = document.getElementById('from-province');
    const toSelect = document.getElementById('to-province');
    const mapWrapper = document.getElementById('map-wrapper');
    const resultBox = document.getElementById('result');
    const resultKm = document.getElementById('result-km');

    const OSRM_BASE = 'https://router.project-osrm.org/route/v1/driving';
    let map = null;
    let fromMarker = null;
    let toMarker = null;
    let line = null;
    let routeRequest = null;
    let provinces = [];

    function haversine(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2)**2 + Math.cos(lat1 * Math.PI/180) * Math.cos(lat2 * Math.PI/180) * Math.sin(dLon/2)**2;
        return 2 * R * Math.asin(Math.sqrt(a));
    }

    function fetchRoadRoute(from, to, onSuccess, onFallback) {
        const url = OSRM_BASE + '/' + from.lng + ',' + from.lat + ';' + to.lng + ',' + to.lat + '?overview=full&geometries=geojson';
        if (routeRequest) routeRequest.abort();
        routeRequest = new AbortController();
        fetch(url, { signal: routeRequest.signal })
            .then(r => r.json())
            .then(data => {
                if (data.code === 'Ok' && data.routes && data.routes[0] && data.routes[0].geometry && data.routes[0].geometry.coordinates) {
                    const coords = data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
                    onSuccess(coords);
                } else {
                    if (onFallback) onFallback();
                }
            })
            .catch(() => { if (onFallback) onFallback(); });
    }

    function fillSelect(select, excludeId) {
        const current = select.value;
        select.innerHTML = '<option value="">İl seçin</option>';
        provinces.forEach(p => {
            if (p.id == excludeId) return;
            const opt = document.createElement('option');
            opt.value = p.id;
            opt.dataset.lat = p.latitude;
            opt.dataset.lng = p.longitude;
            opt.dataset.name = p.name;
            opt.textContent = p.name;
            if (String(p.id) === String(current)) opt.selected = true;
            select.appendChild(opt);
        });
    }

    function getSelectedCoords(select) {
        const opt = select.options[select.selectedIndex];
        if (!opt || !opt.value) return null;
        const lat = parseFloat(opt.dataset.lat);
        const lng = parseFloat(opt.dataset.lng);
        if (isNaN(lat) || isNaN(lng)) return null;
        return { lat, lng, name: opt.dataset.name };
    }

    function updateMap() {
        const from = getSelectedCoords(fromSelect);
        const to = getSelectedCoords(toSelect);

        if (!from && !to) {
            mapWrapper.classList.add('hidden');
            resultBox.classList.add('hidden');
            return;
        }

        if (!map) {
            mapWrapper.classList.remove('hidden');
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
            fromMarker = L.marker([from.lat, from.lng], { className: 'distance-marker-start' })
                .addTo(map).bindPopup('<b>Başlangıç</b><br>' + from.name);
            bounds.push([from.lat, from.lng]);
        }
        if (to) {
            toMarker = L.marker([to.lat, to.lng], { className: 'distance-marker-end' })
                .addTo(map).bindPopup('<b>Varış</b><br>' + to.name);
            bounds.push([to.lat, to.lng]);
        }
        if (from && to) {
            const airKm = Math.round(haversine(from.lat, from.lng, to.lat, to.lng));
            resultKm.textContent = airKm;
            resultBox.classList.remove('hidden');
            fetchRoadRoute(from, to,
                function(coords) {
                    if (line) map.removeLayer(line);
                    line = L.polyline(coords, { color: '#059669', weight: 4, opacity: 0.9 }).addTo(map);
                    map.fitBounds(L.latLngBounds(coords), { padding: [40, 40], maxZoom: 12 });
                },
                function() {
                    if (line) map.removeLayer(line);
                    line = L.polyline([[from.lat, from.lng], [to.lat, to.lng]], { color: '#059669', weight: 4, opacity: 0.9 }).addTo(map);
                }
            );
        } else {
            resultBox.classList.add('hidden');
        }
        if (bounds.length) map.fitBounds(bounds, { padding: [40, 40], maxZoom: 12 });
    }

    fromSelect.addEventListener('change', () => { fillSelect(toSelect, fromSelect.value); updateMap(); });
    toSelect.addEventListener('change', () => { fillSelect(fromSelect, toSelect.value); updateMap(); });

    fetch(provincesApiUrl)
        .then(r => r.json())
        .then(data => {
            if (data.data && data.data.length) {
                provinces = data.data.filter(p => p.latitude != null && p.longitude != null);
                fillSelect(fromSelect);
                fillSelect(toSelect);
            }
        })
        .catch(() => {});
})();
</script>
@endpush
@endsection
