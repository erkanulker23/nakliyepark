<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#059669">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    @php
        $seo_title = trim((string) ($__env->yieldContent('title') ?? $site_meta_title ?? config('seo.site_name', 'NakliyePark')));
        $seo_description = trim((string) ($__env->yieldContent('meta_description') ?? $site_meta_description ?? config('seo.default_description')));
        $seo_canonical = trim((string) $__env->yieldContent('canonical_url'));
        $canonical_url = $seo_canonical !== '' ? $seo_canonical : url()->current();
        $og_image = trim((string) $__env->yieldContent('og_image'));
        $og_image = $og_image !== '' ? $og_image : ($site_logo_url ?? asset('icons/icon-192.png'));
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
    (function(){var s=localStorage.getItem('site-theme');if(s==='dark')document.documentElement.classList.add('dark');else if(s==='light')document.documentElement.classList.remove('dark');else if(window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches)document.documentElement.classList.add('dark');})();
    </script>
    @stack('styles')
    @if(!empty($seo_head_codes ?? null)){!! $seo_head_codes !!}@endif
    @if(!empty($custom_header_html ?? null)){!! $custom_header_html !!}@endif
</head>
<body class="site-selection min-h-screen font-sans safe-top safe-bottom">
    @include('layouts.partials.header')
    <main class="pb-24 sm:pb-12">
        @if(session('success'))
            <div class="page-container pt-4">
                <div class="alert-success">{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="page-container pt-4">
                <div class="alert-error">{{ session('error') }}</div>
            </div>
        @endif
        @if(session('info'))
            <div class="page-container pt-4">
                <div class="rounded-xl bg-sky-50 dark:bg-sky-900/20 text-sky-800 dark:text-sky-200 px-4 py-3 text-sm border border-sky-200 dark:border-sky-800">{{ session('info') }}</div>
            </div>
        @endif
        @yield('content')
    </main>
    @include('layouts.partials.footer')
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
            else {
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                setDark(prefersDark);
            }
        }
        initTheme();
        toggle && toggle.addEventListener('click', function() { setDark(!isDark()); });
    })();
    </script>
</body>
</html>
