@extends('layouts.admin')

@section('title', 'Müşteri: ' . $user->name)
@section('page_heading', $user->name)
@section('page_subtitle', 'Müşteri bilgileri, ihaleler ve teklifler')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('admin.musteriler.index') }}" class="admin-btn-secondary text-sm">← Müşterilere dön</a>
        <a href="{{ route('admin.users.edit', $user) }}" class="admin-btn-primary text-sm">Kullanıcıyı düzenle</a>
        @if($user->isBlocked())
            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">Engelli</span>
            <form method="POST" action="{{ route('admin.blocklist.unblock-user', $user) }}" class="inline">
                @csrf
                <button type="submit" class="admin-btn-secondary text-sm">Engeli kaldır</button>
            </form>
        @elseif($user->id !== auth()->id())
            <form method="POST" action="{{ route('admin.blocklist.block-user', $user) }}" class="inline" onsubmit="return confirm('Bu müşteriyi engellemek istediğinize emin misiniz?');">
                @csrf
                <button type="submit" class="admin-btn-danger text-sm">Engelle</button>
            </form>
        @endif
    </div>

    {{-- Müşteri bilgileri --}}
    <div class="admin-card p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">İletişim bilgileri</h2>
        <dl class="grid sm:grid-cols-2 gap-3 text-sm">
            <div>
                <dt class="text-slate-500 font-medium">Ad Soyad</dt>
                <dd class="text-slate-800">{{ $user->name }}</dd>
            </div>
            <div>
                <dt class="text-slate-500 font-medium">E-posta</dt>
                <dd class="text-slate-800">{{ $user->email }}</dd>
            </div>
            <div>
                <dt class="text-slate-500 font-medium">Telefon</dt>
                <dd class="text-slate-800">{{ $user->phone ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-slate-500 font-medium">Kayıt tarihi</dt>
                <dd class="text-slate-800">{{ $user->created_at?->format('d.m.Y H:i') ?? '—' }}</dd>
            </div>
        </dl>
    </div>

    {{-- İhaleler ve teklifler --}}
    <div class="admin-card p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">İhaleler ({{ $user->ihaleler->count() }})</h2>
        @forelse($user->ihaleler as $ihale)
            <div class="border border-slate-200 rounded-lg p-4 mb-4 last:mb-0">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                    <h3 class="font-medium text-slate-800">
                        {{ $ihale->from_city }} → {{ $ihale->to_city }}
                        @if($ihale->move_date)
                            <span class="text-slate-500 font-normal">· {{ $ihale->move_date->format('d.m.Y') }}</span>
                        @endif
                    </h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        @if($ihale->status === 'published') bg-emerald-100 text-emerald-800
                        @elseif($ihale->status === 'closed') bg-slate-100 text-slate-600
                        @elseif($ihale->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-amber-100 text-amber-800 @endif">
                        @php
                            $statusLabels = ['published' => 'Yayında', 'closed' => 'Kapalı', 'cancelled' => 'İptal', 'pending' => 'Onay bekliyor', 'draft' => 'Taslak'];
                        @endphp
                        {{ $statusLabels[$ihale->status] ?? $ihale->status }}
                    </span>
                </div>
                <p class="text-sm text-slate-500 mb-3">
                    {{ $ihale->distance_km ?? '—' }} km · {{ $ihale->volume_m3 ?? '—' }} m³
                    @if($ihale->description)
                        · {{ Str::limit($ihale->description, 80) }}
                    @endif
                </p>
                <div class="flex items-center gap-2 mb-2">
                    <a href="{{ route('admin.ihaleler.show', $ihale) }}" class="text-indigo-600 hover:underline text-sm font-medium">İhale detayı →</a>
                    <form method="POST" action="{{ route('admin.ihaleler.destroy', $ihale) }}" class="inline" onsubmit="return confirm('Bu ihale ve tüm teklifleri silinecek. Emin misiniz?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-sm font-medium">İhaleyi sil</button>
                    </form>
                </div>
                <h4 class="text-sm font-medium text-slate-700 mt-2 mb-1">Nakliyeciden gelen teklifler ({{ $ihale->teklifler->count() }})</h4>
                <ul class="space-y-1 text-sm">
                    @forelse($ihale->teklifler as $t)
                        <li class="flex justify-between items-center py-1.5 border-b border-slate-100 last:border-0">
                            <span class="text-slate-800">{{ $t->company->name ?? '—' }}</span>
                            <span class="font-medium text-slate-800">{{ number_format($t->amount, 0, ',', '.') }} ₺</span>
                            <span class="text-xs px-2 py-0.5 rounded {{ $t->status === 'accepted' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">{{ $t->status }}</span>
                        </li>
                    @empty
                        <li class="text-slate-500 py-1">Henüz teklif yok.</li>
                    @endforelse
                </ul>
            </div>
        @empty
            <p class="text-slate-500">Bu müşteriye ait ihale yok.</p>
        @endforelse
    </div>
</div>
@endsection
