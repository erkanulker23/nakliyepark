@extends('layouts.musteri')

@section('title', 'Gelen Teklifler')
@section('page_heading', 'Gelen Teklifler')
@section('page_subtitle', 'İhalelerinize gelen teklifler')

@section('content')
@if($teklifler->isEmpty())
    <div class="panel-card p-10 sm:p-12 rounded-2xl text-center">
        <p class="text-[var(--panel-text-muted)]">Henüz gelen teklif yok.</p>
        <a href="{{ route('ihale.create') }}" class="btn-primary inline-flex mt-4">Yeni İhale Oluştur</a>
    </div>
@else
    <div class="space-y-4">
        @foreach($teklifler as $t)
            <a href="{{ route('musteri.ihaleler.show', $t->ihale) }}" class="block">
                <article class="panel-card p-5 sm:p-6 rounded-2xl border border-[var(--panel-border)] bg-[var(--panel-surface)] shadow-sm hover:shadow-md hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-all duration-200 active:scale-[0.99]">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <h3 class="text-lg font-bold text-[var(--panel-text)]">
                                {{ $t->ihale->from_location_text ?? '-' }} → {{ $t->ihale->to_location_text ?? '-' }}
                            </h3>
                            <p class="text-sm text-[var(--panel-text-muted)] mt-0.5">{{ $t->company->name ?? 'Firma' }} · <strong class="text-[var(--panel-text)]">{{ number_format($t->amount, 0, ',', '.') }} ₺</strong></p>
                            @if($t->message)
                                <p class="text-sm text-[var(--panel-text-muted)] mt-1 line-clamp-2">{{ $t->message }}</p>
                            @endif
                            <p class="text-xs text-[var(--panel-text-muted)] mt-2">{{ $t->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <x-panel.status-badge :status="$t->status === 'accepted' ? 'approved' : ($t->status === 'rejected' ? 'rejected' : 'pending')">
                                {{ $t->status === 'accepted' ? 'Kabul edildi' : ($t->status === 'rejected' ? 'Reddedildi' : 'Beklemede') }}
                            </x-panel.status-badge>
                            <span class="btn-primary rounded-xl text-sm py-2.5 px-4">İhaleye git</span>
                        </div>
                    </div>
                </article>
            </a>
        @endforeach
    </div>
    <div class="mt-8">{{ $teklifler->links() }}</div>
@endif
@endsection
