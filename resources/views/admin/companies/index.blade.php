@extends('layouts.admin')

@section('title', 'Firmalar')
@section('page_heading', 'Firmalar')

@section('content')
<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead>
            <tr>
                <th>Firma</th>
                <th>Kullanıcı / İletişim</th>
                <th>İş / Kazanç / Komisyon</th>
                <th>Durum</th>
                <th class="text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($companies as $c)
                <tr>
                    <td>
                        <span class="font-medium text-slate-800">{{ $c->name }}</span>
                        @if($c->city)<br><span class="text-slate-500 text-sm">{{ $c->city }}</span>@endif
                    </td>
                    <td>
                        {{ $c->user->email ?? '-' }}<br>
                        @if($c->phone)<span class="text-slate-500 text-sm">{{ $c->phone }}</span>@endif
                    </td>
                    <td class="text-sm text-slate-600">
                        <strong>{{ $c->acceptedTeklifler()->count() }}</strong> iş · {{ number_format($c->total_earnings, 0, ',', '.') }} ₺ kazanç · {{ number_format($c->total_commission, 0, ',', '.') }} ₺ komisyon
                    </td>
                    <td>
                        @if($c->approved_at)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">Onaylı</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">Onay bekliyor</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.companies.edit', $c) }}" class="text-indigo-600 hover:underline text-sm font-medium">Düzenle</a>
                        @if(!$c->approved_at)
                            <form method="POST" action="{{ route('admin.companies.approve', $c) }}" class="inline ml-2">
                                @csrf
                                <button type="submit" class="text-emerald-600 hover:underline text-sm font-medium">Onayla</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.companies.reject', $c) }}" class="inline ml-2" onsubmit="return confirm('Onayı kaldırmak istediğinize emin misiniz?');">
                                @csrf
                                <button type="submit" class="text-slate-500 hover:underline text-sm">Onayı kaldır</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.companies.destroy', $c) }}" class="inline ml-2" onsubmit="return confirm('Bu firmayı silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm font-medium">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Henüz firma yok.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($companies->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">{{ $companies->links() }}</div>
    @endif
</div>
@endsection
