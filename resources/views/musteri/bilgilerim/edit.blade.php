@extends('layouts.musteri')

@section('title', 'Bilgilerim')
@section('page_heading', 'Bilgilerim')
@section('page_subtitle', 'Hesap bilgilerinizi güncelleyin')

@section('content')
<div class="max-w-2xl">
    <div class="admin-card p-6">
        <form method="POST" action="{{ route('musteri.bilgilerim.update') }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="admin-form-group">
                <label class="admin-label">Ad Soyad *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="admin-input" autocomplete="name">
                @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">E-posta *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="admin-input" autocomplete="email">
                @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Telefon</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="admin-input" placeholder="5XX XXX XX XX" autocomplete="tel">
                @error('phone')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="border-t border-slate-200 dark:border-slate-600 pt-6 mt-6">
                <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Şifre değiştir</h3>
                <p class="text-sm text-slate-500 mb-3">Değiştirmek istemiyorsanız boş bırakın.</p>
                <div class="space-y-4">
                    <div class="admin-form-group">
                        <label class="admin-label">Yeni şifre</label>
                        <input type="password" name="password" class="admin-input" autocomplete="new-password">
                        @error('password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Yeni şifre (tekrar)</label>
                        <input type="password" name="password_confirmation" class="admin-input" autocomplete="new-password">
                    </div>
                </div>
            </div>
            <div class="pt-4">
                <button type="submit" class="admin-btn-primary px-5 py-2.5 rounded-lg font-medium">Kaydet</button>
            </div>
        </form>
    </div>
</div>
@endsection
