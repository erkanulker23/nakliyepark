@extends('layouts.musteri')

@section('title', 'İhalelerim')
@section('page_heading', 'İhalelerim')
@section('page_subtitle', 'Taşınma ilanlarınız')

@section('content')
{{-- Ana aksiyon: Yeni İhale FAB --}}
<a href="{{ route('ihale.create') }}" class="panel-fab" aria-label="Yeni ihale oluştur">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
</a>

<div class="max-w-3xl">
    <p class="text-[var(--panel-text-muted)] text-sm mb-6">İlanlarınıza gelen teklifleri görüntüleyip kabul edebilirsiniz.</p>

    @forelse($ihaleler as $ihale)
        @php
            $teklifCount = $ihale->teklifler()->count();
            $accepted = $ihale->acceptedTeklif;
            try {
                $minTeklif = $ihale->teklifler()->where('status', '!=', 'rejected')->min('amount');
            } catch (\Throwable $e) {
                $minTeklif = null;
            }
            $canClose = $ihale->status === 'published' && !$accepted;
            $canOpen = in_array($ihale->status, ['closed', 'draft'], true) && !$accepted;
            $canPause = $ihale->status === 'published' && !$accepted;
        @endphp
        <div class="panel-card p-4 sm:p-5 mb-4 rounded-2xl border border-[var(--panel-border)] bg-[var(--panel-surface)] shadow-sm hover:shadow-md hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-all">
            <a href="{{ route('musteri.ihaleler.show', $ihale) }}" class="block">
            <div class="flex justify-between items-start gap-3">
                <div class="min-w-0">
                    <p class="font-semibold text-[var(--panel-text)]">{{ $ihale->from_location_text }} → {{ $ihale->to_location_text }}</p>
                    <p class="text-sm text-[var(--panel-text-muted)] mt-0.5">
                        {{ $ihale->volume_m3 }} m³
                        @if($ihale->move_date || $ihale->move_date_end)
                            · @if($ihale->move_date_end && $ihale->move_date != $ihale->move_date_end){{ $ihale->move_date->format('d.m.Y') }} – {{ $ihale->move_date_end->format('d.m.Y') }}@else{{ $ihale->move_date?->format('d.m.Y') ?? $ihale->move_date_end?->format('d.m.Y') }}@endif
                        @else
                            · Tarih belirtilmemiş
                        @endif
                    </p>
                </div>
                <x-panel.status-badge :status="$ihale->status === 'pending' ? 'pending' : ($ihale->status === 'published' ? 'neutral' : ($ihale->status === 'draft' ? 'pending' : 'approved'))">
                    {{ $ihale->status === 'pending' ? 'Onay bekliyor' : ($ihale->status === 'published' ? 'Yayında' : ($ihale->status === 'closed' ? 'Kapalı' : ($ihale->status === 'draft' ? 'Beklemede' : 'Taslak'))) }}
                </x-panel.status-badge>
            </div>
            @if($teklifCount > 0)
                <div class="mt-3 flex items-center gap-2 flex-wrap">
                    <span class="text-sm font-medium text-[var(--panel-text)]">{{ $teklifCount }} teklif</span>
                    @if($minTeklif)
                        <span class="text-sm text-[var(--panel-primary)] font-semibold">En düşük {{ number_format($minTeklif, 0, ',', '.') }} ₺</span>
                    @endif
                </div>
            @endif
            @if($accepted)
                <p class="mt-2 text-sm text-emerald-600 dark:text-emerald-400 font-medium">Teklif kabul edildi</p>
                <p class="mt-1"><a href="{{ route('review.create', $ihale) }}" class="text-sm text-sky-600 dark:text-sky-400 hover:underline">Değerlendirme yap →</a></p>
            @endif
            </a>
            @if($canClose || $canOpen || $canPause)
                <div class="mt-4 pt-4 border-t border-[var(--panel-border)] flex flex-wrap items-center gap-2">
                    @if($canClose)
                        <form method="POST" action="{{ route('musteri.ihaleler.close', $ihale) }}" class="inline" onsubmit="return confirm('İhaleyi kapatmak istediğinize emin misiniz? Bekleyen teklifler reddedilir ve firmalar artık teklif veremez.');">
                            @csrf
                            <button type="submit" class="text-sm py-1.5 px-3 rounded-lg border border-[var(--panel-border)] text-[var(--panel-text-muted)] hover:bg-[var(--panel-bg)]">Kapat</button>
                        </form>
                    @endif
                    @if($canPause)
                        <form method="POST" action="{{ route('musteri.ihaleler.pause', $ihale) }}" class="inline" onsubmit="return confirm('İhaleyi bekleme moduna almak istiyor musunuz? İlan listeden kalkar; istediğiniz zaman tekrar yayına alabilirsiniz.');">
                            @csrf
                            <button type="submit" class="text-sm py-1.5 px-3 rounded-lg border border-[var(--panel-border)] text-[var(--panel-text-muted)] hover:bg-[var(--panel-bg)]">Bekleme moduna al</button>
                        </form>
                    @endif
                    @if($canOpen)
                        <form method="POST" action="{{ route('musteri.ihaleler.open', $ihale) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm py-1.5 px-3 rounded-lg bg-[var(--panel-primary)] text-white hover:opacity-90">Yayına al</button>
                        </form>
                    @endif
                    <a href="{{ route('musteri.ihaleler.show', $ihale) }}" class="text-sm py-1.5 px-3 text-[var(--panel-primary)] hover:underline ml-auto">Detay →</a>
                </div>
            @else
                <div class="mt-4 pt-4 border-t border-[var(--panel-border)]">
                    <a href="{{ route('musteri.ihaleler.show', $ihale) }}" class="text-sm text-[var(--panel-primary)] hover:underline">Detay →</a>
                </div>
            @endif
        </div>
    @empty
        <div class="panel-card p-8 text-center rounded-2xl">
            <p class="text-[var(--panel-text-muted)]">Henüz ihale oluşturmadınız.</p>
            <a href="{{ route('ihale.create') }}" class="btn-primary inline-flex mt-4">İlk İhaleyi Oluştur</a>
        </div>
    @endforelse

    @if($ihaleler->hasPages())
        <div class="mt-6">{{ $ihaleler->links() }}</div>
    @endif
</div>
@endsection
