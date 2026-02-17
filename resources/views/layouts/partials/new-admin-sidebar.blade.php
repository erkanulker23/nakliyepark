@php
$active = fn($routes) => request()->routeIs($routes) ? ' is-active' : '';
@endphp
<aside class="app-admin-sidebar" aria-label="Yönetim menüsü">
    <div class="app-admin-sidebar__inner">
        <nav class="app-admin-sidebar__nav" aria-label="Tüm menü">
            <a href="{{ route('admin.dashboard') }}" class="app-admin-sidebar__link{{ $active('admin.dashboard') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg></span>
                <span class="app-admin-sidebar__label">Kontrol Paneli</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="app-admin-sidebar__link{{ $active('admin.users.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></span>
                <span class="app-admin-sidebar__label">Kullanıcılar</span>
            </a>
            <a href="{{ route('admin.musteriler.index') }}" class="app-admin-sidebar__link{{ $active('admin.musteriler.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span>
                <span class="app-admin-sidebar__label">Müşteriler</span>
            </a>
            <a href="{{ route('admin.companies.index') }}" class="app-admin-sidebar__link{{ $active('admin.companies.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></span>
                <span class="app-admin-sidebar__label">Nakliyeciler</span>
            </a>
            <a href="{{ route('admin.ihaleler.index') }}" class="app-admin-sidebar__link{{ $active('admin.ihaleler.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></span>
                <span class="app-admin-sidebar__label">İhaleler</span>
            </a>
            <a href="{{ route('admin.teklifler.index') }}" class="app-admin-sidebar__link{{ $active('admin.teklifler.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>
                <span class="app-admin-sidebar__label">Teklifler</span>
            </a>
            <a href="{{ route('admin.yuk-ilanlari.index') }}" class="app-admin-sidebar__link{{ $active('admin.yuk-ilanlari.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg></span>
                <span class="app-admin-sidebar__label">Yük İlanları</span>
            </a>
            <a href="{{ route('admin.defter-api.index') }}" class="app-admin-sidebar__link{{ $active('admin.defter-api.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg></span>
                <span class="app-admin-sidebar__label">Defter API</span>
            </a>
            <a href="{{ route('admin.reklam-alanlari.index') }}" class="app-admin-sidebar__link{{ $active('admin.reklam-alanlari.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg></span>
                <span class="app-admin-sidebar__label">Reklam Alanları</span>
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="app-admin-sidebar__link{{ $active('admin.reviews.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></span>
                <span class="app-admin-sidebar__label">Değerlendirmeler</span>
            </a>
            <a href="{{ route('admin.disputes.index') }}" class="app-admin-sidebar__link{{ $active('admin.disputes.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></span>
                <span class="app-admin-sidebar__label">Uyuşmazlıklar</span>
            </a>
            <a href="{{ route('admin.blog.index') }}" class="app-admin-sidebar__link{{ $active('admin.blog.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg></span>
                <span class="app-admin-sidebar__label">Blog</span>
            </a>
            <a href="{{ route('admin.blog-categories.index') }}" class="app-admin-sidebar__link{{ $active('admin.blog-categories.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg></span>
                <span class="app-admin-sidebar__label">Blog kategorileri</span>
            </a>
            <a href="{{ route('admin.faq.index') }}" class="app-admin-sidebar__link{{ $active('admin.faq.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                <span class="app-admin-sidebar__label">SSS</span>
            </a>
            <a href="{{ route('admin.homepage-editor.index') }}" class="app-admin-sidebar__link{{ $active('admin.homepage-editor.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg></span>
                <span class="app-admin-sidebar__label">Anasayfa düzenleyici</span>
            </a>
            <a href="{{ route('admin.sponsors.index') }}" class="app-admin-sidebar__link{{ $active('admin.sponsors.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></span>
                <span class="app-admin-sidebar__label">Sponsorlar</span>
            </a>
            <a href="{{ route('admin.room-templates.index') }}" class="app-admin-sidebar__link{{ $active('admin.room-templates.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg></span>
                <span class="app-admin-sidebar__label">Oda Şablonları</span>
            </a>
            <a href="{{ route('admin.consent-logs.index') }}" class="app-admin-sidebar__link{{ $active('admin.consent-logs.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></span>
                <span class="app-admin-sidebar__label">KVKK Rıza Logları</span>
            </a>
            <a href="{{ route('admin.blocklist.index') }}" class="app-admin-sidebar__link{{ $active('admin.blocklist.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg></span>
                <span class="app-admin-sidebar__label">Engellemeler</span>
            </a>
            <a href="{{ route('admin.sitemap.index') }}" class="app-admin-sidebar__link{{ $active('admin.sitemap.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg></span>
                <span class="app-admin-sidebar__label">Sitemap</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="app-admin-sidebar__link{{ $active('admin.settings.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span>
                <span class="app-admin-sidebar__label">Ayarlar</span>
            </a>
            <a href="{{ route('admin.site-contact-messages.index') }}" class="app-admin-sidebar__link{{ $active('admin.site-contact-messages.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                <span class="app-admin-sidebar__label">İletişim mesajları</span>
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="app-admin-sidebar__link{{ $active('admin.notifications.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-6-6 6 6 0 00-6 6v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg></span>
                <span class="app-admin-sidebar__label">Bildirimler</span>
                @if(($header_unread_count ?? \App\Models\AdminNotification::whereNull('read_at')->count()) > 0)<span class="app-admin-sidebar__badge"></span>@endif
            </a>
            <a href="{{ route('admin.profile.edit') }}" class="app-admin-sidebar__link{{ $active('admin.profile.*') }}">
                <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                <span class="app-admin-sidebar__label">Profilim</span>
            </a>
        </nav>
        <div class="app-admin-sidebar__footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="app-admin-sidebar__link app-admin-sidebar__link--logout">
                    <span class="app-admin-sidebar__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg></span>
                    <span class="app-admin-sidebar__label">Çıkış</span>
                </button>
            </form>
        </div>
    </div>
</aside>
