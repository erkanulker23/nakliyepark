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
                <div class="flex gap-2 flex-wrap">
                    <input type="password" name="password" id="admin-create-user-password" required class="admin-input flex-1 min-w-[200px]" placeholder="En az 8 karakter" autocomplete="new-password">
                    <button type="button" id="admin-generate-password" class="admin-btn-secondary text-sm whitespace-nowrap">Otomatik oluştur</button>
                </div>
                <input type="password" name="password_confirmation" id="admin-create-user-password-confirmation" required class="admin-input mt-2" placeholder="Şifre tekrar" autocomplete="new-password">
                @error('password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Telefon</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" class="admin-input" data-phone-mask placeholder="+90 532 111 22 33">
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

@push('scripts')
<script>
(function() {
    var pw = document.getElementById('admin-create-user-password');
    var pwConf = document.getElementById('admin-create-user-password-confirmation');
    var btn = document.getElementById('admin-generate-password');
    if (!pw || !pwConf || !btn) return;

    function randomPassword(length) {
        length = length || 14;
        var charset = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789!@#$%';
        var result = '';
        for (var i = 0; i < length; i++) {
            result += charset.charAt(Math.floor(Math.random() * charset.length));
        }
        return result;
    }

    btn.addEventListener('click', function() {
        var p = randomPassword(14);
        pw.value = p;
        pwConf.value = p;
        pw.type = 'text';
        pwConf.type = 'text';
        setTimeout(function() { pw.type = 'password'; pwConf.type = 'password'; }, 3000);
    });
})();
</script>
@endpush
@endsection
