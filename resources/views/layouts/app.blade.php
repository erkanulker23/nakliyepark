<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#059669">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>@yield('title', $site_meta_title ?? 'NakliyePark')</title>
    <meta name="description" content="@yield('meta_description', $site_meta_description ?? 'NakliyePark - Akıllı nakliye ve yük borsası')">
    @if(!empty($site_meta_keywords))<meta name="keywords" content="{{ $site_meta_keywords }}">@endif
    @if(!empty($site_logo_url))<meta property="og:image" content="{{ $site_logo_url }}">@endif
    @stack('meta')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen font-sans safe-top safe-bottom">
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
        @yield('content')
    </main>
    @include('layouts.partials.footer')
    @stack('scripts')
</body>
</html>
