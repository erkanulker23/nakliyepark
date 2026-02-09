<header class="sticky top-0 z-50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border-b border-zinc-200/80 dark:border-zinc-800 safe-top">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 shrink-0">
                @if(!empty($site_logo_url))
                    <img src="{{ $site_logo_url }}" alt="{{ $site_meta_title ?? 'NakliyePark' }}" class="h-9 w-auto max-w-[140px] object-contain">
                @else
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-sm shadow-sm">N</span>
                @endif
                <span class="font-semibold text-zinc-900 dark:text-white text-lg tracking-tight">NakliyePark</span>
            </a>
            <nav class="hidden lg:flex items-center gap-0.5">
                <a href="{{ route('ihaleler.index') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400">İhaleler</a>
                <a href="{{ route('firmalar.index') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400">Firmalar</a>
                <a href="{{ route('defter.index') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400">Defter</a>
                <a href="{{ route('pazaryeri.index') }}" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400">Pazaryeri</a>
                <div class="relative" id="tools-dropdown-wrap">
                    <button type="button" class="btn-ghost rounded-lg text-zinc-600 dark:text-zinc-400 inline-flex items-center gap-1 tools-dropdown-btn" aria-expanded="false" aria-haspopup="true" aria-controls="tools-dropdown-menu" id="tools-menu-btn">
                        Yardımcı araçlar
                        <svg class="w-4 h-4 transition-transform tools-dropdown-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="tools-dropdown-menu" class="absolute left-0 top-full mt-1 w-56 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-lg py-1 z-50 tools-dropdown-panel" role="menu" aria-labelledby="tools-menu-btn" hidden>
                        <a href="{{ route('tools.volume') }}" class="block px-4 py-2.5 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800" role="menuitem">Hacim hesaplama</a>
                        <a href="{{ route('tools.distance') }}" class="block px-4 py-2.5 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800" role="menuitem">Mesafe hesaplama</a>
                        <a href="{{ route('tools.cost') }}" class="block px-4 py-2.5 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800" role="menuitem">Tahmini maliyet</a>
                    </div>
                </div>
            </nav>
            <div class="flex items-center gap-2">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn-ghost rounded-lg hidden sm:inline-flex">Admin</a>
                    @endif
                    @if(auth()->user()->isNakliyeci())
                        <a href="{{ route('nakliyeci.dashboard') }}" class="btn-ghost rounded-lg hidden sm:inline-flex">Panel</a>
                        <a href="{{ route('nakliyeci.company.edit') }}" class="btn-ghost rounded-lg hidden sm:inline-flex">Firmam</a>
                        <a href="{{ route('nakliyeci.ledger') }}" class="btn-ghost rounded-lg hidden sm:inline-flex">Defter</a>
                    @endif
                    @if(auth()->user()->isMusteri())
                        <a href="{{ route('musteri.dashboard') }}" class="btn-ghost rounded-lg hidden sm:inline-flex">İhalelerim</a>
                        @php $musteriNotifCount = auth()->user()->userNotifications()->whereNull('read_at')->count(); @endphp
                        <a href="{{ route('musteri.notifications.index') }}" class="btn-ghost rounded-lg hidden sm:inline-flex relative">
                            Bildirimler
                            @if($musteriNotifCount > 0)<span class="absolute -top-0.5 -right-1 min-w-[18px] h-[18px] flex items-center justify-center rounded-full bg-emerald-500 text-white text-xs">{{ $musteriNotifCount > 99 ? '99+' : $musteriNotifCount }}</span>@endif
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="btn-ghost rounded-lg text-zinc-500">Çıkış</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary rounded-lg hidden sm:inline-flex">Giriş</a>
                    <a href="{{ route('register') }}" class="btn-primary rounded-lg">Hizmet ver</a>
                @endauth
            </div>
        </div>
        <div class="lg:hidden flex items-center gap-1 pb-3 overflow-x-auto -mb-px">
            <a href="{{ route('ihaleler.index') }}" class="btn-ghost rounded-lg text-xs whitespace-nowrap py-2.5">İhaleler</a>
            <a href="{{ route('firmalar.index') }}" class="btn-ghost rounded-lg text-xs whitespace-nowrap py-2.5">Firmalar</a>
            <a href="{{ route('defter.index') }}" class="btn-ghost rounded-lg text-xs whitespace-nowrap py-2.5">Defter</a>
            <a href="{{ route('pazaryeri.index') }}" class="btn-ghost rounded-lg text-xs whitespace-nowrap py-2.5">Pazaryeri</a>
            <a href="{{ route('tools.volume') }}" class="btn-ghost rounded-lg text-xs whitespace-nowrap py-2.5">Hacim</a>
            <a href="{{ route('tools.distance') }}" class="btn-ghost rounded-lg text-xs whitespace-nowrap py-2.5">Mesafe</a>
            <a href="{{ route('tools.cost') }}" class="btn-ghost rounded-lg text-xs whitespace-nowrap py-2.5">Maliyet</a>
        </div>
    </div>
</header>
