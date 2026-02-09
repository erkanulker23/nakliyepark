@extends('layouts.admin')

@section('title', 'İhale detay')
@section('page_heading', 'İhale detay')
@section('page_subtitle', $ihale->from_city . ' → ' . $ihale->to_city)

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.ihaleler.index') }}" class="admin-btn-secondary text-sm">← İhalelere dön</a>
            <a href="{{ route('admin.ihaleler.edit', $ihale) }}" class="admin-btn-primary text-sm">Düzenle</a>
            <form method="POST" action="{{ route('admin.ihaleler.destroy', $ihale) }}" class="inline" onsubmit="return confirm('Bu ihaleyi silmek istediğinize emin misiniz?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-btn-danger text-sm">Sil</button>
            </form>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($ihale->status === 'pending')
                <form method="POST" action="{{ route('admin.ihaleler.update-status', $ihale) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="published">
                    <button type="submit" class="admin-btn-primary text-sm">Onayla ve yayına al</button>
                </form>
            @endif
            <form method="POST" action="{{ route('admin.ihaleler.update-status', $ihale) }}" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <select name="status" class="admin-input py-2 w-auto">
                    <option value="pending" {{ $ihale->status === 'pending' ? 'selected' : '' }}>Onay bekliyor</option>
                    <option value="draft" {{ $ihale->status === 'draft' ? 'selected' : '' }}>Taslak</option>
                    <option value="published" {{ $ihale->status === 'published' ? 'selected' : '' }}>Yayında</option>
                    <option value="closed" {{ $ihale->status === 'closed' ? 'selected' : '' }}>Kapalı</option>
                    <option value="cancelled" {{ $ihale->status === 'cancelled' ? 'selected' : '' }}>İptal</option>
                </select>
                <button type="submit" class="admin-btn-secondary text-sm">Durum güncelle</button>
            </form>
        </div>
    </div>

    <div class="admin-card p-6 grid md:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold text-slate-800 mb-2">Güzergah</h3>
            <p class="text-slate-600">{{ $ihale->from_city }} → {{ $ihale->to_city }}</p>
            <p class="text-sm text-slate-500 mt-1">{{ $ihale->distance_km ?? '-' }} km · {{ $ihale->volume_m3 ?? '0' }} m³</p>
            @if($ihale->from_address || $ihale->to_address)
                <p class="text-sm text-slate-500 mt-1">{{ $ihale->from_address }} → {{ $ihale->to_address }}</p>
            @endif
        </div>
        <div>
            <h3 class="font-semibold text-slate-800 mb-2">Talep sahibi</h3>
            <p>{{ $ihale->user?->name ?? $ihale->guest_contact_name ?? 'Misafir' }}</p>
            <p class="text-sm text-slate-500">{{ $ihale->user?->email ?? $ihale->guest_contact_email ?? '-' }}</p>
            @if($ihale->guest_contact_phone)<p class="text-sm text-slate-500">{{ $ihale->guest_contact_phone }}</p>@endif
        </div>
        <div class="md:col-span-2">
            <h3 class="font-semibold text-slate-800 mb-2">Taşınma tarihi</h3>
            @if($ihale->move_date || $ihale->move_date_end)
                <p>
                    @if($ihale->move_date_end && $ihale->move_date != $ihale->move_date_end)
                        {{ $ihale->move_date?->format('d.m.Y') }} – {{ $ihale->move_date_end?->format('d.m.Y') }}
                    @else
                        {{ $ihale->move_date?->format('d.m.Y') ?? '-' }}
                    @endif
                </p>
            @else
                <p class="text-slate-600">Fiyat bakıyorum (tarih belli değil)</p>
            @endif
        </div>
        @if($ihale->description)
            <div class="md:col-span-2">
                <h3 class="font-semibold text-slate-800 mb-2">Açıklama</h3>
                <p class="text-slate-600 whitespace-pre-wrap">{{ $ihale->description }}</p>
            </div>
        @endif
    </div>

    <div class="admin-card p-6">
        <h3 class="font-semibold text-slate-800 mb-3">Teklifler ({{ $ihale->teklifler->count() }})</h3>
        <ul class="space-y-2">
            @forelse($ihale->teklifler as $t)
                <li class="flex justify-between items-center py-2 border-b border-slate-100 last:border-0">
                    <span>{{ $t->company->name ?? '-' }}</span>
                    <span class="font-medium">{{ number_format($t->amount, 0, ',', '.') }} ₺</span>
                    <span class="text-xs px-2 py-0.5 rounded {{ $t->status === 'accepted' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">{{ $t->status }}</span>
                </li>
            @empty
                <li class="text-slate-500">Henüz teklif yok.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
