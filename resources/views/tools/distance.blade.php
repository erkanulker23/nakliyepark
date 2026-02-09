@extends('layouts.app')

@section('title', $metaTitle ?? 'Mesafe Hesaplama - NakliyePark')
@section('meta_description', $metaDescription ?? 'Başlangıç ve varış ili seçerek nakliye mesafesini tahmini olarak hesaplayın. Harita üzerinde kuş uçuşu km görüntüleyin.')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@section('content')
<div class="px-4 py-6 max-w-2xl mx-auto">
    <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">Mesafe Hesaplama</h1>
    <p class="text-sm text-slate-500 mb-4">Başlangıç ve varış ili seçin; haritada görüntüleyin ve tahmini mesafeyi hesaplayın.</p>

    <div class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Başlangıç ili *</label>
                <select id="from-province" class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl">
                    <option value="">İl seçin</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Varış ili *</label>
                <select id="to-province" class="input-touch w-full border border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded-xl">
                    <option value="">İl seçin</option>
                </select>
            </div>
        </div>

        <div id="map-container" class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 hidden" style="height: 320px;">
            <div id="map" class="w-full h-full"></div>
        </div>

        <div id="result" class="card-touch bg-slate-50 dark:bg-slate-800/50 hidden">
            <p class="text-sm text-slate-500">Tahmini mesafe (kuş uçuşu)</p>
            <p class="text-2xl font-bold text-sky-600 dark:text-sky-400 mt-1"><span id="result-km">0</span> km</p>
        </div>
    </div>

    @if(!empty($toolContent))
    <section class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-600" aria-labelledby="nasil-calisir-dist">
        <h2 id="nasil-calisir-dist" class="text-base font-semibold text-slate-800 dark:text-slate-100 mb-3">Mesafe hesaplama nasıl çalışır?</h2>
        <div class="prose prose-sm prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-slate-400">
            {!! $toolContent !!}
        </div>
    </section>
    @endif
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function() {
    const fromSelect = document.getElementById('from-province');
    const toSelect = document.getElementById('to-province');
    const mapContainer = document.getElementById('map-container');
    const resultBox = document.getElementById('result');
    const resultKm = document.getElementById('result-km');

    let map = null;
    let fromMarker = null;
    let toMarker = null;
    let line = null;
    let provinces = [];

    function haversine(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2)**2 + Math.cos(lat1 * Math.PI/180) * Math.cos(lat2 * Math.PI/180) * Math.sin(dLon/2)**2;
        return 2 * R * Math.asin(Math.sqrt(a));
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
            mapContainer.classList.add('hidden');
            resultBox.classList.add('hidden');
            return;
        }

        if (!map) {
            mapContainer.classList.remove('hidden');
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
            fromMarker = L.marker([from.lat, from.lng])
                .addTo(map)
                .bindPopup('<b>Başlangıç</b><br>' + from.name);
            bounds.push([from.lat, from.lng]);
        }
        if (to) {
            toMarker = L.marker([to.lat, to.lng])
                .addTo(map)
                .bindPopup('<b>Varış</b><br>' + to.name);
            bounds.push([to.lat, to.lng]);
        }
        if (from && to) {
            line = L.polyline([[from.lat, from.lng], [to.lat, to.lng]], { color: '#0ea5e9', weight: 3 }).addTo(map);
            const km = Math.round(haversine(from.lat, from.lng, to.lat, to.lng));
            resultKm.textContent = km;
            resultBox.classList.remove('hidden');
        } else {
            resultBox.classList.add('hidden');
        }

        if (bounds.length) {
            map.fitBounds(bounds, { padding: [40, 40], maxZoom: 12 });
        }
    }

    fromSelect.addEventListener('change', () => {
        fillSelect(toSelect, fromSelect.value);
        updateMap();
    });
    toSelect.addEventListener('change', () => {
        fillSelect(fromSelect, toSelect.value);
        updateMap();
    });

    fetch('{{ route("api.turkey.provinces") }}')
        .then(r => r.json())
        .then(data => {
            if (!data.data || !data.data.length) return;
            provinces = data.data.filter(p => p.latitude != null && p.longitude != null);
            fillSelect(fromSelect);
            fillSelect(toSelect);
        })
        .catch(() => {});
})();
</script>
@endpush
@endsection
