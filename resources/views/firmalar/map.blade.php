@extends('layouts.app')

@section('title', 'Haritadaki Nakliyeciler - NakliyePark')
@section('meta_description', 'Konum paylaşan nakliye firmalarını haritada görün. İl veya ilçe ile filtreleyin, firmaya tıklayınca haritada konumu açılsın.')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<style>
/* Map container – modern card with soft shadow */
#firmalar-map-wrap {
    border-radius: 1.25rem;
    overflow: hidden;
    box-shadow: 0 4px 24px -4px rgba(0,0,0,.08), 0 8px 32px -8px rgba(0,0,0,.06);
    border: 1px solid rgba(0,0,0,.06);
}
.dark #firmalar-map-wrap {
    box-shadow: 0 4px 24px -4px rgba(0,0,0,.25);
    border-color: rgba(255,255,255,.06);
}
#firmalar-map {
    background: linear-gradient(180deg, #f0fdf4 0%, #ecfdf5 100%);
    min-height: 480px;
}
.dark #firmalar-map {
    background: linear-gradient(180deg, #052e16 0%, #022c22 100%);
}
/* Zoom controls – pill style */
#firmalar-map .leaflet-control-zoom {
    border: none !important;
    box-shadow: 0 4px 14px rgba(0,0,0,.12) !important;
    border-radius: 12px !important;
    overflow: hidden;
}
#firmalar-map .leaflet-control-zoom a {
    width: 40px !important;
    height: 38px !important;
    line-height: 38px !important;
    background: #fff !important;
    color: #374151 !important;
    border: none !important;
    border-radius: 0 !important;
    margin: 0 !important;
    font-size: 18px !important;
    transition: background .2s, color .2s;
}
#firmalar-map .leaflet-control-zoom a:first-child { border-radius: 12px 12px 0 0 !important; }
#firmalar-map .leaflet-control-zoom a:last-child { border-radius: 0 0 12px 12px !important; }
#firmalar-map .leaflet-control-zoom a:hover {
    background: #059669 !important;
    color: #fff !important;
}
.dark #firmalar-map .leaflet-control-zoom a {
    background: rgba(24,24,27,.95) !important;
    color: #e4e4e7 !important;
}
.dark #firmalar-map .leaflet-control-zoom a:hover {
    background: #059669 !important;
    color: #fff !important;
}
#firmalar-map .leaflet-control-attribution {
    font-size: 10px;
    opacity: .75;
    background: rgba(255,255,255,.9) !important;
    padding: 4px 8px !important;
    border-radius: 6px 0 0 0 !important;
}
.dark #firmalar-map .leaflet-control-attribution { background: rgba(24,24,27,.9) !important; }
/* Custom pin – teardrop with icon */
.firma-map-marker {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 50% 50% 50% 0;
    background: linear-gradient(145deg, #10b981 0%, #059669 50%, #047857 100%);
    transform: rotate(-45deg);
    box-shadow: 0 4px 14px rgba(5,150,105,.35), 0 0 0 3px rgba(255,255,255,.9);
}
.dark .firma-map-marker { box-shadow: 0 4px 14px rgba(5,150,105,.4), 0 0 0 3px rgba(24,24,27,.95); }
.firma-map-marker-inner {
    transform: rotate(45deg);
    color: #fff;
    font-size: 18px;
    filter: drop-shadow(0 1px 1px rgba(0,0,0,.2));
}
/* Popup – clean card */
#firmalar-map .leaflet-popup-content-wrapper {
    border-radius: 16px;
    padding: 0;
    overflow: hidden;
    box-shadow: 0 20px 40px -12px rgba(0,0,0,.2), 0 0 0 1px rgba(0,0,0,.04);
}
.dark #firmalar-map .leaflet-popup-content-wrapper {
    box-shadow: 0 20px 40px -12px rgba(0,0,0,.4);
}
#firmalar-map .leaflet-popup-content { margin: 0; min-width: 240px; }
.leaflet-popup-content-wrapper { background: #fff; }
.dark .leaflet-popup-content-wrapper { background: #27272a; }
.dark .leaflet-popup-content p { color: #fafafa !important; }
.dark .leaflet-popup-content p + p { color: #a1a1aa !important; }
</style>
@endpush

@section('content')
<div class="page-container py-6 sm:py-8">
    {{-- Hero header --}}
    <header class="mb-8">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 text-sm font-medium mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            Konum paylaşan firmalar
        </div>
        <h1 class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white tracking-tight">Haritadaki nakliyeciler</h1>
        <p class="text-zinc-600 dark:text-zinc-400 mt-2 max-w-2xl">Nakliye firmalarını haritada keşfedin. İl ile filtreleyin, listeden seçince haritada konumu açılır.</p>
    </header>

    {{-- Filter bar – card style --}}
    <form method="get" action="{{ route('firmalar.map') }}" class="mb-6 rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-900/80 shadow-sm p-4 flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2">
            <label for="filter-city" class="text-sm font-medium text-zinc-700 dark:text-zinc-300 whitespace-nowrap">İl</label>
            <select name="city" id="filter-city" class="input-touch min-w-[180px] py-2.5 rounded-xl border-zinc-200 dark:border-zinc-600 bg-zinc-50/50 dark:bg-zinc-800/50 text-zinc-900 dark:text-zinc-100 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500">
                <option value="">Tüm iller</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}" {{ ($filters['city'] ?? '') === $city ? 'selected' : '' }}>{{ $city }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            Filtrele
        </button>
    </form>

    @if($companiesJson->isEmpty())
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/80 dark:bg-zinc-900/50 p-14 text-center">
            <div class="w-14 h-14 rounded-2xl bg-zinc-200/80 dark:bg-zinc-700/80 flex items-center justify-center mx-auto mb-4 text-zinc-500 dark:text-zinc-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            </div>
            <p class="text-zinc-600 dark:text-zinc-400 font-medium">Bu filtreye uygun haritada konumu olan firma yok</p>
            <p class="text-sm text-zinc-500 dark:text-zinc-500 mt-1">Filtreyi temizleyerek tüm firmaları görebilirsiniz.</p>
            <a href="{{ route('firmalar.map') }}" class="inline-flex items-center gap-2 mt-5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors">
                Filtreyi temizle
            </a>
        </div>
    @else
        <div class="grid lg:grid-cols-[380px_1fr] gap-6 items-stretch">
            {{-- Sol: Firmalar listesi – modern card --}}
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-900/80 shadow-sm overflow-hidden flex flex-col order-2 lg:order-1">
                <div class="px-5 py-4 border-b border-zinc-200/80 dark:border-zinc-700/80 bg-zinc-50/50 dark:bg-zinc-800/30">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-emerald-500/15 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </span>
                            Firmalar
                        </h2>
                        <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 bg-zinc-200/80 dark:bg-zinc-700/80 px-2.5 py-1 rounded-lg">{{ $firmalar->count() }}</span>
                    </div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1.5">Tıklayınca haritada konum açılır</p>
                </div>
                <div class="flex-1 overflow-y-auto min-h-[320px] max-h-[500px] lg:max-h-[560px]">
                    @foreach($firmalar as $index => $c)
                        @php $isCanli = $c->live_location_updated_at && $c->live_location_updated_at->diffInMinutes(now()) <= 15; @endphp
                        <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors group border-b border-zinc-100 dark:border-zinc-800/80 last:border-b-0">
                            <span class="w-10 h-10 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center text-lg shrink-0">🚚</span>
                            <a href="{{ route('firmalar.show', $c) }}" class="min-w-0 flex-1">
                                <p class="font-medium text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 truncate transition-colors">{{ $c->name }}</p>
                                @if($c->city || $c->district)
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400 truncate mt-0.5">{{ implode(', ', array_filter([$c->district, $c->city])) }}</p>
                                @endif
                            </a>
                            <button type="button" class="firma-map-focus-btn w-9 h-9 rounded-xl border border-zinc-200 dark:border-zinc-600 flex items-center justify-center text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 hover:border-emerald-400 hover:bg-emerald-500/10 shrink-0 transition-all" data-index="{{ $index }}" title="Haritada göster" aria-label="Haritada göster">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </button>
                            @if($isCanli)
                                <span class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 text-xs font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Canlı
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            {{-- Sağ: Harita – büyük ve öne çıkan --}}
            <div class="flex flex-col min-h-[480px] order-1 lg:order-2">
                <div id="firmalar-map-wrap" class="flex-1 rounded-2xl overflow-hidden min-h-[480px] h-[480px] lg:h-[560px]">
                    <div id="firmalar-map" class="w-full h-full rounded-2xl min-h-[480px]" style="height: 100%; min-height: 480px;" data-companies="{{ $companiesJson->toJson() }}"></div>
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
        var popHtml = '<div style="padding:16px 18px; font-family:inherit;">' +
            '<p style="margin:0; font-weight:600; color:#18181b; font-size:15px; letter-spacing:-0.01em;">' + name + '</p>' +
            (city ? '<p style="margin:6px 0 0; font-size:13px; color:#71717a;">' + city + '</p>' : '') +
            '<a href="' + url + '" style="display:inline-flex; align-items:center; gap:6px; margin-top:14px; padding:10px 14px; border-radius:10px; background:linear-gradient(135deg,#059669,#047857); color:#fff; font-size:13px; font-weight:600; text-decoration:none; box-shadow:0 2px 8px rgba(5,150,105,.3);">Firma sayfası →</a>' +
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
