<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff" id="admin-theme-color">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="yandex" content="noindex, nofollow">
    <title>@yield('title', 'Admin') - NakliyePark</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="admin-body min-h-screen antialiased font-sans">
    <div class="flex">
        {{-- Sol menü --}}
        <aside id="admin-sidebar" class="admin-sidebar fixed lg:sticky top-0 left-0 z-40 w-64 border-r border-slate-700/50 shadow-xl lg:shadow-none transition-transform duration-200 ease-out">
            <div class="admin-sidebar-header flex items-center justify-between h-16 px-5 border-b border-slate-700/50">
                <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-logo flex items-center gap-3 font-semibold text-white">
                    <span class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center text-white text-sm font-bold shadow-lg">N</span>
                    <span>NakliyePark</span>
                </a>
                <button type="button" id="sidebar-close" class="admin-sidebar-close lg:hidden p-2 text-slate-400 hover:text-white rounded-lg" aria-label="Menüyü kapat">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="p-3 space-y-0.5 overflow-y-auto" style="max-height: calc(100vh - 4rem);">
                <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Kontrol Paneli
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Kullanıcılar
                </a>
                <a href="{{ route('admin.musteriler.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.musteriler.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Müşteriler
                </a>
                <a href="{{ route('admin.companies.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Nakliyeciler
                </a>
                <a href="{{ route('admin.ihaleler.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.ihaleler.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    İhaleler
                </a>
                <a href="{{ route('admin.teklifler.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.teklifler.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Teklifler
                </a>
                <a href="{{ route('admin.yuk-ilanlari.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.yuk-ilanlari.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Yük İlanları
                </a>
                <a href="{{ route('admin.defter-api.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.defter-api.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Defter API
                </a>
                <a href="{{ route('admin.reklam-alanlari.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.reklam-alanlari.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    Reklam Alanları
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Değerlendirmeler
                </a>
                <a href="{{ route('admin.disputes.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.disputes.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Uyuşmazlıklar
                </a>
                <div class="admin-sidebar-label pt-4 pb-1 px-3 text-xs font-semibold uppercase tracking-wider">İçerik</div>
                <a href="{{ route('admin.blog.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Blog
                </a>
                <a href="{{ route('admin.blog-categories.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.blog-categories.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    Blog kategorileri
                </a>
                <a href="{{ route('admin.faq.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.faq.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    SSS
                </a>
                <a href="{{ route('admin.homepage-editor.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.homepage-editor.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Anasayfa düzenleyici
                </a>
                <a href="{{ route('admin.sponsors.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.sponsors.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Sponsorlar
                </a>
                <a href="{{ route('admin.room-templates.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.room-templates.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    Oda Şablonları
                </a>
                <div class="admin-sidebar-label pt-4 pb-1 px-3 text-xs font-semibold uppercase tracking-wider">Sistem</div>
                <a href="{{ route('admin.consent-logs.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.consent-logs.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    KVKK Rıza Logları
                </a>
                <a href="{{ route('admin.blocklist.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.blocklist.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    Engellemeler
                </a>
                <a href="{{ route('admin.settings.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Ayarlar
                </a>
                <a href="{{ route('admin.site-contact-messages.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.site-contact-messages.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    İletişim mesajları
                </a>
                <div class="admin-sidebar-label pt-4 pb-1 px-3 text-xs font-semibold uppercase tracking-wider">Hesap</div>
                <a href="{{ route('admin.notifications.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-6-6 6 6 0 00-6 6v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    Bildirimler
                    @php $unreadCount = \App\Models\AdminNotification::whereNull('read_at')->count(); @endphp
                    @if($unreadCount > 0)<span class="ml-auto bg-emerald-500 text-white text-xs rounded-full min-w-[20px] h-5 flex items-center justify-center px-1.5">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>@endif
                </a>
                <a href="{{ route('admin.profile.edit') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profilim
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
            <header class="admin-header sticky top-0 z-20 flex items-center justify-between h-16 px-6 bg-white border-b border-slate-200 shadow-sm">
                <button type="button" id="sidebar-open" class="admin-header-btn lg:hidden p-2 -ml-2 text-slate-500 hover:bg-slate-100 rounded-lg" aria-label="Menüyü aç">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="admin-page-title text-lg">@yield('page_heading', 'Admin')</h1>
                    @hasSection('page_subtitle')<p class="admin-page-subtitle">@yield('page_subtitle')</p>@endif
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    {{-- Bildirimler dropdown --}}
                    @php
                        $adminNotifs = $header_notifications ?? collect();
                        $adminUnread = $header_unread_count ?? 0;
                    @endphp
                    <div class="relative" id="admin-notifications-wrap">
                        <button type="button" id="admin-notifications-btn" class="admin-header-btn relative p-2.5 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-700 dark:hover:text-emerald-400" aria-label="Bildirimler" title="Bildirimler" aria-expanded="false" aria-haspopup="true">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-6-6 6 6 0 00-6 6v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if($adminUnread > 0)
                                <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center rounded-full bg-emerald-500 text-white text-xs font-semibold px-1">{{ $adminUnread > 99 ? '99+' : $adminUnread }}</span>
                            @endif
                        </button>
                        <div id="admin-notifications-panel" class="absolute right-0 mt-2 w-80 sm:w-96 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-xl z-50 overflow-hidden hidden" role="dialog" aria-label="Bildirimler listesi">
                            <div class="p-3 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                                <span class="font-semibold text-slate-800 dark:text-slate-200">Bildirimler</span>
                                <a href="{{ route('admin.notifications.index') }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:underline">Tümü</a>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                @forelse($adminNotifs->take(15) as $nb)
                                    <a href="{{ !empty($nb->data['url']) ? $nb->data['url'] : route('admin.notifications.index') }}" class="block px-3 py-2.5 border-b border-slate-100 dark:border-slate-700/50 hover:bg-slate-50 dark:hover:bg-slate-700/50 {{ $nb->read_at ? '' : 'bg-emerald-50/50 dark:bg-emerald-950/20' }}">
                                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200 truncate">{{ $nb->title ?? $nb->type }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ Str::limit($nb->message, 60) }}</p>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $nb->created_at->diffForHumans() }}</p>
                                    </a>
                                @empty
                                    <p class="px-3 py-6 text-sm text-slate-500 dark:text-slate-400 text-center">Bildirim yok.</p>
                                @endforelse
                            </div>
                            <div class="p-2 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/80">
                                <a href="{{ route('admin.notifications.index') }}" class="block text-center text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:underline py-1.5">Tüm bildirimler →</a>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="admin-dark-toggle" class="admin-header-btn p-2.5 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-700 dark:hover:text-emerald-400" aria-label="Açık/Koyu mod" title="Açık/Koyu mod">
                        <svg id="admin-icon-sun" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <svg id="admin-icon-moon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    </button>
                    <a href="{{ url('/') }}" target="_blank" class="hidden sm:inline text-sm text-slate-500 hover:text-slate-800 dark:hover:text-emerald-400 font-medium">Siteyi aç →</a>
                    <a href="{{ route('admin.profile.edit') }}" class="text-sm text-slate-500 hover:text-slate-800 dark:hover:text-emerald-400 font-medium">Profil</a>
                </div>
            </header>
            <div class="flex-1 p-6 lg:p-8">
                @if(session('success'))
                    <div class="admin-alert admin-alert-success mb-6" role="alert">
                        <span class="admin-alert-icon" aria-hidden="true">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </span>
                        <span class="admin-alert-text">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="admin-alert admin-alert-error mb-6" role="alert">
                        <span class="admin-alert-icon" aria-hidden="true">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        </span>
                        <span class="admin-alert-text">{{ session('error') }}</span>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    <script>
        (function() {
            var wrap = document.getElementById('admin-notifications-wrap');
            var btn = document.getElementById('admin-notifications-btn');
            var panel = document.getElementById('admin-notifications-panel');
            if (wrap && btn && panel) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var isHidden = panel.classList.contains('hidden');
                    panel.classList.toggle('hidden', !isHidden);
                    btn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
                });
                document.addEventListener('click', function(e) {
                    if (!wrap.contains(e.target)) {
                        panel.classList.add('hidden');
                        btn.setAttribute('aria-expanded', 'false');
                    }
                });
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        panel.classList.add('hidden');
                        btn.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        })();
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
            var darkToggle = document.getElementById('admin-dark-toggle');
            var iconSun = document.getElementById('admin-icon-sun');
            var iconMoon = document.getElementById('admin-icon-moon');
            var themeColorMeta = document.getElementById('admin-theme-color');
            function setThemeMeta(isDark) {
                if (themeColorMeta) themeColorMeta.setAttribute('content', isDark ? '#0d0d0f' : '#ffffff');
            }
            var saved = localStorage.getItem('admin-dark');
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
                    localStorage.setItem('admin-dark', '1');
                    if (iconSun) iconSun.classList.remove('hidden');
                    if (iconMoon) iconMoon.classList.add('hidden');
                } else {
                    localStorage.setItem('admin-dark', '0');
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
