@extends('layouts.admin')

@section('title', 'Müşteriler')
@section('page_heading', 'Müşteriler')
@section('page_subtitle', 'Tüm müşteri bilgileri ve ihaleleri')

@section('content')
<div class="flex flex-col sm:flex-row sm:flex-nowrap sm:items-center sm:justify-between gap-4 mb-6">
    <form method="get" action="{{ route('admin.musteriler.index') }}" class="flex flex-wrap items-center gap-2 sm:flex-nowrap">
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Ad, e-posta veya telefon ara..." class="admin-input py-2 w-56 text-sm">
        <select name="sort" class="admin-input py-2 w-36 text-sm">
            <option value="name" {{ ($filters['sort'] ?? 'name') === 'name' ? 'selected' : '' }}>Ada göre</option>
            <option value="email" {{ ($filters['sort'] ?? '') === 'email' ? 'selected' : '' }}>E-postaya göre</option>
            <option value="created_at" {{ ($filters['sort'] ?? '') === 'created_at' ? 'selected' : '' }}>Kayıt tarihi</option>
        </select>
        <select name="dir" class="admin-input py-2 w-28 text-sm">
            <option value="asc" {{ ($filters['dir'] ?? 'asc') === 'asc' ? 'selected' : '' }}>A→Z</option>
            <option value="desc" {{ ($filters['dir'] ?? '') === 'desc' ? 'selected' : '' }}>Z→A</option>
        </select>
        <button type="submit" class="admin-btn-secondary text-sm py-2">Filtrele / Ara</button>
        @if(array_filter($filters ?? []))
            <a href="{{ route('admin.musteriler.index') }}" class="text-slate-500 hover:underline text-sm py-2">Temizle</a>
        @endif
    </form>
</div>
<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead>
            <tr>
                <th>Ad</th>
                <th>E-posta</th>
                <th>Telefon</th>
                <th>İhale sayısı</th>
                <th class="text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($musteriler as $m)
                <tr>
                    <td class="font-medium text-slate-800">{{ $m->name }}</td>
                    <td class="text-slate-600">{{ $m->email }}</td>
                    <td class="text-slate-600">{{ $m->phone ?? '—' }}</td>
                    <td class="text-slate-600">{{ $m->ihaleler_count ?? 0 }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.musteriler.show', $m) }}" class="text-emerald-600 hover:underline text-sm font-medium">Detay</a>
                        <a href="{{ route('admin.users.edit', $m) }}" class="ml-2 text-indigo-600 hover:underline text-sm font-medium">Düzenle</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Henüz müşteri yok.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($musteriler->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">{{ $musteriler->links() }}</div>
    @endif
</div>
@endsection
