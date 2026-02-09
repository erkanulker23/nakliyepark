@extends('layouts.admin')

@section('title', 'Müşteriler')
@section('page_heading', 'Müşteriler')
@section('page_subtitle', 'Tüm müşteri bilgileri ve ihaleleri')

@section('content')
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
