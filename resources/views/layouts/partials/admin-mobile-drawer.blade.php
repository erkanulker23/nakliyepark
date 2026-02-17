{{-- Mobil: Menü butonu ile açılan tam menü (drawer) - sol menü ile aynı liste --}}
<div id="admin-mobile-drawer" class="fixed inset-0 z-50 lg:hidden pointer-events-none [&.open]:pointer-events-auto" aria-hidden="true" role="dialog" aria-label="Menü">
    <div id="admin-mobile-drawer-backdrop" class="absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-200" data-close-drawer></div>
    <div id="admin-mobile-drawer-panel" class="absolute top-0 right-0 bottom-0 w-[min(320px,100vw-3rem)] bg-[var(--panel-surface)] shadow-2xl transform translate-x-full transition-transform duration-200 ease-out flex flex-col">
        <div class="flex items-center justify-between p-4 border-b border-[var(--panel-border)]">
            <span class="font-semibold text-[var(--panel-text)]">Menü</span>
            <button type="button" id="admin-mobile-drawer-close" class="p-2 rounded-xl text-[var(--panel-text-muted)] hover:bg-[var(--panel-primary-soft)] hover:text-[var(--panel-primary)]" aria-label="Kapat">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto p-3 space-y-0.5" style="max-height: calc(100vh - 4rem);">
            @include('layouts.partials.admin-menu-links')
            <form method="POST" action="{{ route('logout') }}" class="block mt-2">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-3 py-3 rounded-xl w-full text-left text-[var(--panel-text-muted)] hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Çıkış
                </button>
            </form>
        </nav>
    </div>
</div>
