@extends('layouts.app')

@section('title', $company->seo_meta_title ?: ($company->name . ' - Nakliye Firması'))
@section('meta_description', $company->seo_meta_description ?: ($company->name . ' - ' . ($company->city ? $company->city . ' ' : '') . 'nakliye firması. Hizmetler, iletişim ve değerlendirmeler. NakliyePark üzerinden teklif alın.'))
@section('canonical_url', route('firmalar.show', $company))
@section('og_image', ($company->logo && $company->logo_approved_at) ? asset('storage/'.$company->logo) : null)

@php
    $breadcrumbItems = [
        ['name' => 'Anasayfa', 'url' => route('home')],
        ['name' => 'Nakliye firmaları', 'url' => route('firmalar.index')],
        ['name' => $company->name, 'url' => null],
    ];
@endphp
@include('partials.structured-data-breadcrumb')

@if($company->map_visible && $company->live_latitude && $company->live_longitude)
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush
@endif

@push('structured_data')
@php
    $localBusiness = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => $company->name,
        'url' => route('firmalar.show', $company),
        'description' => strip_tags(Str::limit($company->seo_meta_description ?: $company->description, 300)),
        'image' => ($company->logo && $company->logo_approved_at) ? asset('storage/'.$company->logo) : null,
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => $company->city,
            'addressRegion' => $company->district,
            'streetAddress' => $company->address,
        ],
        'telephone' => $company->phone ? \App\Models\Company::formatPhoneForDisplay($company->phone) : null,
        'email' => $company->email,
    ];
    if (!$company->address && !$company->city && !$company->district) unset($localBusiness['address']);
    if (!$company->phone) unset($localBusiness['telephone']);
    if (!$company->email) unset($localBusiness['email']);
    if (!$localBusiness['image']) unset($localBusiness['image']);
    if ($company->google_reviews_fetched_at && $company->google_rating && $company->google_review_count) {
        $localBusiness['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => (float) $company->google_rating,
            'reviewCount' => (int) $company->google_review_count,
            'bestRating' => 5,
        ];
    }
@endphp
<script type="application/ld+json">{!! json_encode($localBusiness, JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
<div class="min-h-screen bg-zinc-50/80 dark:bg-zinc-950">
    {{-- Breadcrumb --}}
    <div class="page-container py-3 sm:py-4">
        <nav class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-zinc-500 dark:text-zinc-400" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Anasayfa</a>
            <span aria-hidden="true" class="text-zinc-300 dark:text-zinc-600">/</span>
            <a href="{{ route('firmalar.index') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Nakliye firmaları</a>
            <span aria-hidden="true" class="text-zinc-300 dark:text-zinc-600">/</span>
            <span class="text-zinc-700 dark:text-zinc-300 font-medium truncate max-w-[160px] sm:max-w-none">{{ $company->name }}</span>
        </nav>
    </div>

    {{-- Hero: tek kart, logo + isim + konum + istatistik + hizmetler --}}
    @php $companyHasPackage = $company->package && trim($company->package) !== ''; @endphp
    <section class="page-container pb-6 sm:pb-8">
        <div class="rounded-2xl sm:rounded-3xl bg-white/90 dark:bg-zinc-900/80 border {{ $companyHasPackage ? 'border-amber-300/60 dark:border-amber-600/40 ring-2 ring-amber-400/15 dark:ring-amber-500/15' : 'border-zinc-200/60 dark:border-zinc-800/60' }} overflow-hidden relative">
            @if($companyHasPackage)
                <div class="absolute top-0 right-0 z-10 flex items-center gap-1.5 px-3 py-1.5 rounded-bl-2xl bg-gradient-to-r from-amber-500 to-amber-600 text-white text-xs font-bold shadow-lg" aria-hidden="true">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    Gold üye
                </div>
            @endif
            <div class="p-6 sm:p-8 lg:p-10">
                <div class="flex flex-col sm:flex-row sm:items-start gap-6 lg:gap-8">
                    @if($company->logo && $company->logo_approved_at)
                        <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-2xl shrink-0 overflow-hidden flex items-center justify-center bg-white dark:bg-zinc-800 border-2 border-zinc-200/60 dark:border-zinc-700/60 shadow-lg ring-2 ring-white/20 dark:ring-zinc-800/50">
                            <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="w-full h-full object-contain p-1">
                        </div>
                    @else
                        <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-3xl sm:text-4xl font-bold text-white shrink-0 shadow-lg shadow-emerald-500/25 ring-2 ring-white/20">
                            {{ mb_substr($company->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight mb-2 sm:mb-1">{{ $company->name }}</h1>
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            @if($companyHasPackage)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 sm:py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-800 dark:text-amber-200 border border-amber-500/30 shrink-0" title="Paketli üye – öncelikli firma">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    Gold üye
                                </span>
                            @endif
                            @include('partials.company-package-badge', ['company' => $company])
                        </div>
                        @if($company->city || $company->district)
                            <p class="text-zinc-600 dark:text-zinc-400 text-sm sm:text-base flex items-center gap-2 flex-wrap">
                                <svg class="w-4 h-4 text-zinc-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ $company->city }}{{ $company->district ? ', ' . $company->district : '' }}{{ $company->address ? ' · ' . Str::limit($company->address, 45) : '' }}
                            </p>
                        @endif
                        @if(($company->view_count ?? 0) > 0)
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ number_format($company->view_count) }} görüntülenme
                            </p>
                        @endif
                        @if($company->tax_number || $company->tax_office)
                            <div class="mt-3 flex flex-wrap gap-2 sm:gap-3">
                                @if($company->tax_number)
                                    <div class="inline-flex flex-col sm:flex-row sm:items-center gap-0.5 sm:gap-2 px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700 min-w-0">
                                        <span class="text-slate-500 dark:text-slate-400 text-xs font-medium uppercase tracking-wider">Vergi no</span>
                                        <span class="font-mono text-sm font-semibold text-slate-800 dark:text-slate-100 tracking-wide break-all">{{ $company->tax_number }}</span>
                                    </div>
                                @endif
                                @if($company->tax_office)
                                    <div class="inline-flex flex-col sm:flex-row sm:items-center gap-0.5 sm:gap-2 px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700 min-w-0">
                                        <span class="text-slate-500 dark:text-slate-400 text-xs font-medium uppercase tracking-wider">Vergi dairesi</span>
                                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-100 break-words">{{ $company->tax_office }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                @php
                    $serviceLabels = \App\Models\Company::serviceLabels();
                    $companyServices = is_array($company->services ?? null) ? $company->services : [];
                @endphp
                <div class="mt-6 pt-6 border-t border-zinc-200/50 dark:border-zinc-700/50">
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Verdiği hizmetler</p>
                    @if(count($companyServices) > 0)
                        <ul class="flex flex-wrap gap-2">
                            @foreach($companyServices as $key)
                                @if(isset($serviceLabels[$key]))
                                    <li class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 text-sm font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $serviceLabels[$key] }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Henüz eklenmemiş olabilir.</p>
                    @endif
                </div>

                {{-- İstatistikler: güven ve şeffaflık --}}
                <div class="mt-6 pt-6 border-t border-zinc-200/50 dark:border-zinc-700/50">
                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">Platform performansı</p>
                    <div class="flex flex-wrap gap-6 sm:gap-10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center">
                                <span class="text-amber-600 dark:text-amber-400 text-lg font-bold">{{ $reviewAvg > 0 ? number_format($reviewAvg, 1, ',', '') : '—' }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-zinc-900 dark:text-white">Ortalama puan</p>
                                @if($reviewCount > 0)
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400" aria-hidden="true">{{ str_repeat('★', (int) round($reviewAvg)) }}{{ str_repeat('☆', 5 - (int) round($reviewAvg)) }} · {{ $reviewCount }} değerlendirme</p>
                                @else
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Henüz değerlendirme yok</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $completedJobsCount }}</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">Tamamlanan iş</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/10 flex items-center justify-center">
                                <span class="text-sky-600 dark:text-sky-400 text-lg font-bold">{{ $totalTeklifCount }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-zinc-900 dark:text-white">Toplam teklif</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">Platformda verilen teklif sayısı</p>
                            </div>
                        </div>
                        @if($totalTeklifCount > 0)
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center">
                                    <span class="text-violet-600 dark:text-violet-400 text-sm font-bold">%{{ $acceptanceRate }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-zinc-900 dark:text-white">Kabul oranı</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Tekliflerin kabul edilme oranı</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="page-container pb-24 sm:pb-28">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <div class="lg:col-span-8 space-y-8 lg:space-y-10">
                @php
                    $hasMapCoords = $company->map_visible && $company->live_latitude && $company->live_longitude;
                    $hasGoogleMapsUrl = !empty($company->google_maps_url);
                    $showMapSection = $hasMapCoords || $hasGoogleMapsUrl;
                @endphp
                @if($showMapSection)
                    <section>
                        <div class="rounded-2xl bg-white/90 dark:bg-zinc-900/80 border border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden">
                            <div class="px-5 py-4 bg-zinc-50/80 dark:bg-zinc-800/40 border-b border-zinc-200/50 dark:border-zinc-700/50 flex flex-wrap items-center justify-between gap-2">
                                <h2 class="font-semibold text-zinc-900 dark:text-white text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    Konum
                                </h2>
                                @if($hasGoogleMapsUrl)
                                    <a href="{{ $company->google_maps_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:underline">
                                        Google Haritada Aç
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                @endif
                            </div>
                            <div class="p-4 sm:p-5">
                                @if($hasMapCoords)
                                    <div id="company-detail-map" class="w-full rounded-xl overflow-hidden bg-zinc-100 dark:bg-zinc-800/50" style="height: 280px;"></div>
                                @endif
                                @if(!$hasMapCoords && $hasGoogleMapsUrl)
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-3">Firmanın konumunu Google Harita üzerinde görmek için yukarıdaki bağlantıyı kullanın.</p>
                                    <a href="{{ $company->google_maps_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        Google Haritada Gör
                                    </a>
                                @endif
                            </div>
                        </div>
                    </section>
                @endif

                @if($company->description)
                    <section>
                        <div class="rounded-2xl bg-white/90 dark:bg-zinc-900/80 border border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden">
                            <div class="px-5 py-4 bg-zinc-50/80 dark:bg-zinc-800/40 border-b border-zinc-200/50 dark:border-zinc-700/50">
                                <h2 class="font-semibold text-zinc-900 dark:text-white text-sm">Firma hakkında</h2>
                            </div>
                            <div class="p-5 sm:p-6">
                                <p class="text-zinc-600 dark:text-zinc-300 leading-relaxed whitespace-pre-line text-sm sm:text-base">{{ $company->description }}</p>
                            </div>
                        </div>
                    </section>
                @endif

                <section>
                    <div class="rounded-2xl bg-white/90 dark:bg-zinc-900/80 border border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden">
                        <div class="px-5 py-4 bg-zinc-50/80 dark:bg-zinc-800/40 border-b border-zinc-200/50 dark:border-zinc-700/50">
                            <h2 class="font-semibold text-zinc-900 dark:text-white text-sm">Vergi bilgileri ve belgeler</h2>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Vergi bilgileri, sözleşme ve belgeler (K1, sigorta, ruhsat)</p>
                        </div>
                        <div class="p-5 sm:p-6 space-y-6">
                            {{-- Vergi bilgileri --}}
                            <div>
                                <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">Vergi bilgileri</p>
                                @if($company->tax_number || $company->tax_office)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @if($company->tax_number)
                                            <div class="rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-800/50 p-4">
                                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Vergi numarası</p>
                                                <p class="font-mono text-lg font-bold text-slate-800 dark:text-slate-100 tracking-wide break-all">{{ $company->tax_number }}</p>
                                            </div>
                                        @endif
                                        @if($company->tax_office)
                                            <div class="rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-800/50 p-4">
                                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Vergi dairesi</p>
                                                <p class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ $company->tax_office }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-sm text-zinc-400 dark:text-zinc-500 italic">Henüz eklenmemiş olabilir.</p>
                                @endif
                            </div>

                            @if($company->tax_number || $company->tax_office || (optional($company->contracts)->count() > 0))
                                <div class="border-t border-zinc-200/60 dark:border-zinc-700/60 pt-5"></div>
                            @endif

                            {{-- Sözleşmeler --}}
                            @if(optional($company->contracts)->count() > 0)
                                <div>
                                    <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">Sözleşmeler</p>
                                    <div class="grid sm:grid-cols-2 gap-3">
                                        @foreach($company->contracts ?? [] as $contract)
                                            <a href="{{ asset('storage/'.$contract->file_path) }}" target="_blank" rel="noopener" class="flex items-center gap-3 p-4 rounded-xl bg-zinc-50/80 dark:bg-zinc-800/40 border border-zinc-200/50 dark:border-zinc-700/50 hover:border-emerald-300/60 dark:hover:border-emerald-700/40 hover:shadow-md hover:shadow-emerald-500/5 transition-all duration-200 group">
                                                <span class="w-11 h-11 rounded-xl bg-white dark:bg-zinc-700/50 border border-zinc-200/60 dark:border-zinc-600/50 flex items-center justify-center text-zinc-500 shrink-0 group-hover:bg-emerald-500/10 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 group-hover:border-emerald-200/60 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                </span>
                                                <div class="min-w-0 flex-1">
                                                    <p class="font-medium text-zinc-900 dark:text-white text-sm truncate">{{ $contract->title }}</p>
                                                    <p class="text-xs text-zinc-500 mt-0.5">PDF · Yeni sekmede aç</p>
                                                </div>
                                                <svg class="w-4 h-4 text-zinc-400 shrink-0 group-hover:text-emerald-500 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Belgeler (K1, sigorta, ruhsat vb.) --}}
                            <div class="{{ (optional($company->contracts)->count() > 0) ? 'border-t border-zinc-200/60 dark:border-zinc-700/60 pt-5' : '' }}">
                                <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">Belgeler</p>
                                @if(optional($company->documents)->count() > 0)
                                    <div class="grid sm:grid-cols-2 gap-3">
                                        @foreach($company->documents ?? [] as $doc)
                                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" rel="noopener" class="flex items-center gap-3 p-4 rounded-xl bg-zinc-50/80 dark:bg-zinc-800/40 border border-zinc-200/50 dark:border-zinc-700/50 hover:border-emerald-300/60 dark:hover:border-emerald-700/40 hover:shadow-md hover:shadow-emerald-500/5 transition-all duration-200 group">
                                                <span class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0 group-hover:bg-emerald-500/20 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                </span>
                                                <div class="min-w-0 flex-1">
                                                    <p class="font-medium text-zinc-900 dark:text-white text-sm">{{ $doc->type_label }}</p>
                                                    @if($doc->title)<p class="text-xs text-zinc-500 truncate">{{ $doc->title }}</p>@endif
                                                    @if($doc->expires_at)
                                                        <p class="text-xs mt-0.5 {{ $doc->expires_at->isPast() ? 'text-red-600 dark:text-red-400 font-medium' : 'text-zinc-500' }}">Bitiş: {{ $doc->expires_at->format('d.m.Y') }}</p>
                                                    @endif
                                                </div>
                                                <svg class="w-4 h-4 text-zinc-400 shrink-0 group-hover:text-emerald-500 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="flex items-center gap-3 py-3 px-4 rounded-xl bg-zinc-50/60 dark:bg-zinc-800/30 border border-dashed border-zinc-200 dark:border-zinc-700/50">
                                        <span class="w-9 h-9 rounded-lg bg-zinc-200/60 dark:bg-zinc-700/50 flex items-center justify-center text-zinc-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        </span>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Henüz belge eklenmemiş olabilir.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="rounded-2xl bg-white/90 dark:bg-zinc-900/80 border border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden">
                        <div class="px-5 py-4 bg-zinc-50/80 dark:bg-zinc-800/40 border-b border-zinc-200/50 dark:border-zinc-700/50">
                            <h2 class="font-semibold text-zinc-900 dark:text-white text-sm">Galeri</h2>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Taşıma araçları ve görseller</p>
                        </div>
                        <div class="p-5 sm:p-6">
                            @if(optional($company->approvedVehicleImages)->count() > 0)
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4" id="company-gallery">
                                    @foreach($company->approvedVehicleImages ?? [] as $i => $img)
                                        <button type="button" class="company-gallery-thumb w-full text-left rounded-xl overflow-hidden aspect-square group border border-zinc-200/50 dark:border-zinc-700/50 hover:border-emerald-400/60 dark:hover:border-emerald-500/50 hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-zinc-900 cursor-pointer"
                                            data-index="{{ $i }}"
                                            data-src="{{ asset('storage/'.$img->path) }}"
                                            data-caption="{{ $img->caption ?? '' }}"
                                            aria-label="Görseli büyüt ({{ $i + 1 }}/{{ optional($company->approvedVehicleImages)->count() }})">
                                            <div class="relative w-full h-full bg-zinc-100 dark:bg-zinc-800">
                                                <img src="{{ asset('storage/'.$img->path) }}" alt="{{ $img->caption ?? 'Galeri' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                                                <span class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                                                <span class="absolute inset-0 flex items-center justify-center">
                                                    <span class="w-12 h-12 rounded-full bg-white/95 dark:bg-zinc-800/95 flex items-center justify-center text-zinc-700 dark:text-zinc-200 opacity-0 group-hover:opacity-100 scale-75 group-hover:scale-100 transition-all duration-300 shadow-lg">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                                    </span>
                                                </span>
                                            </div>
                                            @if($img->caption)
                                                <p class="p-2.5 text-xs text-zinc-600 dark:text-zinc-400 text-center bg-zinc-50/90 dark:bg-zinc-800/90 border-t border-zinc-200/50 dark:border-zinc-700/50 truncate">{{ $img->caption }}</p>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                                {{-- Lightbox: div tabanlı overlay (dialog yerine - Safari/mobil uyumluluğu için) --}}
                                <div id="gallery-lightbox" class="fixed inset-0 z-[200] flex-col bg-black" aria-hidden="true" role="dialog" aria-label="Galeri lightbox" style="display: none;">
                                    <div class="absolute inset-0 bg-black" id="lightbox-backdrop"></div>
                                    <button type="button" id="lightbox-close" class="absolute top-4 right-4 z-[210] w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-white/50" aria-label="Kapat">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <button type="button" id="lightbox-prev" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 z-[210] w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-white/50" aria-label="Önceki">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <button type="button" id="lightbox-next" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 z-[210] w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-white/50" aria-label="Sonraki">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                    <div class="absolute inset-0 flex items-center justify-center p-4 pt-16 pb-24 box-border pointer-events-none">
                                        <img id="lightbox-img" src="" alt="" class="max-w-full max-h-full w-auto h-auto object-contain rounded-lg shadow-2xl pointer-events-auto" style="max-height: calc(100vh - 140px);">
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 p-4 pb-safe bg-gradient-to-t from-black/90 to-transparent z-[210] pointer-events-auto">
                                        <p id="lightbox-counter" class="text-center text-sm text-white/90 font-medium"></p>
                                        <p id="lightbox-caption" class="text-center text-sm text-white/80 mt-1 min-h-[1.25rem]"></p>
                                    </div>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-12 px-4 rounded-xl bg-zinc-50/60 dark:bg-zinc-800/30 border border-dashed border-zinc-200 dark:border-zinc-700/50">
                                    <span class="w-14 h-14 rounded-2xl bg-zinc-200/60 dark:bg-zinc-700/50 flex items-center justify-center text-zinc-400 dark:text-zinc-500 mb-3">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </span>
                                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Henüz galeri görseli yok</p>
                                    <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">Firma görselleri eklendiğinde burada listelenecek.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>

                <section>
                    <div class="rounded-2xl bg-white/90 dark:bg-zinc-900/80 border border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden">
                        <div class="px-5 py-4 bg-zinc-50/80 dark:bg-zinc-800/40 border-b border-zinc-200/50 dark:border-zinc-700/50">
                            <h2 class="font-semibold text-zinc-900 dark:text-white text-sm">Değerlendirmeler</h2>
                            @if(optional($company->reviews)->count() > 0)
                                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">{{ $reviewCount }} değerlendirme · {{ number_format($reviewAvg, 1, ',', '') }}/5</p>
                            @endif
                        </div>
                        <div class="p-5 sm:p-6">
                            @if(optional($company->reviews)->count() > 0)
                                <ul class="space-y-3">
                                    @foreach($company->reviews ?? [] as $review)
                                        <li class="rounded-xl bg-zinc-50/60 dark:bg-zinc-800/30 border border-zinc-200/50 dark:border-zinc-700/50 p-4 sm:p-5">
                                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                                <span class="text-amber-500 text-base" aria-label="{{ $review->rating }} yıldız">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                                <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ $review->user?->name ?? 'Misafir' }}</span>
                                            </div>
                                            @if($review->comment)
                                                <p class="mt-2 text-zinc-600 dark:text-zinc-300 leading-relaxed text-sm">{{ $review->comment }}</p>
                                            @endif
                                            @if($review->video_path)
                                                <div class="mt-3">
                                                    <video src="{{ asset('storage/'.$review->video_path) }}" controls class="max-w-full rounded-lg max-h-56" playsinline preload="metadata"></video>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-zinc-500 dark:text-zinc-400 text-sm text-center py-4">Henüz değerlendirme yok. İşiniz tamamlandıktan sonra değerlendirme yapabilirsiniz.</p>
                            @endif
                        </div>
                    </div>
                </section>

                @php
                    $hasGoogleReviews = ($company->google_rating !== null && $company->google_rating > 0) || !empty($company->google_reviews_url);
                    $hasYandexReviews = ($company->yandex_rating !== null && $company->yandex_rating > 0) || !empty($company->yandex_reviews_url);
                    $showExternalReviews = $hasGoogleReviews || $hasYandexReviews;
                @endphp
                @if($showExternalReviews)
                    <section>
                        <div class="rounded-2xl bg-white/90 dark:bg-zinc-900/80 border border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden">
                            <div class="px-5 py-4 bg-zinc-50/80 dark:bg-zinc-800/40 border-b border-zinc-200/50 dark:border-zinc-700/50">
                                <h2 class="font-semibold text-zinc-900 dark:text-white text-sm">Dış platform yorumları</h2>
                                <p class="text-xs mt-1 flex items-center gap-1.5 {{ $company->google_reviews_fetched_at ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-500 dark:text-zinc-400' }}">
                                    @if($company->google_reviews_fetched_at)
                                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Yorumlar ve puan Google'dan alınmıştır
                                    @else
                                        <svg class="w-4 h-4 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                        Yorumlar doğrulanmamıştır
                                    @endif
                                </p>
                            </div>
                            <div class="p-5 sm:p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @if($hasGoogleReviews)
                                        <a href="{{ $company->google_reviews_url ?: $company->google_maps_url ?: '#' }}" target="_blank" rel="noopener noreferrer" class="flex flex-col rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800/50 p-5 hover:border-emerald-300 dark:hover:border-emerald-700/50 hover:shadow-md transition-all group">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center gap-2">
                                                    <img src="https://www.google.com/images/branding/googleg/1x/googleg_standard_color_128dp.png" alt="Google" class="w-8 h-8 rounded-lg object-contain" width="32" height="32">
                                                    <span class="font-semibold text-zinc-900 dark:text-white">Google</span>
                                                </div>
                                                <svg class="w-4 h-4 text-zinc-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </div>
                                            <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $company->google_rating ? number_format($company->google_rating, 1, ',', '') : '—' }}/5</p>
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">{{ $company->google_review_count ? number_format($company->google_review_count) . ' yorum' : 'Yorumları gör' }}</p>
                                        </a>
                                    @endif
                                    @if($hasYandexReviews)
                                        @php $yandexUrl = $company->yandex_reviews_url ?: null; @endphp
                                        @if($yandexUrl)
                                            <a href="{{ $yandexUrl }}" target="_blank" rel="noopener noreferrer" class="flex flex-col rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800/50 p-5 hover:border-emerald-300 dark:hover:border-emerald-700/50 hover:shadow-md transition-all group">
                                        @else
                                            <div class="flex flex-col rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800/50 p-5">
                                        @endif
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-8 h-8 rounded-lg bg-red-500 flex items-center justify-center text-white font-bold text-xs">Y</span>
                                                    <span class="font-semibold text-zinc-900 dark:text-white">Yandex</span>
                                                </div>
                                                @if($yandexUrl)<svg class="w-4 h-4 text-zinc-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>@endif
                                            </div>
                                            <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $company->yandex_rating ? number_format($company->yandex_rating, 1, ',', '') : '—' }}/5</p>
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">{{ $company->yandex_review_count ? number_format($company->yandex_review_count) . ' yorum' : 'Yorumları gör' }}</p>
                                        @if($yandexUrl)</a>@else</div>@endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                @endif
            </div>

            {{-- Sidebar: tek iletişim alanı + CTA --}}
            <aside class="lg:col-span-4 mt-8 lg:mt-0" id="iletisim">
                <div class="lg:sticky lg:top-24 space-y-5">
                    <div class="rounded-2xl bg-white/90 dark:bg-zinc-900/80 border border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden">
                        <div class="px-5 py-4 bg-zinc-50/80 dark:bg-zinc-800/40 border-b border-zinc-200/50 dark:border-zinc-700/50">
                            <h3 class="font-semibold text-zinc-900 dark:text-white text-sm">İletişim</h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Arayın veya mesaj atın</p>
                            @if($company->isEmailVerified() || $company->isPhoneVerified() || $company->isOfficialCompanyVerified())
                                <div class="flex flex-wrap items-center gap-2 mt-3 pt-3 border-t border-zinc-200/50 dark:border-zinc-700/50">
                                    @if($company->isEmailVerified())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20 shrink-0" title="E-posta doğrulanmış">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            Mail onaylı
                                        </span>
                                    @endif
                                    @if($company->isPhoneVerified())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20 shrink-0" title="Telefon doğrulanmış">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            Telefon onaylı
                                        </span>
                                    @endif
                                    @if($company->isOfficialCompanyVerified())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20 shrink-0" title="Resmi şirket bilgileri doğrulanmış">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                            Resmi onaylı
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="p-4 space-y-1">
                            @if($company->phone)
                                <a href="tel:{{ preg_replace('/\D/', '', $company->phone) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-zinc-100/80 dark:hover:bg-zinc-800/40 transition-colors group">
                                    <span class="w-9 h-9 rounded-lg bg-emerald-500/15 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0 group-hover:bg-emerald-500/25 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </span>
                                    <span class="font-medium text-zinc-900 dark:text-white text-sm">{{ \App\Models\Company::formatPhoneForDisplay($company->phone) }}</span>
                                    <span class="text-xs text-zinc-400 ml-auto">Ara</span>
                                </a>
                            @endif
                            @if($company->phone_2)
                                <a href="tel:{{ preg_replace('/\D/', '', $company->phone_2) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-zinc-100/80 dark:hover:bg-zinc-800/40 transition-colors">
                                    <span class="w-9 h-9 rounded-lg bg-zinc-100/80 dark:bg-zinc-700/50 flex items-center justify-center text-zinc-500 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </span>
                                    <span class="font-medium text-zinc-900 dark:text-white text-sm">{{ \App\Models\Company::formatPhoneForDisplay($company->phone_2) }}</span>
                                </a>
                            @endif
                            @if($company->whatsapp)
                                @php
                                    $waDigits = ltrim(preg_replace('/\D/', '', $company->whatsapp), '0');
                                    $wa = (str_starts_with($waDigits, '90') ? $waDigits : '90' . $waDigits);
                                    $waDefaultMessage = 'Merhaba size nakliyepark.com üzerinden ulaşıyorum taşımacılık için sizinle iletişime geçmek istiyorum';
                                @endphp
                                <a href="https://wa.me/{{ $wa }}?text={{ urlencode($waDefaultMessage) }}" target="_blank" rel="noopener" class="flex items-center gap-3 p-3 rounded-xl hover:bg-emerald-500/10 dark:hover:bg-emerald-500/10 transition-colors group">
                                    <span class="w-9 h-9 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    </span>
                                    <span class="font-medium text-zinc-900 dark:text-white text-sm">{{ \App\Models\Company::formatPhoneForDisplay($company->whatsapp) }}</span>
                                    <span class="text-xs text-emerald-600 dark:text-emerald-400 ml-auto">WhatsApp</span>
                                </a>
                            @endif
                            @if($company->email)
                                <a href="mailto:{{ $company->email }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-zinc-100/80 dark:hover:bg-zinc-800/40 transition-colors">
                                    <span class="w-9 h-9 rounded-lg bg-zinc-100/80 dark:bg-zinc-700/50 flex items-center justify-center text-zinc-500 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </span>
                                    <span class="text-sm font-medium text-zinc-900 dark:text-white break-all">{{ $company->email }}</span>
                                </a>
                            @endif
                            @if($company->address || $company->city)
                                <div class="flex items-start gap-3 p-3 rounded-xl border-t border-zinc-200/50 dark:border-zinc-700/50 mt-1 pt-3">
                                    <span class="w-9 h-9 rounded-lg bg-zinc-100/80 dark:bg-zinc-700/50 flex items-center justify-center text-zinc-500 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    </span>
                                    <p class="text-xs text-zinc-600 dark:text-zinc-300 leading-snug">
                                        @if($company->address){{ $company->address }}<br>@endif
                                        {{ $company->district ? $company->district . ', ' : '' }}{{ $company->city ?? '' }}
                                    </p>
                                </div>
                            @endif
                            @if(!$company->phone && !$company->phone_2 && !$company->whatsapp && !$company->email && !$company->address && !$company->city)
                                <p class="p-4 text-sm text-zinc-500 dark:text-zinc-400 text-center">İletişim bilgisi yok</p>
                            @endif
                            @if($company->isEmailVerified() || $company->isPhoneVerified() || $company->isOfficialCompanyVerified())
                                <div class="mt-4 pt-4 border-t border-zinc-200/50 dark:border-zinc-700/50 p-3 rounded-xl bg-emerald-50/80 dark:bg-emerald-950/30 border border-emerald-200/60 dark:border-emerald-800/50">
                                    <p class="text-xs font-semibold text-emerald-800 dark:text-emerald-200 uppercase tracking-wider mb-2">Doğrulama bilgileri</p>
                                    <ul class="space-y-1.5 text-sm text-emerald-800/90 dark:text-emerald-200/90">
                                        @if($company->isEmailVerified())
                                            <li class="flex items-start gap-2 min-w-0">
                                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                                <span class="break-words">E-posta adresi doğrulanmış</span>
                                            </li>
                                        @endif
                                        @if($company->isPhoneVerified())
                                            <li class="flex items-start gap-2 min-w-0">
                                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                                <span class="break-words">Telefon numarası doğrulanmış</span>
                                            </li>
                                        @endif
                                        @if($company->isOfficialCompanyVerified())
                                            <li class="flex items-start gap-2 min-w-0">
                                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                                <span class="break-words">Resmi şirket bilgileri (vergi no / vergi dairesi) doğrulanmış</span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('ihale.create') }}" class="block rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-center font-semibold py-4 px-5 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/30 transition-shadow">
                        Bu firmadan teklif al
                    </a>

                    <a href="{{ route('firmalar.index') }}" class="block text-center text-sm text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">← Tüm firmalara dön</a>
                </div>
            </aside>
        </div>
    </div>

    {{-- Mobil: altta sabit Ara / WhatsApp (sadece mobilde görünür) --}}
    @php
        $mobilePhone = $company->phone;
        $mobileWa = $company->whatsapp;
        $mobileWaNum = $mobileWa ? (str_starts_with(ltrim(preg_replace('/\D/', '', $mobileWa), '0'), '90') ? ltrim(preg_replace('/\D/', '', $mobileWa), '0') : '90' . ltrim(preg_replace('/\D/', '', $mobileWa), '0')) : null;
        $mobileWaMessage = 'Merhaba size nakliyepark.com üzerinden ulaşıyorum taşımacılık için sizinle iletişime geçmek istiyorum';
        $mobileWaUrl = $mobileWaNum ? 'https://wa.me/' . $mobileWaNum . '?text=' . urlencode($mobileWaMessage) : null;
    @endphp
    @if($mobilePhone || $mobileWaUrl)
    <div class="fixed bottom-0 left-0 right-0 z-40 flex gap-2 p-3 bg-white/95 dark:bg-zinc-900/95 backdrop-blur border-t border-zinc-200/60 dark:border-zinc-800/60 lg:hidden safe-bottom">
        @if($mobilePhone)
            <a href="tel:{{ preg_replace('/\D/', '', $company->phone) }}" class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-xl bg-emerald-600 text-white font-semibold text-sm shadow-lg shadow-emerald-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                Ara
            </a>
        @endif
        @if($mobileWaUrl)
            <a href="{{ $mobileWaUrl }}" target="_blank" rel="noopener" class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-xl bg-[#25D366] text-white font-semibold text-sm shadow-lg">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                WhatsApp
            </a>
        @endif
    </div>
    @endif
</div>

@if(optional($company->approvedVehicleImages)->count() > 0)
@push('scripts')
<script>
(function(){
    var overlay = document.getElementById('gallery-lightbox');
    var img = document.getElementById('lightbox-img');
    var captionEl = document.getElementById('lightbox-caption');
    var counterEl = document.getElementById('lightbox-counter');
    var thumbs = document.querySelectorAll('.company-gallery-thumb');
    var total = thumbs ? thumbs.length : 0;
    var currentIndex = 0;
    var isOpen = false;

    if (!overlay || !img) return;

    function isOverlayOpen() {
        return overlay && overlay.style.display === 'flex';
    }

    function ensureClosed() {
        if (overlay) {
            overlay.style.display = 'none';
            overlay.setAttribute('aria-hidden', 'true');
        }
        if (img) img.removeAttribute('src');
        if (document.body) document.body.style.overflow = '';
        isOpen = false;
    }

    function items() {
        if (!thumbs || !thumbs.length) return [];
        return Array.prototype.map.call(thumbs, function(t){
            var thumbImg = t.querySelector('img');
            var src = t.dataset.src || (thumbImg ? thumbImg.src : '');
            return { src: src, caption: t.dataset.caption || '' };
        });
    }
    var data = items();

    function open(index) {
        if (total === 0 || !overlay || !img) return;
        currentIndex = (index + total) % total;
        var item = data[currentIndex];
        if (!item || !item.src) return;
        img.src = item.src;
        img.alt = item.caption || 'Galeri';
        if (captionEl) captionEl.textContent = item.caption || '';
        if (counterEl) counterEl.textContent = (currentIndex + 1) + ' / ' + total;
        overlay.style.display = 'flex';
        overlay.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        isOpen = true;
    }

    function close() {
        ensureClosed();
    }

    ensureClosed();
    window.addEventListener('pageshow', function(e) { if (e.persisted) ensureClosed(); });

    if (thumbs) {
        for (var i = 0; i < thumbs.length; i++) {
            thumbs[i].addEventListener('click', (function(ix){ return function(){ open(ix); }; })(i));
        }
    }
    var backdrop = document.getElementById('lightbox-backdrop');
    if (backdrop) backdrop.addEventListener('click', close);
    var closeBtn = document.getElementById('lightbox-close');
    if (closeBtn) closeBtn.addEventListener('click', close);
    var prevBtn = document.getElementById('lightbox-prev');
    if (prevBtn) prevBtn.addEventListener('click', function(e){ e.stopPropagation(); open(currentIndex - 1); });
    var nextBtn = document.getElementById('lightbox-next');
    if (nextBtn) nextBtn.addEventListener('click', function(e){ e.stopPropagation(); open(currentIndex + 1); });

    document.addEventListener('keydown', function(e){
        if (!isOverlayOpen()) return;
        if (e.key === 'Escape') { close(); return; }
        if (e.key === 'ArrowLeft') open(currentIndex - 1);
        if (e.key === 'ArrowRight') open(currentIndex + 1);
    });
})();
</script>
@endpush
@endif

@if($company->map_visible && $company->live_latitude && $company->live_longitude)
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function() {
    var el = document.getElementById('company-detail-map');
    if (!el || typeof L === 'undefined') return;
    var lat = {{ (float) $company->live_latitude }};
    var lng = {{ (float) $company->live_longitude }};
    var name = {!! json_encode($company->name) !!};
    var map = L.map('company-detail-map').setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>' }).addTo(map);
    L.marker([lat, lng]).addTo(map).bindPopup('<strong>' + name + '</strong>');
})();
</script>
@endpush
@endif
@endsection
