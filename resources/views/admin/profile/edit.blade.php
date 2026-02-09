@extends('layouts.admin')

@section('title', 'Profil')
@section('page_heading', 'Profilim')
@section('page_subtitle', auth()->user()->email)

@section('content')
<div class="max-w-2xl">
    <div class="admin-card p-6">
        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5">
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
                <label class="admin-label">Yeni şifre (boş bırakırsanız değişmez)</label>
                <input type="password" name="password" class="admin-input" placeholder="••••••••">
                <input type="password" name="password_confirmation" class="admin-input mt-2" placeholder="Tekrar">
                @error('password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="pt-2">
                <button type="submit" class="admin-btn-primary">Kaydet</button>
            </div>
        </form>
    </div>
</div>
@endsection
