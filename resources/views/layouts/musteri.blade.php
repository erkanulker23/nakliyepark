<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0c4a6e">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Panel') - NakliyePark</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/css/musteri.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="admin-body min-h-screen bg-slate-50 text-slate-800 antialiased font-sans">
    <div class="flex">
        {{-- Sol menü - Müşteri paneli --}}
        <aside id="musteri-sidebar" class="admin-sidebar fixed lg:sticky top-0 left-0 z-40 w-64 border-r border-slate-700/50 shadow-xl lg:shadow-none transition-transform duration-200 ease-out">
            <div class="admin-sidebar-header flex items-center justify-between h-16 px-5 border-b border-slate-700/50">
                <a href="{{ route('musteri.dashboard') }}" class="admin-sidebar-logo flex items-center gap-3 font-semibold min-w-0">
                    @php $musteriAdi = auth()->user()->name ?? 'Müşteri'; $musteriBasHarf = mb_substr($musteriAdi, 0, 1); @endphp
                    <span class="musteri-logo-icon w-9 h-9 rounded-xl bg-sky-600 flex items-center justify-center text-white text-sm font-bold shadow-lg shrink-0">{{ mb_strtoupper($musteriBasHarf) ?: 'M' }}</span>
                    <span class="truncate">{{ $musteriAdi }}</span>
                </a>
                <button type="button" id="sidebar-close" class="admin-sidebar-close lg:hidden p-2 text-slate-400 hover:text-white rounded-lg" aria-label="Menüyü kapat">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="p-3 space-y-0.5 overflow-y-auto" style="max-height: calc(100vh - 4rem);">
                <a href="{{ route('musteri.dashboard') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('musteri.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    İhalelerim
                </a>
                <a href="{{ route('ihale.create') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Yeni İhale
                </a>
                <a href="{{ route('musteri.teklifler.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('musteri.teklifler.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Gelen Teklifler
                </a>
                <a href="{{ route('musteri.mesajlar.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('musteri.mesajlar.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Gelen Mesajlar
                </a>
                <div class="admin-sidebar-label pt-4 pb-1 px-3 text-xs font-semibold uppercase tracking-wider">Hesap</div>
                <a href="{{ route('musteri.bilgilerim.edit') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('musteri.bilgilerim.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Bilgilerim
                </a>
                <a href="{{ route('musteri.notifications.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('musteri.notifications.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    Bildirimler
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
                @if(session('info'))
                    <div class="rounded-xl bg-sky-50 dark:bg-sky-900/20 text-sky-800 dark:text-sky-200 px-4 py-3 text-sm border border-sky-200 dark:border-sky-800 mb-6">
                        {{ session('info') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    <script>
        document.getElementById('sidebar-open')?.addEventListener('click', function() {
            document.getElementById('musteri-sidebar').classList.add('open');
            document.getElementById('sidebar-overlay').classList.remove('opacity-0', 'pointer-events-none');
        });
        function closeSidebar() {
            document.getElementById('musteri-sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.add('opacity-0', 'pointer-events-none');
        }
        document.getElementById('sidebar-close')?.addEventListener('click', closeSidebar);
        document.getElementById('sidebar-overlay')?.addEventListener('click', closeSidebar);
    </script>
    @stack('scripts')
</body>
</html>
