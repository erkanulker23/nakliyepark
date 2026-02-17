<div id="app-drawer" class="app-drawer lg:hidden" aria-hidden="true" role="dialog" aria-label="Menü">
    <div id="app-drawer-backdrop" class="app-drawer__backdrop"></div>
    <div class="app-drawer__panel">
        <div class="app-drawer__header">
            <span class="app-drawer__title">Menü</span>
            <button type="button" id="app-drawer-close" class="app-drawer__close" aria-label="Kapat">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="app-drawer__body">
            @include('layouts.partials.new-admin-menu-links')
            <form method="POST" action="{{ route('logout') }}" class="block mt-4">
                @csrf
                <button type="submit" class="app-nav-link" style="width:100%; justify-content: flex-start; color: var(--app-text-muted);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1.25rem;height:1.25rem"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Çıkış
                </button>
            </form>
        </div>
    </div>
</div>
