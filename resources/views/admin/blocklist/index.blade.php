@extends('layouts.admin')

@section('title', 'Engellemeler')
@section('page_heading', 'Engellemeler')
@section('page_subtitle', 'E-posta, telefon, IP ve hesap engelleme')

@section('content')
@if(session('success'))
    <div class="admin-alert-success mb-4">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="admin-alert-error mb-4">{{ session('error') }}</div>
@endif

<div class="space-y-8">
    {{-- E-posta engelleri --}}
    <div class="admin-card p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-3">E-posta engelleri</h2>
        <form method="POST" action="{{ route('admin.blocklist.store-email') }}" class="flex flex-wrap gap-2 mb-4">
            @csrf
            <input type="email" name="email" class="admin-input w-64" placeholder="ornek@mail.com" required>
            <input type="text" name="reason" class="admin-input w-48" placeholder="Sebep (isteğe bağlı)">
            <button type="submit" class="admin-btn-primary">Ekle</button>
        </form>
        <ul class="space-y-1 text-sm">
            @forelse($emails as $e)
                <li class="flex items-center justify-between py-1.5 border-b border-slate-100 last:border-0">
                    <span class="font-medium text-slate-800">{{ $e->email }}</span>
                    <span class="text-slate-500">{{ $e->reason }}</span>
                    <form method="POST" action="{{ route('admin.blocklist.destroy-email', $e) }}" class="inline" onsubmit="return confirm('Bu engeli kaldırmak istediğinize emin misiniz?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Kaldır</button>
                    </form>
                </li>
            @empty
                <li class="text-slate-500">Engelli e-posta yok.</li>
            @endforelse
        </ul>
        @if($emails->hasPages())
            <div class="mt-3">{{ $emails->links() }}</div>
        @endif
    </div>

    {{-- Telefon engelleri --}}
    <div class="admin-card p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-3">Telefon engelleri</h2>
        <form method="POST" action="{{ route('admin.blocklist.store-phone') }}" class="flex flex-wrap gap-2 mb-4">
            @csrf
            <input type="tel" name="phone" class="admin-input w-48" placeholder="+90 532 111 22 33" data-phone-mask required>
            <input type="text" name="reason" class="admin-input w-48" placeholder="Sebep (isteğe bağlı)">
            <button type="submit" class="admin-btn-primary">Ekle</button>
        </form>
        <ul class="space-y-1 text-sm">
            @forelse($phones as $p)
                <li class="flex items-center justify-between py-1.5 border-b border-slate-100 last:border-0">
                    <span class="font-medium text-slate-800">{{ $p->phone }}</span>
                    <span class="text-slate-500">{{ $p->reason }}</span>
                    <form method="POST" action="{{ route('admin.blocklist.destroy-phone', $p) }}" class="inline" onsubmit="return confirm('Bu engeli kaldırmak istediğinize emin misiniz?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Kaldır</button>
                    </form>
                </li>
            @empty
                <li class="text-slate-500">Engelli telefon yok.</li>
            @endforelse
        </ul>
        @if($phones->hasPages())
            <div class="mt-3">{{ $phones->links() }}</div>
        @endif
    </div>

    {{-- IP engelleri --}}
    <div class="admin-card p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-3">IP engelleri</h2>
        <form method="POST" action="{{ route('admin.blocklist.store-ip') }}" class="flex flex-wrap gap-2 mb-4">
            @csrf
            <input type="text" name="ip" class="admin-input w-48" placeholder="192.168.1.1" required>
            <input type="text" name="reason" class="admin-input w-48" placeholder="Sebep (isteğe bağlı)">
            <button type="submit" class="admin-btn-primary">Ekle</button>
        </form>
        <ul class="space-y-1 text-sm">
            @forelse($ips as $ip)
                <li class="flex items-center justify-between py-1.5 border-b border-slate-100 last:border-0">
                    <span class="font-medium text-slate-800">{{ $ip->ip }}</span>
                    <span class="text-slate-500">{{ $ip->reason }}</span>
                    <form method="POST" action="{{ route('admin.blocklist.destroy-ip', $ip) }}" class="inline" onsubmit="return confirm('Bu engeli kaldırmak istediğinize emin misiniz?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Kaldır</button>
                    </form>
                </li>
            @empty
                <li class="text-slate-500">Engelli IP yok.</li>
            @endforelse
        </ul>
        @if($ips->hasPages())
            <div class="mt-3">{{ $ips->links() }}</div>
        @endif
    </div>

    {{-- Engelli kullanıcılar --}}
    <div class="admin-card p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-3">Engelli kullanıcılar (müşteri/nakliyeci)</h2>
        <p class="text-sm text-slate-500 mb-3">Kullanıcı engellemek için <a href="{{ route('admin.users.index') }}" class="text-emerald-600 hover:underline">Kullanıcılar</a> veya <a href="{{ route('admin.musteriler.index') }}" class="text-emerald-600 hover:underline">Müşteriler</a> sayfasından ilgili kullanıcıya girip &quot;Engelle&quot; butonunu kullanın.</p>
        <ul class="space-y-1 text-sm">
            @forelse($blockedUsers as $u)
                <li class="flex items-center justify-between py-1.5 border-b border-slate-100 last:border-0">
                    <span class="font-medium text-slate-800">{{ $u->name }}</span>
                    <span class="text-slate-500">{{ $u->email }} · {{ $u->role }} · {{ $u->blocked_at?->format('d.m.Y') }}</span>
                    <form method="POST" action="{{ route('admin.blocklist.unblock-user', $u) }}" class="inline">
                        @csrf
                        <button type="submit" class="text-emerald-600 hover:underline">Engeli kaldır</button>
                    </form>
                </li>
            @empty
                <li class="text-slate-500">Engelli kullanıcı yok.</li>
            @endforelse
        </ul>
    </div>

    {{-- Üyeliği askıda firmalar --}}
    <div class="admin-card p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-3">Üyeliği askıda firmalar</h2>
        <p class="text-sm text-slate-500 mb-3">Nakliyeci üyeliğini askıya almak için <a href="{{ route('admin.companies.index') }}" class="text-emerald-600 hover:underline">Firmalar</a> sayfasından ilgili firmaya girip &quot;Üyeliği askıya al&quot; bölümünü kullanın (borç, sözleşme ihlali vb.).</p>
        <ul class="space-y-1 text-sm">
            @forelse($blockedCompanies as $c)
                <li class="flex items-center justify-between py-1.5 border-b border-slate-100 last:border-0 flex-wrap gap-1">
                    <span class="font-medium text-slate-800">{{ $c->name }}</span>
                    <span class="text-slate-500">{{ $c->user->email ?? '-' }} · {{ $c->blocked_at?->format('d.m.Y') }}{{ $c->blocked_reason ? ' · ' . \Illuminate\Support\Str::limit($c->blocked_reason, 40) : '' }}</span>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.companies.edit', $c) }}" class="text-indigo-600 hover:underline">Düzenle</a>
                        <form method="POST" action="{{ route('admin.blocklist.unblock-company', $c) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-emerald-600 hover:underline">Askıyı kaldır</button>
                        </form>
                    </div>
                </li>
            @empty
                <li class="text-slate-500">Üyeliği askıda firma yok.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
