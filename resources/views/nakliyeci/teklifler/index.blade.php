@extends('layouts.nakliyeci')

@section('title', 'Tekliflerim')
@section('page_heading', 'Tekliflerim')
@section('page_subtitle', 'Verdiğiniz tüm teklifler')

@section('content')
<div class="space-y-4">
    @forelse($teklifler as $t)
        @php $ihale = $t->ihale; @endphp
        <a href="{{ $ihale ? route('nakliyeci.ihaleler.show', $ihale) : '#' }}" class="block">
            <article class="panel-card p-5 sm:p-6 rounded-2xl border border-[var(--panel-border)] bg-[var(--panel-surface)] shadow-sm hover:shadow-md hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-all duration-200 active:scale-[0.99]">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <h3 class="text-lg font-bold text-[var(--panel-text)]">
                            {{ $ihale ? $ihale->from_location_text . ' → ' . $ihale->to_location_text : '—' }}
                        </h3>
                        @if($ihale)
                            <p class="text-sm text-[var(--panel-text-muted)] mt-0.5">{{ $ihale->volume_m3 }} m³ · {{ $ihale->move_date?->format('d.m.Y') ?? '-' }}</p>
                        @endif
                        <p class="text-sm text-[var(--panel-text-muted)] mt-2">{{ $t->created_at->format('d.m.Y H:i') }}</p>
                        @if($t->reject_reason)
                            <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">Red gerekçesi: {{ $t->reject_reason }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="text-xl font-bold text-[var(--panel-text)]">{{ number_format($t->amount, 0, ',', '.') }} ₺</span>
                        <x-panel.status-badge :status="$t->status === 'accepted' ? 'approved' : ($t->status === 'rejected' ? 'rejected' : 'pending')">
                            {{ $t->status === 'accepted' ? 'Onaylandı' : ($t->status === 'rejected' ? 'Reddedildi' : 'Beklemede') }}
                        </x-panel.status-badge>
                    </div>
                </div>
            </article>
        </a>
    @empty
        <div class="panel-card p-10 sm:p-12 rounded-2xl text-center">
            <p class="text-[var(--panel-text-muted)]">Henüz teklif vermediniz.</p>
            <a href="{{ route('nakliyeci.ihaleler.index') }}" class="btn-primary inline-flex mt-4">Açık ihalelere git</a>
        </div>
    @endforelse
</div>

@if($teklifler->hasPages())
    <div class="mt-8">{{ $teklifler->links() }}</div>
@endif
@endsection
