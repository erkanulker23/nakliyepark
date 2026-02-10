<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#059669">
    @php
        $seo_title = trim((string) ($__env->yieldContent('title') ?? 'NakliyePark'));
        $seo_description = trim((string) ($__env->yieldContent('meta_description') ?? config('seo.default_description')));
        $canonical_url = url()->current();
        $og_image = asset('icons/icon-192.png');
        $meta_robots = 'index, follow';
    @endphp
    <title>{{ $seo_title ?: 'NakliyePark' }}</title>
    @include('layouts.partials.seo-meta')
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    @if(!empty($site_favicon_url ?? null))
        <link rel="icon" type="image/x-icon" href="{{ $site_favicon_url }}">
        <link rel="shortcut icon" href="{{ $site_favicon_url }}">
    @else
        <link rel="icon" href="{{ asset('icons/icon-192.png') }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    @include('layouts.partials.structured-data')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <script>(function(){var s=localStorage.getItem('site-theme');if(s==='dark')document.documentElement.classList.add('dark');else if(s==='light')document.documentElement.classList.remove('dark');else if(window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches)document.documentElement.classList.add('dark');})();</script>
</head>
<body class="min-h-screen bg-[#fafafa] dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 antialiased font-sans safe-top safe-bottom flex flex-col">
    <div class="fixed top-4 right-4 z-50">
        <button type="button" id="theme-toggle" class="p-2.5 rounded-xl bg-white/80 dark:bg-zinc-800/80 backdrop-blur border border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors" aria-label="Açık/Koyu mod" title="Açık/Koyu mod">
            <svg id="theme-icon-light" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            <svg id="theme-icon-dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
        </button>
    </div>
    <main class="flex-1 flex flex-col items-center justify-center p-4 sm:p-6">
        <a href="{{ url('/') }}" class="site-brand flex items-center gap-2.5 mb-8 shrink-0">
            @if(!empty($site_logo_url ?? null) || !empty($site_logo_dark_url ?? null))
                @if(!empty($site_logo_url))
                    <img src="{{ $site_logo_url }}" alt="{{ $site_meta_title ?? 'NakliyePark' }}" class="h-11 w-auto max-w-[200px] object-contain dark:hidden">
                @endif
                @if(!empty($site_logo_dark_url ?? null))
                    <img src="{{ $site_logo_dark_url }}" alt="{{ $site_meta_title ?? 'NakliyePark' }}" class="h-11 w-auto max-w-[200px] object-contain hidden dark:block">
                @elseif(!empty($site_logo_url))
                    <img src="{{ $site_logo_url }}" alt="{{ $site_meta_title ?? 'NakliyePark' }}" class="h-11 w-auto max-w-[200px] object-contain hidden dark:block">
                @endif
            @else
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-base">N</span>
                <span class="font-semibold text-zinc-900 dark:text-white text-lg">NakliyePark</span>
            @endif
        </a>
        @yield('content')
    </main>
    @stack('scripts')
    <script>(function(){var t=document.getElementById('theme-toggle'),il=document.getElementById('theme-icon-light'),id=document.getElementById('theme-icon-dark');function isD(){return document.documentElement.classList.contains('dark');}function setD(e){if(e){document.documentElement.classList.add('dark');if(il)il.classList.remove('hidden');if(id)id.classList.add('hidden');localStorage.setItem('site-theme','dark');}else{document.documentElement.classList.remove('dark');if(il)il.classList.add('hidden');if(id)id.classList.remove('hidden');localStorage.setItem('site-theme','light');}}var s=localStorage.getItem('site-theme');if(s==='dark')setD(true);else if(s==='light')setD(false);else if(window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches)setD(true);t&&t.addEventListener('click',function(){setD(!isD());});})();</script>
</body>
</html>
