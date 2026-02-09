@extends('layouts.app')

@section('title', 'NakliyePark - Akıllı Nakliye İhalesi')

@section('content')
{{-- Hero: Modern gradient mesh + glassmorphism --}}
<section class="relative min-h-[75vh] sm:min-h-[80vh] flex items-center overflow-hidden">
    {{-- Background image with subtle zoom --}}
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1601579532067-92d5b0b2a2c0?w=1920" alt="" class="absolute inset-0 w-full h-full object-cover scale-105 animate-hero-zoom">
    </div>
    {{-- Gradient mesh overlay --}}
    <div class="absolute inset-0 z-10 bg-gradient-to-br from-zinc-950/80 via-zinc-900/70 to-emerald-950/50"></div>
    <div class="absolute inset-0 z-10 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(16,185,129,0.15),transparent)]"></div>
    {{-- Subtle grid pattern --}}
    <div class="absolute inset-0 z-10 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.1) 1px, transparent 1px); background-size: 60px 60px;"></div>

    <div class="relative z-20 page-container py-24 lg:py-32 text-center">
        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/5 backdrop-blur-md border border-white/10 text-white/90 text-sm font-medium mb-8 animate-fade-up">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Akıllı nakliye platformu
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-7xl font-bold text-white mb-6 tracking-tight max-w-5xl mx-auto leading-[1.1] animate-fade-up" style="animation-delay: 0.1s;">
            Nakliye ihtiyacın,<br>
            <span class="bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">tek tıkla ihale</span>
        </h1>
        <p class="text-lg sm:text-xl text-white/80 max-w-2xl mx-auto mb-12 leading-relaxed animate-fade-up" style="animation-delay: 0.2s;">
            Üye olmadan ihale başlat, firmalardan teklif al. Hızlı, güvenli, şeffaf.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-up" style="animation-delay: 0.3s;">
            <a href="{{ route('ihale.create') }}" class="group inline-flex items-center gap-3 min-h-[56px] px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-semibold rounded-2xl shadow-xl shadow-emerald-500/25 transition-all duration-300 hover:scale-[1.03] hover:shadow-emerald-500/40">
                İhale Başlat
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <a href="{{ route('ihaleler.index') }}" class="inline-flex items-center gap-2 min-h-[56px] px-8 py-4 bg-white/10 backdrop-blur-md hover:bg-white/20 text-white font-medium rounded-2xl border border-white/20 transition-all duration-300">
                İhalelere göz at
            </a>
        </div>

        {{-- Glassmorphism stats --}}
        <div class="mt-20 flex flex-wrap justify-center gap-6 animate-fade-up" style="animation-delay: 0.4s;">
            <div class="flex items-center gap-4 px-8 py-5 rounded-2xl bg-white/5 backdrop-blur-xl border border-white/10">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div class="text-left">
                    <p class="text-2xl sm:text-3xl font-bold text-white">{{ $stats['ihale_count'] }}</p>
                    <p class="text-sm text-white/70">Açık ihale</p>
                </div>
            </div>
            <div class="flex items-center gap-4 px-8 py-5 rounded-2xl bg-white/5 backdrop-blur-xl border border-white/10">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div class="text-left">
                    <p class="text-2xl sm:text-3xl font-bold text-white">{{ $stats['firma_count'] }}</p>
                    <p class="text-sm text-white/70">Firma</p>
                </div>
            </div>
            <div class="flex items-center gap-4 px-8 py-5 rounded-2xl bg-white/5 backdrop-blur-xl border border-white/10">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8 4-8-4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="text-left">
                    <p class="text-2xl sm:text-3xl font-bold text-white">{{ $stats['defter_count'] }}</p>
                    <p class="text-sm text-white/70">Yük ilanı</p>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
@keyframes hero-zoom {
    0% { transform: scale(1.05); }
    100% { transform: scale(1.08); }
}
.animate-hero-zoom {
    animation: hero-zoom 20s ease-in-out infinite alternate;
}
@keyframes fade-up {
    from { opacity: 0; transform: translateY(24px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-up {
    animation: fade-up 0.7s ease-out forwards;
    opacity: 0;
}
@media (prefers-reduced-motion: reduce) {
    .animate-hero-zoom { animation: none; }
    .animate-fade-up { animation: none; opacity: 1; }
}
</style>
@endpush

{{-- Nasıl çalışır — premium horizontal steps --}}
<section class="section-padding bg-white dark:bg-zinc-900">
    <div class="page-container">
        <div class="section-head text-center">
            <h2 class="section-head-title">Nasıl çalışır?</h2>
            <p class="section-head-sub">Üç adımda nakliye ihtiyacını çöz</p>
        </div>
        <div class="flex flex-col md:flex-row md:items-stretch gap-8 md:gap-0 max-w-4xl mx-auto">
            <div class="md:flex-1 flex flex-col items-center text-center md:border-r md:border-zinc-200 dark:md:border-zinc-700 md:pr-8 lg:pr-12">
                <div class="w-20 h-20 rounded-2xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 mb-5">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-2">Adım 1</span>
                <h3 class="font-bold text-xl text-zinc-900 dark:text-white mb-2">İhale başlat</h3>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm leading-relaxed max-w-xs mx-auto">Taşınacak adres, tarih ve eşya bilgisini gir. Üye olmana gerek yok.</p>
            </div>
            <div class="md:flex-1 flex flex-col items-center text-center md:border-r md:border-zinc-200 dark:md:border-zinc-700 md:px-8 lg:px-12">
                <div class="w-20 h-20 rounded-2xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 mb-5">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-2">Adım 2</span>
                <h3 class="font-bold text-xl text-zinc-900 dark:text-white mb-2">Teklif al</h3>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm leading-relaxed max-w-xs mx-auto">Nakliye firmaları senin için fiyat teklifi sunar. Karşılaştır, sor.</p>
            </div>
            <div class="md:flex-1 flex flex-col items-center text-center md:pl-8 lg:pl-12">
                <div class="w-20 h-20 rounded-2xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 mb-5">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-2">Adım 3</span>
                <h3 class="font-bold text-xl text-zinc-900 dark:text-white mb-2">Nakliyecini seç</h3>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm leading-relaxed max-w-xs mx-auto">Beğendiğin firmayı seç, taşıma tarihini netleştir. İşlem tamam.</p>
            </div>
        </div>
    </div>
</section>

@if($musteriVideolari->count() > 0)
<section class="section-padding bg-zinc-50 dark:bg-zinc-900/50">
    <div class="page-container">
        <div class="section-head">
            <h2 class="section-head-title">Müşteri yorumları</h2>
            <p class="section-head-sub">Nakliye deneyimlerini videoda anlattılar</p>
        </div>
        <div class="flex gap-5 overflow-x-auto pb-4 -mx-4 px-4 sm:mx-0 sm:px-0 scrollbar-hide snap-x snap-mandatory">
            @foreach($musteriVideolari as $review)
                <div class="flex-shrink-0 w-[280px] sm:w-[300px] snap-center">
                    <div class="card-premium-flat relative aspect-[9/16] max-h-[400px] bg-zinc-200 dark:bg-zinc-700">
                        @if($review->video_path)
                            <video class="w-full h-full object-cover" src="{{ asset('storage/'.$review->video_path) }}" controls playsinline preload="metadata"></video>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-zinc-500"><span class="text-4xl">🎬</span></div>
                        @endif
                        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/90 to-transparent text-white text-sm">
                            <p class="font-semibold">{{ $review->user->name ?? 'Misafir' }}</p>
                            <p class="text-white/80 text-xs mt-0.5">{{ $review->company->name ?? '' }}</p>
                            @if($review->comment)<p class="mt-2 line-clamp-2 text-xs text-white/90">{{ Str::limit($review->comment, 80) }}</p>@endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Son açılan ihaleler — premium cards --}}
<section class="section-padding bg-zinc-50 dark:bg-zinc-900/50">
    <div class="page-container">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 section-head">
            <div>
                <h2 class="section-head-title">Son açılan ihaleler</h2>
                <p class="section-head-sub">
                    @if($sonIhale)
                        Son ihale {{ $sonIhale->created_at->format('d.m.Y') }} {{ $sonIhale->created_at->format('H:i') }}'de oluşturuldu
                    @else
                        Güncel nakliye talepleri
                    @endif
                </p>
            </div>
            <a href="{{ route('ihaleler.index') }}" class="text-emerald-600 dark:text-emerald-400 font-semibold hover:underline shrink-0">Tüm ihaleler →</a>
        </div>
        @if($sonIhaleler->count() > 0)
            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6">
                @foreach($sonIhaleler as $ihale)
                    <a href="{{ route('ihaleler.show', $ihale) }}" class="group block">
                        <article class="h-full rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm hover:shadow-lg hover:border-zinc-300 dark:hover:border-zinc-700 transition-all duration-300 flex flex-col">
                            <div class="p-5 sm:p-6 flex-1 flex flex-col">
                                {{-- Rota çizgisi: Nereden ——— Nereye --}}
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <span class="text-sm font-bold text-zinc-800 dark:text-zinc-200 shrink-0 max-w-[28%] sm:max-w-[35%] truncate" title="{{ $ihale->from_city }}">{{ $ihale->from_city }}</span>
                                    <span class="flex-1 flex items-center gap-0.5 min-w-0" aria-hidden="true">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0" title="Çıkış"></span>
                                        <span class="flex-1 h-px bg-gradient-to-r from-zinc-300 via-zinc-400 to-zinc-300 dark:from-zinc-600 dark:via-zinc-500 dark:to-zinc-600 mx-0.5"></span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500 shrink-0" title="Varış"></span>
                                    </span>
                                    <span class="text-sm font-bold text-zinc-800 dark:text-zinc-200 shrink-0 max-w-[28%] sm:max-w-[35%] truncate text-right" title="{{ $ihale->to_city }}">{{ $ihale->to_city }}</span>
                                </div>
                                <div class="mt-3 flex items-center justify-between gap-3">
                                    @if($ihale->service_type)
                                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">
                                            {{ (\App\Models\Ihale::serviceTypeLabels())[$ihale->service_type] ?? $ihale->service_type }}
                                        </p>
                                    @else
                                        <span></span>
                                    @endif
                                    <span class="shrink-0 inline-flex items-center gap-1 rounded-full bg-zinc-100 dark:bg-zinc-800 px-2.5 py-1 text-xs font-semibold text-zinc-600 dark:text-zinc-300">
                                        {{ $ihale->teklifler_count }} teklif
                                    </span>
                                </div>
                                <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-zinc-500 dark:text-zinc-400">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                        {{ $ihale->volume_m3 }} m³
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $ihale->move_date ? $ihale->move_date->format('d.m.Y') : 'Tarih yok' }}
                                    </span>
                                </div>
                                @if($ihale->description)
                                    <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2 flex-1">{{ Str::limit($ihale->description, 90) }}</p>
                                @endif
                                <p class="mt-4 text-sm font-semibold text-zinc-600 dark:text-zinc-300 group-hover:text-zinc-900 dark:group-hover:text-white group-hover:underline transition-colors">Detay ve teklif ver →</p>
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>
        @else
            <div class="card-premium-flat p-12 text-center">
                <p class="text-zinc-500">Henüz açık ihale yok.</p>
                <a href="{{ route('ihale.create') }}" class="text-emerald-600 font-semibold mt-2 inline-block hover:underline">İlk ihale sen başlat</a>
            </div>
        @endif
    </div>
</section>

{{-- Nakliye firmaları — premium --}}
@if($firmalar->count() > 0)
<section class="section-padding bg-white dark:bg-zinc-900">
    <div class="page-container">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 section-head">
            <div>
                <h2 class="section-head-title">Nakliye firmaları</h2>
                <p class="section-head-sub">Onaylı taşıma firmaları</p>
            </div>
            <a href="{{ route('firmalar.index') }}" class="text-emerald-600 dark:text-emerald-400 font-semibold hover:underline shrink-0">Tüm firmalar →</a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($firmalar as $firma)
                <a href="{{ route('firmalar.show', $firma) }}" class="group block">
                    <article class="card-premium p-6 sm:p-7 flex items-start gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 flex items-center justify-center text-2xl font-bold text-emerald-600 dark:text-emerald-400 shrink-0 ring-1 ring-emerald-500/20">
                            {{ mb_substr($firma->name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-lg text-zinc-900 dark:text-white group-hover:text-emerald-600 transition-colors">{{ $firma->name }}</h3>
                            @if($firma->city)
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ $firma->city }}
                                </p>
                            @endif
                            @if($firma->description)
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-3 line-clamp-2">{{ Str::limit($firma->description, 90) }}</p>
                            @endif
                        </div>
                        <svg class="w-5 h-5 text-zinc-400 group-hover:text-emerald-500 shrink-0 mt-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </article>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Nakliyat defteri --}}
<section class="section-padding bg-zinc-50 dark:bg-zinc-900/50">
    <div class="page-container">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 section-head">
            <div>
                <h2 class="section-head-title">Nakliyat defteri</h2>
                <p class="section-head-sub">
                    @if($sonDefterKaydi)
                        Son yazılım {{ $sonDefterKaydi->created_at->format('d.m.Y') }} {{ $sonDefterKaydi->created_at->format('H:i') }}
                    @else
                        Firmaların yük ilanları
                    @endif
                </p>
            </div>
            <a href="{{ route('defter.index') }}" class="text-emerald-600 dark:text-emerald-400 font-semibold hover:underline shrink-0">Tümü →</a>
        </div>
        @if($defterIlanlari->count() > 0)
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($defterIlanlari as $ilan)
                    <article class="card-premium-flat p-5">
                        <p class="font-bold text-zinc-900 dark:text-white">{{ $ilan->from_city }} → {{ $ilan->to_city }}</p>
                        <p class="text-sm text-zinc-500 mt-1">{{ $ilan->company->name }}</p>
                        <p class="text-xs text-zinc-400 mt-2">{{ $ilan->created_at->format('d.m.Y H:i') }}@if($ilan->volume_m3) · {{ $ilan->volume_m3 }} m³@endif</p>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- Blog — slider --}}
@if($sonBlog->count() > 0)
<section class="section-padding bg-white dark:bg-zinc-900">
    <div class="page-container">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 section-head">
            <div>
                <h2 class="section-head-title">Bloglar</h2>
                <p class="section-head-sub">Nakliye ve taşıma ipuçları</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="blog-slider-prev" class="w-10 h-10 rounded-full border border-zinc-300 dark:border-zinc-600 flex items-center justify-center text-zinc-600 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors" aria-label="Önceki">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" id="blog-slider-next" class="w-10 h-10 rounded-full border border-zinc-300 dark:border-zinc-600 flex items-center justify-center text-zinc-600 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors" aria-label="Sonraki">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <a href="{{ route('blog.index') }}" class="text-emerald-600 dark:text-emerald-400 font-semibold hover:underline ml-2">Tümü</a>
            </div>
        </div>
        <div class="slider-wrap">
            <div id="blog-slider-track" class="slider-track">
                @foreach($sonBlog as $post)
                    <div class="slider-item">
                        <a href="{{ route('blog.show', $post->slug) }}" class="group block">
                            <article class="card-premium h-full">
                                @if($post->image)
                                    <div class="aspect-[16/10] overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                                        <img src="{{ asset('storage/'.$post->image) }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    </div>
                                @else
                                    <div class="aspect-[16/10] bg-gradient-to-br from-emerald-500/15 to-zinc-100 dark:to-zinc-800 flex items-center justify-center">
                                        <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?w=600" alt="" class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-500">
                                    </div>
                                @endif
                                <div class="p-5 sm:p-6">
                                    <span class="text-xs font-medium text-zinc-400">{{ $post->published_at?->format('d M Y') }}</span>
                                    <h3 class="font-bold text-lg text-zinc-900 dark:text-white mt-2 group-hover:text-emerald-600 transition-colors line-clamp-2">{{ $post->title }}</h3>
                                    @if($post->excerpt)
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-2 line-clamp-2">{{ $post->excerpt }}</p>
                                    @endif
                                </div>
                            </article>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@push('scripts')
<script>
(function() {
    var track = document.getElementById('blog-slider-track');
    var prev = document.getElementById('blog-slider-prev');
    var next = document.getElementById('blog-slider-next');
    if (!track || !prev || !next) return;
    var step = 320;
    prev.addEventListener('click', function() { track.scrollBy({ left: -step, behavior: 'smooth' }); });
    next.addEventListener('click', function() { track.scrollBy({ left: step, behavior: 'smooth' }); });
})();
</script>
@endpush
@endif

{{-- Hızlı erişim --}}
<section class="section-padding bg-zinc-50 dark:bg-zinc-900/50">
    <div class="page-container">
        <h2 class="section-head-title mb-8">Hızlı erişim</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-5">
            <a href="{{ route('ihale.create') }}" class="card-premium flex flex-col items-center justify-center min-h-[120px] p-6 text-center group">
                <span class="w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-3 group-hover:scale-110 transition-transform"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg></span>
                <span class="font-semibold text-zinc-900 dark:text-white">İhale başlat</span>
            </a>
            <a href="{{ route('ihaleler.index') }}" class="card-premium flex flex-col items-center justify-center min-h-[120px] p-6 text-center group">
                <span class="w-14 h-14 rounded-2xl bg-sky-100 dark:bg-sky-900/40 flex items-center justify-center text-sky-600 dark:text-sky-400 mb-3 group-hover:scale-110 transition-transform"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></span>
                <span class="font-semibold text-zinc-900 dark:text-white">İhaleler</span>
            </a>
            <a href="{{ route('firmalar.index') }}" class="card-premium flex flex-col items-center justify-center min-h-[120px] p-6 text-center group">
                <span class="w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400 mb-3 group-hover:scale-110 transition-transform"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></span>
                <span class="font-semibold text-zinc-900 dark:text-white">Firmalar</span>
            </a>
            <a href="{{ route('pazaryeri.index') }}" class="card-premium flex flex-col items-center justify-center min-h-[120px] p-6 text-center group">
                <span class="w-14 h-14 rounded-2xl bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center text-violet-600 dark:text-violet-400 mb-3 group-hover:scale-110 transition-transform"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg></span>
                <span class="font-semibold text-zinc-900 dark:text-white">Pazaryeri</span>
            </a>
        </div>
    </div>
</section>
@endsection
