@extends('layouts.error')

@section('title', 'Sunucu hatası')

@section('content')
<div class="flex flex-col items-center text-center max-w-xl mx-auto">
    <div class="relative mb-8">
        <span class="text-[8rem] sm:text-[10rem] font-bold text-red-500/20 dark:text-red-500/10 select-none leading-none">500</span>
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <span class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center ring-4 ring-red-200/50 dark:ring-red-800/50">
                <svg class="w-12 h-12 sm:w-14 sm:h-14 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </span>
        </div>
    </div>

    <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight mb-3">
        Bir şeyler yanlış gitti
    </h1>
    <p class="text-zinc-600 dark:text-zinc-400 mb-6 leading-relaxed">
        Sunucuda beklenmeyen bir hata oluştu. Lütfen kısa süre sonra tekrar deneyin. Sorun devam ederse bizimle iletişime geçin.
    </p>

    <div class="w-full max-w-md mx-auto mb-8 text-left p-4 rounded-xl bg-red-50/80 dark:bg-red-900/20 border border-red-200/80 dark:border-red-800/50">
        <p class="text-sm font-semibold text-red-900 dark:text-red-200 mb-2">Neden olabilir?</p>
        <ul class="text-sm text-red-800 dark:text-red-300 space-y-1 list-disc list-inside">
            <li>Geçici bir sunucu veya veritabanı hatası</li>
            <li>Bakım veya güncelleme yapılıyor olabilir</li>
            <li>Girdiğiniz veri beklenmeyen bir hataya neden olmuş olabilir</li>
        </ul>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto">
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 min-h-[48px] px-8 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:scale-[1.02] w-full sm:w-auto">
            Ana sayfaya dön
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </a>
        <button type="button" onclick="window.location.reload()" class="inline-flex items-center justify-center gap-2 min-h-[48px] px-8 py-3 rounded-xl font-medium text-zinc-700 dark:text-zinc-200 bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors w-full sm:w-auto">
            Tekrar dene
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </button>
    </div>
</div>
@endsection
