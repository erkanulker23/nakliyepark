@php
    $pendingCount = ($pending_companies_count ?? 0) + ($pending_ihaleler_count ?? 0) + ($teklif_pending_count ?? 0);
    $unreadNotif = $header_unread_count ?? \App\Models\AdminNotification::whereNull('read_at')->count();
@endphp
<nav class="panel-bottom-nav lg:hidden" aria-label="Admin ana menü">
    <div class="panel-bottom-nav-inner">
        <a href="{{ route('admin.dashboard') }}" class="panel-bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <span>Panel</span>
        </a>
        <a href="{{ route('admin.companies.index') }}" class="panel-bottom-nav-item {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
            <span class="relative inline-flex">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                @if($pendingCount > 0)<span class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] flex items-center justify-center rounded-full bg-amber-500 text-white text-[10px] font-bold">{{ $pendingCount > 99 ? '99+' : $pendingCount }}</span>@endif
            </span>
            <span>Firmalar</span>
        </a>
        <a href="{{ route('admin.ihaleler.index') }}" class="panel-bottom-nav-item {{ request()->routeIs('admin.ihaleler.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span>İhaleler</span>
        </a>
        <a href="{{ route('admin.teklifler.index') }}" class="panel-bottom-nav-item {{ request()->routeIs('admin.teklifler.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Teklifler</span>
        </a>
        <button type="button" data-open-admin-drawer class="panel-bottom-nav-item {{ request()->routeIs('admin.users.*', 'admin.musteriler.*', 'admin.disputes.*', 'admin.blog.*', 'admin.settings.*', 'admin.sitemap.*', 'admin.notifications.*', 'admin.profile.*', 'admin.consent-logs.*', 'admin.blocklist.*', 'admin.faq.*', 'admin.site-contact-messages.*') ? 'active' : '' }}" aria-label="Menüyü aç" aria-haspopup="true" aria-expanded="false">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <span>Menü</span>
            @if($unreadNotif > 0)<span class="absolute top-0 right-1/4 w-2 h-2 rounded-full bg-emerald-500"></span>@endif
        </button>
    </div>
</nav>
