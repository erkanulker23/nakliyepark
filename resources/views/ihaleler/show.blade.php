@extends('layouts.app')

@php
    $ihaleServiceLabel = ($ihale->service_type === 'evden_eve_nakliyat') ? 'Evden Eve Nakliyat' : 'Yük Taşıma';
    $ihaleMetaDesc = $ihale->from_city . ' - ' . $ihale->to_city . ' arası ' . $ihaleServiceLabel . ' ihalesi. NakliyePark üzerinden teklif verin veya talebinizi oluşturun.';
@endphp
@section('title', $ihale->from_city . ' - ' . $ihale->to_city . ' Arası ' . $ihaleServiceLabel)
@section('meta_description', $ihaleMetaDesc)
@section('canonical_url', route('ihaleler.show', $ihale))

@php
    $breadcrumbItems = [
        ['name' => 'Anasayfa', 'url' => route('home')],
        ['name' => 'Açık ihaleler', 'url' => route('ihaleler.index')],
        ['name' => $ihale->from_city . ' - ' . $ihale->to_city, 'url' => null],
    ];
@endphp
@include('partials.structured-data-breadcrumb')

@push('styles')
<style>
.ihale-detail-page .ihale-layout {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
  width: 100%;
  max-width: 100%;
}
@media (min-width: 1024px) {
  .ihale-detail-page .ihale-layout {
    grid-template-columns: 1fr 360px;
    gap: 2rem;
    align-items: start;
  }
  .ihale-detail-page .ihale-sidebar-wrap {
    display: block !important;
  }
}
.ihale-detail-page .ihale-main {
  min-width: 0;
  overflow-wrap: break-word;
  overflow-x: hidden;
}
.ihale-detail-page .ihale-sidebar-wrap {
  min-width: 0;
  position: relative;
}
@media (min-width: 1024px) {
  .ihale-detail-page .ihale-sidebar-wrap {
    display: block !important;
  }
}
.ihale-detail-page .ihale-route-addresses {
  display: grid;
  grid-template-columns: 1fr;
  gap: 0;
}
@media (min-width: 640px) {
  .ihale-detail-page .ihale-route-addresses {
    grid-template-columns: 1fr 1fr;
  }
}
</style>
@endpush

@section('content')
@php
    $photos = $ihale->photos ?? collect();
    $photoUrl = function ($path) {
        return str_starts_with($path, 'http') ? $path : asset('storage/'.$path);
    };
    $serviceLabel = match($ihale->service_type ?? '') {
        'evden_eve_nakliyat' => 'Evden eve nakliyat',
        'sehirlerarasi_nakliyat' => 'Şehirler arası',
        'parca_esya_tasimaciligi' => 'Parça eşya',
        'esya_depolama' => 'Eşya depolama',
        'ofis_tasima' => 'Ofis taşıma',
        default => 'Nakliye',
    };
    $showStickyCta = !auth()->check() || (auth()->user()->isNakliyeci() && auth()->user()->company?->isApproved() && !$nakliyeciVerdiMi);
@endphp

<div class="ihale-detail-page min-h-screen bg-gradient-to-b from-slate-50 to-slate-100/80 dark:from-zinc-950 dark:to-zinc-900 pb-20 sm:pb-8 lg:pb-8">
    {{-- Rota çubuğu --}}
    <div class="relative border-b border-slate-200/80 dark:border-zinc-800 bg-gradient-to-r from-amber-50/80 to-emerald-50/80 dark:from-amber-950/20 dark:to-emerald-950/20 overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-sky-500/10 rounded-bl-full transition-colors pointer-events-none" aria-hidden="true"></div>
        <div class="page-container py-4 sm:py-5 relative">
            <nav class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mb-3">
                <a href="{{ route('ihaleler.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Açık ihaleler</a>
                <span class="mx-1.5">/</span>
                <span class="text-zinc-700 dark:text-zinc-300">{{ $ihale->from_city }} → {{ $ihale->to_city }}</span>
            </nav>
            <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                <div class="flex items-center gap-2 min-w-0 flex-1">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-amber-400 text-zinc-900 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    </span>
                    <div class="min-w-0">
                        <p class="font-bold text-zinc-900 dark:text-white text-lg sm:text-xl truncate">{{ $ihale->from_city }} → {{ $ihale->to_city }}</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $serviceLabel }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/80 dark:bg-zinc-800/80 px-4 py-2 text-sm font-semibold text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    {{ $ihale->teklifler_count }} teklif
                </span>
            </div>
        </div>
    </div>

    {{-- Fotoğraflar (galeri: tıklanınca lightbox açılır) --}}
    @if($photos->count() > 0)
        <div class="border-b border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/95">
            <div class="page-container py-3 sm:py-4">
                <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide snap-x snap-mandatory" id="ihale-photo-strip" role="list">
                    @foreach($photos as $index => $photo)
                        <button type="button" class="ihale-gallery-thumb shrink-0 w-[75%] max-w-[280px] sm:w-52 rounded-xl overflow-hidden ring-1 ring-slate-200/80 dark:ring-zinc-700 bg-slate-100 dark:bg-zinc-800 h-36 sm:h-32 snap-center hover:ring-2 hover:ring-emerald-400/50 transition-shadow cursor-pointer border-0 p-0 text-left" data-index="{{ $index }}" data-src="{{ $photoUrl($photo->path) }}" aria-label="Fotoğraf {{ $index + 1 }} / {{ $photos->count() }}">
                            <img src="{{ $photoUrl($photo->path) }}" alt="İhale fotoğrafı {{ $index + 1 }}" class="w-full h-full object-cover pointer-events-none">
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- Galeri lightbox modal --}}
        <div id="ihale-gallery-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog" aria-label="İhale fotoğrafları galerisi">
            <div class="absolute inset-0 bg-black/90" id="ihale-gallery-backdrop"></div>
            <button type="button" class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors" id="ihale-gallery-close" aria-label="Kapat">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            @if($photos->count() > 1)
                <button type="button" class="absolute left-2 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors" id="ihale-gallery-prev" aria-label="Önceki">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors" id="ihale-gallery-next" aria-label="Sonraki">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            @endif
            <div class="absolute inset-0 flex items-center justify-center p-4 pt-16 pb-16">
                <img id="ihale-gallery-image" src="" alt="İhale fotoğrafı" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
            </div>
            <p class="absolute bottom-4 left-0 right-0 text-center text-white/80 text-sm" id="ihale-gallery-counter"></p>
        </div>
    @endif

    <div class="page-container py-6 sm:py-8 max-w-5xl mx-auto w-full">
        <div class="ihale-layout">
            {{-- Ana içerik --}}
            <div class="ihale-main space-y-5 sm:space-y-6">

                {{-- Talep sahibi (mobilde görünür, masaüstünde sidebar’da) --}}
                <div class="lg:hidden flex items-center gap-3 rounded-xl bg-white dark:bg-zinc-900/95 p-4 border border-slate-100 dark:border-zinc-800/80">
                    <div class="w-11 h-11 rounded-xl bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-zinc-600 dark:text-zinc-300 font-bold">
                        @if($ihale->user_id && $ihale->user){{ strtoupper(mb_substr($ihale->user->name, 0, 1)) }}@else M @endif
                    </div>
                    <div>
                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Talep sahibi</p>
                        <p class="font-semibold text-zinc-900 dark:text-white">@if($ihale->user_id && $ihale->user){{ $ihale->user->name }}@else{{ $ihale->guest_contact_name ?? 'Misafir' }}@endif</p>
                    </div>
                </div>

                {{-- Çıkış / Varış (ikonlu) --}}
                <div class="rounded-2xl bg-white dark:bg-zinc-900/95 shadow-sm overflow-hidden min-w-0" style="box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.04);">
                    <div class="ihale-route-addresses divide-y sm:divide-y-0 sm:divide-x divide-slate-100 dark:divide-zinc-700/80">
                        <div class="p-5 flex gap-4 min-w-0">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-500/10 text-red-600 dark:text-red-400" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-1">Çıkış yeri</h3>
                                <p class="font-semibold text-zinc-900 dark:text-white break-words">{{ implode(', ', array_filter([$ihale->from_city, $ihale->from_district, $ihale->from_neighborhood])) ?: '-' }}</p>
                                @if($ihale->from_address)<p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1 break-words">{{ $ihale->from_address }}</p>@endif
                            </div>
                        </div>
                        <div class="p-5 flex gap-4 min-w-0">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-1">Varış yeri</h3>
                                <p class="font-semibold text-zinc-900 dark:text-white break-words">{{ implode(', ', array_filter([$ihale->to_city, $ihale->to_district, $ihale->to_neighborhood])) ?: '-' }}</p>
                                @if($ihale->to_address)<p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1 break-words">{{ $ihale->to_address }}</p>@endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($ihale->description)
                    <div class="rounded-2xl bg-white dark:bg-zinc-900/95 p-5 sm:p-6 border border-slate-100 dark:border-zinc-800/80">
                        <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Açıklama</h3>
                        <p class="text-zinc-600 dark:text-zinc-300 leading-relaxed whitespace-pre-line">{{ $ihale->description }}</p>
                    </div>
                @endif

                {{-- Teklif formu --}}
                @auth
                    @if(auth()->user()->isNakliyeci())
                        @if(auth()->user()->company?->isApproved())
                            @if(!$nakliyeciVerdiMi)
                                <div id="teklif-form" class="rounded-2xl bg-white dark:bg-zinc-900/95 p-5 sm:p-6 border border-slate-100 dark:border-zinc-800/80 scroll-mt-24">
                                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">Teklif ver</h3>
                                    <form method="POST" action="{{ route('nakliyeci.ihaleler.teklif.store') }}" class="space-y-4 max-w-md">
                                        @csrf
                                        <input type="hidden" name="ihale_id" value="{{ $ihale->id }}">
                                        <div>
                                            <label for="amount" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Teklif tutarı (₺) *</label>
                                            <input id="amount" type="number" name="amount" value="{{ old('amount') }}" required min="0" step="1" class="input-touch w-full rounded-xl" placeholder="Örn. 15000">
                                            @error('amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                        </div>
                                        <div>
                                            <label for="message" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Mesaj (opsiyonel)</label>
                                            <textarea id="message" name="message" rows="3" class="input-touch w-full rounded-xl min-h-[88px]" placeholder="Not veya öneriniz">{{ old('message') }}</textarea>
                                        </div>
                                        <button type="submit" class="btn-primary inline-flex items-center gap-2 rounded-xl">
                                            Teklifi gönder
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 p-4">
                                    <p class="text-emerald-800 dark:text-emerald-200 font-medium">Bu ihale için zaten teklif verdiniz.</p>
                                </div>
                            @endif
                        @else
                            <div class="rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 p-4">
                                <p class="text-amber-800 dark:text-amber-200 font-medium mb-1">Teklif verebilmek için firma bilgilerinizin onaylanmış olması gerekir.</p>
                                <p class="text-sm text-amber-700 dark:text-amber-300/90 mb-3">Firma kaydınızı tamamlayıp onay bekleyin veya destek ile iletişime geçin.</p>
                                <a href="{{ route('nakliyeci.company.edit') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-amber-700 dark:text-amber-200 hover:underline">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Firma bilgilerini güncelle
                                </a>
                            </div>
                        @endif
                    @endif
                @else
                    <div class="rounded-2xl bg-zinc-100 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 p-4">
                        <p class="text-zinc-600 dark:text-zinc-400">Teklif vermek için <a href="{{ route('login') }}" class="text-emerald-600 dark:text-emerald-400 font-semibold hover:underline">giriş yapın</a> (nakliyeci hesabı gerekir).</p>
                    </div>
                @endauth

                {{-- Verilen teklifler (tutar: sadece admin, ihale sahibi veya ilgili nakliyeci görür) --}}
                <div class="rounded-2xl bg-white dark:bg-zinc-900/95 overflow-hidden border border-slate-100 dark:border-zinc-800/80 min-w-0">
                    <div class="px-4 py-4 border-b border-slate-100 dark:border-zinc-700/80 flex items-center gap-3">
                        <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </span>
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Verilen teklifler ({{ $ihale->teklifler_count }})</h3>
                    </div>
                    @if($ihale->teklifler->count() > 0)
                        <ul class="divide-y divide-slate-100 dark:divide-zinc-700/80">
                            @foreach($ihale->teklifler as $teklif)
                                @php $canSeeAmount = $teklif->canShowAmountTo(auth()->user(), $ihale); $canEdit = $teklif->canRequestUpdateBy(auth()->user()); @endphp
                                <li class="px-4 py-4 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 min-w-0">
                                    <div class="flex items-start gap-3 min-w-0 flex-1">
                                        @if($show_firmalar_page ?? true)
                                        <a href="{{ route('firmalar.show', $teklif->company) }}" class="flex items-center gap-3 min-w-0 flex-1 group">
                                            @else
                                        <div class="flex items-center gap-3 min-w-0 flex-1 group">
                                            @endif
                                            <span class="w-12 h-12 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 dark:text-zinc-400 shrink-0 group-hover:bg-emerald-500/10 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors overflow-hidden">
                                                @if($teklif->company->logo)
                                                    <img src="{{ asset('storage/'.$teklif->company->logo) }}" alt="{{ $teklif->company->name }}" class="w-12 h-12 rounded-xl object-cover">
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                @endif
                                            </span>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex flex-wrap items-center gap-2 min-w-0">
                                                    <span class="font-semibold text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 truncate max-w-full">{{ $teklif->company->name }}</span>
                                                    <span class="text-xs text-zinc-500 dark:text-zinc-400">Teklif: {{ $teklif->created_at->format('d.m.Y H:i') }}</span>
                                                    @if($teklif->hasPendingUpdate())
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                            Onay bekliyor
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($canSeeAmount && $teklif->message)<p class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2 mt-0.5">{{ $teklif->message }}</p>@endif
                                            </div>
                                            @if($show_firmalar_page ?? true)</a>@else</div>@endif
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 sm:pl-2">
                                        @if($canSeeAmount)
                                            <span class="inline-flex items-center gap-1.5 font-bold text-lg text-emerald-600 dark:text-emerald-400">
                                                <svg class="w-5 h-5 text-emerald-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ number_format($teklif->amount, 0, ',', '.') }} ₺
                                            </span>
                                        @else
                                            <span class="text-zinc-400 dark:text-zinc-500 text-sm">—</span>
                                        @endif
                                        @if($canEdit)
                                            <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium bg-slate-100 hover:bg-slate-200 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-zinc-700 dark:text-zinc-200" data-teklif-edit data-teklif-id="{{ $teklif->id }}" data-amount="{{ $teklif->pending_amount ?? $teklif->amount }}" data-message="{{ e($teklif->pending_message ?? $teklif->message) }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Düzenle
                                            </button>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="px-4 py-8 flex flex-col items-center justify-center gap-2 text-center">
                            <span class="w-12 h-12 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-400 dark:text-zinc-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </span>
                            <p class="text-zinc-500 dark:text-zinc-400 text-sm">Henüz teklif verilmedi. İlk teklifi siz verin.</p>
                        </div>
                    @endif
                </div>

                {{-- Teklif düzenleme modalı (nakliyeci kendi teklifini güncelleme talebi; admin onayı gerekir) --}}
                @auth
                    @if(auth()->user()->isNakliyeci() && $nakliyeciVerdiMi)
                        @php $benimTeklif = $ihale->teklifler->firstWhere('company_id', auth()->user()->company?->id); @endphp
                        @if($benimTeklif && $benimTeklif->canRequestUpdateBy(auth()->user()))
                        <div id="teklif-edit-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog" aria-labelledby="teklif-edit-modal-title">
                            <div class="fixed inset-0 bg-black/50" data-teklif-edit-close></div>
                            <div class="fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md max-h-[90vh] overflow-y-auto rounded-2xl bg-white dark:bg-zinc-900 shadow-xl border border-slate-200 dark:border-zinc-700 p-6">
                                <h2 id="teklif-edit-modal-title" class="text-lg font-bold text-zinc-900 dark:text-white mb-1">Teklifi güncelle</h2>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">Değişiklikler admin onayından sonra yansır.</p>
                                <form method="POST" action="{{ route('nakliyeci.ihaleler.teklif.request-update', [$ihale, $benimTeklif]) }}" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="from_public" value="1">
                                    <div>
                                        <label for="teklif-edit-amount" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Teklif tutarı (₺) *</label>
                                        <input id="teklif-edit-amount" type="number" name="amount" value="{{ old('amount', $benimTeklif->pending_amount ?? $benimTeklif->amount) }}" required min="0" step="1" class="input-touch w-full rounded-xl">
                                    </div>
                                    <div>
                                        <label for="teklif-edit-message" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Mesaj (opsiyonel)</label>
                                        <textarea id="teklif-edit-message" name="message" rows="3" class="input-touch w-full rounded-xl min-h-[80px]">{{ old('message', $benimTeklif->pending_message ?? $benimTeklif->message) }}</textarea>
                                    </div>
                                    <div class="flex gap-2 pt-2">
                                        <button type="submit" class="btn-primary rounded-xl">Güncelleme talebi gönder</button>
                                        <button type="button" class="btn-ghost rounded-xl" data-teklif-edit-close>İptal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                    @endif
                @endauth
            </div>

            {{-- Sağ sidebar (masaüstünde sadece) --}}
            <div class="ihale-sidebar-wrap">
                <div class="space-y-5" style="position: sticky; top: 6rem;">
                    {{-- Özet: Mesafe, Tarih, Büyüklük, Hacim --}}
                    <div class="rounded-2xl bg-white dark:bg-zinc-900/95 border border-slate-100 dark:border-zinc-800/80 p-5">
                        <h3 class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">Özet</h3>
                        <dl class="space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-violet-500/10 text-violet-600 dark:text-violet-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6H20M4 12H20M4 18H20"/></svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <dt class="text-xs text-zinc-500 dark:text-zinc-400">Hacim</dt>
                                    <dd class="font-semibold text-zinc-900 dark:text-white text-sm truncate">{{ $ihale->volume_m3 !== null && $ihale->volume_m3 !== '' ? $ihale->volume_m3 . ' m³' : '—' }}</dd>
                                </div>
                            </div>
                            @if($ihale->distance_km)
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <dt class="text-xs text-zinc-500 dark:text-zinc-400">Mesafe</dt>
                                    <dd class="font-semibold text-zinc-900 dark:text-white text-sm">{{ number_format((float)$ihale->distance_km, 0, ',', '.') }} km</dd>
                                </div>
                            </div>
                            @endif
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <dt class="text-xs text-zinc-500 dark:text-zinc-400">Tarih</dt>
                                    <dd class="font-semibold text-zinc-900 dark:text-white text-sm leading-tight">
                                        @if($ihale->move_date || $ihale->move_date_end)
                                            @if($ihale->move_date_end && $ihale->move_date != $ihale->move_date_end)
                                                {{ $ihale->move_date->format('d.m.Y') }} – {{ $ihale->move_date_end->format('d.m.Y') }}
                                            @else
                                                {{ $ihale->move_date?->format('d.m.Y') ?? $ihale->move_date_end?->format('d.m.Y') }}
                                            @endif
                                        @else
                                            Belirtilmedi
                                        @endif
                                    </dd>
                                </div>
                            </div>
                            @if($ihale->room_type)
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <dt class="text-xs text-zinc-500 dark:text-zinc-400">Büyüklük</dt>
                                    <dd class="font-semibold text-zinc-900 dark:text-white text-sm break-words">{{ $ihale->room_type }}</dd>
                                </div>
                            </div>
                            @endif
                        </dl>
                    </div>
                    <div class="rounded-2xl bg-white dark:bg-zinc-900/95 border border-emerald-100 dark:border-emerald-900/40 bg-gradient-to-br from-emerald-50/50 to-white dark:from-emerald-950/20 dark:to-zinc-900 p-6">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">Bu ihale için teklif verin; müşteri sizinle iletişime geçsin.</p>
                        @auth
                            @if(auth()->user()->isNakliyeci() && auth()->user()->company?->isApproved() && !$nakliyeciVerdiMi)
                                <a href="#teklif-form" class="btn-primary w-full justify-center rounded-xl py-3 inline-flex items-center gap-2">
                                    Hemen teklif ver
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-primary w-full justify-center rounded-xl py-3 inline-flex items-center gap-2">
                                Hemen teklif ver
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endauth
                    </div>
                    <div class="rounded-2xl bg-white dark:bg-zinc-900/95 border border-slate-100 dark:border-zinc-800/80 p-5">
                        <h3 class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">Talep sahibi</h3>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-zinc-600 dark:text-zinc-300 font-bold text-lg">
                                @if($ihale->user_id && $ihale->user){{ strtoupper(mb_substr($ihale->user->name, 0, 1)) }}@else M @endif
                            </div>
                            <div>
                                <p class="font-semibold text-zinc-900 dark:text-white">
                                    @if($ihale->user_id && $ihale->user){{ $ihale->user->name }}@else{{ $ihale->guest_contact_name ?? 'Misafir' }}@endif
                                </p>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $ihale->user_id ? 'Üye' : 'Bireysel' }}</p>
                            </div>
                        </div>
                    </div>
                    @php $ihaleShowSidebar = \App\Models\AdZone::getForPagePosition('ihale_show', 'sidebar', 3); @endphp
                    @if($ihaleShowSidebar->isNotEmpty())
                        @foreach($ihaleShowSidebar as $reklam)
                            <div class="rounded-2xl bg-white dark:bg-zinc-900/95 border border-slate-100 dark:border-zinc-800/80 p-4">
                                @if($reklam->isCode()){!! $reklam->kod !!}@else
                                    @if($reklam->link)<a href="{{ $reklam->link }}" target="_blank" rel="noopener noreferrer nofollow" class="block">@endif
                                    @if($reklam->resim)<img src="{{ $reklam->resim }}" alt="{{ $reklam->baslik ?? 'Reklam' }}" class="w-full rounded-lg mb-2 max-h-32 object-cover" loading="lazy">@endif
                                    @if($reklam->baslik)<p class="font-medium text-zinc-900 dark:text-white text-sm">{{ $reklam->baslik }}</p>@endif
                                    @if($reklam->link)</a>@endif
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Mobil: sabit CTA (sadece gerekirse göster) --}}
    @if($showStickyCta)
    <div class="fixed bottom-0 left-0 right-0 z-40 p-3 bg-white/95 dark:bg-zinc-900/95 border-t border-slate-200 dark:border-zinc-800 backdrop-blur sm:hidden safe-bottom">
        @auth
            <a href="#teklif-form" class="btn-primary w-full justify-center rounded-xl py-3.5 inline-flex items-center gap-2 text-base">
                Hemen teklif ver
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-primary w-full justify-center rounded-xl py-3.5 inline-flex items-center gap-2 text-base">
                Hemen teklif ver
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        @endauth
    </div>
    @endif
</div>

@push('scripts')
<script>
(function() {
  var modal = document.getElementById('teklif-edit-modal');
  if (modal) {
    document.querySelectorAll('[data-teklif-edit]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var amount = this.getAttribute('data-amount');
        var message = this.getAttribute('data-message') || '';
        if (amount != null) {
          var inp = document.getElementById('teklif-edit-amount');
          if (inp) inp.value = amount;
        }
        var msgEl = document.getElementById('teklif-edit-message');
        if (msgEl) msgEl.value = message;
        modal.classList.remove('hidden');
      });
    });
    document.querySelectorAll('[data-teklif-edit-close]').forEach(function(btn) {
      btn.addEventListener('click', function() { modal.classList.add('hidden'); });
    });
  }
})();

(function() {
  var galleryModal = document.getElementById('ihale-gallery-modal');
  if (!galleryModal) return;
  var thumbs = document.querySelectorAll('.ihale-gallery-thumb');
  var imgEl = document.getElementById('ihale-gallery-image');
  var counterEl = document.getElementById('ihale-gallery-counter');
  var closeBtn = document.getElementById('ihale-gallery-close');
  var backdrop = document.getElementById('ihale-gallery-backdrop');
  var prevBtn = document.getElementById('ihale-gallery-prev');
  var nextBtn = document.getElementById('ihale-gallery-next');
  var sources = Array.from(thumbs).map(function(t) { return t.getAttribute('data-src'); });
  var currentIndex = 0;
  var total = sources.length;

  function showSlide(idx) {
    currentIndex = idx;
    if (imgEl) imgEl.src = sources[idx];
    if (counterEl) counterEl.textContent = (idx + 1) + ' / ' + total;
  }

  function openGallery(index) {
    currentIndex = index >= 0 && index < total ? index : 0;
    showSlide(currentIndex);
    galleryModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeGallery() {
    galleryModal.classList.add('hidden');
    document.body.style.overflow = '';
  }

  Array.prototype.forEach.call(thumbs, function(btn, i) {
    btn.addEventListener('click', function() { openGallery(i); });
  });
  if (closeBtn) closeBtn.addEventListener('click', closeGallery);
  if (backdrop) backdrop.addEventListener('click', closeGallery);
  if (prevBtn) prevBtn.addEventListener('click', function() { currentIndex = (currentIndex - 1 + total) % total; showSlide(currentIndex); });
  if (nextBtn) nextBtn.addEventListener('click', function() { currentIndex = (currentIndex + 1) % total; showSlide(currentIndex); });

  document.addEventListener('keydown', function(e) {
    if (!galleryModal.classList.contains('hidden')) {
      if (e.key === 'Escape') closeGallery();
      if (e.key === 'ArrowLeft' && prevBtn) { currentIndex = (currentIndex - 1 + total) % total; showSlide(currentIndex); }
      if (e.key === 'ArrowRight' && nextBtn) { currentIndex = (currentIndex + 1) % total; showSlide(currentIndex); }
    }
  });
})();
</script>
@endpush
@endsection
