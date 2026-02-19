@extends('layouts.nakliyeci')

@section('title', 'Kontrol Paneli')
@section('page_heading', 'Kontrol Paneli')
@section('page_subtitle', $company->name)

@section('content')
@if($company->isBlocked())
    <div class="panel-card p-4 mb-6 border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-200 rounded-2xl">
        <p class="font-semibold">Üyeliğiniz askıya alındı</p>
        @if($company->blocked_reason)<p class="text-sm mt-1 opacity-90">Sebep: {{ $company->blocked_reason }}</p>@endif
        <p class="text-sm mt-1">Teklif veremez ve firmanız sitede listelenmez.</p>
    </div>
@endif

{{-- Hızlı aksiyon: Teklif Ver FAB (sadece onaylı ve bloklu değilse) --}}
@if($company->isApproved() && !$company->isBlocked())
    <a href="{{ route('nakliyeci.ihaleler.index') }}" class="panel-fab" aria-label="Teklif ver">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    </a>
@endif

{{-- Sayılar büyük: stat kartları --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="panel-stat flex items-start gap-3 {{ $company->isApproved() ? 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-200 dark:border-emerald-800' : 'bg-amber-50 dark:bg-amber-950/30 border-amber-200 dark:border-amber-800' }}">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $company->isApproved() ? 'bg-emerald-500/20 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/20 text-amber-600 dark:text-amber-400' }}">
            @if($company->isApproved())
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @endif
        </div>
        <div class="min-w-0">
            <p class="panel-stat-value {{ $company->isApproved() ? 'text-emerald-700 dark:text-emerald-300' : 'text-amber-700 dark:text-amber-300' }} text-xl sm:text-2xl">{{ $company->isApproved() ? 'Onaylı' : 'Bekliyor' }}</p>
            <p class="panel-stat-label">Firma durumu</p>
        </div>
    </div>
    <div class="panel-stat">
        <p class="panel-stat-value text-2xl sm:text-3xl">{{ $company->teklifler()->count() }}</p>
        <p class="panel-stat-label">Toplam teklif</p>
    </div>
    <div class="panel-stat">
        <p class="panel-stat-value text-2xl sm:text-3xl">{{ $company->acceptedTeklifler()->count() }}</p>
        <p class="panel-stat-label">Kabul edilen</p>
    </div>
    <div class="panel-stat">
        <p class="panel-stat-value text-2xl sm:text-3xl">{{ number_format($company->total_earnings, 0, ',', '.') }} ₺</p>
        <p class="panel-stat-label">Toplam kazanç</p>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-4 sm:gap-6 mb-6">
    <div class="panel-card p-4 sm:p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-[var(--panel-text)]">Son teklifler</h2>
            <a href="{{ route('nakliyeci.teklifler.index') }}" class="text-sm font-medium text-[var(--panel-primary)] hover:underline">Tümü →</a>
        </div>
        @forelse($teklifler->take(5) as $t)
            @php $ihale = $t->ihale; @endphp
            <a href="{{ $ihale ? route('nakliyeci.ihaleler.show', $ihale) : '#' }}" class="panel-action-row block w-full text-left hover:border-emerald-300 dark:hover:border-emerald-700 transition-colors">
                <div class="min-w-0 flex-1">
                    <p class="font-medium text-[var(--panel-text)] truncate">{{ $ihale ? $ihale->from_location_text . ' → ' . $ihale->to_location_text : '—' }}</p>
                    <p class="text-sm text-[var(--panel-text-muted)]">{{ number_format($t->amount, 0, ',', '.') }} ₺</p>
                </div>
                <x-panel.status-badge :status="$t->status === 'accepted' ? 'approved' : ($t->status === 'rejected' ? 'rejected' : 'pending')">
                    {{ $t->status === 'accepted' ? 'Onaylandı' : ($t->status === 'rejected' ? 'Reddedildi' : 'Beklemede') }}
                </x-panel.status-badge>
            </a>
        @empty
            <p class="text-[var(--panel-text-muted)] text-sm py-4">Henüz teklif yok.</p>
        @endforelse
    </div>
    <div class="panel-card p-4 sm:p-5">
        <h2 class="font-semibold text-[var(--panel-text)] mb-2">Firma bilgileri</h2>
        <p class="text-sm text-[var(--panel-text-muted)] mb-4">Firma adı, iletişim ve açıklama gibi bilgileri buradan güncelleyebilirsiniz.</p>
        <a href="{{ route('nakliyeci.company.edit') }}" class="btn-primary inline-flex">Firma bilgilerini düzenle</a>
    </div>
</div>

{{-- Haritada görünsün: Değişiklikler anında kaydedilir, ayrı Kaydet butonu yok --}}
<div class="panel-card p-4 sm:p-5 mb-6">
    <h2 class="font-semibold text-[var(--panel-text)] mb-2">Haritada görünsün</h2>
    <p class="text-sm text-[var(--panel-text-muted)] mb-2">Açıkken konumunuz anasayfadaki haritada nakliye firmalarıyla birlikte gösterilir.</p>
    <p class="text-xs text-[var(--panel-text-muted)] mb-4">Değişiklikler anında kaydedilir (ayrı kaydet butonu yok).</p>
    <label class="flex items-center gap-3 cursor-pointer">
        <input type="checkbox" id="map_visible_toggle" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" {{ $company->map_visible ? 'checked' : '' }}>
        <span class="text-sm font-medium text-[var(--panel-text)]">Anasayfada haritada konumumu göster</span>
    </label>
    <p id="map-visibility-status" class="text-xs mt-2 text-[var(--panel-text-muted)]"></p>
</div>

{{-- Açık ihaleler: kart yapısı, teklif ver CTA --}}
<div class="panel-card p-4 sm:p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold text-[var(--panel-text)]">Açık ihaleler</h2>
        <a href="{{ route('nakliyeci.ihaleler.index') }}" class="text-sm font-medium text-[var(--panel-primary)] hover:underline">Tümü →</a>
    </div>
    @if($company->isBlocked())
        <p class="text-[var(--panel-text-muted)] text-sm py-2">Üyeliğiniz askıda olduğu için yeni teklif veremezsiniz.</p>
    @elseif($company->isApproved())
        @forelse($yayindakiIhaleler as $ihale)
            @php $benimTeklif = $ihale->teklifler()->where('company_id', $company->id)->first(); @endphp
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 py-4 border-b border-[var(--panel-border)] last:border-0">
                <div class="flex-1 min-w-0">
                    <a href="{{ route('nakliyeci.ihaleler.show', $ihale) }}" class="font-medium text-[var(--panel-text)] hover:text-[var(--panel-primary)] block">{{ $ihale->from_location_text }} → {{ $ihale->to_location_text }}</a>
                    <p class="text-sm text-[var(--panel-text-muted)]">{{ $ihale->volume_m3 }} m³ · {{ $ihale->move_date?->format('d.m.Y') ?? '-' }}</p>
                </div>
                @if($benimTeklif)
                    <p class="text-sm text-[var(--panel-text-muted)]">Teklifiniz: <strong class="text-[var(--panel-text)]">{{ number_format($benimTeklif->amount, 0, ',', '.') }} ₺</strong> — <x-panel.status-badge :status="$benimTeklif->status === 'accepted' ? 'approved' : ($benimTeklif->status === 'rejected' ? 'rejected' : 'pending')">{{ $benimTeklif->status === 'accepted' ? 'Onaylandı' : ($benimTeklif->status === 'rejected' ? 'Reddedildi' : 'Beklemede') }}</x-panel.status-badge></p>
                @else
                    <form method="POST" action="{{ route('nakliyeci.teklif.store') }}" class="flex gap-2 flex-wrap items-center">
                        @csrf
                        <input type="hidden" name="ihale_id" value="{{ $ihale->id }}">
                        <input type="number" name="amount" min="0" step="100" placeholder="Tutar (₺)" required class="input-touch w-28 sm:w-32 text-sm rounded-xl">
                        <button type="submit" class="btn-primary text-sm">Teklif Ver</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-[var(--panel-text-muted)] text-sm py-2">Şu an açık ihale yok.</p>
        @endforelse
    @else
        <p class="text-[var(--panel-text-muted)] text-sm">Firmanız onaylandıktan sonra teklif verebilirsiniz.</p>
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
                    if (data.map_visible) statusEl.textContent = 'Haritada görünüyorsunuz. Kaydedildi.';
                    else statusEl.textContent = 'Haritada görünmüyorsunuz. Kaydedildi.';
                }
            })
            .catch(() => { if (statusEl) statusEl.textContent = 'Güncelleme gönderilemedi. Tekrar deneyin.'; });
    }
    function updateLocation() {
        if (!navigator.geolocation) { if (statusEl) statusEl.textContent = 'Konum desteklenmiyor.'; return; }
        navigator.geolocation.getCurrentPosition(
            function(pos) { sendLocation(undefined, pos.coords.latitude, pos.coords.longitude); },
            function() { if (statusEl) statusEl.textContent = 'Konum alınamadı.'; }
        );
    }
    toggle.addEventListener('change', function() {
        const on = this.checked;
        sendLocation(on);
        if (on) { updateLocation(); locationInterval = setInterval(updateLocation, 90000); }
        else { if (locationInterval) { clearInterval(locationInterval); locationInterval = null; } }
    });
    if (toggle.checked) { updateLocation(); locationInterval = setInterval(updateLocation, 90000); if (statusEl) statusEl.textContent = 'Haritada görünüyorsunuz.'; }
})();
</script>
@endpush
@endsection
