@extends('layouts.admin')

@section('title', 'Yeni nakliye firması')
@section('page_heading', 'Yeni nakliye firması')
@section('page_subtitle', 'Admin panelinden nakliyeci kullanıcı ve firma ekleyin')

@section('content')
<div class="max-w-2xl">
    <div class="admin-card p-6">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf
            <div class="admin-form-group">
                <label class="admin-label">Yetkili adı soyadı *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="admin-input" placeholder="Örn. Ahmet Yılmaz">
                @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">E-posta *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="admin-input" placeholder="firma@ornek.com">
                @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Şifre *</label>
                <input type="password" name="password" required class="admin-input" placeholder="En az 8 karakter" autocomplete="new-password">
                <input type="password" name="password_confirmation" required class="admin-input mt-2" placeholder="Şifre tekrar" autocomplete="new-password">
                @error('password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Telefon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="admin-input" placeholder="5xxxxxxxxx">
                @error('phone')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Firma adı</label>
                <input type="text" name="company_name" value="{{ old('company_name') }}" class="admin-input" placeholder="Boş bırakırsanız sadece kullanıcı oluşturulur">
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Doldurursanız bu kullanıcıya bağlı bir firma kaydı da oluşturulur (onay bekler).</p>
                @error('company_name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="admin-btn-primary">Nakliye firması oluştur</button>
                <a href="{{ route('admin.users.index') }}" class="admin-btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection
