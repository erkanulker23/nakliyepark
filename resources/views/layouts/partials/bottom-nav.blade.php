{{-- Mobil alt navigasyon: sadece lg altı, kullanıcı tipine göre farklı menü --}}
@php
    $user = auth()->user();
    $isNakliyeci = $user && $user->isNakliyeci();
    $isMusteri = $user && $user->isMusteri();
    $isGuest = !$user || (!$isNakliyeci && !$isMusteri); // misafir veya admin vb. → site menüsü

    $navItemClass = 'flex flex-col items-center justify-center gap-0.5 min-w-[48px] min-h-[48px] rounded-2xl text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-500/5 active:bg-emerald-500/10 transition-colors';
    $navItemActive = 'text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 font-medium';
@endphp
<nav class="fixed bottom-0 left-0 right-0 z-40 lg:hidden bg-white/90 dark:bg-zinc-900/90 backdrop-blur-xl border-t border-zinc-200/80 dark:border-zinc-800 safe-area-bottom safe-bottom" aria-label="Ana navigasyon">
    <div class="max-w-6xl mx-auto flex items-center justify-around min-h-[60px] px-2 py-2">

        @if($isGuest)
            {{-- Misafir: Ana Sayfa | İhaleler | + İhale Başlat | Firmalar --}}
            <a href="{{ url('/') }}" class="{{ $navItemClass }} {{ request()->routeIs('home') ? $navItemActive : '' }}" aria-current="{{ request()->routeIs('home') ? 'page' : null }}">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[10px] sm:text-xs">Ana Sayfa</span>
            </a>
            <a href="{{ route('ihaleler.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('ihaleler.index') ? $navItemActive : '' }}" aria-current="{{ request()->routeIs('ihaleler.index') ? 'page' : null }}">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span class="text-[10px] sm:text-xs">İhaleler</span>
            </a>
            <a href="{{ route('ihale.create') }}" class="flex flex-col items-center justify-center gap-0.5 min-w-[56px] min-h-[52px] -mt-4 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white shadow-lg shadow-emerald-600/30 hover:shadow-emerald-500/40 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 {{ request()->routeIs('ihale.create') ? 'ring-2 ring-emerald-400 ring-offset-2 ring-offset-white dark:ring-offset-zinc-900' : '' }}" aria-current="{{ request()->routeIs('ihale.create') ? 'page' : null }}">
                <svg class="w-7 h-7 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span class="text-[10px] sm:text-xs font-semibold">İhale Başlat</span>
            </a>
            @if($show_firmalar_page ?? true)
            <a href="{{ route('firmalar.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('firmalar.*') ? $navItemActive : '' }}" aria-current="{{ request()->routeIs('firmalar.*') ? 'page' : null }}">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span class="text-[10px] sm:text-xs">Firmalar</span>
            </a>
            @endif
        @endif

        @if($isMusteri)
            {{-- Müşteri: Ana Sayfa | Panel | + İhale Başlat | Teklifler | Hesabım --}}
            <a href="{{ url('/') }}" class="{{ $navItemClass }} {{ request()->routeIs('home') ? $navItemActive : '' }}" aria-current="{{ request()->routeIs('home') ? 'page' : null }}">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[10px] sm:text-xs">Ana Sayfa</span>
            </a>
            <a href="{{ route('musteri.dashboard') }}" class="{{ $navItemClass }} {{ request()->routeIs('musteri.dashboard') ? $navItemActive : '' }}" aria-current="{{ request()->routeIs('musteri.dashboard') ? 'page' : null }}">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span class="text-[10px] sm:text-xs">Panel</span>
            </a>
            <a href="{{ route('ihale.create') }}" class="flex flex-col items-center justify-center gap-0.5 min-w-[56px] min-h-[52px] -mt-4 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white shadow-lg shadow-emerald-600/30 hover:shadow-emerald-500/40 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 {{ request()->routeIs('ihale.create') ? 'ring-2 ring-emerald-400 ring-offset-2 ring-offset-white dark:ring-offset-zinc-900' : '' }}" aria-current="{{ request()->routeIs('ihale.create') ? 'page' : null }}">
                <svg class="w-7 h-7 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span class="text-[10px] sm:text-xs font-semibold">İhale Başlat</span>
            </a>
            <a href="{{ route('musteri.teklifler.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('musteri.teklifler.*') ? $navItemActive : '' }}" aria-current="{{ request()->routeIs('musteri.teklifler.*') ? 'page' : null }}">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span class="text-[10px] sm:text-xs">Teklifler</span>
            </a>
            <a href="{{ route('musteri.bilgilerim.edit') }}" class="{{ $navItemClass }} {{ request()->routeIs('musteri.bilgilerim.*') ? $navItemActive : '' }}" aria-current="{{ request()->routeIs('musteri.bilgilerim.*') ? 'page' : null }}">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="text-[10px] sm:text-xs">Hesabım</span>
            </a>
        @endif

        @if($isNakliyeci)
            {{-- Nakliyeci: Ana Sayfa | Panel | İhaleler | Tekliflerim | Hesabım (FAB yok) --}}
            <a href="{{ url('/') }}" class="{{ $navItemClass }} {{ request()->routeIs('home') ? $navItemActive : '' }}" aria-current="{{ request()->routeIs('home') ? 'page' : null }}">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[10px] sm:text-xs">Ana Sayfa</span>
            </a>
            <a href="{{ route('nakliyeci.dashboard') }}" class="{{ $navItemClass }} {{ request()->routeIs('nakliyeci.dashboard') ? $navItemActive : '' }}" aria-current="{{ request()->routeIs('nakliyeci.dashboard') ? 'page' : null }}">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span class="text-[10px] sm:text-xs">Panel</span>
            </a>
            <a href="{{ route('nakliyeci.ihaleler.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('nakliyeci.ihaleler.*') ? $navItemActive : '' }} min-w-[56px] -mt-1 rounded-2xl bg-emerald-500/15 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/25 {{ request()->routeIs('nakliyeci.ihaleler.*') ? 'bg-emerald-500/20 dark:bg-emerald-500/25 font-medium' : '' }}" aria-current="{{ request()->routeIs('nakliyeci.ihaleler.*') ? 'page' : null }}">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span class="text-[10px] sm:text-xs font-medium">İhaleler</span>
            </a>
            <a href="{{ route('nakliyeci.teklifler.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('nakliyeci.teklifler.*') ? $navItemActive : '' }}" aria-current="{{ request()->routeIs('nakliyeci.teklifler.*') ? 'page' : null }}">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2h-2m-4-1V8"/></svg>
                <span class="text-[10px] sm:text-xs">Tekliflerim</span>
            </a>
            <a href="{{ route('nakliyeci.bilgilerim.edit') }}" class="{{ $navItemClass }} {{ request()->routeIs('nakliyeci.bilgilerim.*') ? $navItemActive : '' }}" aria-current="{{ request()->routeIs('nakliyeci.bilgilerim.*') ? 'page' : null }}">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="text-[10px] sm:text-xs">Hesabım</span>
            </a>
        @endif

    </div>
</nav>
