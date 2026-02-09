@extends('layouts.admin')

@section('title', 'Teklifler')
@section('page_heading', 'Teklifler')

@section('content')
<form method="get" action="{{ route('admin.teklifler.index') }}" class="mb-6 flex flex-wrap items-end gap-2">
    <select name="status" class="admin-input py-2 w-36 text-sm">
        <option value="">Tüm durumlar</option>
        <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Beklemede</option>
        <option value="accepted" {{ ($filters['status'] ?? '') === 'accepted' ? 'selected' : '' }}>Kabul</option>
        <option value="rejected" {{ ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>Red</option>
    </select>
    <select name="company_id" class="admin-input py-2 w-48 text-sm">
        <option value="">Tüm firmalar</option>
        @foreach($companies as $c)
            <option value="{{ $c->id }}" {{ ($filters['company_id'] ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
        @endforeach
    </select>
    <input type="number" name="ihale_id" value="{{ $filters['ihale_id'] ?? '' }}" placeholder="İhale ID" class="admin-input py-2 w-24 text-sm">
    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="admin-input py-2 text-sm">
    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="admin-input py-2 text-sm">
    <button type="submit" class="admin-btn-secondary text-sm py-2">Filtrele</button>
</form>
<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead>
            <tr>
                <th>İhale (güzergah)</th>
                <th>Firma</th>
                <th>Tutar</th>
                <th>Durum</th>
                <th>Tarih</th>
                <th class="text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teklifler as $t)
                <tr>
                    <td>
                        <a href="{{ route('admin.ihaleler.show', $t->ihale) }}" class="text-indigo-600 hover:underline font-medium">{{ $t->ihale->from_city }} → {{ $t->ihale->to_city }}</a>
                    </td>
                    <td>{{ $t->company->name ?? '-' }}</td>
                    <td class="font-medium">{{ number_format($t->amount, 0, ',', '.') }} ₺</td>
                    <td>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $t->status === 'accepted' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">{{ $t->status }}</span>
                    </td>
                    <td class="text-slate-500 text-sm">{{ $t->created_at->format('d.m.Y H:i') }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.teklifler.edit', $t) }}" class="text-indigo-600 hover:underline text-sm font-medium">Düzenle</a>
                        <form method="POST" action="{{ route('admin.teklifler.destroy', $t) }}" class="inline ml-2" onsubmit="return confirm('Bu teklifi silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm font-medium">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">Teklif yok.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($teklifler->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">{{ $teklifler->links() }}</div>
    @endif
</div>
@endsection
