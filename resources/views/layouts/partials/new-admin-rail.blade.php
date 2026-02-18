@php $active = fn($r) => request()->routeIs($r) ? ' is-active' : ''; @endphp
<aside class="app-rail" aria-label="Menü">
    {{-- Diğer menü: flyout body’de, bu buton açar --}}
    <button type="button" class="js-toggle-admin-flyout app-rail__item{{ request()->routeIs('admin.yuk-ilanlari.*','admin.reklam-alanlari.*','admin.reviews.*','admin.blog.*','admin.blog-categories.*','admin.faq.*','admin.homepage-editor.*','admin.sponsors.*','admin.consent-logs.*','admin.blocklist.*','admin.sitemap.*','admin.settings.*','admin.site-contact-messages.*') ? ' is-active' : '' }}" data-tooltip="Diğer menü" aria-haspopup="true" aria-expanded="false">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>
    <a href="{{ route('admin.dashboard') }}" class="app-rail__item{{ $active('admin.dashboard') }}" data-tooltip="Kontrol Paneli">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
    </a>
    <a href="{{ route('admin.users.index') }}" class="app-rail__item{{ $active('admin.users.*') }}" data-tooltip="Kullanıcılar">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    </a>
    <a href="{{ route('admin.musteriler.index') }}" class="app-rail__item{{ $active('admin.musteriler.*') }}" data-tooltip="Müşteriler">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    </a>
    <a href="{{ route('admin.companies.index') }}" class="app-rail__item{{ $active('admin.companies.*') }}" data-tooltip="Nakliyeciler">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    </a>
    <a href="{{ route('admin.ihaleler.index') }}" class="app-rail__item{{ $active('admin.ihaleler.*') }}" data-tooltip="İhaleler">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    </a>
    <a href="{{ route('admin.teklifler.index') }}" class="app-rail__item{{ $active('admin.teklifler.*') }}" data-tooltip="Teklifler">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    </a>
    <a href="{{ route('admin.disputes.index') }}" class="app-rail__item{{ $active('admin.disputes.*') }}" data-tooltip="Uyuşmazlıklar">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    </a>
    <div class="app-rail__grow"></div>
    <a href="{{ route('admin.notifications.index') }}" class="app-rail__item{{ $active('admin.notifications.*') }}" data-tooltip="Bildirimler">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-6-6 6 6 0 00-6 6v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        @if(($header_unread_count ?? \App\Models\AdminNotification::whereNull('read_at')->count()) > 0)<span style="position:absolute;top:6px;right:6px;width:8px;height:8px;border-radius:50%;background:var(--app-accent);"></span>@endif
    </a>
    <a href="{{ route('admin.profile.edit') }}" class="app-rail__item{{ $active('admin.profile.*') }}" data-tooltip="Profilim">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
    </a>
    <form method="POST" action="{{ route('logout') }}" class="app-rail__logout">
        @csrf
        <button type="submit" class="app-rail__item" data-tooltip="Çıkış" aria-label="Çıkış yap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        </button>
    </form>
</aside>
