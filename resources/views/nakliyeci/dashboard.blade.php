@extends('layouts.nakliyeci')

@section('title', 'Kontrol Paneli')
@section('page_heading', 'Kontrol Paneli')
@section('page_subtitle', $company->name)

@section('content')
<div class="nakliyeci-stats grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
    <div class="admin-card nakliyeci-stat-card p-5 sm:p-6 flex items-start gap-4">
        <div class="nakliyeci-stat-icon rounded-xl p-3 {{ $company->isApproved() ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400' : 'bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400' }}">
            @if($company->isApproved())
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @else
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @endif
        </div>
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Firma durumu</p>
            <p class="text-lg font-bold mt-0.5 {{ $company->isApproved() ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                {{ $company->isApproved() ? 'Onaylı' : 'Onay bekliyor' }}
            </p>
        </div>
    </div>
    <div class="admin-card nakliyeci-stat-card p-5 sm:p-6 flex items-start gap-4">
        <div class="nakliyeci-stat-icon rounded-xl p-3 bg-sky-100 dark:bg-sky-900/40 text-sky-600 dark:text-sky-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Toplam teklif</p>
            <p class="text-2xl font-bold text-slate-800 dark:text-slate-200 mt-0.5">{{ $company->teklifler()->count() }}</p>
        </div>
    </div>
    <div class="admin-card nakliyeci-stat-card p-5 sm:p-6 flex items-start gap-4">
        <div class="nakliyeci-stat-icon rounded-xl p-3 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Kabul edilen</p>
            <p class="text-2xl font-bold text-slate-800 dark:text-slate-200 mt-0.5">{{ $company->acceptedTeklifler()->count() }}</p>
        </div>
    </div>
    <div class="admin-card nakliyeci-stat-card p-5 sm:p-6 flex items-start gap-4">
        <div class="nakliyeci-stat-icon rounded-xl p-3 bg-violet-100 dark:bg-violet-900/40 text-violet-600 dark:text-violet-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Toplam kazanç</p>
            <p class="text-2xl font-bold text-slate-800 dark:text-slate-200 mt-0.5">{{ number_format($company->total_earnings, 0, ',', '.') }} ₺</p>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="admin-card p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-slate-800 dark:text-slate-200">Son teklifler</h2>
            <a href="{{ route('nakliyeci.teklifler.index') }}" class="text-sm text-emerald-600 hover:underline font-medium">Tümü →</a>
        </div>
        @forelse($teklifler->take(5) as $t)
            @php $ihale = $t->ihale; @endphp
            <article class="flex justify-between items-center py-3 border-b border-slate-200 dark:border-slate-600 last:border-0 gap-3">
                <div class="min-w-0 flex-1">
                    @if($ihale)
                        <a href="{{ route('nakliyeci.ihaleler.show', $ihale) }}" class="font-medium text-slate-800 dark:text-slate-200 hover:text-emerald-600 dark:hover:text-emerald-400 block truncate">{{ $ihale->from_city }} → {{ $ihale->to_city }}</a>
                    @else
                        <span class="font-medium text-slate-500">—</span>
                    @endif
                    <p class="text-sm text-slate-500">{{ number_format($t->amount, 0, ',', '.') }} ₺</p>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full font-medium shrink-0
                    @if($t->status === 'accepted') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300
                    @elseif($t->status === 'rejected') bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300
                    @else bg-sky-100 text-sky-700 dark:bg-sky-900/50 dark:text-sky-300
                    @endif">
                    {{ $t->status === 'accepted' ? 'Onaylandı' : ($t->status === 'rejected' ? 'Reddedildi' : 'Beklemede') }}
                </span>
            </article>
        @empty
            <p class="text-slate-500 text-sm py-2">Henüz teklif yok.</p>
        @endforelse
    </div>
    <div class="admin-card p-6">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4">Firma bilgileri</h2>
        <p class="text-sm text-slate-500 mb-4">Firma adı, iletişim ve açıklama gibi bilgileri buradan güncelleyebilirsiniz.</p>
        <a href="{{ route('nakliyeci.company.edit') }}" class="admin-btn-primary inline-flex">Firma bilgilerini düzenle</a>
    </div>
    <div class="admin-card p-6" id="map-visibility-card">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">Haritada görünsün</h2>
        <p class="text-sm text-slate-500 mb-4">Açıkken konumunuz anasayfadaki haritada nakliye firmalarıyla birlikte gösterilir. Sayfayı açık tutun ve konum izni verin.</p>
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" id="map_visible_toggle" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" {{ $company->map_visible ? 'checked' : '' }}>
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Anasayfada haritada konumumu göster</span>
        </label>
        <p id="map-visibility-status" class="text-xs mt-2 text-slate-500"></p>
    </div>
</div>

{{-- Açık ihaleler - hızlı teklif --}}
<div class="admin-card p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200">Açık ihaleler</h2>
        <a href="{{ route('nakliyeci.ihaleler.index') }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:underline font-medium">Tümü →</a>
    </div>
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">Tek tıkla teklif verebilir veya detay için satıra tıklayın.</p>
    @if($company->isApproved())
        @forelse($yayindakiIhaleler as $ihale)
            @php $benimTeklif = $ihale->teklifler()->where('company_id', $company->id)->first(); @endphp
            <article class="nakliyeci-ihale-row flex flex-wrap items-center gap-4 py-4 border-b border-slate-200 dark:border-slate-600 last:border-0">
                <div class="flex-1 min-w-[200px]">
                    <a href="{{ route('nakliyeci.ihaleler.show', $ihale) }}" class="font-medium text-slate-800 dark:text-slate-200 hover:text-emerald-600 dark:hover:text-emerald-400">{{ $ihale->from_city }} → {{ $ihale->to_city }}</a>
                    <p class="text-sm text-slate-500 mt-0.5">{{ $ihale->volume_m3 }} m³ · {{ $ihale->move_date?->format('d.m.Y') ?? '-' }}</p>
                </div>
                @if($benimTeklif)
                    <p class="text-sm text-slate-600 dark:text-slate-400">Teklifiniz: <strong>{{ number_format($benimTeklif->amount, 0, ',', '.') }} ₺</strong> — {{ $benimTeklif->status === 'accepted' ? 'Onaylandı' : ($benimTeklif->status === 'rejected' ? 'Reddedildi' : 'Beklemede') }}</p>
                @else
                    <form method="POST" action="{{ route('nakliyeci.teklif.store') }}" class="flex gap-2 flex-wrap items-center">
                        @csrf
                        <input type="hidden" name="ihale_id" value="{{ $ihale->id }}">
                        <input type="number" name="amount" min="0" step="100" placeholder="Tutar (₺)" required
                               class="admin-input w-28 sm:w-32 text-sm">
                        <button type="submit" class="admin-btn-primary text-sm">Teklif Ver</button>
                    </form>
                @endif
            </article>
        @empty
            <p class="text-slate-500 text-sm py-2">Şu an açık ihale yok.</p>
        @endforelse
    @else
        <p class="text-slate-500 text-sm">Firmanız onaylandıktan sonra teklif verebilirsiniz.</p>
    @endif
</div>

@push('scripts')
<script>
(function() {
    const toggle = document.getElementById('map_visible_toggle');
    const statusEl = document.getElementById('map-visibility-status');
    const url = '{{ route("nakliyeci.location.update") }}';
    const csrf = '{{ csrf_token() }}';
    let locationInterval = null;

    function sendLocation(mapVisible, lat, lng) {
        const body = new FormData();
        body.append('_token', csrf);
        if (mapVisible !== undefined) body.append('map_visible', mapVisible ? '1' : '0');
        if (lat != null) body.append('lat', lat);
        if (lng != null) body.append('lng', lng);
        fetch(url, { method: 'POST', body, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                if (data.ok && statusEl) {
                    if (data.map_visible) statusEl.textContent = 'Haritada görünüyorsunuz. Konum güncelleniyor.';
                    else statusEl.textContent = '';
                }
            })
            .catch(() => { if (statusEl) statusEl.textContent = 'Güncelleme gönderilemedi.'; });
    }

    function updateLocation() {
        if (!navigator.geolocation) {
            if (statusEl) statusEl.textContent = 'Tarayıcınız konum desteklemiyor.';
            return;
        }
        navigator.geolocation.getCurrentPosition(
            function(pos) { sendLocation(undefined, pos.coords.latitude, pos.coords.longitude); },
            function() { if (statusEl) statusEl.textContent = 'Konum alınamadı. İzin verin veya sayfayı yenileyin.'; }
        );
    }

    toggle.addEventListener('change', function() {
        const on = this.checked;
        sendLocation(on);
        if (on) {
            updateLocation();
            locationInterval = setInterval(updateLocation, 90000);
        } else {
            if (locationInterval) { clearInterval(locationInterval); locationInterval = null; }
            if (statusEl) statusEl.textContent = '';
        }
    });

    if (toggle.checked) {
        updateLocation();
        locationInterval = setInterval(updateLocation, 90000);
    }
})();
</script>
@endpush
@endsection
