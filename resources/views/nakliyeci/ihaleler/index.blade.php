@extends('layouts.nakliyeci')

@section('title', 'Açık ihaleler')
@section('page_heading', 'Açık ihaleler')
@section('page_subtitle', 'Teklif verebileceğiniz ihaleler')

@section('content')
<p class="text-[var(--panel-text-muted)] text-sm mb-6">İhale detayı ve teklif için karta tıklayın veya «Detay & Teklif» butonunu kullanın.</p>

<div class="space-y-4">
    @forelse($ihaleler as $ihale)
        @php $benimTeklif = $ihale->teklifler()->where('company_id', $company->id)->first(); @endphp
        <a href="{{ route('nakliyeci.ihaleler.show', $ihale) }}" class="block">
            <article class="panel-card p-5 sm:p-6 rounded-2xl border border-[var(--panel-border)] bg-[var(--panel-surface)] shadow-sm hover:shadow-md hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-all duration-200 active:scale-[0.99]">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <h3 class="text-lg font-bold text-[var(--panel-text)] leading-tight">
                            {{ $ihale->from_location_text }} → {{ $ihale->to_location_text }}
                        </h3>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-sm text-[var(--panel-text-muted)]">
                            <span>{{ $ihale->volume_m3 }} m³</span>
                            <span>{{ $ihale->move_date ? $ihale->move_date->format('d.m.Y') : '—' }}</span>
                            <span>{{ $ihale->teklifler_count }} teklif</span>
                        </div>
                    </div>
                    <div class="shrink-0">
                        @if($benimTeklif)
                            <span class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-medium bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-600">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Teklif verdiniz
                            </span>
                        @else
                            <span class="inline-flex items-center justify-center gap-2 min-h-[48px] px-6 py-3 rounded-2xl text-base font-bold bg-[var(--panel-primary)] text-white shadow-lg shadow-emerald-500/25 hover:shadow-xl hover:shadow-emerald-500/30 active:scale-[0.98] transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Detay & Teklif
                            </span>
                        @endif
                    </div>
                </div>
            </article>
        </a>
    @empty
        <div class="panel-card p-10 sm:p-12 rounded-2xl text-center">
            <p class="text-[var(--panel-text-muted)] text-base">Şu an açık ihale yok.</p>
            <p class="text-sm text-[var(--panel-text-muted)] mt-2">Yayınlanan yeni ihaleler burada listelenecek.</p>
        </div>
    @endforelse
</div>

@if($ihaleler->hasPages())
    <div class="mt-8">{{ $ihaleler->links() }}</div>
@endif
@endsection
