{{-- Mobil uygulama alt navigasyonu: sadece mobil/tablet (lg altı) --}}
<nav class="fixed bottom-0 left-0 right-0 z-40 lg:hidden bg-white/95 dark:bg-zinc-900/95 backdrop-blur-xl border-t border-zinc-200 dark:border-zinc-800 safe-area-bottom safe-bottom" aria-label="Ana navigasyon">
    <div class="max-w-6xl mx-auto flex items-center justify-around min-h-[56px] py-2 gap-1">
        <a href="{{ url('/') }}" class="bottom-nav-item flex flex-col items-center justify-center gap-0.5 min-w-[52px] min-h-[44px] rounded-xl text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 active:bg-emerald-500/10 {{ request()->routeIs('home') ? 'text-emerald-600 dark:text-emerald-400 font-medium' : '' }}" aria-current="{{ request()->routeIs('home') ? 'page' : null }}">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-[10px] sm:text-xs">Ana Sayfa</span>
        </a>
        <a href="{{ route('ihaleler.index') }}" class="bottom-nav-item flex flex-col items-center justify-center gap-0.5 min-w-[52px] min-h-[44px] rounded-xl text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 active:bg-emerald-500/10 {{ request()->routeIs('ihaleler.*') ? 'text-emerald-600 dark:text-emerald-400 font-medium' : '' }}" aria-current="{{ request()->routeIs('ihaleler.*') ? 'page' : null }}">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            <span class="text-[10px] sm:text-xs">İhaleler</span>
        </a>
        {{-- İhale oluştur: belirgin FAB, + simgesi, farklı tasarım --}}
        <a href="{{ route('ihale.create') }}" class="group flex flex-col items-center justify-center gap-1 min-w-[64px] min-h-[56px] -mt-5 rounded-2xl bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-700 hover:from-emerald-400 hover:via-emerald-500 hover:to-teal-600 text-white shadow-xl shadow-emerald-600/45 hover:shadow-2xl hover:shadow-emerald-500/50 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 ring-4 ring-white dark:ring-zinc-900/95 border-2 border-white/30 dark:border-white/10 {{ request()->routeIs('ihale.create') || request()->routeIs('ihale.store') ? 'ring-2 ring-emerald-400 ring-offset-2 ring-offset-white dark:ring-offset-zinc-900' : '' }}" aria-current="{{ request()->routeIs('ihale.create') ? 'page' : null }}">
            <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-white/20 backdrop-blur-sm group-hover:bg-white/30 transition-colors">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            </span>
            <span class="text-[10px] sm:text-xs font-bold tracking-wide">+ İhale Başlat</span>
        </a>
        @if($show_firmalar_page ?? true)
        <a href="{{ route('firmalar.index') }}" class="bottom-nav-item flex flex-col items-center justify-center gap-0.5 min-w-[52px] min-h-[44px] rounded-xl text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 active:bg-emerald-500/10 {{ request()->routeIs('firmalar.*') ? 'text-emerald-600 dark:text-emerald-400 font-medium' : '' }}" aria-current="{{ request()->routeIs('firmalar.*') ? 'page' : null }}">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span class="text-[10px] sm:text-xs">Firmalar</span>
        </a>
        @endif
    </div>
</nav>
