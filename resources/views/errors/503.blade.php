@extends('layouts.error')

@section('title', 'Bakım modu')

@section('content')
<div class="flex flex-col items-center text-center max-w-xl mx-auto">
    <div class="relative mb-8">
        <span class="text-[8rem] sm:text-[10rem] font-bold text-amber-500/20 dark:text-amber-500/10 select-none leading-none">503</span>
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <span class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center ring-4 ring-amber-200/50 dark:ring-amber-800/50">
                <svg class="w-12 h-12 sm:w-14 sm:h-14 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </span>
        </div>
    </div>

    <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight mb-3">
        Bakım çalışması yapılıyor
    </h1>
    <p class="text-zinc-600 dark:text-zinc-400 mb-6 leading-relaxed">
        Site şu an güncelleniyor veya bakımda. Lütfen birkaç dakika sonra tekrar deneyin.
    </p>

    <div class="w-full max-w-md mx-auto mb-8 text-left p-4 rounded-xl bg-amber-50/80 dark:bg-amber-900/20 border border-amber-200/80 dark:border-amber-800/50">
        <p class="text-sm font-semibold text-amber-900 dark:text-amber-200 mb-2">Neden olabilir?</p>
        <ul class="text-sm text-amber-800 dark:text-amber-300 space-y-1 list-disc list-inside">
            <li>Planlı bakım veya güncelleme yapılıyor</li>
            <li>Sunucu geçici olarak yeniden başlatılıyor</li>
            <li>Yük çok yüksek olduğunda geçici kısıtlama uygulanabilir</li>
        </ul>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto">
        <button type="button" onclick="window.location.reload()" class="inline-flex items-center justify-center gap-2 min-h-[48px] px-8 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 shadow-lg shadow-amber-500/25 transition-all duration-200 hover:scale-[1.02] w-full sm:w-auto">
            Tekrar dene
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </button>
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 min-h-[48px] px-8 py-3 rounded-xl font-medium text-zinc-700 dark:text-zinc-200 bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors w-full sm:w-auto">
            Ana sayfa
        </a>
    </div>
</div>
@endsection
