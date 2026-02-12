@extends('layouts.admin')

@section('title', 'İhaleler')
@section('page_heading', 'İhaleler')

@section('content')
@php
    $pendingCount = \App\Models\Ihale::where('status', 'pending')->count();
@endphp
@if($pendingCount > 0)
    <div class="mb-4 px-4 py-2 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm">
        <strong>{{ $pendingCount }}</strong> ihale onay bekliyor. <a href="{{ route('admin.ihaleler.index', ['status' => 'pending']) }}" class="underline font-medium">Görüntüle</a>
    </div>
@endif
<div class="flex flex-col sm:flex-row sm:flex-nowrap sm:items-center sm:justify-between gap-4 mb-6">
    <form method="get" action="{{ route('admin.ihaleler.index') }}" class="flex flex-wrap items-center gap-2 sm:flex-nowrap">
        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" placeholder="Başlangıç" class="admin-input py-2 w-36 text-sm" title="Varsayılan: son 30 gün">
        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" placeholder="Bitiş" class="admin-input py-2 w-36 text-sm">
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Genel ara (güzergah, açıklama, kişi)..." class="admin-input py-2 w-52 text-sm">
        <input type="text" name="from_city" value="{{ $filters['from_city'] ?? '' }}" placeholder="Nereden" class="admin-input py-2 w-32 text-sm">
        <input type="text" name="to_city" value="{{ $filters['to_city'] ?? '' }}" placeholder="Nereye" class="admin-input py-2 w-32 text-sm">
        <select name="service_type" class="admin-input py-2 w-44 text-sm">
            <option value="">Hizmet tipi</option>
            @foreach(\App\Models\Ihale::serviceTypeLabels() as $key => $label)
                <option value="{{ $key }}" {{ ($filters['service_type'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="status" class="admin-input py-2 w-36 text-sm">
            <option value="">Tüm durumlar</option>
            <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Onay bekliyor</option>
            <option value="draft" {{ ($filters['status'] ?? '') === 'draft' ? 'selected' : '' }}>Taslak</option>
            <option value="published" {{ ($filters['status'] ?? '') === 'published' ? 'selected' : '' }}>Yayında</option>
            <option value="closed" {{ ($filters['status'] ?? '') === 'closed' ? 'selected' : '' }}>Kapalı</option>
            <option value="cancelled" {{ ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' }}>İptal</option>
        </select>
        <button type="submit" class="admin-btn-secondary text-sm py-2">Filtrele / Ara</button>
        @if(array_filter($filters ?? []))
            <a href="{{ route('admin.ihaleler.index') }}" class="text-slate-500 hover:underline text-sm py-2">Temizle</a>
        @endif
    </form>
    <a href="{{ route('admin.ihaleler.create') }}" class="admin-btn-primary">Yeni ihale</a>
</div>
<div class="mb-4 flex flex-wrap items-center gap-2">
    <form method="POST" action="{{ route('admin.ihaleler.bulk-publish') }}" id="form-bulk-publish" class="inline">
        @csrf
        <input type="hidden" name="ids" id="bulk-ids-publish" value="">
        <button type="submit" class="admin-btn-secondary text-sm" onclick="setBulkIds('bulk-ids-publish'); return document.getElementById('bulk-ids-publish').value;">Seçilenleri yayınla</button>
    </form>
    <form method="POST" action="{{ route('admin.ihaleler.bulk-close') }}" id="form-bulk-close" class="inline">
        @csrf
        <input type="hidden" name="ids" id="bulk-ids-close" value="">
        <button type="submit" class="admin-btn-secondary text-sm" onclick="setBulkIds('bulk-ids-close'); return document.getElementById('bulk-ids-close').value;">Seçilenleri kapat</button>
    </form>
    <span class="text-slate-500 text-sm">Listeden ihaleleri seçip toplu işlem uygulayabilirsiniz (yayınla: onay bekleyenler, kapat: yayındakiler).</span>
</div>
<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead>
            <tr>
                <th class="w-10"><input type="checkbox" id="select-all" title="Tümünü seç"></th>
                <th>Güzergah</th>
                <th>Talep sahibi</th>
                <th>Tarih / Hacim</th>
                <th>Durum</th>
                <th class="text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ihaleler as $i)
                <tr>
                    <td>
                        @if($i->status === 'pending' || $i->status === 'published')
                            <input type="checkbox" class="bulk-ihale-id" value="{{ $i->id }}" data-status="{{ $i->status }}">
                        @else
                            <span class="text-slate-300">—</span>
                        @endif
                    </td>
                    <td class="font-medium">{{ $i->from_city }} → {{ $i->to_city }}</td>
                    <td>{{ $i->user?->name ?? $i->guest_contact_name ?? 'Misafir' }}</td>
                    <td class="text-slate-600">
                        @if($i->move_date || $i->move_date_end)
                            @if($i->move_date_end && $i->move_date != $i->move_date_end)
                                {{ $i->move_date?->format('d.m.Y') }} – {{ $i->move_date_end?->format('d.m.Y') }}
                            @else
                                {{ $i->move_date?->format('d.m.Y') ?? '-' }}
                            @endif
                        @else
                            Fiyat bakıyorum
                        @endif
                        · {{ $i->volume_m3 }} m³
                    </td>
                    <td>
                        @php $statusLabels = ['pending' => 'Onay bekliyor', 'draft' => 'Taslak', 'published' => 'Yayında', 'closed' => 'Kapalı', 'cancelled' => 'İptal']; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            @if($i->status === 'pending') bg-amber-100 text-amber-800
                            @elseif($i->status === 'published') bg-emerald-100 text-emerald-800
                            @elseif($i->status === 'closed') bg-slate-100 text-slate-700
                            @elseif($i->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-slate-100 text-slate-700 @endif">
                            {{ $statusLabels[$i->status] ?? $i->status }}
                        </span>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.ihaleler.show', $i) }}" class="text-indigo-600 hover:underline text-sm font-medium">Detay</a>
                        <a href="{{ route('admin.ihaleler.edit', $i) }}" class="ml-2 text-indigo-600 hover:underline text-sm font-medium">Düzenle</a>
                        <form method="POST" action="{{ route('admin.ihaleler.destroy', $i) }}" class="inline ml-2" onsubmit="return confirm('Bu ihaleyi silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm font-medium">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">İhale yok.</td></tr>
            @endforelse
        </tbody>
    </table>
    <script>
    document.getElementById('select-all')?.addEventListener('change', function() {
        document.querySelectorAll('.bulk-ihale-id').forEach(function(cb) { cb.checked = this.checked; }.bind(this));
    });
    function setBulkIds(hiddenId) {
        var ids = Array.from(document.querySelectorAll('.bulk-ihale-id:checked')).map(function(c) { return c.value; });
        document.getElementById(hiddenId).value = ids.join(',');
    }
    </script>
    @if($ihaleler->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">{{ $ihaleler->links() }}</div>
    @endif
</div>
@endsection
