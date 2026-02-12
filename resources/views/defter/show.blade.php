@extends('layouts.app')

@section('title', 'Defter İlanı: ' . $ilan->from_city . ' → ' . $ilan->to_city . ' - NakliyePark')
@section('meta_description', 'Nakliyat defteri ilanı: ' . $ilan->company->name . ' - ' . $ilan->from_city . ' → ' . $ilan->to_city . ($ilan->description ? ' - ' . \Str::limit($ilan->description, 100) : ''))

@section('content')
<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900/50">
    <div class="page-container py-8 sm:py-12">
        <p class="mb-4">
            <a href="{{ route('defter.index') }}" class="text-sm text-zinc-500 dark:text-zinc-400 hover:text-amber-600 dark:hover:text-amber-400">← Deftere dön</a>
        </p>
        <article class="card-premium-flat p-5 sm:p-6 max-w-3xl">
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
                            @if($dakika < 2) Şimdi
                            @elseif($dakika < 60) {{ $tarih->diffForHumans(now(), null, true) }}
                            @else {{ $tarih->diffForHumans(now()) }}
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
                    <div class="mt-3">
                        @include('partials.defter-share-buttons', ['url' => route('defter.show', $ilan), 'title' => $ilanShareTitle, 'label' => 'Paylaş'])
                    </div>
                </div>
                <div class="shrink-0 text-right text-xs text-zinc-500 dark:text-zinc-400">
                    @if($ilan->company->created_at)
                        {{ $ilan->company->created_at->locale('tr')->translatedFormat('F Y') }} katıldı
                    @endif
                </div>
            </div>

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

            @auth
                @if(auth()->user()->isNakliyeci() && auth()->user()->company?->isApproved() && $ilan->company_id !== auth()->user()->company?->id)
                    <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <form method="POST" action="{{ route('nakliyeci.ledger.reply.store', $ilan) }}" class="flex flex-col sm:flex-row gap-2">
                            @csrf
                            <label class="sr-only" for="yanit-body">Yanıtınız</label>
                            <textarea id="yanit-body" name="body" rows="2" class="input-touch text-sm flex-1 resize-none" placeholder="Bu ilana yanıt yazın..." maxlength="2000" required>{{ old('body') }}</textarea>
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
    </div>
</div>
@endsection
