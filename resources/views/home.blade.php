@extends('layouts.app')

@section('title', 'NakliyePark - Akıllı Nakliye İhalesi')
@section('meta_description', 'Nakliye ihtiyacınızı ihale ile çözün. Üye olmadan nakliye ihalesi başlatın, onaylı nakliye firmalarından teklif alın. Evden eve nakliyat, yük taşıma. Hızlı ve güvenli.')

@section('content')
{{-- Hero: Sade, modern — metin sol, illüstrasyon sağ; açık ve koyu mod uyumlu --}}
<section class="relative min-h-[70vh] sm:min-h-[80vh] flex items-center overflow-hidden bg-gradient-to-b from-zinc-100 via-white to-zinc-50 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950">
    <div class="absolute inset-0 z-10 bg-[radial-gradient(ellipse_60%_50%_at_50%_0%,rgba(16,185,129,0.08),transparent)] dark:bg-[radial-gradient(ellipse_60%_50%_at_50%_0%,rgba(16,185,129,0.06),transparent)]"></div>

    <div class="relative z-20 page-container py-16 lg:py-24 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="text-center lg:text-left order-2 lg:order-1">
                <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400/90 mb-5">Akıllı nakliye platformu</p>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-zinc-900 dark:text-white mb-4 tracking-tight max-w-xl mx-auto lg:mx-0 leading-tight">
                    Nakliye ihtiyacın,<br>
                    <span class="text-emerald-600 dark:text-emerald-400">tek tıkla ihale</span>
                </h1>
                <p class="text-lg text-zinc-600 dark:text-zinc-400 max-w-lg mx-auto lg:mx-0 mb-8">
                    Üye olmadan ihale başlat, firmalardan teklif al. Hızlı, güvenli, şeffaf.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 mb-12">
                    <a href="{{ route('ihale.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-12 px-8 bg-emerald-800 hover:bg-emerald-700 dark:bg-emerald-700 dark:hover:bg-emerald-600 text-white font-semibold rounded-xl transition-colors">
                        İhale Başlat
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="{{ route('ihaleler.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center h-12 px-8 text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white font-medium rounded-xl border border-zinc-300 dark:border-zinc-600 hover:border-zinc-400 dark:hover:border-zinc-500 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        İhalelere göz at
                    </a>
                </div>
                <div class="flex flex-wrap justify-center lg:justify-start gap-6 sm:gap-8 text-center lg:text-left">
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tabular-nums">{{ $stats['ihale_count'] }}</p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-500">Açık ihale</p>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tabular-nums">{{ $stats['firma_count'] }}</p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-500">Firma</p>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tabular-nums">{{ $stats['defter_count'] }}</p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-500">Yük ilanı</p>
                    </div>
                </div>
            </div>
            {{-- İllüstrasyon: sadece masaüstünde (lg+) gösterilir, mobilde gizli --}}
            <div class="order-1 lg:order-2 hidden lg:flex justify-center lg:justify-end">
                <img src="{{ asset('icons/hero-illustration.svg') }}" alt="İhale ve nakliye platformu" width="800" height="600" class="w-full max-w-md xl:max-w-lg h-auto opacity-95" fetchpriority="high">
            </div>
        </div>
    </div>
</section>

{{-- Hero CTA: Nakliyeci — geliştirilmiş CTA kartı --}}
<section class="border-y border-zinc-200/80 dark:border-zinc-800 bg-zinc-50/80 dark:bg-zinc-900/80" aria-labelledby="cta-nakliyeci-title">
    <div class="page-container py-6 sm:py-8">
        <div class="max-w-4xl mx-auto rounded-2xl bg-white/80 dark:bg-zinc-900/80 shadow-lg shadow-emerald-500/5 dark:shadow-emerald-500/10 backdrop-blur-sm overflow-hidden">
            <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5 sm:gap-6">
                <div class="min-w-0 flex-1">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <span class="shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white shadow-md">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </span>
                        <div>
                            <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-0.5">Nakliyeci ol</p>
                            <h2 id="cta-nakliyeci-title" class="text-lg sm:text-xl font-bold text-zinc-900 dark:text-white tracking-tight">Açık ihalelere teklif ver, yeni müşterilere ulaş</h2>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Ücretsiz üyelikle platforma katıl: NakliyePark’taki nakliye taleplerine teklif ver, firmanı müşterilerle buluştur.</p>
                            <ul class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">
                                <li class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Açık ihalelere anında teklif
                                </li>
                                <li class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Müşteriler seni görsün
                                </li>
                                <li class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    İşini büyüt
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <a href="{{ route('register') }}" class="group shrink-0 inline-flex items-center justify-center gap-2 min-w-[160px] px-6 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 dark:from-orange-500 dark:to-amber-500 dark:hover:from-orange-400 dark:hover:to-amber-400 text-white text-sm font-semibold shadow-lg shadow-orange-500/30 dark:shadow-orange-500/25 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-orange-500/35 dark:hover:shadow-orange-400/30 ring-2 ring-white/20 dark:ring-white/10">
                    Üyelik oluştur
                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

@php $homeReklamUst = \App\Models\AdZone::getForPagePosition('home', 'ust', 2); @endphp
@if($homeReklamUst->isNotEmpty())
<section class="border-b border-zinc-200/80 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50">
    <div class="page-container py-4 sm:py-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-4xl mx-auto">
            @foreach($homeReklamUst as $reklam)
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden bg-white dark:bg-zinc-800 p-4 min-h-[100px] flex items-center justify-center">
                    @if($reklam->isCode()){!! $reklam->kod !!}@else
                        @if($reklam->link)<a href="{{ $reklam->link }}" target="_blank" rel="noopener noreferrer nofollow" class="block w-full">@endif
                        @if($reklam->resim)<img src="{{ $reklam->resim }}" alt="{{ $reklam->baslik ?? 'Reklam' }}" class="w-full h-24 object-cover rounded-lg mb-2" loading="lazy">@endif
                        @if($reklam->baslik)<p class="font-medium text-zinc-900 dark:text-white">{{ $reklam->baslik }}</p>@endif
                        @if($reklam->link)</a>@endif
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

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

<div id="home-ordered-sections">
{{-- Nasıl çalışır — premium horizontal steps --}}
@if($homeSections['home_show_how_it_works'] ?? true)
<div data-section-key="home_show_how_it_works">
<section class="section-padding relative overflow-hidden bg-white dark:bg-zinc-950 border-b border-zinc-200/60 dark:border-zinc-800/60">
    <div class="absolute inset-0 pointer-events-none opacity-30" aria-hidden="true">
        <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full bg-emerald-300/30 dark:bg-emerald-600/10 blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 rounded-full bg-teal-300/20 dark:bg-teal-600/10 blur-3xl"></div>
    </div>
    <div class="page-container relative z-10">
        <div class="section-head text-center mb-14">
            <span class="inline-block w-12 h-1 rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 mb-4"></span>
            <h2 class="section-head-title">Nasıl çalışır?</h2>
            <p class="section-head-sub">Üç adımda nakliye ihtiyacını çöz</p>
        </div>
        <div class="flex flex-col md:flex-row md:items-stretch gap-8 md:gap-0 max-w-5xl mx-auto">
            <div class="md:flex-1 flex flex-col items-center text-center md:border-r md:border-zinc-200/80 dark:md:border-zinc-700 md:pr-8 lg:pr-12">
                <div class="relative w-24 h-24 rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-xl shadow-emerald-500/35 mb-6 ring-4 ring-emerald-500/10">
                    <span class="absolute -top-1 -right-1 w-8 h-8 rounded-full bg-white dark:bg-zinc-800 text-emerald-600 dark:text-emerald-400 font-bold text-sm flex items-center justify-center shadow-md border-2 border-emerald-500/20">1</span>
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-2">Adım 1</span>
                <h3 class="font-bold text-xl text-zinc-900 dark:text-white mb-2">İhale başlat</h3>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm leading-relaxed max-w-xs mx-auto">Taşınacak adres, tarih ve eşya bilgisini gir. Üye olmana gerek yok.</p>
            </div>
            <div class="md:flex-1 flex flex-col items-center text-center md:border-r md:border-zinc-200/80 dark:md:border-zinc-700 md:px-8 lg:px-12 relative">
                <div class="relative w-24 h-24 rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-xl shadow-emerald-500/35 mb-6 ring-4 ring-emerald-500/10">
                    <span class="absolute -top-1 -right-1 w-8 h-8 rounded-full bg-white dark:bg-zinc-800 text-emerald-600 dark:text-emerald-400 font-bold text-sm flex items-center justify-center shadow-md border-2 border-emerald-500/20">2</span>
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-2">Adım 2</span>
                <h3 class="font-bold text-xl text-zinc-900 dark:text-white mb-2">Teklif al</h3>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm leading-relaxed max-w-xs mx-auto">Nakliye firmaları senin için fiyat teklifi sunar. Karşılaştır, sor.</p>
            </div>
            <div class="md:flex-1 flex flex-col items-center text-center md:pl-8 lg:pl-12">
                <div class="relative w-24 h-24 rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-xl shadow-emerald-500/35 mb-6 ring-4 ring-emerald-500/10">
                    <span class="absolute -top-1 -right-1 w-8 h-8 rounded-full bg-white dark:bg-zinc-800 text-emerald-600 dark:text-emerald-400 font-bold text-sm flex items-center justify-center shadow-md border-2 border-emerald-500/20">3</span>
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-2">Adım 3</span>
                <h3 class="font-bold text-xl text-zinc-900 dark:text-white mb-2">Nakliyecini seç</h3>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm leading-relaxed max-w-xs mx-auto">Beğendiğin firmayı seç, taşıma tarihini netleştir. İşlem tamam.</p>
            </div>
        </div>
    </div>
</section>
</div>
@endif

@php $homeReklamAlt = \App\Models\AdZone::getForPagePosition('home', 'alt', 2); @endphp
@if($homeReklamAlt->isNotEmpty())
<section class="border-t border-zinc-200/80 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50">
    <div class="page-container py-4 sm:py-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-4xl mx-auto">
            @foreach($homeReklamAlt as $reklam)
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden bg-white dark:bg-zinc-800 p-4 min-h-[100px] flex items-center justify-center">
                    @if($reklam->isCode()){!! $reklam->kod !!}@else
                        @if($reklam->link)<a href="{{ $reklam->link }}" target="_blank" rel="noopener noreferrer nofollow" class="block w-full">@endif
                        @if($reklam->resim)<img src="{{ $reklam->resim }}" alt="{{ $reklam->baslik ?? 'Reklam' }}" class="w-full h-24 object-cover rounded-lg mb-2" loading="lazy">@endif
                        @if($reklam->baslik)<p class="font-medium text-zinc-900 dark:text-white">{{ $reklam->baslik }}</p>@endif
                        @if($reklam->link)</a>@endif
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Müşteri yorumları — sade, okunaklı tasarım --}}
@if($homeSections['home_show_customer_experiences'] ?? true)
<div data-section-key="home_show_customer_experiences">
<section class="section-padding bg-zinc-50/80 dark:bg-zinc-900/80 border-t border-zinc-200/60 dark:border-zinc-800/60" id="musteri-videolari">
    <div class="page-container">
        <div class="section-head text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white mb-4">Müşteri Deneyimleri</h2>
            <p class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed">
                Aşağıdaki videolar, NakliyePark üzerinden taşınan gerçek müşterilerimizin deneyimlerini anlatır.
                Hangi firmayı seçeceğinize karar vermeden önce, taşınma sürecinin nasıl yönetildiğini doğrudan kullanıcıların ağzından dinleyin.
            </p>
        </div>

        @if($musteriVideolari->count() > 0)
            <div class="relative max-w-5xl mx-auto mt-10">
                <button type="button" id="video-slider-prev" class="video-slider-btn absolute left-0 top-1/2 -translate-y-1/2 z-20 -translate-x-2 sm:translate-x-0 w-12 h-12 rounded-xl bg-white dark:bg-zinc-800 shadow-md border border-zinc-200 dark:border-zinc-700 flex items-center justify-center text-zinc-600 dark:text-zinc-300 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-200 dark:hover:border-emerald-800 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/50" aria-label="Önceki video">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" id="video-slider-next" class="video-slider-btn absolute right-0 top-1/2 -translate-y-1/2 z-20 translate-x-2 sm:translate-x-0 w-12 h-12 rounded-xl bg-white dark:bg-zinc-800 shadow-md border border-zinc-200 dark:border-zinc-700 flex items-center justify-center text-zinc-600 dark:text-zinc-300 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-200 dark:hover:border-emerald-800 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/50" aria-label="Sonraki video">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

                <div id="video-slider-track" class="flex gap-4 overflow-x-auto overflow-y-visible pb-12 pt-8 px-4 sm:px-16 scrollbar-hide snap-x snap-mandatory scroll-smooth" style="scroll-behavior: smooth; perspective: 1000px;">
                    @foreach($musteriVideolari as $index => $review)
                        <div class="video-slide flex-shrink-0 snap-center transition-all duration-500 ease-out" data-index="{{ $index }}" style="transform-style: preserve-3d;">
                            <div class="video-slide-inner video-slide-container rounded-[2rem] overflow-hidden shadow-2xl transition-all duration-500 ease-out bg-zinc-900">
                                <div class="relative aspect-[9/16]">
                                    @if($review->video_path)
                                        <video class="w-full h-full object-cover video-el" src="{{ Str::startsWith($review->video_path, 'http') ? $review->video_path : asset('storage/'.$review->video_path) }}" playsinline preload="metadata" loop></video>
                                        <button type="button" class="video-play-overlay absolute inset-0 flex items-center justify-center bg-black/10 hover:bg-black/20 transition-colors z-10" aria-label="Oynat">
                                            <span class="video-play-icon w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-black/30 backdrop-blur-sm flex items-center justify-center shadow-2xl border border-white/40 transition-transform hover:scale-110">
                                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-white fill-current drop-shadow-lg" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </span>
                                        </button>

                                        @if($review->comment)
                                            <div class="absolute bottom-6 left-4 right-4 bg-white/95 backdrop-blur-sm p-4 rounded-2xl shadow-xl z-20 transform transition-all duration-500 ease-in-out">
                                                <p class="text-sm sm:text-base text-zinc-800 font-medium leading-snug">
                                                    "{{ Str::limit($review->comment, 100) }}"
                                                </p>
                                            </div>
                                        @endif
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-zinc-400 bg-zinc-800"><span class="text-4xl">🎬</span></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="video-slider-dots" class="flex justify-center gap-2 mt-5 flex-wrap">
                    @foreach($musteriVideolari as $index => $review)
                        <button type="button" class="video-slider-dot focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:ring-offset-2 rounded-full" data-index="{{ $index }}" aria-label="Video {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            </div>
        @else
            <div class="max-w-xl mx-auto mt-10 rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800/50 p-10 text-center">
                <div class="w-16 h-16 rounded-2xl bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center mx-auto mb-4 text-3xl">🎬</div>
                <h3 class="font-semibold text-zinc-900 dark:text-white mb-1">Müşteri yorumları</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Taşınma sonrası değerlendirme yapıp video yükleyen müşterilerimizin deneyimleri burada listelenecek.</p>
            </div>
        @endif
    </div>
</section>
</div>
@endif

@if($musteriVideolari->count() > 0 && ($homeSections['home_show_customer_experiences'] ?? true))
@push('styles')
<style>
/* Müşteri videoları — yeni kart tasarımı: 3D perspektif ve video içi kartlar */
#video-slider-track {
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
#video-slider-track::-webkit-scrollbar {
    display: none;
}
.video-slide {
    width: 280px;
    max-width: 85vw;
    perspective: 1200px;
}
.video-slide .video-slide-inner {
    transform-origin: center;
    transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
}

/* Perspektif Efektleri */
.video-slide.is-prev .video-slide-inner {
    transform: rotateY(20deg) scale(0.9);
    opacity: 0.7;
}
.video-slide.is-next .video-slide-inner {
    transform: rotateY(-20deg) scale(0.9);
    opacity: 0.7;
}
.video-slide.is-far-prev .video-slide-inner {
    transform: rotateY(35deg) scale(0.8);
    opacity: 0.4;
}
.video-slide.is-far-next .video-slide-inner {
    transform: rotateY(-35deg) scale(0.8);
    opacity: 0.4;
}
.video-slide.active .video-slide-inner {
    transform: rotateY(0deg) scale(1.05);
    opacity: 1;
    z-index: 10;
}

.video-play-overlay {
    transition: opacity 0.3s ease;
}
.video-slide-container.playing .video-play-overlay {
    opacity: 0;
    pointer-events: none;
}
.video-slide-container.playing .absolute.bottom-6 {
    transform: translateY(120%);
    opacity: 0;
}

.video-slider-dot {
    width: 0.6rem;
    height: 0.6rem;
    border-radius: 9999px;
    background: rgb(203 213 225);
    transition: all 0.3s ease;
}
.dark .video-slider-dot { background: rgb(71 85 105); }
.video-slider-dot.active {
    width: 2rem;
    background: #10b981;
}

@media (min-width: 640px) {
    .video-slide { width: 320px; }
}
@media (prefers-reduced-motion: reduce) {
    .video-slide, .video-slide .video-slide-inner { transition: none; }
}
</style>
@endpush
@push('scripts')
<script>
(function() {
    var track = document.getElementById('video-slider-track');
    var dots = document.getElementById('video-slider-dots');
    if (!track || !dots) return;
    var slides = track.querySelectorAll('.video-slide');
    var dotBtns = dots.querySelectorAll('.video-slider-dot');
    var total = slides.length;
    if (total === 0) return;

    function setActive(index) {
        index = Math.max(0, Math.min(index, total - 1));
        slides.forEach(function(s, i) {
            s.classList.remove('active', 'is-prev', 'is-next', 'is-far-prev', 'is-far-next');

            if (i === index) {
                s.classList.add('active');
            } else if (i === index - 1) {
                s.classList.add('is-prev');
            } else if (i === index + 1) {
                s.classList.add('is-next');
            } else if (i < index - 1) {
                s.classList.add('is-far-prev');
            } else if (i > index + 1) {
                s.classList.add('is-far-next');
            }

            var ctr = s.querySelector('.video-slide-container');
            var vid = s.querySelector('.video-el');
            if (vid && i !== index) {
                vid.pause();
                if (ctr) ctr.classList.remove('playing');
            }
        });
        dotBtns.forEach(function(d, i) {
            d.classList.toggle('active', i === index);
        });
    }

    function initVideoSlides() {
        slides.forEach(function(slide) {
            var ctr = slide.querySelector('.video-slide-container');
            var vid = slide.querySelector('.video-el');
            var overlay = slide.querySelector('.video-play-overlay');
            if (!vid || !overlay || !ctr) return;
            overlay.addEventListener('click', function() {
                if (!slide.classList.contains('active')) return;
                vid.play();
                ctr.classList.add('playing');
            });
            vid.addEventListener('play', function() { ctr.classList.add('playing'); });
            vid.addEventListener('pause', function() { ctr.classList.remove('playing'); });
            vid.addEventListener('ended', function() { ctr.classList.remove('playing'); });
        });
    }

    function scrollToIndex(index) {
        index = Math.max(0, Math.min(index, total - 1));
        var slide = slides[index];
        if (slide) {
            var left = slide.offsetLeft - (track.offsetWidth / 2) + (slide.offsetWidth / 2);
            track.scrollTo({ left: left, behavior: 'smooth' });
        }
        setActive(index);
    }

    function updateActiveFromScroll() {
        var trackRect = track.getBoundingClientRect();
        var center = trackRect.left + trackRect.width / 2;
        var best = 0, bestDist = Infinity;
        slides.forEach(function(s, i) {
            var r = s.getBoundingClientRect();
            var slideCenter = r.left + r.width / 2;
            var d = Math.abs(slideCenter - center);
            if (d < bestDist) { bestDist = d; best = i; }
        });
        setActive(best);
    }

    var scrollTicking = false;
    track.addEventListener('scroll', function() {
        if (!scrollTicking) {
            requestAnimationFrame(function() {
                updateActiveFromScroll();
                scrollTicking = false;
            });
            scrollTicking = true;
        }
    });

    document.getElementById('video-slider-prev').addEventListener('click', function() {
        var current = Array.from(slides).findIndex(function(s) { return s.classList.contains('active'); });
        scrollToIndex(current <= 0 ? total - 1 : current - 1);
    });
    document.getElementById('video-slider-next').addEventListener('click', function() {
        var current = Array.from(slides).findIndex(function(s) { return s.classList.contains('active'); });
        scrollToIndex(current < 0 ? 0 : (current + 1) % total);
    });

    dotBtns.forEach(function(btn, i) {
        btn.addEventListener('click', function() { scrollToIndex(i); });
    });

    initVideoSlides();
    setActive(0);
    setTimeout(function() { scrollToIndex(0); }, 100);
})();
</script>
@endpush
@endif

{{-- Son açılan ihaleler — sade arka plan, dekoratif şekiller --}}
@if($homeSections['home_show_latest_ihaleler'] ?? true)
<div data-section-key="home_show_latest_ihaleler">
<section class="section-padding relative overflow-hidden bg-white dark:bg-zinc-950 border-t border-zinc-200/60 dark:border-zinc-800/60">
    {{-- Sade arka plan şekilleri --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute top-20 right-10 w-64 h-64 rounded-full bg-emerald-200/30 dark:bg-emerald-500/10 blur-3xl"></div>
        <div class="absolute bottom-20 left-10 w-48 h-48 rounded-full bg-teal-200/25 dark:bg-teal-500/10 blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full border border-emerald-200/20 dark:border-emerald-500/10"></div>
        <div class="absolute top-32 left-1/4 w-2 h-2 rounded-full bg-emerald-400/40 dark:bg-emerald-500/30"></div>
        <div class="absolute bottom-40 right-1/3 w-3 h-3 rounded-full bg-teal-400/30 dark:bg-teal-500/20"></div>
    </div>
    <div class="page-container relative z-10">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 section-head mb-10">
            <div>
                <span class="inline-block w-10 h-1 rounded-full bg-emerald-500 mb-3"></span>
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
                        <article class="h-full rounded-2xl border-2 border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-md hover:shadow-xl hover:border-emerald-200 dark:hover:border-emerald-800/50 hover:-translate-y-0.5 transition-all duration-300 flex flex-col">
                            <div class="p-5 sm:p-6 flex-1 flex flex-col">
                                {{-- Rota çizgisi: Nereden ——— Nereye --}}
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <span class="text-sm font-bold text-zinc-800 dark:text-zinc-200 shrink-0 max-w-[28%] sm:max-w-[35%] truncate" title="{{ $ihale->from_location_text }}">{{ $ihale->from_location_text }}</span>
                                    <span class="flex-1 flex items-center gap-0.5 min-w-0" aria-hidden="true">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0" title="Çıkış"></span>
                                        <span class="flex-1 h-px bg-gradient-to-r from-zinc-300 via-zinc-400 to-zinc-300 dark:from-zinc-600 dark:via-zinc-500 dark:to-zinc-600 mx-0.5"></span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500 shrink-0" title="Varış"></span>
                                    </span>
                                    <span class="text-sm font-bold text-zinc-800 dark:text-zinc-200 shrink-0 max-w-[28%] sm:max-w-[35%] truncate text-right" title="{{ $ihale->to_location_text }}">{{ $ihale->to_location_text }}</span>
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
                <p class="text-zinc-500 dark:text-zinc-400">Henüz açık ihale yok.</p>
                <a href="{{ route('ihale.create') }}" class="text-emerald-600 dark:text-emerald-400 font-semibold mt-2 inline-block hover:underline">İlk ihale sen başlat</a>
            </div>
        @endif
    </div>
</section>
</div>
@endif

{{-- Firmalar haritada — sadece veri varsa göster --}}
@php
$haritadaGoster = $firmalarHaritada->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'city' => $c->city, 'lat' => (float)$c->live_latitude, 'lng' => (float)$c->live_longitude, 'url' => route('firmalar.show', $c)])->values();
@endphp
@if(($show_firmalar_page ?? true) && ($homeSections['home_show_firmalar'] ?? true) && $haritadaGoster->count() > 0)
<div data-section-key="home_show_firmalar">
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<style>
#home-companies-map-wrap { position: relative; border-radius: 1rem; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,.08), 0 0 0 1px rgba(0,0,0,.04); }
#home-companies-map { background: #f4f4f5; min-height: 320px; }
.dark #home-companies-map { background: #27272a; }
#home-companies-map .leaflet-control-zoom { border: none !important; }
#home-companies-map .leaflet-control-zoom a { width: 36px !important; height: 36px !important; line-height: 36px !important; background: #fff !important; color: #374151 !important; border-radius: 10px !important; margin: 6px !important; box-shadow: 0 1px 3px rgba(0,0,0,.1) !important; }
#home-companies-map .leaflet-control-zoom a:hover { background: #059669 !important; color: #fff !important; }
.dark #home-companies-map .leaflet-control-zoom a { background: #27272a !important; color: #a1a1aa !important; }
.dark #home-companies-map .leaflet-control-zoom a:hover { background: #059669 !important; color: #fff !important; }
#home-companies-map .leaflet-control-attribution { font-size: 10px; opacity: .8; }
.home-map-marker { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50% 50% 50% 0; background: linear-gradient(135deg, #059669 0%, #047857 100%); transform: rotate(-45deg); box-shadow: 0 2px 10px rgba(5,150,105,.4); border: 2px solid #fff; }
.home-map-marker-inner { transform: rotate(45deg); color: #fff; font-size: 16px; }
.home-map-badge { position: absolute; top: 12px; left: 12px; z-index: 1000; padding: 6px 12px; border-radius: 10px; background: rgba(255,255,255,.95); font-size: 12px; font-weight: 600; color: #374151; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.dark .home-map-badge { background: rgba(39,39,42,.95); color: #e4e4e7; }
#home-companies-map .leaflet-popup-content-wrapper { border-radius: 12px; padding: 0; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0,0,0,.15); }
#home-companies-map .leaflet-popup-content { margin: 0; min-width: 220px; }
</style>
@endpush
<section class="section-padding bg-white dark:bg-zinc-950 border-t border-zinc-200/60 dark:border-zinc-800/60">
    <div class="page-container">
        <div class="mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Firmalar haritada</h2>
            <p class="text-zinc-500 dark:text-zinc-400 mt-1">
                Konum paylaşan nakliye firmaları haritada. Listeden firmaya tıklayınca haritada konumu gösterilir; işaretçiye tıklayıp firma sayfasına gidebilirsiniz.
            </p>
        </div>
        <div class="grid lg:grid-cols-[1fr_340px] gap-6 lg:gap-8 items-stretch">
            <div class="flex flex-col min-h-[320px]">
                    <div id="home-companies-map-wrap" class="flex-1 rounded-2xl overflow-hidden" style="min-height: 320px;">
                        <div class="home-map-badge">{{ $haritadaGoster->count() }} firma</div>
                        <div id="home-companies-map" class="w-full rounded-2xl" style="height: 320px; min-height: 320px;" data-companies="{{ json_encode($haritadaGoster) }}"></div>
                    </div>
                </div>
                <div class="flex flex-col">
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 overflow-hidden flex flex-col">
                        <div class="px-4 py-3 border-b border-zinc-200 dark:border-zinc-700">
                            <h3 class="font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                Haritadaki firmalar
                            </h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Firmaya tıklayınca haritada tam konumu gösterilir</p>
                        </div>
                        <div class="flex-1 overflow-y-auto divide-y divide-zinc-200 dark:divide-zinc-700 max-h-[320px] lg:max-h-[420px]">
                            @foreach($firmalarHaritada as $index => $c)
                                @php
                                    $lastUpdate = isset($c->live_location_updated_at) ? $c->live_location_updated_at : null;
                                    $isCanli = $lastUpdate && $lastUpdate->diffInMinutes(now()) <= 15;
                                @endphp
                                <div class="flex items-center gap-3 px-4 py-3 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/50 transition-colors group">
                                    <span class="w-9 h-9 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">🚚</span>
                                    <a href="{{ route('firmalar.show', $c) }}" class="min-w-0 flex-1">
                                        <p class="font-medium text-zinc-900 dark:text-white group-hover:text-emerald-600 truncate">{{ $c->name }}</p>
                                        @if($c->city)<p class="text-sm text-zinc-500 dark:text-zinc-400 truncate">{{ $c->city }}</p>@endif
                                    </a>
                                    <button type="button" class="home-map-focus-btn w-8 h-8 rounded-lg border border-zinc-200 dark:border-zinc-600 flex items-center justify-center text-zinc-500 hover:text-emerald-600 hover:border-emerald-400 shrink-0 transition-colors" data-focus-map="{{ $index }}" title="Haritada konumu göster" aria-label="Haritada konumu göster">
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
                </div>
        </div>
    </div>
</section>
</div>
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function() {
    function initMap() {
        var el = document.getElementById('home-companies-map');
        if (!el || typeof L === 'undefined') return;
        var raw = el.getAttribute('data-companies');
        if (!raw) return;
        var companies = [];
        try { companies = JSON.parse(raw); } catch (e) { return; }
        if (!companies.length) return;
        var center = { lat: companies[0].lat, lng: companies[0].lng };
        if (companies.length > 1) {
            var sumLat = 0, sumLng = 0;
            companies.forEach(function(c) { sumLat += c.lat; sumLng += c.lng; });
            center = { lat: sumLat / companies.length, lng: sumLng / companies.length };
        }
        var map = L.map('home-companies-map', { zoomControl: true }).setView([center.lat, center.lng], 6);
        map.zoomControl.setPosition('topright');
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>', maxZoom: 19 }).addTo(map);
        var pinIcon = L.divIcon({
            className: 'home-map-marker',
            html: '<span class="home-map-marker-inner">🚚</span>',
            iconSize: [40, 40],
            iconAnchor: [20, 40]
        });
        var bounds = [];
        window.homeMapMarkers = [];
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
            window.homeMapMarkers.push(m);
            bounds.push([c.lat, c.lng]);
        });
        if (bounds.length > 1) map.fitBounds(bounds, { padding: [40, 40], maxZoom: 11 });
        window.homeMap = map;
        document.querySelectorAll('.home-map-focus-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var i = parseInt(btn.getAttribute('data-focus-map'), 10);
                if (!isNaN(i) && window.homeMapMarkers && window.homeMapMarkers[i]) {
                    var marker = window.homeMapMarkers[i];
                    var latLng = marker.getLatLng();
                    map.setView([latLng.lat, latLng.lng], 15);
                    marker.openPopup();
                }
            });
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initMap, 100);
        });
    } else {
        setTimeout(initMap, 100);
    }
})();
</script>
@endpush
@endif

{{-- Nakliye firmaları — sade, okunaklı kartlar --}}
@if(($show_firmalar_page ?? true) && ($homeSections['home_show_firmalar'] ?? true) && $firmalar->count() > 0)
<div data-section-key="home_show_firmalar">
<section class="section-padding pb-16 sm:pb-20 relative bg-white dark:bg-zinc-950 overflow-hidden">
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-100/30 dark:bg-emerald-950/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none" aria-hidden="true"></div>
    <div class="page-container relative z-10">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
            <div>
                <span class="inline-block w-10 h-1 rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 mb-3"></span>
                <h2 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Nakliye firmaları</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1">Onaylı taşıma firmalarından teklif alın</p>
            </div>
            <a href="{{ route('firmalar.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 text-sm font-medium hover:opacity-90 transition-opacity shrink-0">
                Tüm firmalar
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($firmalar as $firma)
                <a href="{{ route('firmalar.show', $firma) }}" class="group flex items-center gap-4 p-5 sm:p-6 rounded-2xl border-2 border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/80 shadow-sm hover:shadow-lg hover:border-emerald-300 dark:hover:border-emerald-700/50 hover:bg-emerald-50/30 dark:hover:bg-zinc-800/80 transition-all duration-300">
                    @if($firma->logo && $firma->logo_approved_at)
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl shrink-0 overflow-hidden flex items-center justify-center bg-white dark:bg-zinc-800 border border-zinc-200/60 dark:border-zinc-700 shadow-sm">
                            <img src="{{ asset('storage/'.$firma->logo) }}" alt="{{ $firma->name }}" class="w-full h-full object-contain p-1" width="96" height="96" loading="lazy">
                        </div>
                    @else
                        <span class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-emerald-500 flex items-center justify-center text-2xl font-bold text-white shrink-0 shadow-sm">{{ mb_substr($firma->name, 0, 1) }}</span>
                    @endif
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-semibold text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">{{ $firma->name }}</h3>
                            @include('partials.company-package-badge', ['firma' => $firma])
                        </div>
                        @if($firma->city)
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">{{ $firma->city }}</p>
                        @endif
                        @if($firma->description)
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-2 line-clamp-2">{{ Str::limit($firma->description, 70) }}</p>
                        @endif
                    </div>
                    <span class="w-9 h-9 rounded-lg bg-zinc-200/80 dark:bg-zinc-700 flex items-center justify-center text-zinc-500 dark:text-zinc-400 group-hover:bg-emerald-500 group-hover:text-white group-hover:translate-x-0.5 transition-all shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>
</div>
@endif

{{-- Sponsorlarımız — slider, her seferde 3 görünür --}}
@if(($homeSections['home_show_sponsors'] ?? true) && $sponsors->count() > 0)
<div data-section-key="home_show_sponsors">
<section class="py-8 sm:py-10 relative overflow-hidden bg-zinc-50/80 dark:bg-zinc-900/80 border-b border-zinc-200/60 dark:border-zinc-800/60">
    <div class="page-container">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <p class="text-xs font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Sponsorlarımız</p>
            @if($sponsors->count() > 3)
            <div class="flex items-center gap-2">
                <button type="button" id="sponsor-slider-prev" class="w-9 h-9 rounded-full border border-zinc-300 dark:border-zinc-600 flex items-center justify-center text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors" aria-label="Önceki">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" id="sponsor-slider-next" class="w-9 h-9 rounded-full border border-zinc-300 dark:border-zinc-600 flex items-center justify-center text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors" aria-label="Sonraki">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            @endif
        </div>
        <div class="relative -mx-4 sm:mx-0">
            <div id="sponsor-slider-track" class="flex gap-4 sm:gap-6 overflow-x-auto overflow-y-hidden pb-2 scrollbar-hide snap-x snap-mandatory scroll-smooth" style="scroll-behavior: smooth;">
                @foreach($sponsors as $sponsor)
                    @php $link = $sponsor->url ?? '#'; @endphp
                    <div class="sponsor-slide flex-shrink-0 w-[calc(100vw/3-1.5rem)] min-w-[140px] max-w-[200px] sm:min-w-[180px] sm:max-w-[240px] snap-center mx-auto first:ml-4 sm:first:ml-0 last:mr-4 sm:last:mr-0">
                        @if($link !== '#')
                            <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="group block w-full h-full">
                                <div class="flex items-center justify-center h-20 sm:h-24 px-4 py-4 rounded-xl bg-white dark:bg-zinc-800/60 border border-zinc-200/80 dark:border-zinc-700/80 shadow-sm hover:shadow-md hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-all duration-300 hover:-translate-y-0.5">
                                    @if($sponsor->logo)
                                        <img src="{{ asset('storage/'.$sponsor->logo) }}" alt="{{ $sponsor->name }}" class="h-12 sm:h-14 w-full max-w-[180px] object-contain opacity-90 group-hover:opacity-100 transition-opacity">
                                    @else
                                        <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors text-center">{{ $sponsor->name }}</span>
                                    @endif
                                </div>
                            </a>
                        @else
                            <div class="flex items-center justify-center h-20 sm:h-24 px-4 py-4 rounded-xl bg-white/60 dark:bg-zinc-800/40 border border-zinc-200/60 dark:border-zinc-700/60">
                                @if($sponsor->logo)
                                    <img src="{{ asset('storage/'.$sponsor->logo) }}" alt="{{ $sponsor->name }}" class="h-12 sm:h-14 w-full max-w-[180px] object-contain opacity-75">
                                @else
                                    <span class="text-sm font-medium text-zinc-500 dark:text-zinc-500 text-center">{{ $sponsor->name }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
</div>
@if($sponsors->count() > 3)
@push('scripts')
<script>
(function() {
    var track = document.getElementById('sponsor-slider-track');
    var prev = document.getElementById('sponsor-slider-prev');
    var next = document.getElementById('sponsor-slider-next');
    if (!track || !prev || !next) return;
    prev.addEventListener('click', function() { track.scrollBy({ left: -track.offsetWidth, behavior: 'smooth' }); });
    next.addEventListener('click', function() { track.scrollBy({ left: track.offsetWidth, behavior: 'smooth' }); });
})();
</script>
@endpush
@endif
@endif

{{-- Nakliyeci paketleri — anasayfada, farklı arka plan --}}
@if(($homeSections['home_show_pricing'] ?? true) && count($paketler) > 0)
<div data-section-key="home_show_pricing">
<section class="section-padding relative overflow-hidden bg-zinc-50/80 dark:bg-zinc-900/80 border-t border-zinc-200/60 dark:border-zinc-800/60">
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05]" style="background-image: radial-gradient(circle at 2px 2px, currentColor 1px, transparent 0); background-size: 28px 28px;"></div>
    <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-emerald-300/20 dark:bg-emerald-600/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-teal-300/20 dark:bg-teal-600/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
    <div class="page-container relative z-10">
        <div class="section-head text-center">
            <h2 class="section-head-title">Nakliye firması mısınız?</h2>
            <p class="section-head-sub">İhalelere teklif verin, müşterilere ulaşın. Size uygun paketi seçin.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
            @foreach($paketler as $paket)
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800/50 p-6 flex flex-col {{ isset($paket['popular']) && $paket['popular'] ? 'ring-2 ring-emerald-500 dark:ring-emerald-400 border-emerald-500/30' : '' }}">
                    @if(isset($paket['popular']) && $paket['popular'])
                        <span class="inline-block w-fit mx-auto -mt-1 mb-2 px-3 py-0.5 rounded-full bg-emerald-500 text-white text-xs font-semibold">En popüler</span>
                    @endif
                    <h3 class="font-bold text-lg text-zinc-900 dark:text-white text-center">{{ $paket['name'] }}</h3>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 text-center mt-2">{{ number_format($paket['price'], 0, ',', '.') }} ₺<span class="text-sm font-normal text-zinc-500 dark:text-zinc-400">/ay</span></p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-3 text-center">{{ $paket['description'] }}</p>
                    <ul class="mt-4 space-y-2 flex-1">
                        @foreach(array_slice($paket['features'] ?? [], 0, 4) as $feature)
                            <li class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-6 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        @auth
                            @if(auth()->user()->role === 'nakliyeci')
                                <a href="{{ route('nakliyeci.paketler.index') }}" class="block w-full py-3 px-4 text-center font-semibold rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white transition-colors">Paketleri görüntüle</a>
                            @else
                                <a href="{{ route('register') }}" class="block w-full py-3 px-4 text-center font-semibold rounded-xl border-2 border-emerald-500 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500 hover:text-white transition-colors">Firma olarak kayıt ol</a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="block w-full py-3 px-4 text-center font-semibold rounded-xl border-2 border-emerald-500 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500 hover:text-white transition-colors">Firma olarak kayıt ol</a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
</div>
@endif

{{-- Blog — slider, farklı arka plan --}}
@if(($homeSections['home_show_blog'] ?? true) && $sonBlog->count() > 0)
<div data-section-key="home_show_blog">
<section class="section-padding relative overflow-hidden bg-white dark:bg-zinc-950 border-t border-zinc-200/60 dark:border-zinc-800/60">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute top-1/4 left-0 w-80 h-80 rounded-full bg-amber-200/20 dark:bg-amber-500/10 blur-3xl"></div>
        <div class="absolute bottom-1/4 right-0 w-96 h-96 rounded-full bg-indigo-200/20 dark:bg-indigo-500/10 blur-3xl"></div>
        <div class="absolute inset-0 opacity-[0.02] dark:opacity-[0.03]" style="background-image: linear-gradient(45deg, transparent 48%, currentColor 50%, transparent 52%); background-size: 24px 24px;"></div>
    </div>
    <div class="page-container relative z-10">
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
        <div class="slider-wrap overflow-hidden">
            <div id="blog-slider-track" class="slider-track flex gap-5 overflow-x-auto overflow-y-hidden pb-4 scroll-smooth snap-x snap-mandatory scrollbar-hide" style="scroll-behavior: smooth; -webkit-overflow-scrolling: touch;">
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
                                        <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?w=600&q=75" alt="" width="600" height="375" loading="lazy" class="w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-500">
                                    </div>
                                @endif
                                <div class="p-5 sm:p-6">
                                    <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">{{ $post->published_at?->format('d M Y') }}</span>
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
</div>
@push('scripts')
<script>
(function() {
    function initBlogSlider() {
        var track = document.getElementById('blog-slider-track');
        var prev = document.getElementById('blog-slider-prev');
        var next = document.getElementById('blog-slider-next');
        if (!track || !prev || !next) return;

        function getStep() {
            var item = track.querySelector('.slider-item');
            if (!item) return 380;
            var gap = 20;
            return item.getBoundingClientRect().width + gap;
        }

        function scrollPrev() {
            var step = getStep();
            track.scrollBy({ left: -step, behavior: 'smooth' });
        }
        function scrollNext() {
            var step = getStep();
            track.scrollBy({ left: step, behavior: 'smooth' });
        }

        prev.addEventListener('click', scrollPrev);
        next.addEventListener('click', scrollNext);
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBlogSlider);
    } else {
        initBlogSlider();
    }
})();
</script>
@endpush
@endif

</div>{{-- #home-ordered-sections --}}

@push('scripts')
<script>
(function() {
    var container = document.getElementById('home-ordered-sections');
    if (!container) return;
    var order = @json($homeSectionOrder ?? []);
    if (!order.length) return;
    var children = Array.from(container.children);
    var byKey = {};
    children.forEach(function(el) {
        var k = el.getAttribute('data-section-key');
        if (k) { if (!byKey[k]) byKey[k] = []; byKey[k].push(el); }
    });
    order.forEach(function(key) {
        (byKey[key] || []).forEach(function(el) { container.appendChild(el); });
    });
})();
</script>
@endpush

{{-- Sıkça sorulan sorular (anasayfa): Müşteri + Nakliyeci — Hızlı erişim tarzı --}}
@if((isset($faqsHomeMusteri) && $faqsHomeMusteri->isNotEmpty()) || (isset($faqsHomeNakliyeci) && $faqsHomeNakliyeci->isNotEmpty()))
<section class="section-padding relative bg-zinc-50/80 dark:bg-zinc-900/80 border-t border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden" id="sss">
    <div class="absolute inset-0 pointer-events-none opacity-30 dark:opacity-20" aria-hidden="true">
        <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full bg-emerald-200/30 dark:bg-emerald-500/15 blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 rounded-full bg-teal-200/20 dark:bg-teal-500/15 blur-3xl"></div>
    </div>
    <div class="page-container relative z-10">
        <div class="text-center mb-10">
            <span class="inline-block w-12 h-1 rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 mb-4"></span>
            <h2 class="section-head-title">Sıkça sorulan sorular</h2>
            <p class="text-zinc-500 dark:text-zinc-300 mt-2 max-w-xl mx-auto">Müşteri ve nakliyeci için nakliye ve platform hakkında merak ettikleriniz</p>
            <a href="{{ route('faq.index') }}" class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 rounded-xl bg-emerald-800 hover:bg-emerald-700 dark:bg-emerald-700 dark:hover:bg-emerald-600 text-white text-sm font-semibold transition-colors shadow-lg shadow-emerald-500/20 dark:shadow-emerald-500/25">
                Tüm SSS
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid md:grid-cols-2 gap-6 max-w-5xl mx-auto">
            {{-- Müşteri için SSS — emerald kart --}}
            @if(isset($faqsHomeMusteri) && $faqsHomeMusteri->isNotEmpty())
            <div class="relative rounded-2xl bg-white dark:bg-zinc-800/90 border-2 border-zinc-200/80 dark:border-zinc-600 shadow-lg dark:shadow-xl dark:shadow-black/30 hover:shadow-xl dark:hover:shadow-2xl hover:border-emerald-200 dark:hover:border-emerald-600/60 transition-all duration-300 overflow-hidden">
                <span class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/10 dark:bg-emerald-500/20 rounded-bl-full" aria-hidden="true"></span>
                <div class="relative p-6">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-zinc-900 dark:text-white mb-5">
                        <span class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/30 flex items-center justify-center text-emerald-600 dark:text-emerald-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        Müşteri için SSS
                    </h3>
                    <div class="space-y-2">
                        @foreach($faqsHomeMusteri as $faq)
                            <details class="group rounded-xl bg-zinc-50/80 dark:bg-zinc-800 border border-zinc-200/60 dark:border-zinc-600 overflow-hidden">
                                <summary class="flex items-center justify-between gap-3 cursor-pointer list-none py-3 px-4 text-left font-medium text-zinc-800 dark:text-white hover:text-emerald-600 dark:hover:text-white hover:bg-emerald-50/50 dark:hover:bg-zinc-700/80 dark:group-open:bg-zinc-700/80 dark:group-open:text-white transition-colors border-l-4 border-transparent dark:border-zinc-800 dark:group-open:border-emerald-500">
                                    <span class="pr-2 text-sm">{{ $faq->question }}</span>
                                    <span class="shrink-0 w-8 h-8 rounded-lg bg-emerald-100 dark:bg-zinc-600 flex items-center justify-center text-emerald-600 dark:text-white group-open:rotate-180 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </span>
                                </summary>
                                <div class="px-4 pb-3 pt-0">
                                    <p class="text-sm text-zinc-600 dark:text-zinc-200 leading-relaxed pl-3 border-l-2 border-emerald-300 dark:border-emerald-500">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </p>
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            {{-- Nakliyeci için SSS — teal kart --}}
            @if(isset($faqsHomeNakliyeci) && $faqsHomeNakliyeci->isNotEmpty())
            <div class="relative rounded-2xl bg-white dark:bg-zinc-800/90 border-2 border-zinc-200/80 dark:border-zinc-600 shadow-lg dark:shadow-xl dark:shadow-black/30 hover:shadow-xl dark:hover:shadow-2xl hover:border-teal-200 dark:hover:border-teal-600/60 transition-all duration-300 overflow-hidden">
                <span class="absolute top-0 right-0 w-24 h-24 bg-teal-500/10 dark:bg-teal-500/20 rounded-bl-full" aria-hidden="true"></span>
                <div class="relative p-6">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-zinc-900 dark:text-white mb-5">
                        <span class="w-10 h-10 rounded-xl bg-teal-100 dark:bg-teal-500/30 flex items-center justify-center text-teal-600 dark:text-teal-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </span>
                        Nakliyeci için SSS
                    </h3>
                    <div class="space-y-2">
                        @foreach($faqsHomeNakliyeci as $faq)
                            <details class="group rounded-xl bg-zinc-50/80 dark:bg-zinc-800 border border-zinc-200/60 dark:border-zinc-600 overflow-hidden">
                                <summary class="flex items-center justify-between gap-3 cursor-pointer list-none py-3 px-4 text-left font-medium text-zinc-800 dark:text-white hover:text-teal-600 dark:hover:text-white hover:bg-teal-50/50 dark:hover:bg-zinc-700/80 dark:group-open:bg-zinc-700/80 dark:group-open:text-white transition-colors border-l-4 border-transparent dark:border-zinc-800 dark:group-open:border-teal-500">
                                    <span class="pr-2 text-sm">{{ $faq->question }}</span>
                                    <span class="shrink-0 w-8 h-8 rounded-lg bg-teal-100 dark:bg-zinc-600 flex items-center justify-center text-teal-600 dark:text-white group-open:rotate-180 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </span>
                                </summary>
                                <div class="px-4 pb-3 pt-0">
                                    <p class="text-sm text-zinc-600 dark:text-zinc-200 leading-relaxed pl-3 border-l-2 border-teal-300 dark:border-teal-500">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </p>
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- Hızlı erişim --}}
<section class="section-padding relative bg-white dark:bg-zinc-950 border-t border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden">
    <div class="absolute inset-0 pointer-events-none opacity-40" aria-hidden="true">
        <div class="absolute bottom-0 left-0 w-80 h-80 rounded-full bg-teal-200/30 dark:bg-teal-600/10 blur-3xl"></div>
        <div class="absolute top-0 right-0 w-72 h-72 rounded-full bg-amber-200/20 dark:bg-amber-600/10 blur-3xl"></div>
    </div>
    <div class="page-container relative z-10">
        <div class="text-center mb-10">
            <span class="inline-block w-12 h-1 rounded-full bg-gradient-to-r from-teal-500 to-amber-500 mb-4"></span>
            <h2 class="section-head-title">Hızlı erişim</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-5">
            <a href="{{ route('ihale.create') }}" class="relative flex flex-col items-center justify-center min-h-[140px] p-6 rounded-2xl bg-white dark:bg-zinc-900/80 border-2 border-zinc-200/80 dark:border-zinc-800 text-center group shadow-md hover:shadow-xl hover:border-emerald-300 dark:hover:border-emerald-700/50 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <span class="absolute top-0 right-0 w-20 h-20 bg-emerald-500/10 rounded-bl-full group-hover:bg-emerald-500/20 transition-colors"></span>
                <span class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-teal-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-3 group-hover:scale-110 transition-transform"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg></span>
                <span class="relative font-bold text-zinc-900 dark:text-white">İhale başlat</span>
            </a>
            <a href="{{ route('ihaleler.index') }}" class="relative flex flex-col items-center justify-center min-h-[140px] p-6 rounded-2xl bg-white dark:bg-zinc-900/80 border-2 border-zinc-200/80 dark:border-zinc-800 text-center group shadow-md hover:shadow-xl hover:border-sky-300 dark:hover:border-sky-700/50 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <span class="absolute top-0 right-0 w-20 h-20 bg-sky-500/10 rounded-bl-full group-hover:bg-sky-500/20 transition-colors"></span>
                <span class="relative w-16 h-16 rounded-2xl bg-sky-100 dark:bg-sky-900/40 flex items-center justify-center text-sky-600 dark:text-sky-400 mb-3 group-hover:scale-110 transition-transform"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></span>
                <span class="relative font-bold text-zinc-900 dark:text-white">İhaleler</span>
            </a>
            @if($show_firmalar_page ?? true)
            <a href="{{ route('firmalar.index') }}" class="relative flex flex-col items-center justify-center min-h-[140px] p-6 rounded-2xl bg-white dark:bg-zinc-900/80 border-2 border-zinc-200/80 dark:border-zinc-800 text-center group shadow-md hover:shadow-xl hover:border-amber-300 dark:hover:border-amber-700/50 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <span class="absolute top-0 right-0 w-20 h-20 bg-amber-500/10 rounded-bl-full group-hover:bg-amber-500/20 transition-colors"></span>
                <span class="relative w-16 h-16 rounded-2xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400 mb-3 group-hover:scale-110 transition-transform"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></span>
                <span class="relative font-bold text-zinc-900 dark:text-white">Firmalar</span>
            </a>
            @endif
            @if($show_pazaryeri_page ?? true)
            <a href="{{ route('pazaryeri.index') }}" class="relative flex flex-col items-center justify-center min-h-[140px] p-6 rounded-2xl bg-white dark:bg-zinc-900/80 border-2 border-zinc-200/80 dark:border-zinc-800 text-center group shadow-md hover:shadow-xl hover:border-violet-300 dark:hover:border-violet-700/50 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <span class="absolute top-0 right-0 w-20 h-20 bg-violet-500/10 rounded-bl-full group-hover:bg-violet-500/20 transition-colors"></span>
                <span class="relative w-16 h-16 rounded-2xl bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center text-violet-600 dark:text-violet-400 mb-3 group-hover:scale-110 transition-transform"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg></span>
                <span class="relative font-bold text-zinc-900 dark:text-white">Pazaryeri</span>
            </a>
            @endif
        </div>
    </div>
</section>

@endsection
