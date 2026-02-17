@extends('layouts.error')

@section('title', 'Erişim engellendi')

@section('content')
<div class="flex flex-col items-center text-center max-w-xl mx-auto">
    <div class="relative mb-8">
        <span class="text-[8rem] sm:text-[10rem] font-bold text-rose-500/20 dark:text-rose-500/10 select-none leading-none">403</span>
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <span class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center ring-4 ring-rose-200/50 dark:ring-rose-800/50">
                <svg class="w-12 h-12 sm:w-14 sm:h-14 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </span>
        </div>
    </div>

    <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight mb-3">
        Bu sayfaya erişim yetkiniz yok
    </h1>
    <p class="text-zinc-600 dark:text-zinc-400 mb-6 leading-relaxed">
        Bu içeriği görüntülemek veya işlem yapmak için gerekli izinlere sahip değilsiniz.
    </p>

    <div class="w-full max-w-md mx-auto mb-8 text-left p-4 rounded-xl bg-rose-50/80 dark:bg-rose-900/20 border border-rose-200/80 dark:border-rose-800/50">
        <p class="text-sm font-semibold text-rose-900 dark:text-rose-200 mb-2">Neden olabilir?</p>
        <ul class="text-sm text-rose-800 dark:text-rose-300 space-y-1 list-disc list-inside">
            <li>Bu sayfa sadece yönetici veya yetkili kullanıcılar içindir</li>
            <li>Farklı bir kullanıcı rolüyle giriş yapmış olabilirsiniz</li>
            <li>Hesabınız askıya alınmış veya kısıtlanmış olabilir</li>
        </ul>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto">
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 min-h-[48px] px-8 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:scale-[1.02] w-full sm:w-auto">
            Ana sayfaya dön
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </a>
        @auth
        <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center gap-2 min-h-[48px] px-8 py-3 rounded-xl font-medium text-zinc-700 dark:text-zinc-200 bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors w-full sm:w-auto">
            Geri git
        </a>
        @else
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 min-h-[48px] px-8 py-3 rounded-xl font-medium text-zinc-700 dark:text-zinc-200 bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors w-full sm:w-auto">
            Giriş yap
        </a>
        @endauth
    </div>
</div>
@endsection
