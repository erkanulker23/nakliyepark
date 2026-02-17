<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover, maximum-scale=5, user-scalable=yes">
    <meta name="theme-color" content="#059669">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    @php
        $seo_title = trim((string) ($__env->yieldContent('title') ?? $site_meta_title ?? config('seo.site_name', 'NakliyePark')));
        $seo_description = trim((string) ($__env->yieldContent('meta_description') ?? $site_meta_description ?? config('seo.default_description')));
        $seo_canonical = trim((string) $__env->yieldContent('canonical_url'));
        $canonical_url = $seo_canonical !== '' ? $seo_canonical : url()->current();
        $og_image = trim((string) $__env->yieldContent('og_image'));
        $og_image = $og_image !== '' ? $og_image : ($site_logo_url ?? asset('icons/icon-192.png'));
        $og_type = trim((string) $__env->yieldContent('og_type'));
        $og_type = $og_type !== '' ? $og_type : 'website';
        $meta_robots = trim((string) $__env->yieldContent('meta_robots'));
        $meta_robots = $meta_robots !== '' ? $meta_robots : 'index, follow';
    @endphp
    <title>{{ $seo_title ?: config('seo.site_name') }}</title>
    @include('layouts.partials.seo-meta')
    @stack('meta')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    @if(!empty($site_favicon_url ?? null))
        <link rel="icon" type="image/x-icon" href="{{ $site_favicon_url }}">
        <link rel="shortcut icon" href="{{ $site_favicon_url }}">
    @else
        <link rel="icon" href="{{ asset('icons/icon-192.png') }}">
    @endif
    <link rel="apple-touch-icon" href="{{ !empty($site_logo_url ?? null) ? $site_logo_url : asset('icons/icon-192.png') }}">
    @include('layouts.partials.structured-data')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
    (function(){var s=localStorage.getItem('site-theme');if(s==='dark')document.documentElement.classList.add('dark');else if(s==='light')document.documentElement.classList.remove('dark');else document.documentElement.classList.add('dark');})();
    </script>
    @stack('styles')
    @if(!empty($seo_head_codes ?? null)){!! $seo_head_codes !!}@endif
    @if(!empty($custom_header_html ?? null)){!! $custom_header_html !!}@endif
</head>
<body class="site-selection min-h-screen font-sans safe-top safe-bottom">
    @include('layouts.partials.header')
    {{-- Toast: sabit konum, sayfa düzenini bozmaz, animasyonlu --}}
    <div id="toast-container" class="fixed top-20 right-4 left-4 sm:left-auto sm:max-w-sm z-[100] flex flex-col gap-2 pointer-events-none" aria-live="polite"></div>
    @php
        $toastType = session('success') ? 'success' : (session('error') ? 'error' : (session('info') ? 'info' : null));
        $toastMessage = session('success') ?: session('error') ?: session('info');
        // Giriş yapmamış kullanıcıya "oturum süresi doldu / tekrar giriş yapın" gibi mesajları gösterme (yanlış anlaşılmayı önle)
        if (!auth()->check() && $toastMessage && (str_contains($toastMessage, 'oturum') || str_contains($toastMessage, 'süresi doldu') || str_contains($toastMessage, 'tekrar giriş') || str_contains($toastMessage, 'çıkış yapın'))) {
            $toastType = null;
            $toastMessage = null;
        }
    @endphp
    @if($toastType && $toastMessage)
        <div id="toast-initial" role="alert" class="toast toast-enter pointer-events-auto fixed top-20 right-4 left-4 sm:left-auto sm:max-w-sm z-[100] shadow-lg rounded-xl flex items-start gap-3 px-4 py-3
            @if($toastType === 'success') border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-100
            @elseif($toastType === 'error') border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30 text-red-800 dark:text-red-100
            @else border border-sky-200 dark:border-sky-800 bg-sky-50 dark:bg-sky-900/30 text-sky-800 dark:text-sky-100
            @endif">
            @if($toastType === 'success')
                <span class="shrink-0 flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-600 dark:text-emerald-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>
            @elseif($toastType === 'error')
                <span class="shrink-0 flex h-8 w-8 items-center justify-center rounded-full bg-red-500/20 text-red-600 dark:text-red-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></span>
            @else
                <span class="shrink-0 flex h-8 w-8 items-center justify-center rounded-full bg-sky-500/20 text-sky-600 dark:text-sky-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
            @endif
            <p class="text-sm font-medium flex-1 min-w-0">{{ $toastMessage }}</p>
            <button type="button" id="toast-close-btn" class="toast-close shrink-0 p-1 rounded-lg hover:opacity-80" aria-label="Kapat">&times;</button>
        </div>
    @endif
    <main class="min-h-screen pb-24 sm:pb-12 overflow-x-hidden max-w-[100vw]">
        @yield('content')
    </main>
    @include('layouts.partials.footer')
    @include('layouts.partials.bottom-nav')
    @if(!empty($custom_footer_html ?? null)){!! $custom_footer_html !!}@endif
    @if(!empty($custom_scripts ?? null)){!! $custom_scripts !!}@endif
    @stack('scripts')
    <script>
    (function() {
        var STORAGE_KEY = 'site-theme';
        var toggle = document.getElementById('theme-toggle');
        var iconLight = document.getElementById('theme-icon-light');
        var iconDark = document.getElementById('theme-icon-dark');
        function isDark() { return document.documentElement.classList.contains('dark'); }
        function setDark(enabled) {
            if (enabled) {
                document.documentElement.classList.add('dark');
                if (iconLight) { iconLight.classList.remove('hidden'); }
                if (iconDark) { iconDark.classList.add('hidden'); }
                localStorage.setItem(STORAGE_KEY, 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                if (iconLight) { iconLight.classList.add('hidden'); }
                if (iconDark) { iconDark.classList.remove('hidden'); }
                localStorage.setItem(STORAGE_KEY, 'light');
            }
        }
        function initTheme() {
            var saved = localStorage.getItem(STORAGE_KEY);
            if (saved === 'dark') setDark(true);
            else if (saved === 'light') setDark(false);
            else setDark(true);
        }
        initTheme();
        toggle && toggle.addEventListener('click', function() { setDark(!isDark()); });
    })();
    (function() {
        var toast = document.getElementById('toast-initial');
        if (!toast) return;
        var closeBtn = document.getElementById('toast-close-btn');
        var duration = 5000;
        var timeoutId;
        function dismiss() {
            toast.classList.remove('toast-enter');
            toast.classList.add('toast-exit');
            if (timeoutId) clearTimeout(timeoutId);
            setTimeout(function() {
                if (toast.parentNode) toast.parentNode.removeChild(toast);
            }, 300);
        }
        if (closeBtn) closeBtn.addEventListener('click', dismiss);
        timeoutId = setTimeout(dismiss, duration);
    })();
    </script>
</body>
</html>
