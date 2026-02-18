<header class="sticky top-0 z-50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border-b border-zinc-200/80 dark:border-zinc-800 safe-top safe-area-top">
    <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between min-h-[56px] sm:min-h-[72px] py-2">
            <a href="{{ url('/') }}" class="site-brand flex items-center gap-2.5 shrink-0">
                @if(!empty($site_logo_url) || !empty($site_logo_dark_url ?? null))
                    @if(!empty($site_logo_url))
                        <img src="{{ $site_logo_url }}" alt="{{ $site_meta_title ?? 'NakliyePark' }}" class="site-brand-logo h-11 w-auto max-w-[200px] object-contain dark:hidden">
                    @endif
                    @if(!empty($site_logo_dark_url ?? null))
                        <img src="{{ $site_logo_dark_url }}" alt="{{ $site_meta_title ?? 'NakliyePark' }}" class="site-brand-logo h-11 w-auto max-w-[200px] object-contain hidden dark:block">
                    @elseif(!empty($site_logo_url))
                        <img src="{{ $site_logo_url }}" alt="{{ $site_meta_title ?? 'NakliyePark' }}" class="site-brand-logo h-11 w-auto max-w-[200px] object-contain hidden dark:block">
                    @endif
                @else
                    <span class="site-brand-fallback flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-base shadow-sm">N</span>
                    <span class="site-brand-text font-semibold text-zinc-900 dark:text-white text-lg tracking-tight">NakliyePark</span>
                @endif
                @if(config('app.beta', true))
                    <span class="beta-badge hidden sm:inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold uppercase tracking-wider bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30" title="Bu sürüm beta aşamasındadır">Beta</span>
                @endif
            </a>
            {{-- Masaüstü: tam nav + dropdown --}}
            <nav class="hidden lg:flex items-center gap-0.5">
                <a href="{{ route('ihaleler.index') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400">İhaleler</a>
                @if($show_firmalar_page ?? true)
                <a href="{{ route('firmalar.index') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400">Firmalar</a>
                @endif
                <a href="{{ route('defter.index') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400">Defter</a>
                <a href="{{ route('pazaryeri.index') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400">Pazaryeri</a>
                <div class="relative" id="tools-dropdown-wrap">
                    <button type="button" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400 inline-flex items-center gap-1 tools-dropdown-btn" aria-expanded="false" aria-haspopup="true" aria-controls="tools-dropdown-menu" id="tools-menu-btn">
                        Yardımcı araçlar
                        <svg class="w-4 h-4 transition-transform tools-dropdown-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="tools-dropdown-menu" class="absolute left-0 top-full mt-1 w-56 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-lg py-1 z-50 tools-dropdown-panel" role="menu" aria-labelledby="tools-menu-btn" hidden>
                        <a href="{{ route('tools.volume') }}" class="block px-4 py-2.5 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800" role="menuitem">Hacim hesaplama</a>
                        <a href="{{ route('tools.distance') }}" class="block px-4 py-2.5 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800" role="menuitem">Mesafe hesaplama</a>
                        <a href="{{ route('tools.checklist') }}" class="block px-4 py-2.5 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800" role="menuitem">Taşınma kontrol listesi</a>
                        <a href="{{ route('tools.moving-calendar') }}" class="block px-4 py-2.5 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800" role="menuitem">Taşınma takvimi</a>
                        <a href="{{ route('tools.price-estimator') }}" class="block px-4 py-2.5 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800" role="menuitem">Tahmini fiyat</a>
                        @if($show_firmalar_page ?? true)
                        <a href="{{ route('tools.company-lookup') }}" class="block px-4 py-2.5 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800" role="menuitem">Firma sorgula</a>
                        <a href="{{ route('firmalar.map') }}" class="block px-4 py-2.5 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800" role="menuitem">Nakliyeci bul</a>
                        @endif
                    </div>
                </div>
            </nav>
            <div class="flex items-center gap-1 sm:gap-2 relative z-[50]">
                <button type="button" id="theme-toggle" class="btn-ghost rounded-lg p-2.5 text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400" aria-label="Açık/Koyu mod" title="Açık/Koyu mod">
                    <svg id="theme-icon-light" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    <svg id="theme-icon-dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
                @auth
                    @if(auth()->user()->isAdmin())
                        {{-- Admin girişli: Panel linki + misafir görünümü (güvenlik için frontend'de admin belli olmasın) --}}
                        <a href="{{ route('admin.dashboard') }}" class="btn-ghost rounded-lg hidden sm:inline-flex text-zinc-600 dark:text-zinc-400">Admin</a>
                        <a href="{{ route('login') }}" class="btn-secondary rounded-lg hidden sm:inline-flex">Giriş</a>
                        <a href="{{ route('register') }}" class="btn-secondary rounded-lg hidden sm:inline-flex">Üye ol</a>
                        <a href="{{ route('ihale.create') }}" class="hidden sm:inline-flex items-center justify-center gap-2 min-h-[44px] px-5 py-2.5 rounded-xl font-semibold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 shadow-lg shadow-orange-500/25 transition-all">İhale başlat</a>
                    @else
                        @if(auth()->user()->isNakliyeci())
                            <a href="{{ route('nakliyeci.company.edit') }}" class="btn-ghost rounded-lg hidden sm:inline-flex">Firmam</a>
                        @elseif(auth()->user()->isMusteri())
                            <a href="{{ route('musteri.dashboard') }}" class="btn-ghost rounded-lg hidden sm:inline-flex">İhalelerim</a>
                        @endif
                        @include('layouts.partials.notifications-dropdown')
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-secondary rounded-lg hidden sm:inline-flex">Giriş</a>
                    <a href="{{ route('register') }}" class="btn-secondary rounded-lg hidden sm:inline-flex">Üye ol</a>
                    <a href="{{ route('ihale.create') }}" class="hidden sm:inline-flex items-center justify-center gap-2 min-h-[44px] px-5 py-2.5 rounded-xl font-semibold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 shadow-lg shadow-orange-500/25 transition-all">İhale başlat</a>
                @endauth
            </div>
        </div>
        {{-- Mobil: yatay kaydırmalı link satırı (JS yok) --}}
        <nav class="lg:hidden flex overflow-x-auto gap-1 py-2 -mx-3 px-3 sm:-mx-6 sm:px-6 scrollbar-hide border-t border-zinc-200/80 dark:border-zinc-800/80 mt-1" aria-label="Sayfa linkleri" style="-webkit-overflow-scrolling: touch;">
            <a href="{{ route('ihaleler.index') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400 shrink-0 py-2 text-sm">İhaleler</a>
            @if($show_firmalar_page ?? true)
            <a href="{{ route('firmalar.index') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400 shrink-0 py-2 text-sm">Firmalar</a>
            @endif
            <a href="{{ route('defter.index') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400 shrink-0 py-2 text-sm">Defter</a>
            <a href="{{ route('pazaryeri.index') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400 shrink-0 py-2 text-sm">Pazaryeri</a>
            <a href="{{ route('tools.volume') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400 shrink-0 py-2 text-sm">Hacim</a>
            <a href="{{ route('tools.distance') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400 shrink-0 py-2 text-sm">Mesafe</a>
            <a href="{{ route('tools.price-estimator') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400 shrink-0 py-2 text-sm">Tahmini fiyat</a>
            @if($show_firmalar_page ?? true)
            <a href="{{ route('firmalar.map') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400 shrink-0 py-2 text-sm">Nakliyeci bul</a>
            @endif
        </nav>
    </div>
</header>
