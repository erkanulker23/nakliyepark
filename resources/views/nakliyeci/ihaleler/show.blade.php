@extends('layouts.nakliyeci')

@section('title', $ihale->from_city . ' → ' . $ihale->to_city)
@section('page_heading', 'İhale detayı')
@section('page_subtitle', $ihale->from_city . ' → ' . $ihale->to_city)

@section('content')
<div class="max-w-3xl space-y-6">
    <nav class="text-sm text-slate-500">
        <a href="{{ route('nakliyeci.ihaleler.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">← Açık ihaleler</a>
    </nav>

    {{-- Özet --}}
    <div class="admin-card p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-200">{{ $ihale->from_city }} → {{ $ihale->to_city }}</h1>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $ihale->volume_m3 }} m³
                    @if($ihale->move_date)
                        · {{ $ihale->move_date->format('d.m.Y') }} taşıma
                    @endif
                    @if($ihale->service_type)
                        · {{ \App\Models\Ihale::serviceTypeLabels()[$ihale->service_type] ?? $ihale->service_type }}
                    @endif
                </p>
            </div>
            <span class="text-sm font-medium text-slate-500">{{ $ihale->teklifler()->count() }} teklif</span>
        </div>
    </div>

    {{-- Çıkış / Varış --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="admin-card p-6">
            <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Çıkış yeri</h2>
            <p class="font-medium text-slate-800 dark:text-slate-200">{{ $ihale->from_district ? $ihale->from_district . ', ' : '' }}{{ $ihale->from_city }}</p>
            @if($ihale->from_address)
                <p class="text-sm text-slate-500 mt-1">{{ $ihale->from_address }}</p>
            @endif
        </div>
        <div class="admin-card p-6">
            <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Varış yeri</h2>
            <p class="font-medium text-slate-800 dark:text-slate-200">{{ $ihale->to_district ? $ihale->to_district . ', ' : '' }}{{ $ihale->to_city }}</p>
            @if($ihale->to_address)
                <p class="text-sm text-slate-500 mt-1">{{ $ihale->to_address }}</p>
            @endif
        </div>
    </div>

    {{-- Genel bilgi --}}
    <div class="admin-card p-6">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Genel bilgi</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
            @if($ihale->room_type)
                <div><dt class="text-slate-500">Eşya büyüklüğü</dt><dd class="font-medium text-slate-800 dark:text-slate-200">{{ $ihale->room_type }}</dd></div>
            @endif
            <div><dt class="text-slate-500">Hacim</dt><dd class="font-medium text-slate-800 dark:text-slate-200">{{ $ihale->volume_m3 }} m³</dd></div>
            @if($ihale->distance_km)
                <div><dt class="text-slate-500">Mesafe</dt><dd class="font-medium text-slate-800 dark:text-slate-200">{{ number_format((float)$ihale->distance_km, 1, ',', '.') }} km</dd></div>
            @endif
        </dl>
    </div>

    @if($ihale->description)
        <div class="admin-card p-6">
            <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Açıklama</h2>
            <p class="text-slate-600 dark:text-slate-400 whitespace-pre-line">{{ $ihale->description }}</p>
        </div>
    @endif

    {{-- Teklif ver / verdim --}}
    <div class="admin-card p-6">
        @if($nakliyeciVerdiMi && $benimTeklif)
            <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">Sizin teklifiniz</h2>
            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($benimTeklif->amount, 0, ',', '.') }} ₺</p>
            <p class="text-sm text-slate-500 mt-1">Durum: {{ $benimTeklif->status === 'accepted' ? 'Onaylandı' : ($benimTeklif->status === 'rejected' ? 'Reddedildi' : 'Beklemede') }}</p>
            @if($benimTeklif->message)
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">{{ $benimTeklif->message }}</p>
            @endif
        @else
            <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4">Teklif ver</h2>
            <form method="POST" action="{{ route('nakliyeci.ihaleler.teklif.store') }}" class="space-y-4 max-w-md">
                @csrf
                <input type="hidden" name="ihale_id" value="{{ $ihale->id }}">
                <div class="admin-form-group">
                    <label for="ihale-teklif-amount" class="admin-label">Teklif tutarı (₺) *</label>
                    <input id="ihale-teklif-amount" type="number" name="amount" value="{{ old('amount') }}" required min="0" step="1" class="admin-input" placeholder="Örn. 15000">
                    @error('amount')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label for="ihale-teklif-message" class="admin-label">Mesaj (opsiyonel)</label>
                    <textarea id="ihale-teklif-message" name="message" rows="3" class="admin-input" placeholder="Not veya öneriniz">{{ old('message') }}</textarea>
                </div>
                <button type="submit" class="admin-btn-primary">Teklifi gönder</button>
            </form>
        @endif
    </div>
</div>
@endsection
