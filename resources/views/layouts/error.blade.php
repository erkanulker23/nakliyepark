<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#059669">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Hata') - NakliyePark</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
    (function(){var s=localStorage.getItem('site-theme');if(s==='dark')document.documentElement.classList.add('dark');else if(s==='light')document.documentElement.classList.remove('dark');else document.documentElement.classList.add('dark');})();
    </script>
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 font-sans antialiased safe-top safe-bottom">
    <header class="sticky top-0 z-50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border-b border-zinc-200/80 dark:border-zinc-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between min-h-[56px] sm:min-h-[72px] py-2">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 shrink-0">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-base shadow-sm">N</span>
                    <span class="font-semibold text-zinc-900 dark:text-white text-lg tracking-tight">NakliyePark</span>
                </a>
                <div class="flex items-center gap-2">
                    <button type="button" id="error-theme-toggle" class="inline-flex items-center justify-center p-2.5 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-emerald-600 dark:hover:text-emerald-400" aria-label="Açık/Koyu mod">
                        <svg id="error-theme-icon-light" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <svg id="error-theme-icon-dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    </button>
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 min-h-[44px] px-4 py-2.5 rounded-xl font-medium text-sm bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-700">Ana sayfa</a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20">
        @yield('content')
    </main>

    <script>
    (function() {
        var toggle = document.getElementById('error-theme-toggle');
        var iconLight = document.getElementById('error-theme-icon-light');
        var iconDark = document.getElementById('error-theme-icon-dark');
        function isDark() { return document.documentElement.classList.contains('dark'); }
        function setDark(enabled) {
            if (enabled) {
                document.documentElement.classList.add('dark');
                if (iconLight) iconLight.classList.remove('hidden');
                if (iconDark) iconDark.classList.add('hidden');
                localStorage.setItem('site-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                if (iconLight) iconLight.classList.add('hidden');
                if (iconDark) iconDark.classList.remove('hidden');
                localStorage.setItem('site-theme', 'light');
            }
        }
        var saved = localStorage.getItem('site-theme');
        if (saved === 'dark') setDark(true);
        else if (saved === 'light') setDark(false);
        else setDark(true);
        toggle && toggle.addEventListener('click', function() { setDark(!isDark()); });
    })();
    </script>
</body>
</html>
