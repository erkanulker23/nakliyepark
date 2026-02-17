{{-- Sol menü paneli — body seviyesinde, rail yok; header’daki Menü butonu ile açılır --}}
<div id="app-admin-flyout" class="app-flyout is-hidden" role="menu" aria-hidden="true">
    <div class="app-flyout__head">Tüm menü</div>
    <nav class="app-flyout__nav" aria-label="Tüm menü linkleri">
        @include('layouts.partials.new-admin-menu-links')
    </nav>
    <div class="app-flyout__footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="app-nav-link" style="width:100%; justify-content: flex-start; color: var(--app-text-muted);">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1.125rem;height:1.125rem"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Çıkış
            </button>
        </form>
    </div>
</div>
