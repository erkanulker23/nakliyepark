@extends('layouts.admin')

@section('title', 'Kullanıcı düzenle')
@section('page_heading', 'Kullanıcı düzenle')
@section('page_subtitle', $user->email)

@section('content')
<div class="max-w-2xl space-y-4 min-w-0">
    {{-- Onay durumu & Engelleme — satır satır, butonlar iç içe olmasın --}}
    <div class="admin-card p-4 space-y-4">
        <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
            <span class="text-sm text-zinc-600 dark:text-zinc-400">E-posta doğrulandı:</span>
            @if($user->email_verified_at)
                <span class="text-sm font-medium text-emerald-600 dark:text-emerald-400">Evet</span>
            @else
                <span class="text-sm text-amber-600 dark:text-amber-400">Hayır</span>
                <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline">
                    @csrf
                    <button type="submit" class="admin-btn-primary text-sm">Doğrulama mailini gönder</button>
                </form>
            @endif
        </div>
        @if($user->isNakliyeci() && $user->company)
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 pt-2 border-t border-slate-200 dark:border-slate-600">
                <span class="text-sm text-zinc-600 dark:text-zinc-400">Firma onayı:</span>
                <a href="{{ route('admin.companies.edit', $user->company) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">{{ $user->company->name }}</a>
                @if($user->company->approved_at)
                    <span class="text-sm font-medium text-emerald-600 dark:text-emerald-400">Onaylı</span>
                @else
                    <span class="text-sm font-medium text-amber-600 dark:text-amber-400">Onaylı değil</span>
                    <form method="POST" action="{{ route('admin.companies.approve', $user->company) }}" class="inline">
                        @csrf
                        <button type="submit" class="admin-btn-primary text-sm">Firmayı onayla</button>
                    </form>
                @endif
            </div>
        @elseif($user->isNakliyeci())
            <div class="pt-2 border-t border-slate-200 dark:border-slate-600 space-y-3">
                <p class="text-sm text-amber-600 dark:text-amber-400 font-medium">Firma oluşturmamış</p>
                <form method="POST" action="{{ route('admin.users.create-company', $user) }}" class="flex flex-wrap items-end gap-3">
                    @csrf
                    <div class="min-w-[200px]">
                        <label for="company_name" class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Firma adı</label>
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" required maxlength="255" class="admin-input text-sm" placeholder="Örn: ABC Nakliyat">
                        @error('company_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="admin-btn-primary text-sm">Firma oluştur</button>
                </form>
                <p class="text-xs text-slate-500 dark:text-slate-400">Firma oluşturduktan sonra firma düzenleme sayfasına yönlendirilirsiniz.</p>
                <div class="pt-2">
                    <form method="POST" action="{{ route('admin.users.send-company-reminder', $user) }}" class="inline">
                        @csrf
                        <button type="submit" class="admin-btn-secondary text-sm">Firma oluşturması için hatırlatma maili gönder</button>
                    </form>
                </div>
            </div>
        @endif
        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 pt-2 border-t border-slate-200 dark:border-slate-600">
            @if($user->isBlocked())
                <span class="text-sm text-red-600 dark:text-red-400 font-medium">Engelli</span>
                <form method="POST" action="{{ route('admin.blocklist.unblock-user', $user) }}" class="inline">
                    @csrf
                    <button type="submit" class="admin-btn-primary text-sm">Engeli kaldır</button>
                </form>
            @elseif($user->id !== auth()->id())
                <form method="POST" action="{{ route('admin.blocklist.block-user', $user) }}" class="inline" onsubmit="return confirm('Bu kullanıcıyı engellemek istediğinize emin misiniz?');">
                    @csrf
                    <button type="submit" class="admin-btn-danger text-sm">Kullanıcıyı engelle</button>
                </form>
            @endif
        </div>
    </div>
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
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="admin-input" data-phone-mask placeholder="+90 532 111 22 33">
                @error('phone')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Yeni şifre (boş bırakırsanız değişmez)</label>
                <div class="relative">
                    <input type="password" name="password" id="admin-user-password" class="admin-input pr-10" placeholder="••••••••" autocomplete="new-password">
                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded" aria-label="Şifreyi göster/gizle" title="Şifreyi göster" data-toggle-password="admin-user-password">
                        <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878a4.5 4.5 0 106.262 6.262M4.031 11.117A10.047 10.047 0 002 12c0 4.478 2.943 8.268 7 9.543 3.974-1.271 7.26-3.678 9.608-6.424M19 12a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
                <div class="relative mt-2">
                    <input type="password" name="password_confirmation" id="admin-user-password-confirmation" class="admin-input pr-10" placeholder="Tekrar" autocomplete="new-password">
                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded" aria-label="Şifreyi göster/gizle" title="Şifreyi göster" data-toggle-password="admin-user-password-confirmation">
                        <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878a4.5 4.5 0 106.262 6.262M4.031 11.117A10.047 10.047 0 002 12c0 4.478 2.943 8.268 7 9.543 3.974-1.271 7.26-3.678 9.608-6.424M19 12a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
                @error('password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="admin-btn-primary">Kaydet</button>
                <a href="{{ route('admin.users.index') }}" class="admin-btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function() {
    document.querySelectorAll('[data-toggle-password]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-toggle-password');
            var input = document.getElementById(id);
            if (!input) return;
            var open = btn.querySelector('.eye-open');
            var closed = btn.querySelector('.eye-closed');
            if (input.type === 'password') {
                input.type = 'text';
                if (open) open.classList.add('hidden');
                if (closed) closed.classList.remove('hidden');
                btn.setAttribute('title', 'Şifreyi gizle');
                btn.setAttribute('aria-label', 'Şifreyi gizle');
            } else {
                input.type = 'password';
                if (open) open.classList.remove('hidden');
                if (closed) closed.classList.add('hidden');
                btn.setAttribute('title', 'Şifreyi göster');
                btn.setAttribute('aria-label', 'Şifreyi göster');
            }
        });
    });
})();
</script>
@endpush
@endsection
