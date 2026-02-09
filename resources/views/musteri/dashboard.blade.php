@extends('layouts.app')

@section('title', 'Panelim - NakliyePark')

@section('content')
<div class="px-4 py-6 max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">İhalelerim</h1>
        <a href="{{ route('ihale.create') }}" class="btn-touch bg-sky-500 text-white rounded-xl">+ Yeni İhale</a>
    </div>

    @forelse($ihaleler as $ihale)
        <article class="card-touch bg-white dark:bg-slate-800 mb-4">
            <a href="{{ route('musteri.ihaleler.show', $ihale) }}" class="block">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-medium text-slate-800 dark:text-slate-100">{{ $ihale->from_city }} → {{ $ihale->to_city }}</p>
                    <p class="text-sm text-slate-500">{{ $ihale->volume_m3 }} m³
                        @if($ihale->move_date || $ihale->move_date_end)
                            · @if($ihale->move_date_end && $ihale->move_date != $ihale->move_date_end){{ $ihale->move_date->format('d.m.Y') }} – {{ $ihale->move_date_end->format('d.m.Y') }}@else{{ $ihale->move_date?->format('d.m.Y') ?? $ihale->move_date_end?->format('d.m.Y') }}@endif
                        @else
                            · Fiyat bakıyorum
                        @endif
                    </p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full
                    @if($ihale->status === 'pending') bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300
                    @elseif($ihale->status === 'published') bg-sky-100 text-sky-700 dark:bg-sky-900/50 dark:text-sky-300
                    @elseif($ihale->status === 'closed') bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300
                    @else bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300
                    @endif">
                    {{ $ihale->status === 'pending' ? 'Onay bekliyor' : ($ihale->status === 'published' ? 'Yayında' : ($ihale->status === 'closed' ? 'Kapalı' : 'Taslak')) }}
                </span>
            </div>
            @php $teklifCount = $ihale->teklifler()->count(); @endphp
            @if($teklifCount > 0)
                <p class="text-sm text-slate-500 mt-2">{{ $teklifCount }} teklif alındı.</p>
            @endif
            @php $accepted = $ihale->acceptedTeklif; @endphp
            @if($accepted)
                <a href="{{ route('review.create', $ihale) }}" class="inline-block mt-2 text-sm text-sky-500" onclick="event.stopPropagation();">Değerlendirme yap (video yükleyebilirsiniz)</a>
            @endif
            </a>
        </article>
    @empty
        <p class="text-slate-500 text-center py-8">Henüz ihale oluşturmadınız.</p>
        <div class="text-center">
            <a href="{{ route('ihale.create') }}" class="btn-touch bg-sky-500 text-white rounded-xl inline-flex">İlk İhaleyi Oluştur</a>
        </div>
    @endforelse

    @if($ihaleler->hasPages())
        <div class="mt-4">{{ $ihaleler->links() }}</div>
    @endif
</div>
@endsection
