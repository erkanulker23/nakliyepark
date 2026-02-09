@extends('layouts.app')

@section('title', 'Nakliyat Defteri - NakliyePark')

@section('content')
<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900/50">
    {{-- Hero --}}
    <section class="relative py-8 sm:py-12 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-amber-500/5 to-transparent dark:from-amber-500/5"></div>
        <div class="page-container relative">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="max-w-2xl">
                    <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Nakliyat defteri</h1>
                    <p class="text-zinc-600 dark:text-zinc-400 mt-1 text-sm sm:text-base">Firmaların paylaştığı yük ilanları. Günde 200'den fazla ilan ekleniyor.</p>
                </div>
                @auth
                    @if(auth()->user()->isNakliyeci() && auth()->user()->company?->isApproved())
                        <a href="{{ route('nakliyeci.ledger.create') }}" class="btn-primary shrink-0 inline-flex gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Deftere yaz
                        </a>
                    @else
                        <span class="btn-secondary shrink-0 opacity-75 cursor-not-allowed inline-flex gap-2" title="Deftere yazabilmeniz için nakliyeci girişi yapmanız gerekmektedir.">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Deftere yaz
                        </span>
                        <p class="w-full sm:w-auto mt-1 text-xs text-zinc-500 dark:text-zinc-400">Deftere yazabilmeniz için nakliyeci girişi yapmanız gerekmektedir.</p>
                    @endif
                @else
                    <div class="shrink-0 flex flex-col items-start sm:items-end">
                        <span class="btn-secondary opacity-75 cursor-not-allowed inline-flex gap-2 pointer-events-none" title="Deftere yazabilmeniz için nakliyeci girişi yapmanız gerekmektedir.">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Deftere yaz
                        </span>
                        <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Deftere yazabilmeniz için nakliyeci girişi yapmanız gerekmektedir.</p>
                        <p class="mt-1 text-xs">
                            <a href="{{ route('login') }}" class="text-amber-600 dark:text-amber-400 hover:underline">Giriş yap</a>
                            <span class="text-zinc-400 mx-1">/</span>
                            <a href="{{ route('register') }}" class="text-amber-600 dark:text-amber-400 hover:underline">Kayıt ol</a>
                        </p>
                    </div>
                @endauth
            </div>
        </div>
    </section>

    <div class="page-container pb-16 sm:pb-24">
        {{-- Üst reklam alanı (rastgele) --}}
        @if($reklamUst->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                @foreach($reklamUst as $reklam)
                    <div class="defter-reklam rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden bg-white dark:bg-zinc-800 p-4">
                        @if($reklam->link)
                            <a href="{{ $reklam->link }}" target="_blank" rel="noopener noreferrer" class="block">
                        @endif
                        @if($reklam->resim)
                            <img src="{{ $reklam->resim }}" alt="{{ $reklam->baslik ?? 'Reklam' }}" class="w-full h-24 object-cover rounded-lg mb-2">
                        @endif
                        @if($reklam->baslik)
                            <p class="font-medium text-zinc-900 dark:text-white">{{ $reklam->baslik }}</p>
                        @endif
                        @if($reklam->icerik)
                            <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">{!! $reklam->icerik !!}</div>
                        @endif
                        @if($reklam->link)
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Şehir filtreleri (chip'ler) --}}
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach($popularCities as $city)
                @php
                    $activeNereden = request('nereden') === $city;
                @endphp
                <a href="{{ route('defter.index', array_merge(request()->only(['nereye', 'tarih', 'ara']), $activeNereden ? [] : ['nereden' => $city])) }}"
                   class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $activeNereden ? 'bg-amber-500 text-white' : 'bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:border-amber-400 dark:hover:border-amber-500' }}">
                    {{ $city }}
                </a>
            @endforeach
        </div>

        {{-- Yeni müşteri talepleri --}}
        @if($sonIhaleler->isNotEmpty())
            <div class="card-premium-flat p-5 sm:p-6 mb-6 border-l-4 border-amber-500 bg-amber-50/50 dark:bg-amber-900/10 dark:border-amber-500">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </span>
                        <div>
                            <h2 class="font-semibold text-zinc-900 dark:text-white">Yeni müşteri talepleri var!</h2>
                            <ul class="mt-2 space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                                @foreach($sonIhaleler as $ihale)
                                    <li>
                                        <a href="{{ route('ihaleler.show', $ihale) }}" class="hover:text-amber-600 dark:hover:text-amber-400">
                                            {{ $ihale->from_city }} → {{ $ihale->to_city }} arası evden eve nakliyat →
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <a href="{{ route('ihaleler.index') }}" class="btn-primary shrink-0 inline-flex gap-2">
                        Hemen Teklif Ver →
                    </a>
                </div>
            </div>
        @endif

        {{-- Defterde ara --}}
        <div class="card-premium-flat p-4 sm:p-5 mb-6">
            <form method="GET" action="{{ route('defter.index') }}" class="flex flex-col sm:flex-row gap-3">
                <input type="hidden" name="nereden" value="{{ request('nereden') }}">
                <input type="hidden" name="nereye" value="{{ request('nereye') }}">
                <input type="hidden" name="tarih" value="{{ request('tarih') }}">
                <div class="flex-1 flex flex-col sm:flex-row gap-3">
                    <label class="sr-only" for="defter-ara">Defterde ara</label>
                    <input id="defter-ara" type="text" name="ara" value="{{ request('ara') }}" class="input-touch py-2.5 text-sm flex-1" placeholder="Defterde ara! Şehir, güzergah veya açıklama...">
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary py-2.5 text-sm">Ara</button>
                        @if(request()->hasAny(['nereden','nereye','tarih','ara']))
                            <a href="{{ route('defter.index') }}" class="btn-secondary py-2.5 text-sm">Temizle</a>
                        @endif
                    </div>
                </div>
            </form>
            <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">Deftere yazmak için nakliyeci girişi yapıp yukarıdaki <strong>Deftere yaz</strong> butonunu kullanın.</p>
        </div>

        {{-- Promosyon bantları --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            <a href="{{ route('register') }}" class="flex items-center justify-between gap-4 p-4 rounded-2xl bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-800 hover:bg-sky-100 dark:hover:bg-sky-900/30 transition-colors">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-sky-200 dark:bg-sky-800 text-sky-600 dark:text-sky-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </span>
                    <span class="font-medium text-zinc-900 dark:text-white">Güvenli Komisyon Sistemi</span>
                </div>
                <span class="text-sm font-medium text-sky-600 dark:text-sky-400 shrink-0">Hemen Başlat →</span>
            </a>
            <a href="{{ route('pazaryeri.index') }}" class="flex items-center justify-between gap-4 p-4 rounded-2xl bg-violet-50 dark:bg-violet-900/20 border border-violet-200 dark:border-violet-800 hover:bg-violet-100 dark:hover:bg-violet-900/30 transition-colors">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-violet-200 dark:bg-violet-800 text-violet-600 dark:text-violet-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </span>
                    <span class="font-medium text-zinc-900 dark:text-white">Paylaşımlarını öne çıkar.</span>
                </div>
                <span class="text-sm font-medium text-violet-600 dark:text-violet-400 shrink-0">Hemen Öne Çık →</span>
            </a>
        </div>

        <div class="lg:flex lg:gap-8">
            {{-- Ana içerik --}}
            <div class="flex-1 min-w-0">
                {{-- Defter listesi (kartlar) --}}
                <div class="space-y-4">
                    @forelse($ilanlar as $ilan)
                        <article class="card-premium-flat p-5 sm:p-6 hover:border-amber-200 dark:hover:border-amber-800/50 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <a href="{{ route('firmalar.show', $ilan->company) }}" class="font-semibold text-zinc-900 dark:text-white hover:text-amber-600 dark:hover:text-amber-400 uppercase tracking-wide">
                                            {{ $ilan->company->name }}
                                        </a>
                                        <span class="text-xs text-zinc-400 dark:text-zinc-500">#{{ 98000000 + $ilan->id }}</span>
                                        <span class="inline-flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            @php
                                                $tarih = $ilan->created_at->locale('tr');
                                                $dakika = $ilan->created_at->diffInMinutes(now());
                                            @endphp
                                            @if($dakika < 2)
                                                Şimdi
                                            @elseif($dakika < 60)
                                                {{ $tarih->diffForHumans(now(), null, true) }}
                                            @else
                                                {{ $tarih->diffForHumans(now()) }}
                                            @endif
                                        </span>
                                    </div>
                                    @if($ilan->description)
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">{{ $ilan->description }}</p>
                                    @endif
                                    <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mt-2">
                                        {{ $ilan->from_city }} → {{ $ilan->to_city }}
                                        @if($ilan->load_date)
                                            <span class="text-zinc-500 dark:text-zinc-400 font-normal"> · Yük: {{ $ilan->load_date->format('d.m.Y') }}</span>
                                        @endif
                                        @if($ilan->volume_m3)
                                            <span class="text-amber-600 dark:text-amber-400"> · {{ number_format($ilan->volume_m3, 1) }} m³</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="shrink-0 text-right text-xs text-zinc-500 dark:text-zinc-400">
                                    @if($ilan->company->created_at)
                                        {{ $ilan->company->created_at->locale('tr')->translatedFormat('F Y') }} katıldı
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="card-premium-flat p-12 text-center">
                            <p class="text-zinc-500 dark:text-zinc-400">Henüz yük ilanı yok veya filtreye uygun kayıt bulunamadı.</p>
                            <a href="{{ route('defter.index') }}" class="inline-block mt-3 text-sm font-medium text-amber-600 dark:text-amber-400 hover:underline">Filtreleri temizle</a>
                        </div>
                    @endforelse
                </div>

                @if($ilanlar->hasPages())
                    <div class="mt-8">{{ $ilanlar->links() }}</div>
                @endif
            </div>

            {{-- Sağ sütun: Reklamlar (rastgele) --}}
            @if($reklamSidebar->isNotEmpty())
                <aside class="lg:w-72 shrink-0 mt-8 lg:mt-0 space-y-4">
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Reklamlar</p>
                    @foreach($reklamSidebar as $reklam)
                        <div class="defter-reklam card-premium-flat p-4 overflow-hidden">
                            @if($reklam->link)
                                <a href="{{ $reklam->link }}" target="_blank" rel="noopener noreferrer" class="block">
                            @endif
                            @if($reklam->resim)
                                <img src="{{ $reklam->resim }}" alt="{{ $reklam->baslik ?? 'Reklam' }}" class="w-full h-32 object-cover rounded-lg mb-2">
                            @endif
                            @if($reklam->baslik)
                                <p class="font-semibold text-zinc-900 dark:text-white">{{ $reklam->baslik }}</p>
                            @endif
                            @if($reklam->icerik)
                                <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">{!! $reklam->icerik !!}</div>
                            @endif
                            @if($reklam->link)
                                </a>
                            @endif
                        </div>
                    @endforeach
                </aside>
            @endif
        </div>

        {{-- Alt reklam alanı (rastgele) --}}
        @if($reklamAlt->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-10 pt-8 border-t border-zinc-200 dark:border-zinc-700">
                @foreach($reklamAlt as $reklam)
                    <div class="defter-reklam rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden bg-white dark:bg-zinc-800 p-4">
                        @if($reklam->link)
                            <a href="{{ $reklam->link }}" target="_blank" rel="noopener noreferrer" class="block">
                        @endif
                        @if($reklam->resim)
                            <img src="{{ $reklam->resim }}" alt="{{ $reklam->baslik ?? 'Reklam' }}" class="w-full h-20 object-cover rounded-lg mb-2">
                        @endif
                        @if($reklam->baslik)
                            <p class="font-medium text-zinc-900 dark:text-white">{{ $reklam->baslik }}</p>
                        @endif
                        @if($reklam->icerik)
                            <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">{!! $reklam->icerik !!}</div>
                        @endif
                        @if($reklam->link)
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
