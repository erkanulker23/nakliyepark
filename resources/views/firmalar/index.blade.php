@extends('layouts.app')

@section('title', 'Nakliye Firmaları - NakliyePark')
@section('meta_description', 'Onaylı nakliye firmaları listesi. Evden eve nakliyat, yük taşıma ve lojistik firmalarına göz atın, değerlendirmeleri okuyun ve doğrudan teklif alın.')

@section('content')
<div class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
    {{-- Başlık alanı: sade --}}
    <section class="border-b border-zinc-200/70 dark:border-zinc-800/70 bg-white dark:bg-zinc-900/50">
        <div class="page-container py-8 sm:py-10">
            <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">
                Nakliye firmaları
            </h1>
            <p class="text-zinc-500 dark:text-zinc-400 mt-1 text-sm sm:text-base">
                Onaylı taşıma firmalarına göz atın, profillerini inceleyin.
            </p>

            {{-- Arama ve filtreler --}}
            <form action="{{ route('firmalar.index') }}" method="get" class="mt-6 space-y-3">
                <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:flex-wrap">
                    <div class="relative flex-1 min-w-0 sm:max-w-sm">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Firma veya şehir ara…" class="w-full h-11 pl-10 pr-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 text-sm">
                    </div>
                    <select name="city" class="h-11 px-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 text-zinc-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500">
                        <option value="">Tüm şehirler</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ ($filters['city'] ?? '') == $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                    <select name="service" class="h-11 px-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 text-zinc-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500">
                        <option value="">Tüm hizmetler</option>
                        @foreach($serviceLabels as $key => $label)
                            <option value="{{ $key }}" {{ ($filters['service'] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="h-11 px-5 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-500 transition-colors">
                        Filtrele
                    </button>
                    @if(!empty($filters['q']) || !empty($filters['city']) || !empty($filters['service']))
                        <a href="{{ route('firmalar.index') }}" class="h-11 inline-flex items-center text-sm text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300">Filtreleri temizle</a>
                    @endif
                </div>
            </form>
        </div>
    </section>

    <div class="page-container py-6 sm:py-8">
        @if($firmalar->count() > 0)
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-5">
                <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $firmalar->total() }}</span> firma listeleniyor
            </p>

            {{-- 2 sütun grid: mobilde 1, sm+ 2 yan yana --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                @foreach($firmalar as $firma)
                    <a href="{{ route('firmalar.show', $firma) }}" class="group flex flex-col rounded-2xl bg-white dark:bg-zinc-900/80 border border-zinc-200/70 dark:border-zinc-800/70 p-5 sm:p-6 hover:border-emerald-300/70 dark:hover:border-emerald-700/50 hover:shadow-md hover:shadow-zinc-200/50 dark:hover:shadow-none transition-all duration-200">
                        <div class="flex gap-4">
                            @if($firma->logo && $firma->logo_approved_at && trim($firma->logo) !== '')
                                <img src="{{ asset('storage/'.$firma->logo) }}" alt="{{ $firma->name }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover shrink-0 border border-zinc-100 dark:border-zinc-800 shadow-sm">
                            @else
                                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-emerald-500 flex items-center justify-center text-2xl sm:text-3xl font-bold text-white shrink-0 shadow-sm" aria-hidden="true">
                                    {{ mb_substr(trim($firma->name) ?: 'F', 0, 1) }}
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <h2 class="font-semibold text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors truncate pr-1">
                                    {{ $firma->name }}
                                </h2>
                                @if($firma->city)
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-zinc-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        {{ $firma->city }}
                                    </p>
                                @endif
                                <div class="mt-2 flex items-center gap-2 flex-wrap">
                                    @include('partials.company-package-badge', ['firma' => $firma])
                                    @if($firma->reviews_count > 0)
                                        <span class="text-sm text-zinc-500 dark:text-zinc-400">
                                            <span class="text-amber-500">★</span> {{ $firma->reviews_count }} değerlendirme
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($firma->description)
                            <p class="mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800/80 text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2 leading-relaxed">
                                {{ Str::limit($firma->description, 100) }}
                            </p>
                        @endif
                        <div class="mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800/80 flex items-center justify-between">
                            <span class="text-sm text-zinc-500 dark:text-zinc-400">Profili incele</span>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-500/20 transition-colors">
                                <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($firmalar->hasPages())
                <div class="mt-10 flex justify-center">
                    <div class="flex items-center gap-1 rounded-xl bg-white/90 dark:bg-zinc-900/80 border border-zinc-200/60 dark:border-zinc-800/60 p-1.5">
                        {{ $firmalar->links() }}
                    </div>
                </div>
            @endif
        @else
            <div class="max-w-sm mx-auto text-center py-16">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-zinc-200 dark:bg-zinc-800 flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-zinc-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Henüz firma yok</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1 text-sm">Filtreleri değiştirmeyi veya daha sonra tekrar bakmayı deneyin.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mt-6 text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:underline">
                    Anasayfaya dön
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
