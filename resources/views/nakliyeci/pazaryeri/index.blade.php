@extends('layouts.nakliyeci')

@section('title', 'Pazaryeri ilanlarım')
@section('page_heading', 'Pazaryeri ilanlarım')
@section('page_subtitle', 'Satılık veya kiralık araç ilanlarınız')

@section('content')
<div class="max-w-4xl">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <p class="text-sm text-slate-500">Pazaryerinde yayınlanan araç ilanlarınızı buradan yönetin. Site ziyaretçileri ilanlarınızı görebilir.</p>
        <a href="{{ route('nakliyeci.pazaryeri.create') }}" class="admin-btn-primary inline-flex">+ İlan ekle</a>
    </div>
    <div class="space-y-4">
        @forelse($listings as $item)
            <div class="admin-card p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="font-semibold text-slate-800 dark:text-slate-200 truncate">{{ $item->title }}</h2>
                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium {{ $item->listing_type === 'rent' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' }}">
                            {{ $listingTypes[$item->listing_type] ?? $item->listing_type }}
                        </span>
                        @if($item->status !== 'active')
                            <span class="text-xs text-slate-500">{{ $item->status }}</span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-500 mt-1">
                        {{ $vehicleTypes[$item->vehicle_type] ?? $item->vehicle_type }}
                        @if($item->city) · {{ $item->city }}@endif
                        @if($item->year) · {{ $item->year }} model@endif
                        @if($item->price) · {{ number_format($item->price, 0, ',', '.') }} ₺@endif
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <a href="{{ route('pazaryeri.show', [$item, \Illuminate\Support\Str::slug($item->title)]) }}" target="_blank" rel="noopener" class="admin-btn-secondary text-sm py-2 px-3">Görüntüle</a>
                    <a href="{{ route('nakliyeci.pazaryeri.edit', $item) }}" class="admin-btn-secondary text-sm py-2 px-3">Düzenle</a>
                    <form method="POST" action="{{ route('nakliyeci.pazaryeri.destroy', $item) }}" class="inline" onsubmit="return confirm('Bu ilanı silmek istediğinize emin misiniz?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:underline">Sil</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="admin-card p-12 text-center text-slate-500">
                Henüz pazaryeri ilanınız yok. <a href="{{ route('nakliyeci.pazaryeri.create') }}" class="text-emerald-600 hover:underline">İlk ilanı ekleyin</a> — satılık veya kiralık araç ilanı verebilirsiniz.
            </div>
        @endforelse
    </div>
</div>
@endsection
