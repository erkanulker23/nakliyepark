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
        {{-- İhale oluştur: vurgulu, yükseltilmiş buton (FAB tarzı) --}}
        <a href="{{ route('ihale.create') }}" class="flex flex-col items-center justify-center gap-0.5 min-w-[56px] min-h-[48px] -mt-4 rounded-2xl bg-gradient-to-b from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white shadow-lg shadow-emerald-500/35 hover:shadow-xl hover:shadow-emerald-500/40 active:scale-95 transition-all duration-200 ring-4 ring-white dark:ring-zinc-900/95 {{ request()->routeIs('ihale.create') || request()->routeIs('ihale.store') ? 'ring-2 ring-emerald-400' : '' }}" aria-current="{{ request()->routeIs('ihale.create') ? 'page' : null }}">
            <svg class="w-7 h-7 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span class="text-[10px] sm:text-xs font-semibold">İhale Başlat</span>
        </a>
        @if($show_firmalar_page ?? true)
        <a href="{{ route('firmalar.index') }}" class="bottom-nav-item flex flex-col items-center justify-center gap-0.5 min-w-[52px] min-h-[44px] rounded-xl text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 active:bg-emerald-500/10 {{ request()->routeIs('firmalar.*') ? 'text-emerald-600 dark:text-emerald-400 font-medium' : '' }}" aria-current="{{ request()->routeIs('firmalar.*') ? 'page' : null }}">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span class="text-[10px] sm:text-xs">Firmalar</span>
        </a>
        @endif
        <a href="{{ route('defter.index') }}" class="bottom-nav-item flex flex-col items-center justify-center gap-0.5 min-w-[52px] min-h-[44px] rounded-xl text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 active:bg-emerald-500/10 {{ request()->routeIs('defter.*') ? 'text-emerald-600 dark:text-emerald-400 font-medium' : '' }}" aria-current="{{ request()->routeIs('defter.*') ? 'page' : null }}">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <span class="text-[10px] sm:text-xs">Defter</span>
        </a>
        <a href="{{ route('pazaryeri.index') }}" class="bottom-nav-item flex flex-col items-center justify-center gap-0.5 min-w-[52px] min-h-[44px] rounded-xl text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 active:bg-emerald-500/10 {{ request()->routeIs('pazaryeri.*') ? 'text-emerald-600 dark:text-emerald-400 font-medium' : '' }}" aria-current="{{ request()->routeIs('pazaryeri.*') ? 'page' : null }}">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            <span class="text-[10px] sm:text-xs">Pazaryeri</span>
        </a>
    </div>
</nav>
