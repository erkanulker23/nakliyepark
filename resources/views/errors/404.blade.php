@extends('layouts.error')

@section('title', 'Sayfa bulunamadı')

@section('content')
<div class="flex flex-col items-center text-center max-w-xl mx-auto">
    {{-- Decorative --}}
    <div class="relative mb-8">
        <span class="text-[8rem] sm:text-[10rem] font-bold text-emerald-500/20 dark:text-emerald-500/10 select-none leading-none">404</span>
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <span class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center ring-4 ring-emerald-200/50 dark:ring-emerald-800/50">
                <svg class="w-12 h-12 sm:w-14 sm:h-14 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
        </div>
    </div>

    <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight mb-3">
        Sayfa bulunamadı
    </h1>
    <p class="text-zinc-600 dark:text-zinc-400 mb-6 leading-relaxed">
        Aradığınız sayfa kaldırılmış, adresi değişmiş veya geçici olarak kullanılamıyor olabilir.
    </p>

    <div class="w-full max-w-md mx-auto mb-8 text-left p-4 rounded-xl bg-emerald-50/80 dark:bg-emerald-900/20 border border-emerald-200/80 dark:border-emerald-800/50">
        <p class="text-sm font-semibold text-emerald-900 dark:text-emerald-200 mb-2">Neden olabilir?</p>
        <ul class="text-sm text-emerald-800 dark:text-emerald-300 space-y-1 list-disc list-inside">
            <li>Adres (URL) yanlış yazılmış veya eski bir linke tıklandı</li>
            <li>İçerik yayından kaldırılmış veya taşınmış olabilir</li>
            <li>İhale veya ilan süresi dolmuş olabilir</li>
        </ul>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto">
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 min-h-[48px] px-8 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:scale-[1.02] w-full sm:w-auto">
            Ana sayfaya dön
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </a>
        @if(Route::has('ihaleler.index'))
        <a href="{{ route('ihaleler.index') }}" class="inline-flex items-center justify-center gap-2 min-h-[48px] px-8 py-3 rounded-xl font-medium text-zinc-700 dark:text-zinc-200 bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors w-full sm:w-auto">
            İhalelere göz at
        </a>
        @endif
    </div>
</div>
@endsection
