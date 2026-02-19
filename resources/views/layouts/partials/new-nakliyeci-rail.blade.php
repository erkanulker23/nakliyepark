@php $active = fn($r) => request()->routeIs($r) ? ' is-active' : ''; @endphp
<aside class="app-rail" aria-label="Menü">
    <a href="{{ route('nakliyeci.dashboard') }}" class="app-rail__item{{ $active('nakliyeci.dashboard') }}" data-tooltip="Kontrol Paneli">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
    </a>
    <a href="{{ route('nakliyeci.ihaleler.index') }}" class="app-rail__item{{ $active('nakliyeci.ihaleler.*') }}" data-tooltip="Açık İhaleler">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    </a>
    <a href="{{ route('nakliyeci.teklifler.index') }}" class="app-rail__item{{ $active('nakliyeci.teklifler.*') }}" data-tooltip="Tekliflerim">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    </a>
    <a href="{{ route('nakliyeci.borc.index') }}" class="app-rail__item{{ $active('nakliyeci.borc.*') }}" data-tooltip="Borç / Ödeme">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6a2 2 0 002 2zm2-8a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    </a>
    <a href="{{ route('nakliyeci.company.edit') }}" class="app-rail__item{{ $active('nakliyeci.company.*') }}" data-tooltip="Firma Bilgileri">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    </a>
    @if($show_pazaryeri_page ?? true)
    <a href="{{ route('nakliyeci.pazaryeri.index') }}" class="app-rail__item{{ $active('nakliyeci.pazaryeri.*') }}" data-tooltip="Pazaryeri İlanlarım">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
    </a>
    @endif
    <div class="app-rail__grow"></div>
    <a href="{{ route('nakliyeci.bilgilerim.edit') }}" class="app-rail__item{{ $active('nakliyeci.bilgilerim.*') }}" data-tooltip="Bilgilerim">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
    </a>
    <form method="POST" action="{{ route('logout') }}" class="app-rail__logout">
        @csrf
        <button type="submit" class="app-rail__item" data-tooltip="Çıkış" aria-label="Çıkış yap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        </button>
    </form>
</aside>
