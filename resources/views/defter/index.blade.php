@extends('layouts.app')

@section('title', 'Nakliyat Defteri - NakliyePark')
@section('meta_description', 'Nakliyat defteri: Yük ilanları ve boş dönüş ilanları. Nakliye firmaları yük ve dönüş ilanlarını burada paylaşır. Ücretsiz ilan verin.')

@section('content')
<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900/50">
    {{-- Hero --}}
    <section class="relative py-8 sm:py-12 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-amber-500/5 to-transparent dark:from-amber-500/5"></div>
        <div class="page-container relative">
            {{-- Başlık + Deftere yaz --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Nakliyat defteri</h1>
                @auth
                    @if(auth()->user()->isNakliyeci())
                        @if(auth()->user()->company?->isApproved())
                            <div class="flex flex-wrap items-center gap-2 shrink-0">
                                @include('partials.defter-share-buttons', ['url' => route('defter.index'), 'title' => 'Nakliyat Defteri — Yük ve boş dönüş ilanları. Nakliyeciler burada ilan paylaşır.', 'label' => 'Defteri paylaş', 'wrapperClass' => 'shrink-0'])
                                <button type="button" id="defter-yaz-open" class="btn-primary shrink-0 inline-flex gap-2" aria-haspopup="dialog" aria-expanded="false">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Deftere yaz
                                </button>
                            </div>
                        @else
                            <div class="flex flex-wrap items-center gap-2 shrink-0">
                                @include('partials.defter-share-buttons', ['url' => route('defter.index'), 'title' => 'Nakliyat Defteri — Yük ve boş dönüş ilanları. Nakliyeciler burada ilan paylaşır.', 'label' => 'Defteri paylaş', 'wrapperClass' => 'shrink-0'])
                                <span class="btn-secondary shrink-0 opacity-75 cursor-not-allowed inline-flex gap-2" title="Firmanız onaylandıktan sonra deftere yazabilirsiniz.">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Deftere yaz
                                </span>
                            </div>
                        @endif
                    @else
                        <div class="flex flex-wrap items-center gap-2 shrink-0">
                            @include('partials.defter-share-buttons', ['url' => route('defter.index'), 'title' => 'Nakliyat Defteri — Yük ve boş dönüş ilanları. Nakliyeciler burada ilan paylaşır.', 'label' => 'Defteri paylaş', 'wrapperClass' => 'shrink-0'])
                            <span class="btn-secondary shrink-0 opacity-75 cursor-not-allowed inline-flex gap-2" title="Deftere yazabilmeniz için nakliyeci girişi yapmanız gerekmektedir.">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Deftere yaz
                            </span>
                        </div>
                    @endif
                @else
                    <div class="flex flex-wrap items-center gap-2 shrink-0">
                        @include('partials.defter-share-buttons', ['url' => route('defter.index'), 'title' => 'Nakliyat Defteri — Yük ve boş dönüş ilanları. Nakliyeciler burada ilan paylaşır.', 'label' => 'Defteri paylaş', 'wrapperClass' => 'shrink-0'])
                        <span class="btn-secondary shrink-0 opacity-75 cursor-not-allowed inline-flex gap-2 pointer-events-none" title="Deftere yazabilmeniz için nakliyeci girişi yapmanız gerekmektedir.">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Deftere yaz
                        </span>
                    </div>
                @endauth
            </div>

            {{-- İstatistikler --}}
            <div class="flex flex-wrap gap-3 sm:gap-4 mb-4">
                <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200/80 dark:border-amber-800/50">
                    <span class="text-2xl font-bold text-amber-600 dark:text-amber-400 tabular-nums">{{ number_format($todayCount) }}</span>
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">bugün</span>
                </div>
                <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-zinc-100 dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700">
                    <span class="text-2xl font-bold text-zinc-800 dark:text-zinc-200 tabular-nums">{{ number_format($weekCount) }}</span>
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">bu hafta</span>
                </div>
                <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-zinc-100 dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700">
                    <span class="text-2xl font-bold text-zinc-800 dark:text-zinc-200 tabular-nums">{{ number_format($totalCount) }}</span>
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">toplam</span>
                </div>
            </div>

            {{-- Bilgi metni --}}
            <p class="text-zinc-600 dark:text-zinc-400 text-sm">
                Firmaların paylaştığı yük ilanları.
                @guest
                    <a href="{{ route('login') }}" class="text-amber-600 dark:text-amber-400 hover:underline">Giriş yap</a>
                    <span class="text-zinc-400 mx-1">/</span>
                    <a href="{{ route('register') }}" class="text-amber-600 dark:text-amber-400 hover:underline">Kayıt ol</a>
                    ile deftere yazabilirsiniz.
                @else
                    @if(auth()->user()->isNakliyeci())
                        @if(!auth()->user()->company?->isApproved())
                            <span class="text-amber-600 dark:text-amber-400">Firmanız onaylı değilse deftere yazamazsınız. <a href="{{ route('nakliyeci.company.edit') }}" class="font-medium underline">Firma bilgilerinizi tamamlayın</a>.</span>
                        @else
                            Yukarıdaki buton ile hemen ilan ekleyebilirsiniz.
                        @endif
                    @else
                        Deftere yazabilmeniz için nakliyeci girişi yapmanız gerekmektedir.
                    @endif
                @endguest
            </p>
        </div>
    </section>

    <div class="page-container pb-16 sm:pb-24">
        {{-- Üst reklam alanı --}}
        @php $reklamUst = \App\Models\AdZone::getForPagePosition('defter', 'ust', 2); @endphp
        @if($reklamUst->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                @foreach($reklamUst as $reklam)
                    <div class="defter-reklam rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden bg-white dark:bg-zinc-800 p-4">
                        @if($reklam->isCode())
                            {!! $reklam->kod !!}
                        @else
                            @if($reklam->link)<a href="{{ $reklam->link }}" target="_blank" rel="noopener noreferrer nofollow" class="block">@endif
                            @if($reklam->resim)<img src="{{ $reklam->resim }}" alt="{{ $reklam->baslik ?? 'Reklam' }}" class="w-full h-24 object-cover rounded-lg mb-2" loading="lazy">@endif
                            @if($reklam->baslik)<p class="font-medium text-zinc-900 dark:text-white">{{ $reklam->baslik }}</p>@endif
                            @if($reklam->link)</a>@endif
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
            <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">Deftere yazmak veya ilanlara yanıt vermek için nakliyeci girişi yapıp yukarıdaki <strong>Deftere yaz</strong> butonunu kullanın. Mevcut ilanların altından yanıt yazabilirsiniz.</p>
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
                                        @if($show_firmalar_page ?? true)
                                        <a href="{{ route('firmalar.show', $ilan->company) }}" class="font-semibold text-zinc-900 dark:text-white hover:text-amber-600 dark:hover:text-amber-400 uppercase tracking-wide">
                                            {{ $ilan->company->name }}
                                        </a>
                                        @else
                                        <span class="font-semibold text-zinc-900 dark:text-white uppercase tracking-wide">{{ $ilan->company->name }}</span>
                                        @endif
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
                                    @php
                                        $ilanShareTitle = 'Nakliyat Defteri - Yük İlanı: ' . $ilan->company->name . ' — ' . $ilan->from_city . ' → ' . $ilan->to_city;
                                        if ($ilan->load_date) { $ilanShareTitle .= ' · Yük: ' . $ilan->load_date->format('d.m.Y'); }
                                        if ($ilan->volume_m3) { $ilanShareTitle .= ' · ' . number_format((float) $ilan->volume_m3, 1, ',', '') . ' m³'; }
                                    @endphp
                                    <span class="inline-block mt-3">
                                        @include('partials.defter-share-buttons', ['url' => route('defter.show', $ilan), 'title' => $ilanShareTitle, 'label' => 'Paylaş'])
                                    </span>
                                </div>
                                <div class="shrink-0 text-right text-xs text-zinc-500 dark:text-zinc-400">
                                    @if($ilan->company->created_at)
                                        {{ $ilan->company->created_at->locale('tr')->translatedFormat('F Y') }} katıldı
                                    @endif
                                </div>
                            </div>

                            {{-- Yanıtlar --}}
                            @if($ilan->yanitlar->isNotEmpty())
                                <div class="mt-5 pt-5 border-t border-zinc-200 dark:border-zinc-700">
                                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-4">Yanıtlar ({{ $ilan->yanitlar->count() }})</p>
                                    <div class="space-y-4">
                                        @foreach($ilan->yanitlar as $yanit)
                                            <div class="pl-4 py-3 pr-3 rounded-xl bg-zinc-100/80 dark:bg-zinc-800/50 border-l-4 border-amber-400/70 dark:border-amber-500/50">
                                                <div class="flex flex-wrap items-baseline gap-2 mb-1.5">
                                                    @if($show_firmalar_page ?? true)
                                                        <a href="{{ route('firmalar.show', $yanit->company) }}" class="font-semibold text-zinc-900 dark:text-white hover:text-amber-600 dark:hover:text-amber-400">{{ $yanit->company->name }}</a>
                                                    @else
                                                        <span class="font-semibold text-zinc-900 dark:text-white">{{ $yanit->company->name }}</span>
                                                    @endif
                                                    <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $yanit->created_at->locale('tr')->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">{{ nl2br(e($yanit->body)) }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Yanıtla (sadece nakliyeci, kendi ilanı değilse) --}}
                            @auth
                                @if(auth()->user()->isNakliyeci() && auth()->user()->company?->isApproved() && $ilan->company_id !== auth()->user()->company?->id)
                                    <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                                        <form method="POST" action="{{ route('nakliyeci.ledger.reply.store', $ilan) }}" class="flex flex-col sm:flex-row gap-2">
                                            @csrf
                                            <label class="sr-only" for="yanit-{{ $ilan->id }}">Yanıtınız</label>
                                            <textarea id="yanit-{{ $ilan->id }}" name="body" rows="2" class="input-touch text-sm flex-1 resize-none" placeholder="Bu ilana yanıt yazın (örn. bu güzergahta boşum, yük birleştirebiliriz...)" maxlength="2000" required>{{ old('body') }}</textarea>
                                            <button type="submit" class="btn-primary shrink-0 self-end sm:self-auto py-2.5 px-4 text-sm">Gönder</button>
                                        </form>
                                    </div>
                                @endif
                            @else
                                <p class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700 text-xs text-zinc-500 dark:text-zinc-400">
                                    Bu ilana yanıt yazmak için <a href="{{ route('login') }}" class="text-amber-600 dark:text-amber-400 hover:underline">nakliyeci girişi</a> yapın.
                                </p>
                            @endauth
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

            {{-- Sağ sütun: Reklamlar --}}
            @php $reklamSidebar = \App\Models\AdZone::getForPagePosition('defter', 'sidebar', 5); @endphp
            @if($reklamSidebar->isNotEmpty())
                <aside class="lg:w-72 shrink-0 mt-8 lg:mt-0 space-y-4">
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Reklamlar</p>
                    @foreach($reklamSidebar as $reklam)
                        <div class="defter-reklam card-premium-flat p-4 overflow-hidden">
                            @if($reklam->isCode())
                                {!! $reklam->kod !!}
                            @else
                                @if($reklam->link)<a href="{{ $reklam->link }}" target="_blank" rel="noopener noreferrer nofollow" class="block">@endif
                                @if($reklam->resim)<img src="{{ $reklam->resim }}" alt="{{ $reklam->baslik ?? 'Reklam' }}" class="w-full h-32 object-cover rounded-lg mb-2" loading="lazy">@endif
                                @if($reklam->baslik)<p class="font-semibold text-zinc-900 dark:text-white">{{ $reklam->baslik }}</p>@endif
                                @if($reklam->link)</a>@endif
                            @endif
                        </div>
                    @endforeach
                </aside>
            @endif
        </div>

        {{-- Alt reklam alanı --}}
        @php $reklamAlt = \App\Models\AdZone::getForPagePosition('defter', 'alt', 2); @endphp
        @if($reklamAlt->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-10 pt-8 border-t border-zinc-200 dark:border-zinc-700">
                @foreach($reklamAlt as $reklam)
                    <div class="defter-reklam rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden bg-white dark:bg-zinc-800 p-4">
                        @if($reklam->isCode())
                            {!! $reklam->kod !!}
                        @else
                            @if($reklam->link)<a href="{{ $reklam->link }}" target="_blank" rel="noopener noreferrer nofollow" class="block">@endif
                            @if($reklam->resim)<img src="{{ $reklam->resim }}" alt="{{ $reklam->baslik ?? 'Reklam' }}" class="w-full h-20 object-cover rounded-lg mb-2" loading="lazy">@endif
                            @if($reklam->baslik)<p class="font-medium text-zinc-900 dark:text-white">{{ $reklam->baslik }}</p>@endif
                            @if($reklam->link)</a>@endif
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Modal: Deftere yaz (sadece onaylı nakliyeci) --}}
@auth
    @if(auth()->user()->isNakliyeci() && auth()->user()->company?->isApproved())
        <div id="defter-yaz-modal" class="fixed inset-0 z-[200] hidden" role="dialog" aria-modal="true" aria-labelledby="defter-yaz-modal-title">
            <div class="absolute inset-0 bg-zinc-900/60 dark:bg-zinc-950/70 backdrop-blur-sm" id="defter-yaz-overlay"></div>
            <div class="fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg max-h-[90vh] overflow-y-auto mx-4 z-10">
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-2xl p-6">
                    <div class="flex items-center justify-between gap-4 mb-5">
                        <h2 id="defter-yaz-modal-title" class="text-lg font-semibold text-zinc-900 dark:text-white">Deftere yaz</h2>
                        <button type="button" id="defter-yaz-close" class="p-2 rounded-lg text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 dark:text-zinc-400" aria-label="Kapat">&times;</button>
                    </div>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-5">Yük veya boş dönüş ilanınızı paylaşın. İlan defter sayfasında listelenir.</p>
                    <form method="POST" action="{{ route('nakliyeci.ledger.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="redirect_to" value="defter">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="defter-from_city" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Nereden (il) *</label>
                                <select id="defter-from_city" name="from_city" required class="input-touch py-2.5 text-sm" data-old="{{ old('from_city') }}">
                                    <option value="">İl seçin</option>
                                </select>
                                @error('from_city')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="defter-to_city" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Nereye (il) *</label>
                                <select id="defter-to_city" name="to_city" required class="input-touch py-2.5 text-sm" data-old="{{ old('to_city') }}">
                                    <option value="">İl seçin</option>
                                </select>
                                @error('to_city')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="defter-load_date" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Yük tarihi</label>
                                <input type="date" id="defter-load_date" name="load_date" value="{{ old('load_date') }}" class="input-touch py-2.5 text-sm">
                                @error('load_date')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="defter-volume_m3" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Hacim (m³)</label>
                                <input type="number" id="defter-volume_m3" name="volume_m3" value="{{ old('volume_m3') }}" step="0.01" min="0" class="input-touch py-2.5 text-sm" placeholder="Örn. 50">
                                @error('volume_m3')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label for="defter-load_type" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Yük tipi</label>
                            <input type="text" id="defter-load_type" name="load_type" value="{{ old('load_type') }}" class="input-touch py-2.5 text-sm" placeholder="Palet, koli vb.">
                        </div>
                        <div>
                            <label for="defter-vehicle_type" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Araç tipi</label>
                            <input type="text" id="defter-vehicle_type" name="vehicle_type" value="{{ old('vehicle_type') }}" class="input-touch py-2.5 text-sm" placeholder="Kamyon, TIR vb.">
                        </div>
                        <div>
                            <label for="defter-description" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Açıklama</label>
                            <textarea id="defter-description" name="description" rows="3" class="input-touch text-sm resize-none" placeholder="Detay varsa yazın">{{ old('description') }}</textarea>
                        </div>
                        <div class="flex flex-wrap gap-3 pt-2">
                            <button type="submit" class="btn-primary py-2.5 px-5 text-sm">Deftere yaz</button>
                            <button type="button" id="defter-yaz-close-2" class="btn-secondary py-2.5 px-5 text-sm">İptal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
        (function() {
            var modal = document.getElementById('defter-yaz-modal');
            var openBtn = document.getElementById('defter-yaz-open');
            var overlay = document.getElementById('defter-yaz-overlay');
            var closeBtn = document.getElementById('defter-yaz-close');
            var closeBtn2 = document.getElementById('defter-yaz-close-2');
            var fromSelect = document.getElementById('defter-from_city');
            var toSelect = document.getElementById('defter-to_city');
            var provincesLoaded = false;
            var provincesApiUrl = '{{ route("api.turkey.provinces") }}';

            function fillSelectWithProvinces(select, data) {
                if (!select) return;
                while (select.options.length > 1) select.removeChild(select.lastChild);
                (data || []).forEach(function(p) {
                    var opt = document.createElement('option');
                    opt.value = p.name;
                    opt.textContent = p.name;
                    select.appendChild(opt);
                });
                var oldVal = select.getAttribute('data-old');
                if (oldVal) select.value = oldVal;
            }

            function loadProvincesAndFill() {
                if (provincesLoaded) {
                    if (fromSelect) fillSelectWithProvinces(fromSelect, window._defterProvinces || []);
                    if (toSelect) fillSelectWithProvinces(toSelect, window._defterProvinces || []);
                    return;
                }
                fetch(provincesApiUrl)
                    .then(function(r) { return r.json(); })
                    .then(function(res) {
                        var data = res.data || [];
                        window._defterProvinces = data;
                        provincesLoaded = true;
                        fillSelectWithProvinces(fromSelect, data);
                        fillSelectWithProvinces(toSelect, data);
                    })
                    .catch(function() {
                        if (fromSelect && fromSelect.options.length === 1) {
                            var opt = document.createElement('option');
                            opt.value = '';
                            opt.textContent = 'İller yüklenemedi';
                            fromSelect.appendChild(opt);
                        }
                        if (toSelect && toSelect.options.length === 1) {
                            var opt = document.createElement('option');
                            opt.value = '';
                            opt.textContent = 'İller yüklenemedi';
                            toSelect.appendChild(opt);
                        }
                    });
            }

            function openModal() {
                if (!modal) return;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                if (openBtn) openBtn.setAttribute('aria-expanded', 'true');
                loadProvincesAndFill();
            }
            function closeModal() {
                if (!modal) return;
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                if (openBtn) openBtn.setAttribute('aria-expanded', 'false');
            }
            openBtn && openBtn.addEventListener('click', openModal);
            overlay && overlay.addEventListener('click', closeModal);
            closeBtn && closeBtn.addEventListener('click', closeModal);
            closeBtn2 && closeBtn2.addEventListener('click', closeModal);
            modal && modal.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeModal();
            });
            @if(old('from_city') !== null || old('to_city') !== null)
            openModal();
            @endif
        })();
        </script>
    @endif
@endauth
@endsection
