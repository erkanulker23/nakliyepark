@extends('layouts.app')

@section('title', 'Haritadaki Nakliyeciler - NakliyePark')
@section('meta_description', 'Konum paylaşan nakliye firmalarını haritada görün. İl veya ilçe ile filtreleyin, firmaya tıklayınca haritada konumu açılsın.')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<style>
#firmalar-map-wrap { border-radius: 1rem; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,.08), 0 0 0 1px rgba(0,0,0,.04); }
#firmalar-map { background: #f4f4f5; min-height: 420px; }
.dark #firmalar-map { background: #27272a; }
#firmalar-map .leaflet-control-zoom { border: none !important; }
#firmalar-map .leaflet-control-zoom a { width: 36px !important; height: 36px !important; line-height: 36px !important; background: #fff !important; color: #374151 !important; border-radius: 10px !important; margin: 6px !important; box-shadow: 0 1px 3px rgba(0,0,0,.1) !important; }
#firmalar-map .leaflet-control-zoom a:hover { background: #059669 !important; color: #fff !important; }
.dark #firmalar-map .leaflet-control-zoom a { background: #27272a !important; color: #a1a1aa !important; }
.dark #firmalar-map .leaflet-control-zoom a:hover { background: #059669 !important; color: #fff !important; }
#firmalar-map .leaflet-control-attribution { font-size: 10px; opacity: .8; }
.firma-map-marker { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50% 50% 50% 0; background: linear-gradient(135deg, #059669 0%, #047857 100%); transform: rotate(-45deg); box-shadow: 0 2px 10px rgba(5,150,105,.4); border: 2px solid #fff; }
.firma-map-marker-inner { transform: rotate(45deg); color: #fff; font-size: 16px; }
#firmalar-map .leaflet-popup-content-wrapper { border-radius: 12px; padding: 0; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0,0,0,.15); }
#firmalar-map .leaflet-popup-content { margin: 0; min-width: 220px; }
</style>
@endpush

@section('content')
<div class="page-container py-6 sm:py-8">
    <header class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Haritadaki nakliyeciler</h1>
        <p class="text-zinc-500 dark:text-zinc-400 mt-1">Konum paylaşan nakliye firmaları haritada. İl ile filtreleyin veya listeden firmaya tıklayınca haritada konumu gösterilir.</p>
    </header>

    <form method="get" action="{{ route('firmalar.map') }}" class="mb-4 flex flex-wrap items-center gap-3">
        <label for="filter-city" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">İl:</label>
        <select name="city" id="filter-city" class="input-touch w-48 text-sm py-2.5 rounded-xl">
            <option value="">Tümü</option>
            @foreach($cities as $city)
                <option value="{{ $city }}" {{ ($filters['city'] ?? '') === $city ? 'selected' : '' }}>{{ $city }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary py-2.5 px-4 text-sm">Filtrele</button>
    </form>

    @if($companiesJson->isEmpty())
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/50 p-12 text-center">
            <p class="text-zinc-500 dark:text-zinc-400">Bu filtreye uygun haritada konumu olan firma bulunamadı.</p>
            <a href="{{ route('firmalar.map') }}" class="text-emerald-600 dark:text-emerald-400 font-medium mt-2 inline-block hover:underline">Filtreyi temizle</a>
        </div>
    @else
        <div class="grid lg:grid-cols-2 gap-6 items-stretch">
            {{-- Sol: Firmalar listesi --}}
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 overflow-hidden flex flex-col order-2 lg:order-1">
                <div class="px-4 py-3 border-b border-zinc-200 dark:border-zinc-700">
                    <h2 class="font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        Firmalar ({{ $firmalar->count() }})
                    </h2>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Firmaya tıklayınca haritada konumu gösterilir</p>
                </div>
                <div class="flex-1 overflow-y-auto divide-y divide-zinc-200 dark:divide-zinc-700 min-h-[320px] max-h-[480px] lg:max-h-[520px]">
                    @foreach($firmalar as $index => $c)
                        @php $isCanli = $c->live_location_updated_at && $c->live_location_updated_at->diffInMinutes(now()) <= 15; @endphp
                        <div class="flex items-center gap-3 px-4 py-3 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/50 transition-colors group">
                            <span class="w-9 h-9 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">🚚</span>
                            <a href="{{ route('firmalar.show', $c) }}" class="min-w-0 flex-1">
                                <p class="font-medium text-zinc-900 dark:text-white group-hover:text-emerald-600 truncate">{{ $c->name }}</p>
                                @if($c->city || $c->district)
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400 truncate">{{ implode(', ', array_filter([$c->district, $c->city])) }}</p>
                                @endif
                            </a>
                            <button type="button" class="firma-map-focus-btn w-8 h-8 rounded-lg border border-zinc-200 dark:border-zinc-600 flex items-center justify-center text-zinc-500 hover:text-emerald-600 hover:border-emerald-400 shrink-0 transition-colors" data-index="{{ $index }}" title="Haritada göster" aria-label="Haritada göster">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </button>
                            @if($isCanli)
                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 text-xs font-medium">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span> Canlı
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            {{-- Sağ: Harita --}}
            <div class="flex flex-col min-h-[420px] order-1 lg:order-2">
                <div id="firmalar-map-wrap" class="flex-1 rounded-2xl overflow-hidden" style="min-height: 420px;">
                    <div id="firmalar-map" class="w-full rounded-2xl" style="height: 420px; min-height: 420px;" data-companies="{{ $companiesJson->toJson() }}"></div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
@if(!$companiesJson->isEmpty())
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function() {
    var el = document.getElementById('firmalar-map');
    if (!el || typeof L === 'undefined') return;
    var raw = el.getAttribute('data-companies');
    var companies = [];
    try { companies = JSON.parse(raw); } catch (e) { return; }
    if (!companies.length) return;
    var center = { lat: companies[0].lat, lng: companies[0].lng };
    if (companies.length > 1) {
        var sumLat = 0, sumLng = 0;
        companies.forEach(function(c) { sumLat += c.lat; sumLng += c.lng; });
        center = { lat: sumLat / companies.length, lng: sumLng / companies.length };
    }
    var map = L.map('firmalar-map', { zoomControl: true }).setView([center.lat, center.lng], 6);
    map.zoomControl.setPosition('topright');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>', maxZoom: 19 }).addTo(map);
    var pinIcon = L.divIcon({
        className: 'firma-map-marker',
        html: '<span class="firma-map-marker-inner">🚚</span>',
        iconSize: [40, 40],
        iconAnchor: [20, 40]
    });
    var bounds = [];
    window.firmaMapMarkers = [];
    companies.forEach(function(c) {
        var name = (c.name || 'Firma').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        var city = (c.city || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        var url = (c.url || '#').replace(/"/g, '&quot;');
        var popHtml = '<div style="padding:12px 14px; font-family:inherit;">' +
            '<p style="margin:0; font-weight:600; color:#18181b; font-size:14px;">' + name + '</p>' +
            (city ? '<p style="margin:4px 0 0; font-size:13px; color:#71717a;">' + city + '</p>' : '') +
            '<a href="' + url + '" style="display:inline-block; margin-top:10px; padding:8px 12px; border-radius:8px; background:#059669; color:#fff; font-size:12px; font-weight:600; text-decoration:none;">Firma sayfası</a>' +
            '</div>';
        var m = L.marker([c.lat, c.lng], { icon: pinIcon }).addTo(map);
        m.bindPopup(popHtml, { maxWidth: 280, minWidth: 220 });
        window.firmaMapMarkers.push(m);
        bounds.push([c.lat, c.lng]);
    });
    if (bounds.length > 1) map.fitBounds(bounds, { padding: [40, 40], maxZoom: 11 });
    window.firmaMap = map;
    document.querySelectorAll('.firma-map-focus-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var i = parseInt(btn.getAttribute('data-index'), 10);
            if (!isNaN(i) && window.firmaMapMarkers && window.firmaMapMarkers[i]) {
                var marker = window.firmaMapMarkers[i];
                var latLng = marker.getLatLng();
                map.setView([latLng.lat, latLng.lng], 15);
                marker.openPopup();
            }
        });
    });
})();
</script>
@endif
@endpush
