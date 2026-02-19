@extends('layouts.app')

@section('title', 'Nakliye Firmaları - NakliyePark')
@section('meta_description', 'Onaylı nakliye firmaları listesi. Evden eve nakliyat, yük taşıma ve lojistik firmalarına göz atın, değerlendirmeleri okuyun ve doğrudan teklif alın.')

@section('content')
<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900/50">
    {{-- Hero (ihaleler ile aynı stil) --}}
    <section class="relative py-12 sm:py-16 overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-500/10 rounded-bl-full group-hover:bg-emerald-500/20 transition-colors pointer-events-none" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-zinc-200/60 via-transparent to-zinc-300/30 dark:from-zinc-800/50 dark:to-zinc-800/20"></div>
        <div class="page-container relative">
            <div class="max-w-2xl">
                <h1 class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white tracking-tight">Nakliye firmaları</h1>
                <p class="text-zinc-600 dark:text-zinc-400 mt-2 text-base sm:text-lg">Onaylı taşıma firmalarına göz atın, profillerini inceleyin.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('firmalar.map') }}" class="btn-primary inline-flex gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Haritadaki nakliyeciler
                    </a>
                    <a href="{{ route('home') }}" class="btn-secondary">Anasayfa</a>
                </div>
            </div>
        </div>
    </section>

    <div class="page-container pb-16 sm:pb-24">
        {{-- Filtreler (ihaleler ile aynı kart yapısı) --}}
        <div class="mb-8 sm:mb-10">
            <h2 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Filtreler</h2>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-3">Firma adı, şehir veya hizmet tipine göre arayın.</p>
            <form method="get" action="{{ route('firmalar.index') }}" class="card p-4 sm:p-5 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <div class="flex flex-wrap items-end gap-3 sm:gap-4">
                    <div class="flex-1 min-w-[180px]">
                        <label for="q" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Ara</label>
                        <input type="search" name="q" id="q" value="{{ $filters['q'] ?? '' }}" placeholder="Firma veya şehir ara…" class="input-touch text-sm py-2.5">
                    </div>
                    <div class="w-full sm:w-48">
                        <label for="city" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Şehir</label>
                        <select name="city" id="city" class="input-touch text-sm py-2.5">
                            <option value="">Tüm şehirler</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ ($filters['city'] ?? '') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full sm:w-48">
                        <label for="service" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Hizmet tipi</label>
                        <select name="service" id="service" class="input-touch text-sm py-2.5">
                            <option value="">Tümü</option>
                            @foreach($serviceLabels as $key => $label)
                                <option value="{{ $key }}" {{ ($filters['service'] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <button type="submit" class="btn-primary py-2.5 px-4 text-sm">Filtrele</button>
                        <a href="{{ route('firmalar.index') }}" class="btn-secondary py-2.5 px-4 text-sm">Temizle</a>
                    </div>
                </div>
            </form>
        </div>

        @if($firmalar->count() > 0)
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">{{ $firmalar->total() }} firma listeleniyor</p>
            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6">
                @foreach($firmalar as $firma)
                    <a href="{{ route('firmalar.show', $firma) }}" class="group block">
                        <article class="relative h-full rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm hover:shadow-lg hover:border-zinc-300 dark:hover:border-zinc-700 transition-all duration-300 flex flex-col">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-500/10 rounded-bl-full group-hover:bg-emerald-500/20 transition-colors pointer-events-none" aria-hidden="true"></div>
                            <div class="p-5 sm:p-6 flex-1 flex flex-col relative">
                                <div class="flex gap-4">
                                    @if($firma->logo && $firma->logo_approved_at && trim($firma->logo) !== '')
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl shrink-0 overflow-hidden flex items-center justify-center bg-white dark:bg-zinc-800 border border-zinc-200/60 dark:border-zinc-700/60 ring-1 ring-zinc-100 dark:ring-zinc-800">
                                            <img src="{{ asset('storage/'.$firma->logo) }}" alt="{{ $firma->name }}" class="w-full h-full object-contain p-0.5">
                                        </div>
                                    @else
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-emerald-500 flex items-center justify-center text-2xl font-bold text-white shrink-0" aria-hidden="true">
                                            {{ mb_substr(trim($firma->name) ?: 'F', 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <h2 class="font-semibold text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors truncate">
                                            {{ $firma->name }}
                                        </h2>
                                        @if($firma->city)
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-zinc-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                                {{ $firma->city }}
                                            </p>
                                        @endif
                                        <div class="mt-2 flex items-center gap-2 flex-wrap">
                                            @include('partials.company-package-badge', ['firma' => $firma])
                                            @if($firma->reviews_count > 0)
                                                <span class="shrink-0 inline-flex items-center gap-1 rounded-full bg-zinc-100 dark:bg-zinc-800 px-2.5 py-1 text-xs font-semibold text-zinc-600 dark:text-zinc-300">
                                                    ★ {{ $firma->reviews_count }} değerlendirme
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if($firma->description)
                                    <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2 flex-1">{{ Str::limit($firma->description, 90) }}</p>
                                @endif
                                <p class="mt-4 text-sm font-semibold text-zinc-600 dark:text-zinc-300 group-hover:text-zinc-900 dark:group-hover:text-white group-hover:underline transition-colors">Profili incele →</p>
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>

            @if($firmalar->hasPages())
                <div class="mt-10">{{ $firmalar->links() }}</div>
            @endif
        @else
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-12 sm:p-16 text-center max-w-lg mx-auto shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Bu kriterlere uygun firma yok</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-2">Filtreleri gevşetin veya haritadaki nakliyecileri inceleyin.</p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('firmalar.index') }}" class="btn-secondary">Filtreleri temizle</a>
                    <a href="{{ route('firmalar.map') }}" class="btn-primary">Haritadaki nakliyeciler</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
