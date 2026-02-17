<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff" id="nakliyeci-theme-color">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="yandex" content="noindex, nofollow">
    <title>@yield('title', optional(auth()->user()->company)->name ?: 'Firma Paneli') - NakliyePark</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/css/nakliyeci.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="admin-body min-h-screen bg-slate-50 text-slate-800 antialiased font-sans">
    <div class="flex">
        {{-- Sol menü - Firma paneli --}}
        <aside id="admin-sidebar" class="admin-sidebar fixed lg:sticky top-0 left-0 z-40 w-64 border-r border-slate-700/50 shadow-xl lg:shadow-none transition-transform duration-200 ease-out">
            <div class="admin-sidebar-header flex items-center justify-between h-16 px-5 border-b border-slate-700/50">
                <a href="{{ route('nakliyeci.dashboard') }}" class="admin-sidebar-logo flex items-center gap-3 font-semibold min-w-0">
                    <span class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center text-white text-sm font-bold shadow-lg shrink-0">N</span>
                    <span class="truncate">{{ optional(auth()->user()->company)->name ?: 'Firma Paneli' }}</span>
                </a>
                <button type="button" id="sidebar-close" class="admin-sidebar-close lg:hidden p-2 text-slate-400 hover:text-white rounded-lg" aria-label="Menüyü kapat">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="p-3 space-y-0.5 overflow-y-auto" style="max-height: calc(100vh - 4rem);">
                <a href="{{ route('nakliyeci.dashboard') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('nakliyeci.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Kontrol Paneli
                </a>
                <a href="{{ route('nakliyeci.company.edit') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('nakliyeci.company.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Firma Bilgileri
                </a>
                <a href="{{ route('nakliyeci.teklifler.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('nakliyeci.teklifler.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Tekliflerim
                </a>
                <a href="{{ route('nakliyeci.ledger') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('nakliyeci.ledger*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    Defter
                </a>
                <a href="{{ route('nakliyeci.galeri.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('nakliyeci.galeri.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Galeri
                </a>
                <a href="{{ route('nakliyeci.pazaryeri.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('nakliyeci.pazaryeri.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Pazaryeri
                </a>
                <a href="{{ route('nakliyeci.evraklar.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('nakliyeci.evraklar.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Şirket Evrakları
                </a>
                <a href="{{ route('nakliyeci.cari.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('nakliyeci.cari.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Cari
                </a>
                <a href="{{ route('nakliyeci.borc.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('nakliyeci.borc.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6a2 2 0 002 2zm2-8a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Borç / Ödeme
                </a>
                <a href="{{ route('nakliyeci.paketler.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('nakliyeci.paketler.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8 4-8-4m0 0l8-4 8 4m-8-4v10M12 21a9 9 0 01-9-9 9 9 0 019-9 9 9 0 019 9 9 9 0 01-9 9z"/></svg>
                    Paketler
                </a>
                <a href="{{ route('nakliyeci.ihaleler.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('nakliyeci.ihaleler.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Açık İhaleler
                </a>
                <div class="admin-sidebar-label pt-4 pb-1 px-3 text-xs font-semibold uppercase tracking-wider">Hesap</div>
                <a href="{{ route('nakliyeci.bilgilerim.edit') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('nakliyeci.bilgilerim.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Bilgilerim
                </a>
                <a href="{{ url('/') }}" target="_blank" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Siteye git
                </a>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="nav-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-left">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Çıkış
                    </button>
                </form>
            </nav>
        </aside>
        <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-black/50 lg:hidden opacity-0 pointer-events-none transition-opacity" aria-hidden="true"></div>
        <main class="flex-1 min-h-screen flex flex-col bg-slate-50 admin-main-wrap">
            <header class="admin-header sticky top-0 z-20 flex items-center justify-between h-16 px-4 sm:px-6 bg-white border-b border-slate-200 shadow-sm">
                <button type="button" id="sidebar-open" class="admin-header-btn lg:hidden p-2 -ml-2 text-slate-500 hover:bg-slate-100 rounded-lg" aria-label="Menüyü aç">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="min-w-0 flex-1 mx-3">
                    <h1 class="admin-page-title text-lg font-bold truncate">@yield('page_heading', 'Panel')</h1>
                    @hasSection('page_subtitle')<p class="admin-page-subtitle truncate">@yield('page_subtitle')</p>@endif
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    @include('layouts.partials.notifications-dropdown')
                    <button type="button" id="nakliyeci-dark-toggle" class="admin-header-btn p-2 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700" aria-label="Açık/Koyu mod" title="Açık/Koyu mod">
                        <svg id="nakliyeci-icon-sun" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <svg id="nakliyeci-icon-moon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    </button>
                </div>
            </header>
            <div class="flex-1 p-6 lg:p-8">
                @if(session('success'))
                    <div class="admin-alert-success mb-6 rounded-lg border px-4 py-3 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="admin-alert-error mb-6 rounded-lg border px-4 py-3 text-sm font-medium">
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    <script>
        document.getElementById('sidebar-open')?.addEventListener('click', function() {
            document.getElementById('admin-sidebar').classList.add('open');
            document.getElementById('sidebar-overlay').classList.remove('opacity-0', 'pointer-events-none');
        });
        function closeSidebar() {
            document.getElementById('admin-sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.add('opacity-0', 'pointer-events-none');
        }
        document.getElementById('sidebar-close')?.addEventListener('click', closeSidebar);
        document.getElementById('sidebar-overlay')?.addEventListener('click', closeSidebar);
        (function() {
            var darkToggle = document.getElementById('nakliyeci-dark-toggle');
            var iconSun = document.getElementById('nakliyeci-icon-sun');
            var iconMoon = document.getElementById('nakliyeci-icon-moon');
            var themeColorMeta = document.getElementById('nakliyeci-theme-color');
            function setThemeMeta(isDark) {
                if (themeColorMeta) themeColorMeta.setAttribute('content', isDark ? '#0f172a' : '#ffffff');
            }
            var saved = localStorage.getItem('nakliyeci-dark');
            if (saved === '1') {
                document.documentElement.classList.add('admin-dark', 'dark');
                if (iconSun) iconSun.classList.remove('hidden');
                if (iconMoon) iconMoon.classList.add('hidden');
                setThemeMeta(true);
            } else {
                setThemeMeta(false);
            }
            darkToggle?.addEventListener('click', function() {
                document.documentElement.classList.toggle('admin-dark');
                document.documentElement.classList.toggle('dark');
                var isDark = document.documentElement.classList.contains('admin-dark');
                if (isDark) {
                    localStorage.setItem('nakliyeci-dark', '1');
                    if (iconSun) iconSun.classList.remove('hidden');
                    if (iconMoon) iconMoon.classList.add('hidden');
                } else {
                    localStorage.setItem('nakliyeci-dark', '0');
                    if (iconSun) iconSun.classList.add('hidden');
                    if (iconMoon) iconMoon.classList.remove('hidden');
                }
                setThemeMeta(isDark);
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
