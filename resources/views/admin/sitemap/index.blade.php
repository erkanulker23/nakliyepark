@extends('layouts.admin')

@section('title', 'Sitemap')
@section('page_heading', 'Sitemap')

@section('content')
<div class="space-y-6 max-w-3xl">
    <p class="text-slate-600 dark:text-slate-400 text-sm">
        Arama motorları (Google, Yandex, Bing) sitenizi daha iyi tarayabilsin diye <strong>sitemap.xml</strong> otomatik üretiliyor. Bu sayfa sadece bilgi ve yönetim içindir; sitemap her istekte güncel hesaplanır.
    </p>

    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Sitemap adresi</span>
        </div>
        <div class="p-4 flex flex-wrap items-center gap-3">
            <code class="flex-1 min-w-0 text-sm bg-slate-100 dark:bg-slate-700 px-3 py-2 rounded-lg break-all" id="sitemap-url">{{ $sitemapUrl }}</code>
            <a href="{{ $sitemapUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm bg-emerald-500 text-white hover:bg-emerald-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Sitemap’i aç
            </a>
            <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('sitemap-url').textContent); this.textContent='Kopyalandı!'; setTimeout(() => this.textContent='Kopyala', 2000)" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm bg-slate-200 dark:bg-slate-600 text-slate-800 dark:text-slate-200 hover:bg-slate-300 dark:hover:bg-slate-500">
                Kopyala
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Toplam URL</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ number_format($totalUrls) }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Statik sayfalar</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $countStatic }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">İhaleler (yayında)</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ number_format($countIhaleler) }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Sitemap’te en fazla 500</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Firmalar + Blog</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ number_format($countCompanies) }} + {{ $countBlog }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Firma max 1000, blog max 200</p>
        </div>
    </div>

    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-xl p-4">
        <p class="text-sm font-medium text-amber-900 dark:text-amber-200 mb-1">Google Search Console</p>
        <p class="text-sm text-amber-800 dark:text-amber-300">
            Sitemap’i Google’a göndermek için <a href="https://search.google.com/search-console" target="_blank" rel="noopener noreferrer" class="underline hover:no-underline">Search Console</a> → Tarama → Sitemap’ler bölümüne <code class="bg-amber-200/80 dark:bg-amber-800/50 px-1 rounded">sitemap.xml</code> ekleyebilirsiniz.
        </p>
    </div>
</div>
@endsection
