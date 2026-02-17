@extends('layouts.admin')

@section('title', 'Firmalar')
@section('page_heading', 'Nakliyeciler (Firmalar)')

@section('content')
<p class="text-sm text-[var(--panel-text-muted)] mb-6">Nakliyeci firmaları burada listelenir. <a href="{{ route('admin.users.index') }}" class="text-[var(--panel-primary)] hover:underline font-medium">Kullanıcılar</a> sayfasından tüm hesapları yönetebilirsiniz.</p>

<div class="flex flex-wrap items-center gap-3 mb-6">
    <a href="{{ route('admin.users.create') }}" class="btn-primary inline-flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Yeni nakliyeci profili
    </a>
</div>

<form method="get" action="{{ route('admin.companies.index') }}" class="panel-card p-4 rounded-2xl mb-6 min-w-0">
    <div class="flex flex-wrap items-center gap-3 min-w-0">
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Firma, şehir veya e-posta..." class="input-touch flex-1 min-w-[180px] max-w-xs rounded-xl text-sm" aria-label="Ara">
        <select name="approved" class="input-touch w-auto min-w-[140px] rounded-xl text-sm">
            <option value="">Onay: Tümü</option>
            <option value="1" {{ ($filters['approved'] ?? '') === '1' ? 'selected' : '' }}>Onaylı</option>
            <option value="0" {{ ($filters['approved'] ?? '') === '0' ? 'selected' : '' }}>Onay bekliyor</option>
        </select>
        <select name="blocked" class="input-touch w-auto min-w-[120px] rounded-xl text-sm">
            <option value="">Üyelik: Tümü</option>
            <option value="0" {{ ($filters['blocked'] ?? '') === '0' ? 'selected' : '' }}>Aktif</option>
            <option value="1" {{ ($filters['blocked'] ?? '') === '1' ? 'selected' : '' }}>Askıda</option>
        </select>
        <select name="package" class="input-touch w-auto min-w-[120px] rounded-xl text-sm">
            <option value="">Tüm paketler</option>
            @foreach($paketler as $p)
                <option value="{{ $p['id'] ?? '' }}" {{ ($filters['package'] ?? '') === ($p['id'] ?? '') ? 'selected' : '' }}>{{ $p['name'] ?? $p['id'] ?? '' }}</option>
            @endforeach
        </select>
        <select name="sort" class="input-touch w-auto min-w-[120px] rounded-xl text-sm">
            <option value="created_at" {{ ($filters['sort'] ?? 'created_at') === 'created_at' ? 'selected' : '' }}>En yeni</option>
            <option value="name" {{ ($filters['sort'] ?? '') === 'name' ? 'selected' : '' }}>Ada göre</option>
            <option value="city" {{ ($filters['sort'] ?? '') === 'city' ? 'selected' : '' }}>Şehre göre</option>
        </select>
        <button type="submit" class="btn-secondary rounded-xl text-sm py-2.5 px-4">Filtrele</button>
        @if(array_filter($filters ?? []))
            <a href="{{ route('admin.companies.index') }}" class="btn-ghost rounded-xl text-sm py-2.5 px-4">Temizle</a>
        @endif
    </div>
</form>

<div class="space-y-4">
    @forelse($companies as $c)
        <div class="panel-card p-5 sm:p-6 rounded-2xl border border-[var(--panel-border)] bg-[var(--panel-surface)] shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div class="min-w-0">
                    <h3 class="text-lg font-bold text-[var(--panel-text)]">{{ $c->name }}</h3>
                    @if($c->city)<p class="text-sm text-[var(--panel-text-muted)] mt-0.5">{{ $c->city }}</p>@endif
                    <p class="text-sm text-[var(--panel-text-muted)] mt-1">{{ $c->user->email ?? '-' }}</p>
                    @if($c->phone)<p class="text-sm text-[var(--panel-text-muted)]">{{ $c->phone }}</p>@endif
                    <p class="text-sm text-[var(--panel-text-muted)] mt-2">
                        <strong class="text-[var(--panel-text)]">{{ $c->acceptedTeklifler()->count() }}</strong> iş
                        · {{ number_format($c->total_earnings, 0, ',', '.') }} ₺ kazanç
                        · {{ number_format($c->total_commission, 0, ',', '.') }} ₺ komisyon
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    @if($c->blocked_at)
                        <x-panel.status-badge status="rejected">Askıda</x-panel.status-badge>
                    @endif
                    @if($c->approved_at)
                        <x-panel.status-badge status="approved">Onaylı</x-panel.status-badge>
                    @else
                        <x-panel.status-badge status="pending">Onay bekliyor</x-panel.status-badge>
                    @endif
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-[var(--panel-border)] flex flex-wrap items-center gap-3">
                <form method="POST" action="{{ route('admin.companies.update-package', $c) }}" class="flex items-center gap-2" onchange="this.submit()">
                    @csrf
                    @method('PATCH')
                    <label class="text-sm text-[var(--panel-text-muted)]">Paket:</label>
                    <select name="package" class="input-touch w-auto min-w-[140px] rounded-xl text-sm py-2">
                        <option value="">— Yok —</option>
                        @foreach($paketler as $p)
                            <option value="{{ $p['id'] ?? '' }}" {{ ($c->package ?? '') === ($p['id'] ?? '') ? 'selected' : '' }}>{{ $p['name'] ?? $p['id'] }}</option>
                        @endforeach
                    </select>
                </form>
                <span class="text-[var(--panel-border)]">|</span>
                @if($c->approved_at && !empty($c->slug))
                    <a href="{{ route('firmalar.show', $c->slug) }}" target="_blank" rel="noopener" class="text-sm font-medium text-[var(--panel-primary)] hover:underline">Firma sayfası</a>
                    <span class="text-[var(--panel-border)]">|</span>
                @endif
                <a href="{{ route('admin.companies.edit', $c) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Düzenle</a>
                @if(!$c->approved_at)
                    <form method="POST" action="{{ route('admin.companies.approve', $c) }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:underline">Onayla</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.companies.reject', $c) }}" class="inline" onsubmit="return confirm('Onayı kaldırmak istediğinize emin misiniz?');">
                        @csrf
                        <button type="submit" class="text-sm text-[var(--panel-text-muted)] hover:underline">Onayı kaldır</button>
                    </form>
                @endif
                <form method="POST" action="{{ route('admin.companies.destroy', $c) }}" class="inline" onsubmit="return confirm('Bu firmayı silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-medium text-red-600 dark:text-red-400 hover:underline">Sil</button>
                </form>
            </div>
        </div>
    @empty
        <div class="panel-card p-10 sm:p-12 rounded-2xl text-center">
            <p class="text-[var(--panel-text-muted)]">Henüz firma yok.</p>
        </div>
    @endforelse
</div>

@if($companies->hasPages())
    <div class="mt-8">{{ $companies->links() }}</div>
@endif
@endsection
