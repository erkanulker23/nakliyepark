@extends('layouts.app')

@section('title', $ihale->from_city . ' - ' . $ihale->to_city . ' Arası ' . (($ihale->service_type === 'evden_eve_nakliyat') ? 'Evden Eve Nakliyat' : 'İhale'))

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@section('content')
<div class="min-h-screen bg-[#fafafa] dark:bg-zinc-900/50">
    {{-- Kompakt fotoğraf galerisi --}}
    @php
        $photos = $ihale->photos;
        $photoUrl = function ($path) {
            return str_starts_with($path, 'http') ? $path : asset('storage/'.$path);
        };
    @endphp
    @if($photos->count() > 0)
        <div class="w-full bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800">
            <div class="page-container py-3 sm:py-4 max-w-5xl">
                <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide snap-x snap-mandatory -mx-4 px-4 sm:mx-0 sm:px-0">
                    @foreach($photos as $photo)
                        <a href="{{ $photoUrl($photo->path) }}" target="_blank" rel="noopener" class="shrink-0 w-[72%] sm:w-64 rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 h-36 sm:h-40 snap-center shadow-sm hover:ring-2 hover:ring-emerald-400/50 transition-shadow">
                            <img src="{{ $photoUrl($photo->path) }}" alt="İhale fotoğrafı" class="w-full h-full object-cover">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="page-container py-6 sm:py-8 max-w-5xl">
        {{-- Breadcrumb --}}
        <nav class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">
            <a href="{{ route('ihaleler.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Açık ihaleler</a>
            <span class="mx-2">/</span>
            <span class="text-zinc-700 dark:text-zinc-300">{{ $ihale->from_city }} → {{ $ihale->to_city }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-start gap-8">
            {{-- Sol: Başlık, tarih, kullanıcı, CTA --}}
            <div class="lg:flex-1 space-y-6">
                {{-- Başlık ve tarih --}}
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="min-w-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight flex items-center gap-2">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-400 text-zinc-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8 4-8-4m0 0l8-4 8 4m-8-4v10M12 21a9 9 0 01-9-9 9 9 0 019-9 9 9 0 019 9 9 9 0 01-9 9z"/></svg>
                            </span>
                            {{ $ihale->from_city }} - {{ $ihale->to_city }} Arası
                            @if($ihale->service_type === 'evden_eve_nakliyat')
                                Evden Eve Nakliyat
                            @else
                                Nakliye İhalesi
                            @endif
                        </h1>
                        @if($ihale->move_date || $ihale->move_date_end)
                            <p class="text-zinc-600 dark:text-zinc-400 mt-2 font-medium">
                                @if($ihale->move_date_end && $ihale->move_date != $ihale->move_date_end)
                                    {{ $ihale->move_date->locale('tr')->translatedFormat('j F Y') }} – {{ $ihale->move_date_end->locale('tr')->translatedFormat('j F Y') }} arasında taşınacak
                                @else
                                    {{ $ihale->move_date->locale('tr')->translatedFormat('j F Y') }} tarihinde taşınacak
                                @endif
                            </p>
                        @elseif(str_contains($ihale->description ?? '', 'Fiyat karşılaştırması'))
                            <p class="text-zinc-600 dark:text-zinc-400 mt-2 font-medium">Fiyat bakıyorum, tarih henüz belli değil</p>
                        @endif
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 dark:bg-emerald-900/40 px-4 py-2 text-sm font-semibold text-emerald-700 dark:text-emerald-300 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        {{ $ihale->teklifler_count }} teklif
                    </span>
                </div>

                {{-- Talep sahibi + Hemen teklif ver --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-zinc-600 dark:text-zinc-300 font-semibold">
                            @if($ihale->user_id && $ihale->user)
                                {{ strtoupper(mb_substr($ihale->user->name, 0, 1)) }}
                            @else
                                M
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-zinc-900 dark:text-white">
                                @if($ihale->user_id && $ihale->user)
                                    {{ $ihale->user->name }}
                                @else
                                    {{ $ihale->guest_contact_name ?? 'Misafir' }}
                                @endif
                            </p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $ihale->user_id ? 'Üye' : 'Bireysel' }}
                            </p>
                        </div>
                    </div>
                    @auth
                        @if(auth()->user()->isNakliyeci() && auth()->user()->company?->isApproved() && !$nakliyeciVerdiMi)
                            <a href="#teklif-form" class="btn-primary inline-flex items-center gap-2 shrink-0">
                                Hemen teklif ver
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-primary inline-flex items-center gap-2 shrink-0">
                            Hemen teklif ver
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Sağ: Detay kartları (Çıkış, Varış, Genel, Yol) --}}
            <div class="lg:w-[380px] shrink-0 space-y-6">
                {{-- Çıkış yeri --}}
                <div class="card rounded-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800">
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Çıkış yeri</h2>
                    </div>
                    <dl class="px-5 py-4 space-y-3">
                        <div class="flex justify-between gap-4">
                            <dt class="text-sm text-zinc-500 dark:text-zinc-400 shrink-0">Nereden</dt>
                            <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right">{{ $ihale->from_district ? $ihale->from_district . ', ' : '' }}{{ $ihale->from_city }}</dd>
                        </div>
                        @if($ihale->from_address)
                            <div class="flex justify-between gap-4">
                                <dt class="text-sm text-zinc-500 dark:text-zinc-400 shrink-0">Adres</dt>
                                <dd class="text-sm text-zinc-700 dark:text-zinc-300 text-right">{{ $ihale->from_address }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Varış yeri --}}
                <div class="card rounded-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800">
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Varış yeri</h2>
                    </div>
                    <dl class="px-5 py-4 space-y-3">
                        <div class="flex justify-between gap-4">
                            <dt class="text-sm text-zinc-500 dark:text-zinc-400 shrink-0">Nereye</dt>
                            <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right">{{ $ihale->to_district ? $ihale->to_district . ', ' : '' }}{{ $ihale->to_city }}</dd>
                        </div>
                        @if($ihale->to_address)
                            <div class="flex justify-between gap-4">
                                <dt class="text-sm text-zinc-500 dark:text-zinc-400 shrink-0">Adres</dt>
                                <dd class="text-sm text-zinc-700 dark:text-zinc-300 text-right">{{ $ihale->to_address }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Genel bilgi --}}
                <div class="card rounded-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800">
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Genel bilgi</h2>
                    </div>
                    <dl class="px-5 py-4 space-y-3">
                        @if($ihale->room_type)
                            <div class="flex justify-between gap-4">
                                <dt class="text-sm text-zinc-500 dark:text-zinc-400 shrink-0">Eşyanın büyüklüğü</dt>
                                <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right">{{ $ihale->room_type }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between gap-4">
                            <dt class="text-sm text-zinc-500 dark:text-zinc-400 shrink-0">Hacim</dt>
                            <dd class="text-sm font-medium text-zinc-900 dark:text-white text-right">{{ $ihale->volume_m3 }} m³</dd>
                        </div>
                        @if($ihale->service_type)
                            <div class="flex justify-between gap-4">
                                <dt class="text-sm text-zinc-500 dark:text-zinc-400 shrink-0">Hizmet tipi</dt>
                                <dd class="text-sm text-zinc-900 dark:text-white text-right">
                                    @switch($ihale->service_type)
                                        @case('evden_eve_nakliyat') Evden eve nakliyat @break
                                        @case('sehirlerarasi_nakliyat') Şehirler arası @break
                                        @case('parca_esya_tasimaciligi') Parça eşya @break
                                        @case('esya_depolama') Eşya depolama @break
                                        @case('ofis_tasima') Ofis taşıma @break
                                        @default {{ $ihale->service_type }}
                                    @endswitch
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Yol bilgileri --}}
                @if($ihale->distance_km)
                    @php
                        $km = (float) $ihale->distance_km;
                        $avgSpeed = 80;
                        $hours = $km / $avgSpeed;
                        $h = (int) floor($hours);
                        $m = (int) round(($hours - $h) * 60);
                    @endphp
                    <div class="card rounded-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                        <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800">
                            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Yol bilgileri</h2>
                        </div>
                        <div class="px-5 py-4 space-y-3">
                            <p class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                </span>
                                {{ $ihale->from_city }} ile {{ $ihale->to_city }} arasındaki mesafe {{ number_format($km, 1, ',', '.') }} km
                            </p>
                            <p class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                Tahmini yolculuk süresi {{ $h }} saat {{ $m }} dk
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Harita: modern güzergah --}}
        @if($ihale->from_city && $ihale->to_city)
            <div class="mt-8 card rounded-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between gap-3">
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Güzergah</h2>
                    <span id="route-distance" class="text-sm text-zinc-500 dark:text-zinc-400 hidden"></span>
                </div>
                <div id="ihale-route-map" class="w-full h-[280px] sm:h-[360px] bg-zinc-100 dark:bg-zinc-800 rounded-b-2xl" data-from="{{ $ihale->from_city }}" data-to="{{ $ihale->to_city }}"></div>
            </div>
        @endif

        @if($ihale->description)
            <div class="mt-8 card rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 sm:p-6">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3">Açıklama</h2>
                <p class="text-zinc-600 dark:text-zinc-300 leading-relaxed whitespace-pre-line">{{ $ihale->description }}</p>
            </div>
        @endif

        {{-- Teklif formu (nakliyeci) --}}
        @auth
            @if(auth()->user()->isNakliyeci() && auth()->user()->company?->isApproved())
                @if(!$nakliyeciVerdiMi)
                    <div id="teklif-form" class="mt-8 card rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 sm:p-6 scroll-mt-6">
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">Teklif ver</h2>
                        <form method="POST" action="{{ route('nakliyeci.teklif.store') }}" class="space-y-4 max-w-md">
                            @csrf
                            <input type="hidden" name="ihale_id" value="{{ $ihale->id }}">
                            <div>
                                <label for="amount" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Teklif tutarı (₺) *</label>
                                <input id="amount" type="number" name="amount" value="{{ old('amount') }}" required min="0" step="1" class="input-touch" placeholder="Örn. 15000">
                                @error('amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Mesaj (opsiyonel)</label>
                                <textarea id="message" name="message" rows="3" class="input-touch min-h-[88px]" placeholder="Not veya öneriniz">{{ old('message') }}</textarea>
                            </div>
                            <button type="submit" class="btn-primary inline-flex items-center gap-2">
                                Teklifi gönder
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mt-8 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-200 dark:border-emerald-800">
                        <p class="text-emerald-800 dark:text-emerald-200 font-medium">Bu ihale için zaten teklif verdiniz.</p>
                    </div>
                @endif
            @endif
        @else
            <div class="mt-8 p-4 rounded-2xl bg-zinc-100 dark:bg-zinc-800/50">
                <p class="text-zinc-600 dark:text-zinc-400">Teklif vermek için <a href="{{ route('login') }}" class="text-emerald-600 dark:text-emerald-400 font-semibold hover:underline">giriş yapın</a> (nakliyeci hesabı gerekir).</p>
            </div>
        @endauth

        {{-- Verilen teklifler --}}
        <div class="mt-8 card rounded-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Verilen teklifler ({{ $ihale->teklifler_count }})</h2>
            </div>
            @if($ihale->teklifler->count() > 0)
                <ul class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @foreach($ihale->teklifler as $teklif)
                        <li class="px-5 py-4 flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('firmalar.show', $teklif->company) }}" class="font-semibold text-zinc-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400">{{ $teklif->company->name }}</a>
                                @if($teklif->message)
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">{{ $teklif->message }}</p>
                                @endif
                            </div>
                            <div class="shrink-0 font-bold text-lg text-emerald-600 dark:text-emerald-400">
                                {{ number_format($teklif->amount, 0, ',', '.') }} ₺
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="px-5 py-8 text-center text-zinc-500 dark:text-zinc-400">Henüz teklif verilmedi. İlk teklifi siz verin.</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function() {
    const el = document.getElementById('ihale-route-map');
    if (!el) return;
    const fromName = el.dataset.from?.trim();
    const toName = el.dataset.to?.trim();
    if (!fromName || !toName) return;

    const distanceEl = document.getElementById('route-distance');

    function initMap(from, to, routeCoords) {
        const map = L.map('ihale-route-map', { zoomControl: true }).setView([39, 35], 6);
        map.zoomControl.setPosition('topright');

        // OpenStreetMap – ücretsiz, API anahtarı gerektirmez (401 önlenir)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(map);

        const startIcon = L.divIcon({
            className: 'route-marker route-marker-start',
            html: '<div class="route-marker-inner"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg><span>Çıkış</span></div>',
            iconSize: [44, 44],
            iconAnchor: [22, 44]
        });
        const endIcon = L.divIcon({
            className: 'route-marker route-marker-end',
            html: '<div class="route-marker-inner"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg><span>Varış</span></div>',
            iconSize: [44, 44],
            iconAnchor: [22, 44]
        });

        L.marker([from.lat, from.lng], { icon: startIcon }).addTo(map).bindTooltip(from.name, { permanent: false, direction: 'top', className: 'route-tooltip' });
        L.marker([to.lat, to.lng], { icon: endIcon }).addTo(map).bindTooltip(to.name, { permanent: false, direction: 'top', className: 'route-tooltip' });

        if (routeCoords && routeCoords.length >= 2) {
            const line = L.polyline(routeCoords, {
                color: '#059669',
                weight: 5,
                opacity: 0.9,
                lineJoin: 'round',
                lineCap: 'round'
            }).addTo(map);
            map.fitBounds(line.getBounds(), { padding: [50, 50], maxZoom: 10 });
        } else {
            const fallback = [[from.lat, from.lng], [to.lat, to.lng]];
            L.polyline(fallback, { color: '#059669', weight: 4, opacity: 0.8, dashArray: '8,8' }).addTo(map);
            map.fitBounds([from, to].map(p => [p.lat, p.lng]), { padding: [50, 50], maxZoom: 10 });
        }
    }

    function showDistance(meters) {
        if (distanceEl && meters != null) {
            distanceEl.textContent = (meters / 1000).toFixed(1) + ' km';
            distanceEl.classList.remove('hidden');
        }
    }

    fetch('{{ url("/api/turkey/provinces") }}')
        .then(r => r.json())
        .then(data => {
            const provinces = (data.data || []).filter(p => p.latitude != null && p.longitude != null);
            const from = provinces.find(p => p.name === fromName);
            const to = provinces.find(p => p.name === toName);
            if (!from || !to) return;

            const fromCoords = { lat: parseFloat(from.latitude), lng: parseFloat(from.longitude), name: from.name };
            const toCoords = { lat: parseFloat(to.latitude), lng: parseFloat(to.longitude), name: to.name };

            // OSRM: gerçek karayolu güzergahı (lng,lat sırası)
            const osrmUrl = 'https://router.project-osrm.org/route/v1/driving/' + fromCoords.lng + ',' + fromCoords.lat + ';' + toCoords.lng + ',' + toCoords.lat + '?overview=full&geometries=geojson';
            fetch(osrmUrl)
                .then(r => r.json())
                .then(osrm => {
                    let routeCoords = null;
                    if (osrm.code === 'Ok' && osrm.routes && osrm.routes[0]) {
                        const route = osrm.routes[0];
                        if (route.geometry && route.geometry.coordinates && route.geometry.coordinates.length) {
                            routeCoords = route.geometry.coordinates.map(c => [c[1], c[0]]);
                            if (route.distance != null) showDistance(route.distance);
                        }
                    }
                    initMap(fromCoords, toCoords, routeCoords);
                })
                .catch(() => initMap(fromCoords, toCoords, null));
        })
        .catch(() => {});
})();
</script>
@endpush
@push('styles')
<style>
.route-marker { background: none !important; border: none !important; }
.route-marker-inner {
    display: flex; flex-direction: column; align-items: center; gap: 2px;
    width: 44px; height: 44px; padding: 6px;
    border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,.2);
    font-size: 10px; font-weight: 600; white-space: nowrap;
}
.route-marker-start .route-marker-inner { background: #dc2626; color: #fff; }
.route-marker-end .route-marker-inner { background: #059669; color: #fff; }
.route-marker-inner svg { width: 20px; height: 20px; }
.route-tooltip { font-size: 12px; font-weight: 500; }
</style>
@endpush
@endsection
