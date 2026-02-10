@extends('layouts.musteri')

@section('title', 'İhalelerim')
@section('page_heading', 'İhalelerim')
@section('page_subtitle', 'Taşınma ilanlarınız')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center justify-between mb-6">
        <p class="text-slate-500 text-sm">İlanlarınıza gelen teklifleri görüntüleyip kabul edebilirsiniz.</p>
        <a href="{{ route('ihale.create') }}" class="admin-btn-primary px-4 py-2 rounded-lg shrink-0">+ Yeni İhale</a>
    </div>

    @forelse($ihaleler as $ihale)
        <article class="admin-card mb-4">
            <a href="{{ route('musteri.ihaleler.show', $ihale) }}" class="block p-4 sm:p-5">
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
        <div class="admin-card p-8 text-center">
            <p class="text-slate-500">Henüz ihale oluşturmadınız.</p>
            <a href="{{ route('ihale.create') }}" class="inline-block mt-4 admin-btn-primary px-4 py-2 rounded-lg">İlk İhaleyi Oluştur</a>
        </div>
    @endforelse

    @if($ihaleler->hasPages())
        <div class="mt-6">{{ $ihaleler->links() }}</div>
    @endif
</div>
@endsection
