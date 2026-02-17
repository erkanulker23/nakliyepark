{{-- Mobil alt navigasyon: sadece lg altı, en fazla 3 öğe, kullanıcı tipine göre farklı menü --}}
@php
    $user = auth()->user();
    $isNakliyeci = $user && $user->isNakliyeci();
    $isMusteri = $user && $user->isMusteri();
    $isGuest = !$user || (!$isNakliyeci && !$isMusteri);

    $base = 'flex flex-col items-center justify-center gap-1 min-h-[56px] flex-1 min-w-0 px-2 py-2 rounded-xl text-zinc-500 dark:text-zinc-400 transition-all duration-200 ';
    $active = 'text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 dark:bg-emerald-500/15 font-medium';
    $cta = 'text-white bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-600 dark:hover:bg-emerald-500 shadow-xl shadow-emerald-600/40 font-bold ring-2 ring-emerald-400/30 ring-inset';
@endphp
<nav class="fixed bottom-0 left-0 right-0 z-40 lg:hidden bg-white/95 dark:bg-zinc-900/95 backdrop-blur-xl border-t border-zinc-200/80 dark:border-zinc-800 safe-area-bottom pb-safe" aria-label="Ana navigasyon">
    <div class="max-w-lg mx-auto px-3 py-2.5">
        <div class="flex items-stretch justify-center gap-1 rounded-2xl bg-zinc-100/80 dark:bg-zinc-800/50 p-1.5">

            @if($isGuest)
                {{-- Üyeliksiz: Ana Sayfa | İhale Oluştur (ana) | Firmalar --}}
                <a href="{{ url('/') }}" class="{{ $base }} {{ request()->routeIs('home') ? $active : 'hover:text-zinc-700 dark:hover:text-zinc-300 hover:bg-white/60 dark:hover:bg-zinc-700/60' }}" aria-current="{{ request()->routeIs('home') ? 'page' : null }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="text-[10px] sm:text-xs truncate w-full text-center">Ana Sayfa</span>
                </a>
                <a href="{{ route('ihale.create') }}" class="{{ $base }} {{ $cta }} {{ request()->routeIs('ihale.*') ? 'ring-2 ring-emerald-400 ring-offset-2 ring-offset-zinc-100 dark:ring-offset-zinc-800' : '' }} rounded-xl" aria-current="{{ request()->routeIs('ihale.*') ? 'page' : null }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span class="text-[10px] sm:text-xs truncate w-full text-center">İhale Oluştur</span>
                </a>
                @if($show_firmalar_page ?? true)
                <a href="{{ route('firmalar.index') }}" class="{{ $base }} {{ request()->routeIs('firmalar.*') ? $active : 'hover:text-zinc-700 dark:hover:text-zinc-300 hover:bg-white/60 dark:hover:bg-zinc-700/60' }}" aria-current="{{ request()->routeIs('firmalar.*') ? 'page' : null }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="text-[10px] sm:text-xs truncate w-full text-center">Firmalar</span>
                </a>
                @else
                <a href="{{ route('ihaleler.index') }}" class="{{ $base }} {{ request()->routeIs('ihaleler.index') ? $active : 'hover:text-zinc-700 dark:hover:text-zinc-300 hover:bg-white/60 dark:hover:bg-zinc-700/60' }}" aria-current="{{ request()->routeIs('ihaleler.index') ? 'page' : null }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span class="text-[10px] sm:text-xs truncate w-full text-center">İhaleler</span>
                </a>
                @endif
            @endif

            @if($isMusteri)
                {{-- Müşteri: Panel | İhale Oluştur (ana) | Hesabım --}}
                <a href="{{ route('musteri.dashboard') }}" class="{{ $base }} {{ request()->routeIs('musteri.dashboard') ? $active : 'hover:text-zinc-700 dark:hover:text-zinc-300 hover:bg-white/60 dark:hover:bg-zinc-700/60' }}" aria-current="{{ request()->routeIs('musteri.dashboard') ? 'page' : null }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span class="text-[10px] sm:text-xs truncate w-full text-center">Panel</span>
                </a>
                <a href="{{ route('ihale.create') }}" class="{{ $base }} {{ $cta }} {{ request()->routeIs('ihale.*') ? 'ring-2 ring-emerald-400 ring-offset-2 ring-offset-zinc-100 dark:ring-offset-zinc-800' : '' }} rounded-xl" aria-current="{{ request()->routeIs('ihale.*') ? 'page' : null }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span class="text-[10px] sm:text-xs truncate w-full text-center">İhale Oluştur</span>
                </a>
                <a href="{{ route('musteri.bilgilerim.edit') }}" class="{{ $base }} {{ request()->routeIs('musteri.bilgilerim.*') ? $active : 'hover:text-zinc-700 dark:hover:text-zinc-300 hover:bg-white/60 dark:hover:bg-zinc-700/60' }}" aria-current="{{ request()->routeIs('musteri.bilgilerim.*') ? 'page' : null }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="text-[10px] sm:text-xs truncate w-full text-center">Hesabım</span>
                </a>
            @endif

            @if($isNakliyeci)
                {{-- Nakliye firması: Panel | İhaleler (ana) | Hesabım --}}
                <a href="{{ route('nakliyeci.dashboard') }}" class="{{ $base }} {{ request()->routeIs('nakliyeci.dashboard') ? $active : 'hover:text-zinc-700 dark:hover:text-zinc-300 hover:bg-white/60 dark:hover:bg-zinc-700/60' }}" aria-current="{{ request()->routeIs('nakliyeci.dashboard') ? 'page' : null }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span class="text-[10px] sm:text-xs truncate w-full text-center">Panel</span>
                </a>
                <a href="{{ route('nakliyeci.ihaleler.index') }}" class="{{ $base }} {{ $cta }} {{ request()->routeIs('nakliyeci.ihaleler.*') ? 'ring-2 ring-emerald-400 ring-offset-2 ring-offset-zinc-100 dark:ring-offset-zinc-800' : '' }} rounded-xl" aria-current="{{ request()->routeIs('nakliyeci.ihaleler.*') ? 'page' : null }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span class="text-[10px] sm:text-xs truncate w-full text-center">İhaleler</span>
                </a>
                <a href="{{ route('nakliyeci.company.edit') }}" class="{{ $base }} {{ request()->routeIs('nakliyeci.company.*') ? $active : 'hover:text-zinc-700 dark:hover:text-zinc-300 hover:bg-white/60 dark:hover:bg-zinc-700/60' }}" aria-current="{{ request()->routeIs('nakliyeci.company.*') ? 'page' : null }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="text-[10px] sm:text-xs truncate w-full text-center">Firmam</span>
                </a>
            @endif

        </div>
    </div>
</nav>
