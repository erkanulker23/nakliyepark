@extends('layouts.admin')

@section('title', 'Yük İlanları')
@section('page_heading', 'Yük İlanları')

@section('content')
<div class="flex flex-col sm:flex-row sm:flex-nowrap sm:items-center sm:justify-between gap-4 mb-6">
    <form method="get" action="{{ route('admin.yuk-ilanlari.index') }}" class="flex flex-wrap items-center gap-2 sm:flex-nowrap">
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Güzergah, firma veya açıklama ara..." class="admin-input py-2 w-52 text-sm">
        <input type="text" name="from_city" value="{{ $filters['from_city'] ?? '' }}" placeholder="Nereden" class="admin-input py-2 w-28 text-sm">
        <input type="text" name="to_city" value="{{ $filters['to_city'] ?? '' }}" placeholder="Nereye" class="admin-input py-2 w-28 text-sm">
        <select name="status" class="admin-input py-2 w-32 text-sm">
            <option value="">Tüm durumlar</option>
            <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Pasif</option>
            <option value="draft" {{ ($filters['status'] ?? '') === 'draft' ? 'selected' : '' }}>Taslak</option>
        </select>
        <button type="submit" class="admin-btn-secondary text-sm py-2">Filtrele / Ara</button>
        @if(array_filter($filters ?? []))
            <a href="{{ route('admin.yuk-ilanlari.index') }}" class="text-slate-500 hover:underline text-sm py-2">Temizle</a>
        @endif
    </form>
    <a href="{{ route('admin.yuk-ilanlari.create') }}" class="admin-btn-primary">Yeni yük ilanı</a>
</div>
<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead>
            <tr>
                <th>Güzergah</th>
                <th>Firma</th>
                <th>Yük tipi / Tarih</th>
                <th>Durum</th>
                <th class="text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ilanlar as $i)
                <tr>
                    <td class="font-medium">{{ $i->from_city }} → {{ $i->to_city }}</td>
                    <td>{{ $i->company->name ?? '-' }}</td>
                    <td class="text-slate-600">{{ $i->load_type ?? '-' }} · {{ $i->load_date?->format('d.m.Y') }} · {{ $i->volume_m3 }} m³</td>
                    <td><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">{{ $i->status }}</span></td>
                    <td class="text-right">
                        <a href="{{ route('admin.yuk-ilanlari.show', $i) }}" class="text-indigo-600 hover:underline text-sm font-medium">Detay</a>
                        <a href="{{ route('admin.yuk-ilanlari.edit', $i) }}" class="ml-2 text-indigo-600 hover:underline text-sm font-medium">Düzenle</a>
                        <form method="POST" action="{{ route('admin.yuk-ilanlari.destroy', $i) }}" class="inline ml-2" onsubmit="return confirm('Bu ilanı silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm font-medium">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Yük ilanı yok.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($ilanlar->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">{{ $ilanlar->links() }}</div>
    @endif
</div>
@endsection
