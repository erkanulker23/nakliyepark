@extends('layouts.nakliyeci')

@section('title', 'Bilgilerim')
@section('page_heading', 'Kişisel Bilgilerim')
@section('page_subtitle', 'Profil bilgilerinizi ve fotoğrafınızı güncelleyin')

@section('content')
<div class="max-w-2xl">
    <div class="admin-card p-6">
        <form method="POST" action="{{ route('nakliyeci.bilgilerim.update') }}" class="space-y-5" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="admin-form-group">
                <label class="admin-label">Profil fotoğrafı</label>
                <div class="flex flex-wrap items-center gap-4">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profil" class="w-24 h-24 rounded-xl object-cover border-2 border-slate-200 dark:border-slate-600">
                        <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 cursor-pointer">
                            <input type="checkbox" name="remove_avatar" value="1" class="rounded border-slate-300">
                            Mevcut fotoğrafı kaldır
                        </label>
                    @else
                        <div class="w-24 h-24 rounded-xl bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-400 text-2xl font-bold">
                            {{ mb_strtoupper(mb_substr($user->name ?? 'N', 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" class="admin-input text-sm py-2">
                        <p class="text-xs text-slate-500 mt-1">JPG, PNG veya WebP. En fazla 2 MB.</p>
                        @error('avatar')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

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
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="admin-input" placeholder="+90 532 111 22 33" autocomplete="tel" data-phone-mask>
                @error('phone')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="border-t border-slate-200 dark:border-slate-600 pt-6 mt-6">
                <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Şifre değiştir</h3>
                <p class="text-sm text-slate-500 mb-3">Yeni şifre girmek için önce mevcut şifrenizi girin. Değiştirmek istemiyorsanız alanları boş bırakın.</p>
                <div class="space-y-4">
                    <div class="admin-form-group">
                        <label class="admin-label">Mevcut şifre</label>
                        <div class="relative">
                            <input type="password" name="current_password" id="current_password" class="admin-input pr-10" autocomplete="current-password" placeholder="Şifre değiştirecekseniz doldurun">
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1 rounded password-toggle" aria-label="Şifreyi göster" data-target="current_password">
                                <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878a4.5 4.5 0 106.262 6.262M4.031 11.117A8.001 8.001 0 0014.9 5.527m-1.902 8.08a8 8 0 01-1.27 1.27"/></svg>
                            </button>
                        </div>
                        @error('current_password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Yeni şifre</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" class="admin-input pr-10" autocomplete="new-password">
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1 rounded password-toggle" aria-label="Şifreyi göster" data-target="password">
                                <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878a4.5 4.5 0 106.262 6.262M4.031 11.117A8.001 8.001 0 0014.9 5.527m-1.902 8.08a8 8 0 01-1.27 1.27"/></svg>
                            </button>
                        </div>
                        @error('password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Yeni şifre (tekrar)</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="admin-input pr-10" autocomplete="new-password">
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1 rounded password-toggle" aria-label="Şifreyi göster" data-target="password_confirmation">
                                <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878a4.5 4.5 0 106.262 6.262M4.031 11.117A8.001 8.001 0 0014.9 5.527m-1.902 8.08a8 8 0 01-1.27 1.27"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="text-sm text-slate-500 mt-3">
                    Mevcut şifrenizi hatırlamıyorsanız
                    <form method="POST" action="{{ route('nakliyeci.bilgilerim.send-reset-link') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sky-600 dark:text-sky-400 hover:underline font-medium">e-posta adresinize şifre sıfırlama linki gönderin</button>.
                    </form>
                </div>
            </div>
            <div class="pt-4">
                <button type="submit" class="admin-btn-primary px-5 py-2.5 rounded-lg font-medium">Kaydet</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.password-toggle').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-target');
        var input = document.getElementById(id);
        if (!input) return;
        var open = this.querySelector('.eye-open');
        var closed = this.querySelector('.eye-closed');
        if (input.type === 'password') {
            input.type = 'text';
            open.classList.add('hidden');
            closed.classList.remove('hidden');
            btn.setAttribute('aria-label', 'Şifreyi gizle');
        } else {
            input.type = 'password';
            open.classList.remove('hidden');
            closed.classList.add('hidden');
            btn.setAttribute('aria-label', 'Şifreyi göster');
        }
    });
});
</script>
@endpush
@endsection
