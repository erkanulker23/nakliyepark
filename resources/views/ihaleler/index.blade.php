@extends('layouts.app')

@section('title', 'Açık İhaleler - NakliyePark')
@section('meta_description', 'Açık nakliye ihaleleri listesi. Evden eve nakliyat ve yük taşıma talepleri. Şehir, tarih ve hizmet tipine göre filtreleyin, teklif verin veya kendi ihale talebinizi oluşturun.')

@section('content')
<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900/50">
    {{-- Hero --}}
    <section class="relative py-12 sm:py-16 overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-sky-500/10 rounded-bl-full group-hover:bg-sky-500/20 transition-colors pointer-events-none" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-zinc-200/60 via-transparent to-zinc-300/30 dark:from-zinc-800/50 dark:to-zinc-800/20"></div>
        <div class="page-container relative">
            <div class="max-w-2xl">
                <h1 class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white tracking-tight">Açık ihaleler</h1>
                <p class="text-zinc-600 dark:text-zinc-400 mt-2 text-base sm:text-lg">Nakliye talepleri ve teklif fırsatları. Filtreleyin, detaya girip teklif verin.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('ihale.create') }}" class="btn-primary inline-flex gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        İhale başlat
                    </a>
                    <a href="{{ route('home') }}" class="btn-secondary">Anasayfa</a>
                </div>
            </div>
        </div>
    </section>

    @php $ihaleListUst = \App\Models\AdZone::getForPagePosition('ihale_list', 'ust', 2); @endphp
    @if($ihaleListUst->isNotEmpty())
        <div class="page-container mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($ihaleListUst as $reklam)
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden bg-white dark:bg-zinc-800 p-4">
                        @if($reklam->isCode()){!! $reklam->kod !!}@else
                            @if($reklam->link)<a href="{{ $reklam->link }}" target="_blank" rel="noopener noreferrer nofollow" class="block">@endif
                            @if($reklam->resim)<img src="{{ $reklam->resim }}" alt="{{ $reklam->baslik ?? 'Reklam' }}" class="w-full h-24 object-cover rounded-lg mb-2" loading="lazy">@endif
                            @if($reklam->baslik)<p class="font-medium text-zinc-900 dark:text-white">{{ $reklam->baslik }}</p>@endif
                            @if($reklam->link)</a>@endif
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="page-container pb-16 sm:pb-24">
        {{-- Filtreler --}}
        <div class="mb-8 sm:mb-10">
            <h2 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Filtreler</h2>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-3">Şehir, tarih, hizmet tipi veya hacme göre arayın; sıralamayı değiştirin.</p>
            <form method="get" action="{{ route('ihaleler.index') }}" class="card p-4 sm:p-5 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                <div class="flex flex-wrap items-end gap-3 sm:gap-4">
                    <div class="flex-1 min-w-[140px]">
                        <label for="from_city" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Nereden</label>
                        <input type="text" name="from_city" id="from_city" value="{{ $filters['from_city'] ?? '' }}" placeholder="Şehir"
                            class="input-touch text-sm py-2.5"
                            list="from_city_list">
                        <datalist id="from_city_list">
                            @foreach($filterOptions['cities_from'] as $c)
                                <option value="{{ $c }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="flex-1 min-w-[140px]">
                        <label for="to_city" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Nereye</label>
                        <input type="text" name="to_city" id="to_city" value="{{ $filters['to_city'] ?? '' }}" placeholder="Şehir"
                            class="input-touch text-sm py-2.5"
                            list="to_city_list">
                        <datalist id="to_city_list">
                            @foreach($filterOptions['cities_to'] as $c)
                                <option value="{{ $c }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="w-full sm:w-48">
                        <label for="service_type" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Hizmet tipi</label>
                        <select name="service_type" id="service_type" class="input-touch text-sm py-2.5">
                            <option value="">Tümü</option>
                            @foreach($filterOptions['service_types'] as $key => $label)
                                <option value="{{ $key }}" {{ ($filters['service_type'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full sm:w-40">
                        <label for="move_date_from" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Tarih (başlangıç)</label>
                        <input type="date" name="move_date_from" id="move_date_from" value="{{ $filters['move_date_from'] ?? '' }}" class="input-touch text-sm py-2.5">
                    </div>
                    <div class="w-full sm:w-40">
                        <label for="move_date_to" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Tarih (bitiş)</label>
                        <input type="date" name="move_date_to" id="move_date_to" value="{{ $filters['move_date_to'] ?? '' }}" class="input-touch text-sm py-2.5">
                    </div>
                    <div class="w-full sm:w-32">
                        <label for="volume_min" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Min. hacim (m³)</label>
                        <input type="number" name="volume_min" id="volume_min" value="{{ $filters['volume_min'] ?? '' }}" placeholder="0" min="0" step="0.1" class="input-touch text-sm py-2.5">
                    </div>
                    <div class="w-full sm:w-44">
                        <label for="sort" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Sırala</label>
                        <select name="sort" id="sort" class="input-touch text-sm py-2.5">
                            <option value="newest" {{ ($filters['sort'] ?? 'newest') === 'newest' ? 'selected' : '' }}>En yeni</option>
                            <option value="date_asc" {{ ($filters['sort'] ?? '') === 'date_asc' ? 'selected' : '' }}>Taşıma tarihi (önce erken)</option>
                            <option value="date_desc" {{ ($filters['sort'] ?? '') === 'date_desc' ? 'selected' : '' }}>Taşıma tarihi (önce geç)</option>
                            <option value="volume_desc" {{ ($filters['sort'] ?? '') === 'volume_desc' ? 'selected' : '' }}>Hacim (büyükten küçüğe)</option>
                            <option value="teklif_desc" {{ ($filters['sort'] ?? '') === 'teklif_desc' ? 'selected' : '' }}>Çok teklif alan</option>
                        </select>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <button type="submit" class="btn-primary py-2.5 px-4 text-sm">Filtrele</button>
                        <a href="{{ route('ihaleler.index') }}" class="btn-secondary py-2.5 px-4 text-sm">Temizle</a>
                    </div>
                </div>
            </form>
        </div>

        @if($ihaleler->count() > 0)
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">{{ $ihaleler->total() }} ihale listeleniyor</p>
            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6">
                @foreach($ihaleler as $ihale)
                    <a href="{{ route('ihaleler.show', $ihale) }}" class="group block">
                        <article class="relative h-full rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm hover:shadow-lg hover:border-zinc-300 dark:hover:border-zinc-700 transition-all duration-300 flex flex-col">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-sky-500/10 rounded-bl-full group-hover:bg-sky-500/20 transition-colors pointer-events-none" aria-hidden="true"></div>
                            <div class="p-5 sm:p-6 flex-1 flex flex-col relative">
                                {{-- Rota çizgisi: Nereden ——— Nereye --}}
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <span class="text-sm font-bold text-zinc-800 dark:text-zinc-200 shrink-0 max-w-[28%] sm:max-w-[35%] truncate" title="{{ $ihale->from_location_text }}">{{ $ihale->from_location_text }}</span>
                                    <span class="flex-1 flex items-center gap-0.5 min-w-0" aria-hidden="true">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0" title="Çıkış"></span>
                                        <span class="flex-1 h-px bg-gradient-to-r from-zinc-300 via-zinc-400 to-zinc-300 dark:from-zinc-600 dark:via-zinc-500 dark:to-zinc-600 mx-0.5"></span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500 shrink-0" title="Varış"></span>
                                    </span>
                                    <span class="text-sm font-bold text-zinc-800 dark:text-zinc-200 shrink-0 max-w-[28%] sm:max-w-[35%] truncate text-right" title="{{ $ihale->to_location_text }}">{{ $ihale->to_location_text }}</span>
                                </div>
                                <div class="mt-3 flex items-center justify-between gap-3">
                                    @if($ihale->service_type)
                                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">
                                            {{ (\App\Models\Ihale::serviceTypeLabels())[$ihale->service_type] ?? $ihale->service_type }}
                                        </p>
                                    @else
                                        <span></span>
                                    @endif
                                    <span class="shrink-0 inline-flex items-center gap-1 rounded-full bg-zinc-100 dark:bg-zinc-800 px-2.5 py-1 text-xs font-semibold text-zinc-600 dark:text-zinc-300">
                                        {{ $ihale->teklifler_count }} teklif
                                    </span>
                                </div>
                                <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-zinc-500 dark:text-zinc-400">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                        {{ $ihale->volume_m3 }} m³
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @if($ihale->move_date || $ihale->move_date_end)
    @if($ihale->move_date_end && $ihale->move_date != $ihale->move_date_end)
        {{ $ihale->move_date->format('d.m.Y') }} – {{ $ihale->move_date_end->format('d.m.Y') }}
    @else
        {{ $ihale->move_date?->format('d.m.Y') ?? $ihale->move_date_end?->format('d.m.Y') ?? 'Tarih yok' }}
    @endif
@else
    Fiyat bakıyorum
@endif
                                    </span>
                                </div>
                                @if($ihale->description)
                                    <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2 flex-1">{{ Str::limit($ihale->description, 90) }}</p>
                                @endif
                                <p class="mt-4 text-sm font-semibold text-zinc-600 dark:text-zinc-300 group-hover:text-zinc-900 dark:group-hover:text-white group-hover:underline transition-colors">Detay ve teklif ver →</p>
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>
            @if($ihaleler->hasPages())
                <div class="mt-10">{{ $ihaleler->links() }}</div>
            @endif
            @php $ihaleListAlt = \App\Models\AdZone::getForPagePosition('ihale_list', 'alt', 2); @endphp
            @if($ihaleListAlt->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-10 pt-8 border-t border-zinc-200 dark:border-zinc-700">
                    @foreach($ihaleListAlt as $reklam)
                        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden bg-white dark:bg-zinc-800 p-4">
                            @if($reklam->isCode()){!! $reklam->kod !!}@else
                                @if($reklam->link)<a href="{{ $reklam->link }}" target="_blank" rel="noopener noreferrer nofollow" class="block">@endif
                                @if($reklam->resim)<img src="{{ $reklam->resim }}" alt="{{ $reklam->baslik ?? 'Reklam' }}" class="w-full h-20 object-cover rounded-lg mb-2" loading="lazy">@endif
                                @if($reklam->baslik)<p class="font-medium text-zinc-900 dark:text-white">{{ $reklam->baslik }}</p>@endif
                                @if($reklam->link)</a>@endif
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-12 sm:p-16 text-center max-w-lg mx-auto shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Bu kriterlere uygun ihale yok</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-2">Filtreleri gevşetin veya yeni bir ihale başlatın.</p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('ihaleler.index') }}" class="btn-secondary">Filtreleri temizle</a>
                    <a href="{{ route('ihale.create') }}" class="btn-primary">İhale başlat</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
