@extends('layouts.app')

@section('title', $metaTitle ?? 'Firma Sorgula - NakliyePark')
@section('meta_description', $metaDescription ?? 'Nakliye firmasının cep veya sabit telefon numarasına göre firma sayfasını bulun.')

@section('content')
<div class="page-container py-6 sm:py-8">
    <header class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Firma sorgula</h1>
        <p class="mt-1.5 text-sm text-zinc-500 dark:text-zinc-400">Cep veya telefon numarasına göre nakliye firmasını bulun.</p>
        <p class="mt-3 text-zinc-600 dark:text-zinc-400 text-base max-w-2xl">Firmanın kayıtlı cep numarası veya sabit telefon numarasını girin; bu numarayı iletişim bilgisi olarak kullanan onaylı firmalar listelenir.</p>
    </header>

    <div class="max-w-2xl">
        <div class="card rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6">
                <form action="{{ route('tools.company-lookup') }}" method="get" class="space-y-4">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Cep veya telefon numarası</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $searchPhone) }}" inputmode="numeric" autocomplete="tel"
                               placeholder="5XX XXX XX XX veya 0XXX XXX XX XX"
                               class="w-full min-h-[48px] px-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 text-base">
                        <p class="mt-1.5 text-xs text-zinc-500 dark:text-zinc-400">Başında 0 veya 90 olmadan da girebilirsiniz (örn. 532 123 45 67).</p>
                    </div>
                    <button type="submit" class="w-full sm:w-auto h-11 px-6 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-500 transition-colors inline-flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Firma ara
                    </button>
                </form>
            </div>
        </div>

        @if($searchPhone !== '')
            <div class="mt-6 sm:mt-8">
                @if($companies->isEmpty())
                    <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 sm:p-8 text-center">
                        <p class="text-zinc-600 dark:text-zinc-400">Bu numaraya kayıtlı onaylı firma bulunamadı.</p>
                        <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-500">Numarayı kontrol edip tekrar deneyin veya <a href="{{ route('firmalar.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">firma listesine</a> göz atın.</p>
                    </div>
                @else
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">
                        <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $companies->count() }}</span> firma bulundu
                    </p>
                    <div class="space-y-4">
                        @foreach($companies as $company)
                            <a href="{{ route('firmalar.show', $company) }}" class="group flex gap-4 rounded-2xl bg-white dark:bg-zinc-900/80 border border-zinc-200/70 dark:border-zinc-800/70 p-5 sm:p-6 hover:border-emerald-300/70 dark:hover:border-emerald-700/50 hover:shadow-md transition-all duration-200 block">
                                @if($company->logo && $company->logo_approved_at && trim($company->logo) !== '')
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl shrink-0 overflow-hidden flex items-center justify-center bg-white dark:bg-zinc-800 border border-zinc-200/60 dark:border-zinc-700 shadow-sm">
                                        <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="w-full h-full object-contain p-1">
                                    </div>
                                @else
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-emerald-500 flex items-center justify-center text-2xl sm:text-3xl font-bold text-white shrink-0 shadow-sm" aria-hidden="true">
                                        {{ mb_substr(trim($company->name) ?: 'F', 0, 1) }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <h2 class="font-semibold text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors truncate pr-1">
                                        {{ $company->name }}
                                    </h2>
                                    @if($company->city || $company->district)
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-zinc-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                            {{ $company->city }}{{ $company->district ? ', ' . $company->district : '' }}
                                        </p>
                                    @endif
                                    <div class="mt-2 flex items-center gap-2 flex-wrap">
                                        @include('partials.company-package-badge', ['company' => $company])
                                    </div>
                                </div>
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-500/20 transition-colors shrink-0 self-center">
                                    <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>

    @if(!empty($toolContent) || $searchPhone === '')
        <section class="mt-8 pt-6 border-t border-zinc-200 dark:border-zinc-800" aria-labelledby="nasil-calisir-firma-sorgula">
            <h2 id="nasil-calisir-firma-sorgula" class="text-lg font-semibold text-zinc-900 dark:text-white mb-3">Firma sorgulama nasıl kullanılır?</h2>
            <div class="prose prose-sm prose-zinc dark:prose-invert max-w-none text-zinc-600 dark:text-zinc-400">
                @if(!empty($toolContent))
                    {!! $toolContent !!}
                @else
                    <p>Elinde sadece nakliye firmasının cep veya sabit telefon numarası olan kullanıcılar, bu numarayı girerek firmayı NakliyePark üzerinde bulabilir. Sadece onaylı ve listemizde yer alan firmalar gösterilir. Bulunan firmaya tıklayarak profil sayfasına gidebilir, iletişim bilgilerini ve değerlendirmeleri inceleyebilirsiniz.</p>
                @endif
            </div>
        </section>
    @endif
</div>
@endsection
