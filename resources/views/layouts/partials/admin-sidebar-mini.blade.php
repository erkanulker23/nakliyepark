<aside class="panel-sidebar-mini" aria-label="Admin kenar menü">
    {{-- Ana sayfalar: ikon + tooltip --}}
    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-tooltip="Kontrol Paneli">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
    </a>
    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" data-tooltip="Kullanıcılar">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    </a>
    <a href="{{ route('admin.musteriler.index') }}" class="nav-item {{ request()->routeIs('admin.musteriler.*') ? 'active' : '' }}" data-tooltip="Müşteriler">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    </a>
    <a href="{{ route('admin.companies.index') }}" class="nav-item {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}" data-tooltip="Nakliyeciler">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    </a>
    <a href="{{ route('admin.ihaleler.index') }}" class="nav-item {{ request()->routeIs('admin.ihaleler.*') ? 'active' : '' }}" data-tooltip="İhaleler">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    </a>
    <a href="{{ route('admin.teklifler.index') }}" class="nav-item {{ request()->routeIs('admin.teklifler.*') ? 'active' : '' }}" data-tooltip="Teklifler">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    </a>
    <a href="{{ route('admin.disputes.index') }}" class="nav-item {{ request()->routeIs('admin.disputes.*') ? 'active' : '' }}" data-tooltip="Uyuşmazlıklar">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    </a>
    {{-- Menü: tüm diğer linkler flyout'ta --}}
    <div class="relative" id="admin-desktop-menu-wrap">
        <button type="button" id="admin-desktop-menu-btn" class="nav-item w-full {{ request()->routeIs('admin.yuk-ilanlari.*', 'admin.reklam-alanlari.*', 'admin.reviews.*', 'admin.blog.*', 'admin.blog-categories.*', 'admin.faq.*', 'admin.homepage-editor.*', 'admin.sponsors.*', 'admin.consent-logs.*', 'admin.blocklist.*', 'admin.sitemap.*', 'admin.settings.*', 'admin.site-contact-messages.*') ? 'active' : '' }}" data-tooltip="Diğer menü" aria-haspopup="true" aria-expanded="false" aria-controls="admin-desktop-menu-flyout">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div id="admin-desktop-menu-flyout" class="absolute left-full top-0 ml-1 w-64 max-h-[min(80vh,500px)] overflow-y-auto bg-[var(--panel-surface)] border border-[var(--panel-border)] rounded-2xl shadow-xl z-50 py-2 hidden" role="menu" aria-label="Diğer menü" aria-hidden="true">
            <div class="px-3 py-2 border-b border-[var(--panel-border)]">
                <span class="text-sm font-semibold text-[var(--panel-text)]">Tüm menü</span>
            </div>
            <nav class="p-2 space-y-0.5">
                @include('layouts.partials.admin-menu-links')
            </nav>
            <div class="border-t border-[var(--panel-border)] p-2 mt-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-xl w-full text-left text-sm text-[var(--panel-text-muted)] hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Çıkış
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="flex-1"></div>
    <a href="{{ route('admin.notifications.index') }}" class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}" data-tooltip="Bildirimler">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-6-6 6 6 0 00-6 6v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        @php $unreadCount = $header_unread_count ?? \App\Models\AdminNotification::whereNull('read_at')->count(); @endphp
        @if($unreadCount > 0)<span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-emerald-500"></span>@endif
    </a>
    <a href="{{ route('admin.profile.edit') }}" class="nav-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" data-tooltip="Profilim">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
    </a>
</aside>
