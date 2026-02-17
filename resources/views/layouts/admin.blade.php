<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#f8fafc" id="app-theme-color">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'Admin') - NakliyePark</title>
    {{-- Kritik stil: CSS gelmeden önce arka plan (Vite/build sonrası ana CSS bunları override eder) --}}
    <style>
        .app-shell { background: #f8fafc; color: #0f172a; min-height: 100vh; }
        .app-shell .app-main { background: #f8fafc; }
        html.app-dark .app-shell { background: #0f172a; color: #f1f5f9; }
        html.app-dark .app-shell .app-main { background: #0f172a; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="app-shell has-sidebar antialiased" data-panel-version="new">
    @include('layouts.partials.new-admin-sidebar')

    <div class="flex flex-col min-h-screen lg:min-h-0">
        <header class="app-bar">
            <button type="button" data-open-app-drawer class="app-bar__btn app-bar__menu-btn lg:hidden" aria-label="Menü" aria-haspopup="true" aria-expanded="false">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <span class="app-bar__menu-btn-label">Menü</span>
            </button>
            <div class="app-bar__spacer">
                <h1 class="app-bar__title">@yield('page_heading', 'Admin')</h1>
                @hasSection('page_subtitle')<p class="app-bar__subtitle">@yield('page_subtitle')</p>@endif
            </div>
            <div class="app-bar__actions">
                <a href="{{ route('admin.notifications.index') }}" class="app-bar__btn relative lg:hidden" aria-label="Bildirimler">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-6-6 6 6 0 00-6 6v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if(($header_unread_count ?? 0) > 0)<span style="position:absolute;top:8px;right:8px;width:8px;height:8px;border-radius:50%;background:var(--app-accent);"></span>@endif
                </a>
                <button type="button" id="app-theme-toggle" class="app-bar__btn" aria-label="Açık/Koyu mod">
                    <svg id="app-icon-sun" class="hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1.25rem;height:1.25rem"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg id="app-icon-moon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1.25rem;height:1.25rem"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
                <a href="{{ url('/') }}" target="_blank" rel="noopener" class="app-bar__btn hidden sm:inline-flex text-sm font-medium" style="width:auto;padding:0 0.5rem">Site →</a>
            </div>
        </header>

        <main class="app-main">
            <div class="app-main__inner">
            @if(session('success'))
                <div class="app-alert app-alert--success" role="alert">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="app-alert app-alert--error" role="alert">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @php
                $adminInfo = session('info');
                if ($adminInfo && (str_contains($adminInfo, 'süresi doldu') || str_contains($adminInfo, 'tekrar giriş yapın'))) {
                    $adminInfo = null;
                }
            @endphp
            @if($adminInfo)
                <div class="app-alert app-alert--info" role="alert">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <span>{{ $adminInfo }}</span>
                </div>
            @endif
            @yield('content')
            </div>
        </main>

        @include('layouts.partials.new-admin-bottom')
    </div>

    @include('layouts.partials.new-admin-drawer')

    <script>
    (function() {
        var drawer = document.getElementById('app-drawer');
        var closeBtn = document.getElementById('app-drawer-close');
        var backdrop = document.getElementById('app-drawer-backdrop');
        function openDrawer() {
            if (drawer) {
                drawer.classList.add('is-open');
                drawer.setAttribute('aria-hidden', 'false');
                document.querySelectorAll('[data-open-app-drawer]').forEach(function(b) { b.setAttribute('aria-expanded', 'true'); });
                document.body.style.overflow = 'hidden';
            }
        }
        function closeDrawer() {
            if (drawer) {
                drawer.classList.remove('is-open');
                drawer.setAttribute('aria-hidden', 'true');
                document.querySelectorAll('[data-open-app-drawer]').forEach(function(b) { b.setAttribute('aria-expanded', 'false'); });
                document.body.style.overflow = '';
            }
        }
        document.querySelectorAll('[data-open-app-drawer]').forEach(function(b) { b.addEventListener('click', openDrawer); });
        if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
        if (backdrop) backdrop.addEventListener('click', closeDrawer);
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeDrawer(); });
    })();
    (function() {
        var toggle = document.getElementById('app-theme-toggle');
        var iconSun = document.getElementById('app-icon-sun');
        var iconMoon = document.getElementById('app-icon-moon');
        var meta = document.getElementById('app-theme-color');
        function setMeta(dark) {
            if (meta) meta.setAttribute('content', dark ? '#0f172a' : '#f8fafc');
        }
        var saved = localStorage.getItem('app-dark');
        if (saved === '1') {
            document.documentElement.classList.add('app-dark');
            if (iconSun) iconSun.classList.remove('hidden');
            if (iconMoon) iconMoon.classList.add('hidden');
            setMeta(true);
        } else { setMeta(false); }
        if (toggle) {
            toggle.addEventListener('click', function() {
                document.documentElement.classList.toggle('app-dark');
                var dark = document.documentElement.classList.contains('app-dark');
                localStorage.setItem('app-dark', dark ? '1' : '0');
                if (iconSun) iconSun.classList.toggle('hidden', !dark);
                if (iconMoon) iconMoon.classList.toggle('hidden', dark);
                setMeta(dark);
            });
        }
    })();
    </script>
    @stack('scripts')
</body>
</html>
