@extends('layouts.admin')

@section('title', 'Yük İlanları')
@section('page_heading', 'Yük İlanları')

@section('content')
<div class="flex justify-end mb-6">
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
