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
            <form method="POST" action="{{ route('admin.ihaleler.destroy', $ihale) }}" class="inline" onsubmit="return confirm('Bu ihaleyi silmek istediğinize emin misiniz? Silme nedeni audit log\'a kaydedilir.');">
                @csrf
                @method('DELETE')
                <input type="text" name="action_reason" class="admin-input py-1.5 w-48 text-sm mr-1" placeholder="Silme nedeni (isteğe bağlı)" maxlength="1000">
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
        <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Teklifler ({{ $ihale->teklifler->count() }})</h3>
        <ul class="space-y-3">
            @forelse($ihale->teklifler as $t)
                @php
                    $statusLabel = match($t->status) { 'accepted' => 'Kabul', 'rejected' => 'İptal', default => 'Beklemede' };
                @endphp
                <li class="flex flex-wrap items-center justify-between gap-2 py-2 border-b border-slate-200 dark:border-slate-600 last:border-0">
                    <div>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $t->company->name ?? '-' }}</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400 ml-2">Teklif: {{ $t->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-medium">{{ number_format($t->amount, 0, ',', '.') }} ₺</span>
                        <span class="text-xs px-2 py-0.5 rounded {{ $t->status === 'accepted' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200' : ($t->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300') }}">{{ $statusLabel }}</span>
                        @if($t->status !== 'rejected')
                            <form method="POST" action="{{ route('admin.ihaleler.teklif.reject', [$ihale, $t]) }}" class="inline" onsubmit="return confirm('Bu teklifi iptal etmek istediğinize emin misiniz?');">
                                @csrf
                                <button type="submit" class="text-xs px-2 py-1 rounded bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/40 dark:text-red-200 dark:hover:bg-red-800/60">İptal et</button>
                            </form>
                        @endif
                    </div>
                </li>
            @empty
                <li class="text-slate-500 dark:text-slate-400">Henüz teklif yok.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
