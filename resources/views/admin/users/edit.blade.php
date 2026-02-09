@extends('layouts.admin')

@section('title', 'Kullanıcı düzenle')
@section('page_heading', 'Kullanıcı düzenle')
@section('page_subtitle', $user->email)

@section('content')
<div class="max-w-2xl space-y-4">
    @if($user->isBlocked())
        <div class="admin-alert-error rounded-lg px-4 py-3">Bu kullanıcı engelli. Engeli kaldırmak için aşağıdaki butonu kullanın.</div>
        <form method="POST" action="{{ route('admin.blocklist.unblock-user', $user) }}" class="inline">
            @csrf
            <button type="submit" class="admin-btn-primary">Engeli kaldır</button>
        </form>
    @elseif($user->id !== auth()->id())
        <form method="POST" action="{{ route('admin.blocklist.block-user', $user) }}" class="inline" onsubmit="return confirm('Bu kullanıcıyı engellemek istediğinize emin misiniz?');">
            @csrf
            <button type="submit" class="admin-btn-danger">Kullanıcıyı engelle</button>
        </form>
    @endif
    <div class="admin-card p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="admin-form-group">
                <label class="admin-label">Ad *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="admin-input">
                @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">E-posta *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="admin-input">
                @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Rol *</label>
                <select name="role" class="admin-input">
                    <option value="musteri" {{ old('role', $user->role) === 'musteri' ? 'selected' : '' }}>Müşteri</option>
                    <option value="nakliyeci" {{ old('role', $user->role) === 'nakliyeci' ? 'selected' : '' }}>Nakliyeci</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Telefon</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="admin-input">
                @error('phone')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Yeni şifre (boş bırakırsanız değişmez)</label>
                <input type="password" name="password" class="admin-input" placeholder="••••••••">
                <input type="password" name="password_confirmation" class="admin-input mt-2" placeholder="Tekrar">
                @error('password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="admin-btn-primary">Kaydet</button>
                <a href="{{ route('admin.users.index') }}" class="admin-btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection
