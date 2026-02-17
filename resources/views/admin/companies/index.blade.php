@extends('layouts.admin')

@section('title', 'Firmalar')
@section('page_heading', 'Nakliyeciler (Firmalar)')

@section('content')
<p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Nakliyeci olarak kayıt olup firma bilgilerini dolduran kullanıcılar burada listelenir. Onay filtresinden <strong>Onaylı</strong> veya <strong>Onay bekliyor</strong> seçerek listeleyebilirsiniz. Tüm kullanıcıları (nakliyeci/müşteri) görmek için <a href="{{ route('admin.users.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Kullanıcılar</a> sayfasını kullanın.</p>
<div class="flex flex-col sm:flex-row sm:flex-nowrap sm:items-center sm:justify-between gap-4 mb-6">
    <a href="{{ route('admin.users.create') }}" class="admin-btn-primary inline-flex items-center gap-2 shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Yeni nakliyeci profili oluştur
    </a>
</div>
<div class="flex flex-col sm:flex-row sm:flex-nowrap sm:items-center sm:justify-between gap-4 mb-6">
    <form method="get" action="{{ route('admin.companies.index') }}" class="flex flex-wrap items-center gap-2 sm:flex-nowrap">
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Firma, şehir veya e-posta ara..." class="admin-input py-2 w-56 text-sm">
        <select name="approved" class="admin-input py-2 w-40 text-sm">
            <option value="">Onay: Tümü</option>
            <option value="1" {{ ($filters['approved'] ?? '') === '1' ? 'selected' : '' }}>Onaylı</option>
            <option value="0" {{ ($filters['approved'] ?? '') === '0' ? 'selected' : '' }}>Onay bekliyor</option>
        </select>
        <select name="blocked" class="admin-input py-2 w-36 text-sm">
            <option value="">Üyelik: Tümü</option>
            <option value="0" {{ ($filters['blocked'] ?? '') === '0' ? 'selected' : '' }}>Aktif</option>
            <option value="1" {{ ($filters['blocked'] ?? '') === '1' ? 'selected' : '' }}>Askıda</option>
        </select>
        <select name="package" class="admin-input py-2 w-36 text-sm">
            <option value="">Tüm paketler</option>
            @foreach($paketler as $p)
                <option value="{{ $p['id'] ?? '' }}" {{ ($filters['package'] ?? '') === ($p['id'] ?? '') ? 'selected' : '' }}>{{ $p['name'] ?? $p['id'] ?? '' }}</option>
            @endforeach
        </select>
        <select name="sort" class="admin-input py-2 w-36 text-sm">
            <option value="created_at" {{ ($filters['sort'] ?? 'created_at') === 'created_at' ? 'selected' : '' }}>En yeni</option>
            <option value="name" {{ ($filters['sort'] ?? '') === 'name' ? 'selected' : '' }}>Ada göre</option>
            <option value="city" {{ ($filters['sort'] ?? '') === 'city' ? 'selected' : '' }}>Şehre göre</option>
        </select>
        <select name="dir" class="admin-input py-2 w-28 text-sm">
            <option value="desc" {{ ($filters['dir'] ?? 'desc') === 'desc' ? 'selected' : '' }}>Azalan</option>
            <option value="asc" {{ ($filters['dir'] ?? '') === 'asc' ? 'selected' : '' }}>Artan</option>
        </select>
        <button type="submit" class="admin-btn-secondary text-sm py-2">Filtrele / Ara</button>
        @if(array_filter($filters ?? []))
            <a href="{{ route('admin.companies.index') }}" class="admin-btn-secondary text-sm py-2">Temizle</a>
        @endif
    </form>
</div>
<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead>
            <tr>
                <th>Firma</th>
                <th>Kullanıcı / İletişim</th>
                <th>Paket</th>
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
                    <td>
                        <form method="POST" action="{{ route('admin.companies.update-package', $c) }}" class="inline" onchange="this.submit()">
                            @csrf
                            @method('PATCH')
                            <select name="package" class="admin-input py-1.5 px-2 text-xs w-32">
                                <option value="">— Paket yok —</option>
                                @foreach($paketler as $p)
                                    <option value="{{ $p['id'] ?? '' }}" {{ ($c->package ?? '') === ($p['id'] ?? '') ? 'selected' : '' }}>{{ $p['name'] ?? $p['id'] }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="text-sm text-slate-600">
                        <strong>{{ $c->acceptedTeklifler()->count() }}</strong> iş · {{ number_format($c->total_earnings, 0, ',', '.') }} ₺ kazanç · {{ number_format($c->total_commission, 0, ',', '.') }} ₺ komisyon
                    </td>
                    <td>
                        @if($c->blocked_at)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300" title="{{ $c->blocked_reason ?? 'Üyelik askıda' }}">Askıda</span>
                        @endif
                        @if($c->approved_at)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">Onaylı</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">Onay bekliyor</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($c->approved_at && $c->slug)
                            <a href="{{ route('firmalar.show', $c) }}" target="_blank" rel="noopener" class="text-slate-600 dark:text-slate-400 hover:underline text-sm font-medium">Firma sayfası</a>
                            <span class="text-slate-300 dark:text-slate-600 mx-1">|</span>
                        @endif
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
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">Henüz firma yok.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($companies->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">{{ $companies->links() }}</div>
    @endif
</div>
@endsection
