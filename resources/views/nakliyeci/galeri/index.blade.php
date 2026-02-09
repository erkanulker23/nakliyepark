@extends('layouts.nakliyeci')

@section('title', 'Galeri')
@section('page_heading', 'Firma galerisi')
@section('page_subtitle', 'Araç veya iş fotoğraflarınız')

@section('content')
<div class="max-w-4xl">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <p class="text-sm text-slate-500">Firmanıza ait fotoğrafları ekleyin. Müşteriler profilinizde görebilir.</p>
        <a href="{{ route('nakliyeci.galeri.create') }}" class="admin-btn-primary inline-flex">+ Fotoğraf ekle</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        @forelse($images as $img)
            <div class="admin-card overflow-hidden group">
                <a href="{{ asset('storage/'.$img->path) }}" target="_blank" class="block aspect-square bg-slate-100 dark:bg-slate-800">
                    <img src="{{ asset('storage/'.$img->path) }}" alt="{{ $img->caption ?? 'Galeri' }}" class="w-full h-full object-cover">
                </a>
                @if($img->caption)
                    <p class="p-2 text-sm text-slate-600 dark:text-slate-400 truncate">{{ $img->caption }}</p>
                @endif
                <form method="POST" action="{{ route('nakliyeci.galeri.destroy', $img->id) }}" class="p-2 border-t border-slate-200 dark:border-slate-600" onsubmit="return confirm('Bu fotoğrafı silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:underline">Sil</button>
                </form>
            </div>
        @empty
            <div class="col-span-full admin-card p-12 text-center text-slate-500">
                Henüz fotoğraf yok. <a href="{{ route('nakliyeci.galeri.create') }}" class="text-emerald-600 hover:underline">İlk fotoğrafı ekleyin</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
