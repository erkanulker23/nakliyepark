@extends('layouts.app')

@section('title', $company->seo_meta_title ?: ($company->name . ' - Nakliye Firması'))

@section('content')
<div class="min-h-screen bg-[#f8fafc] dark:bg-zinc-950">
    {{-- Breadcrumb --}}
    <div class="page-container py-4 sm:py-5">
        <nav class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-zinc-500 dark:text-zinc-400">
            <a href="{{ route('home') }}" class="link-muted hover:underline">Anasayfa</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('firmalar.index') }}" class="link-muted hover:underline">Nakliye firmaları</a>
            <span aria-hidden="true">/</span>
            <span class="text-zinc-700 dark:text-zinc-300 font-medium truncate max-w-[180px] sm:max-w-none">{{ $company->name }}</span>
        </nav>
    </div>

    {{-- Hero --}}
    <section class="relative pb-8 sm:pb-12">
        <div class="absolute inset-0 bg-gradient-to-b from-emerald-500/6 to-transparent dark:from-emerald-500/8"></div>
        <div class="page-container relative">
            <div class="rounded-2xl sm:rounded-3xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900/90 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8 lg:p-10">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-6 lg:gap-8">
                        @if($company->logo)
                            <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover ring-1 ring-zinc-200/80 dark:ring-zinc-700 shrink-0 shadow-md">
                        @else
                            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-3xl sm:text-4xl font-bold text-white shrink-0 shadow-lg shadow-emerald-500/20">
                                {{ mb_substr($company->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-zinc-900 dark:text-white tracking-tight">{{ $company->name }}</h1>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                    NakliyePark Onaylı
                                </span>
                            </div>
                            @if($company->city || $company->district)
                                <p class="text-zinc-600 dark:text-zinc-400 flex items-center gap-2 flex-wrap">
                                    <svg class="w-5 h-5 text-zinc-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ $company->city }}{{ $company->district ? ', ' . $company->district : '' }}{{ $company->address ? ' · ' . Str::limit($company->address, 50) : '' }}
                                </p>
                            @endif
                            @if($company->tax_number || $company->tax_office)
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2 flex flex-wrap items-center gap-x-4 gap-y-0">
                                    @if($company->tax_number)<span>Vergi no: {{ $company->tax_number }}</span>@endif
                                    @if($company->tax_office)<span>Vergi dairesi: {{ $company->tax_office }}</span>@endif
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- İstatistikler --}}
                    <div class="mt-8 pt-8 border-t border-zinc-200 dark:border-zinc-700/80 grid grid-cols-2 sm:grid-cols-3 gap-6 sm:gap-8">
                        <div class="text-center sm:text-left">
                            <p class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white">{{ $reviewAvg > 0 ? number_format($reviewAvg, 1, ',', '') : '—' }}</p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 flex items-center justify-center sm:justify-start gap-1.5">
                                @if($reviewCount > 0)
                                    <span class="text-amber-500" aria-hidden="true">{{ str_repeat('★', (int) round($reviewAvg)) }}{{ str_repeat('☆', 5 - (int) round($reviewAvg)) }}</span>
                                @endif
                                Ortalama puan
                            </p>
                        </div>
                        <div class="text-center sm:text-left">
                            <p class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white">{{ $reviewCount }}</p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Müşteri değerlendirmesi</p>
                        </div>
                        <div class="text-center sm:text-left col-span-2 sm:col-span-1">
                            <p class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white">{{ $completedJobsCount }}</p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Tamamlanan iş</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="page-container pb-20 sm:pb-28">
        <div class="lg:grid lg:grid-cols-12 lg:gap-10">
            <div class="lg:col-span-8 space-y-10 lg:space-y-12">
                @php
                    $hasContact = $company->phone || $company->phone_2 || $company->whatsapp || $company->email || $company->address || $company->city;
                @endphp
                @if($hasContact)
                    <section>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white tracking-tight mb-4">İletişim bilgileri</h2>
                        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden">
                            <div class="p-5 sm:p-6 space-y-1">
                                @if($company->phone)
                                    <a href="tel:{{ preg_replace('/\D/', '', $company->phone) }}" class="flex items-center gap-4 p-4 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors group">
                                        <span class="w-11 h-11 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 shrink-0 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/30 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        </span>
                                        <span class="font-medium text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">{{ $company->phone }}</span>
                                        <span class="text-xs text-zinc-500 ml-auto">Telefon</span>
                                    </a>
                                @endif
                                @if($company->phone_2)
                                    <a href="tel:{{ preg_replace('/\D/', '', $company->phone_2) }}" class="flex items-center gap-4 p-4 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors group">
                                        <span class="w-11 h-11 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        </span>
                                        <span class="font-medium text-zinc-900 dark:text-white">{{ $company->phone_2 }}</span>
                                        <span class="text-xs text-zinc-500 ml-auto">İkinci telefon</span>
                                    </a>
                                @endif
                                @if($company->whatsapp)
                                    @php $waDigits = ltrim(preg_replace('/\D/', '', $company->whatsapp), '0'); $wa = (str_starts_with($waDigits, '90') ? $waDigits : '90' . $waDigits); @endphp
                                    <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="flex items-center gap-4 p-4 rounded-xl hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors group">
                                        <span class="w-11 h-11 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                        </span>
                                        <span class="font-medium text-zinc-900 dark:text-white">{{ $company->whatsapp }}</span>
                                        <span class="text-xs text-emerald-600 dark:text-emerald-400 ml-auto">WhatsApp</span>
                                    </a>
                                @endif
                                @if($company->email)
                                    <a href="mailto:{{ $company->email }}" class="flex items-center gap-4 p-4 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors group">
                                        <span class="w-11 h-11 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </span>
                                        <span class="font-medium text-zinc-900 dark:text-white break-all">{{ $company->email }}</span>
                                    </a>
                                @endif
                                @if($company->address || $company->city)
                                    <div class="flex items-start gap-4 p-4 rounded-xl border-t border-zinc-100 dark:border-zinc-800">
                                        <span class="w-11 h-11 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        </span>
                                        <p class="text-sm text-zinc-600 dark:text-zinc-300 pt-2">
                                            @if($company->address){{ $company->address }}<br>@endif
                                            {{ $company->district ? $company->district . ', ' : '' }}{{ $company->city ?? '' }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>
                @endif

                @if($company->description)
                    <section>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white tracking-tight mb-4">Firma hakkında</h2>
                        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm p-6 sm:p-7">
                            <p class="text-zinc-600 dark:text-zinc-300 leading-relaxed whitespace-pre-line">{{ $company->description }}</p>
                        </div>
                    </section>
                @endif

                @if($company->contracts->count() > 0 || $company->documents->count() > 0)
                    <section>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white tracking-tight mb-1">Resmi evraklar</h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-5">Firmanın sözleşme ve belgeleri (K1, sigorta, ruhsat vb.)</p>

                        @if($company->contracts->count() > 0)
                            <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-3">Şirket sözleşmeleri</h3>
                            <div class="grid sm:grid-cols-2 gap-4 mb-6">
                                @foreach($company->contracts as $contract)
                                    <a href="{{ asset('storage/'.$contract->file_path) }}" target="_blank" rel="noopener" class="flex items-center gap-4 p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 hover:border-emerald-200 dark:hover:border-emerald-800/50 hover:shadow-md transition-all group">
                                        <span class="w-12 h-12 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 shrink-0 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/30 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-zinc-900 dark:text-white">{{ $contract->title }}</p>
                                            <p class="text-xs text-zinc-500">PDF · Yeni sekmede aç</p>
                                        </div>
                                        <svg class="w-5 h-5 text-zinc-400 shrink-0 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        @if($company->documents->count() > 0)
                            <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-3">Belgeler (K1, yeşil kart, sigorta, ruhsat)</h3>
                            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($company->documents as $doc)
                                    <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" rel="noopener" class="flex items-center gap-4 p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 hover:border-emerald-200 dark:hover:border-emerald-800/50 hover:shadow-md transition-all group">
                                        <span class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-zinc-900 dark:text-white">{{ $doc->type_label }}</p>
                                            @if($doc->title)<p class="text-xs text-zinc-500">{{ $doc->title }}</p>@endif
                                            @if($doc->expires_at)
                                                <p class="text-xs {{ $doc->expires_at->isPast() ? 'text-red-600 dark:text-red-400' : 'text-zinc-500' }}">Son geçerlilik: {{ $doc->expires_at->format('d.m.Y') }}</p>
                                            @endif
                                        </div>
                                        <svg class="w-5 h-5 text-zinc-400 shrink-0 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </section>
                @endif

                @if($company->vehicleImages->count() > 0)
                    <section>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white tracking-tight mb-1">Galeri</h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-5">Firmanın taşıma araçları ve görselleri</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($company->vehicleImages as $img)
                                <a href="{{ asset('storage/'.$img->path) }}" target="_blank" rel="noopener" class="block rounded-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden aspect-square group shadow-sm hover:shadow-md transition-shadow">
                                    <img src="{{ asset('storage/'.$img->path) }}" alt="{{ $img->caption ?? 'Galeri' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @if($img->caption)
                                        <p class="p-2.5 text-xs text-zinc-500 dark:text-zinc-400 text-center bg-zinc-50 dark:bg-zinc-800/80">{{ $img->caption }}</p>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                <section>
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white tracking-tight mb-1">Müşteri değerlendirmeleri</h2>
                    @if($company->reviews->count() > 0)
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-5">{{ $reviewCount }} değerlendirme · Ortalama {{ number_format($reviewAvg, 1, ',', '') }}/5</p>
                        <ul class="space-y-4">
                            @foreach($company->reviews as $review)
                                <li class="rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 sm:p-6 shadow-sm">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <span class="text-amber-500 text-lg" aria-label="{{ $review->rating }} yıldız">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                        <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ $review->user->name ?? 'Misafir' }}</span>
                                    </div>
                                    @if($review->comment)
                                        <p class="mt-3 text-zinc-600 dark:text-zinc-300 leading-relaxed">{{ $review->comment }}</p>
                                    @endif
                                    @if($review->video_path)
                                        <div class="mt-3">
                                            <video src="{{ asset('storage/'.$review->video_path) }}" controls class="max-w-full rounded-xl max-h-64" playsinline preload="metadata"></video>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-5">Henüz değerlendirme yapılmamış.</p>
                        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 text-center">
                            <p class="text-zinc-500 dark:text-zinc-400">Bu firma için ilk değerlendirmeyi siz yapın — işiniz tamamlandıktan sonra değerlendirme sayfasına yönlendirileceksiniz.</p>
                        </div>
                    @endif
                </section>
            </div>

            <aside class="lg:col-span-4 mt-10 lg:mt-0">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm p-6">
                        <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">İletişim</h3>
                        @if($company->phone)
                            <a href="tel:{{ preg_replace('/\D/', '', $company->phone) }}" class="flex items-center gap-3 p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors mb-2 group">
                                <span class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </span>
                                <span class="font-medium text-zinc-900 dark:text-white">{{ $company->phone }}</span>
                            </a>
                        @endif
                        @if($company->phone_2)
                            <a href="tel:{{ preg_replace('/\D/', '', $company->phone_2) }}" class="flex items-center gap-3 p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors mb-2">
                                <span class="w-10 h-10 rounded-lg bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-zinc-500 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </span>
                                <span class="font-medium text-zinc-900 dark:text-white">{{ $company->phone_2 }}</span>
                            </a>
                        @endif
                        @if($company->whatsapp)
                            @php $waDigits = ltrim(preg_replace('/\D/', '', $company->whatsapp), '0'); $wa = (str_starts_with($waDigits, '90') ? $waDigits : '90' . $waDigits); @endphp
                            <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="flex items-center gap-3 p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 transition-colors mb-2">
                                <span class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </span>
                                <span class="font-medium text-zinc-900 dark:text-white">{{ $company->whatsapp }}</span>
                                <span class="text-xs text-emerald-600 dark:text-emerald-400">WhatsApp</span>
                            </a>
                        @endif
                        @if($company->email)
                            <a href="mailto:{{ $company->email }}" class="flex items-center gap-3 p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors mb-2">
                                <span class="w-10 h-10 rounded-lg bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-zinc-500 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </span>
                                <span class="text-sm font-medium text-zinc-900 dark:text-white break-all">{{ $company->email }}</span>
                            </a>
                        @endif
                        @if($company->address || $company->city)
                            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 mt-2">
                                <span class="w-10 h-10 rounded-lg bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-zinc-500 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                </span>
                                <p class="text-sm text-zinc-600 dark:text-zinc-300">
                                    @if($company->address){{ $company->address }}<br>@endif
                                    {{ $company->district ? $company->district . ', ' : '' }}{{ $company->city ?? '' }}
                                </p>
                            </div>
                        @endif
                        @if(!$company->phone && !$company->phone_2 && !$company->whatsapp && !$company->email && !$company->address && !$company->city)
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">İletişim bilgisi eklenmemiş.</p>
                        @endif
                    </div>

                    <div class="rounded-2xl border border-emerald-200/60 dark:border-emerald-800/50 bg-gradient-to-br from-emerald-500/8 to-teal-500/8 dark:from-emerald-500/12 dark:to-teal-500/12 p-6">
                        <h3 class="font-semibold text-zinc-900 dark:text-white mb-2">Bu firmadan teklif alın</h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-5">Nakliye ihtiyacınızı ilan edin, firmamız size teklif sunabilsin.</p>
                        <a href="{{ route('ihale.create') }}" class="btn-primary w-full justify-center rounded-xl">İhale oluştur</a>
                    </div>

                    <a href="{{ route('firmalar.index') }}" class="btn-ghost w-full justify-center text-sm rounded-xl">← Tüm firmalara dön</a>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
