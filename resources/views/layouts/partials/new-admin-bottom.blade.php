@php
$pendingCount = ($pending_companies_count ?? 0) + ($pending_ihaleler_count ?? 0) + ($teklif_pending_count ?? 0);
$active = fn($r) => request()->routeIs($r) ? ' is-active' : '';
@endphp
<nav class="app-bottom" aria-label="Ana menü">
    <div class="app-bottom__inner">
        <a href="{{ route('admin.dashboard') }}" class="app-bottom__item{{ $active('admin.dashboard') }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <span>Panel</span>
        </a>
        <a href="{{ route('admin.companies.index') }}" class="app-bottom__item{{ $active('admin.companies.*') }}">
            <span style="position:relative;display:inline-flex">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                @if($pendingCount > 0)<span style="position:absolute;top:-4px;right:-4px;min-width:18px;height:18px;padding:0 4px;font-size:10px;font-weight:700;border-radius:9999px;background:var(--app-accent);color:#fff;display:flex;align-items:center;justify-content:center">{{ $pendingCount > 99 ? '99+' : $pendingCount }}</span>@endif
            </span>
            <span>Firmalar</span>
        </a>
        <a href="{{ route('admin.ihaleler.index') }}" class="app-bottom__item{{ $active('admin.ihaleler.*') }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span>İhaleler</span>
        </a>
        <a href="{{ route('admin.teklifler.index') }}" class="app-bottom__item{{ $active('admin.teklifler.*') }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Teklifler</span>
        </a>
        <button type="button" data-open-app-drawer class="app-bottom__item{{ request()->routeIs('admin.users.*','admin.musteriler.*','admin.disputes.*','admin.blog.*','admin.settings.*','admin.sitemap.*','admin.notifications.*','admin.profile.*','admin.consent-logs.*','admin.blocklist.*','admin.faq.*','admin.site-contact-messages.*','admin.yuk-ilanlari.*','admin.reklam-alanlari.*','admin.reviews.*','admin.blog-categories.*','admin.homepage-editor.*','admin.sponsors.*') ? ' is-active' : '' }}" aria-label="Menüyü aç" aria-haspopup="true" aria-expanded="false">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <span>Menü</span>
            @if(($header_unread_count ?? 0) > 0)<span style="position:absolute;top:0;right:25%;width:8px;height:8px;border-radius:50%;background:var(--app-accent);"></span>@endif
        </button>
    </div>
</nav>
