<nav class="sticky top-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur border-b border-slate-200 dark:border-slate-700 safe-top">
    <div class="flex items-center justify-between min-h-[44px] sm:min-h-[52px] px-4 lg:container lg:mx-auto">
        <a href="{{ url('/') }}" class="btn-touch text-sky-600 dark:text-sky-400 font-bold text-lg">
            NakliyePark
        </a>
        <div class="flex items-center gap-1 sm:gap-2">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn-touch text-sm text-slate-600 dark:text-slate-400">Admin</a>
                @endif
                @if(auth()->user()->isNakliyeci())
                    <a href="{{ route('nakliyeci.dashboard') }}" class="btn-touch text-sm text-slate-600 dark:text-slate-400">Panel</a>
                    <a href="{{ route('nakliyeci.ledger') }}" class="btn-touch text-sm text-slate-600 dark:text-slate-400">Defter</a>
                @endif
                @if(auth()->user()->isMusteri())
                    <a href="{{ route('musteri.dashboard') }}" class="btn-touch text-sm text-slate-600 dark:text-slate-400">Panelim</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-touch text-sm bg-slate-100 dark:bg-slate-800 rounded-xl">Giriş</a>
                <a href="{{ route('register') }}" class="btn-touch text-sm bg-sky-500 text-white rounded-xl">Kayıt</a>
            @endauth
        </div>
    </div>
</nav>
