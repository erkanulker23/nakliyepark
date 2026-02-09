<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#059669">
    <title>@yield('title', 'NakliyePark')</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-[#fafafa] dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 antialiased font-sans safe-top safe-bottom flex flex-col">
    <main class="flex-1 flex flex-col items-center justify-center p-4 sm:p-6">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 mb-8 shrink-0">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-sm">N</span>
            <span class="font-semibold text-zinc-900 dark:text-white text-lg">NakliyePark</span>
        </a>
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
