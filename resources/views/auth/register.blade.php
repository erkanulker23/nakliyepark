@extends('layouts.guest')

@section('title', 'Hesap Oluştur - NakliyePark')
@section('meta_description', 'NakliyePark hesabı oluşturun. Müşteri olarak ihale açın veya nakliye firması olarak teklif verin. Ücretsiz kayıt.')

@section('content')
<div class="w-full max-w-md">
    <div class="card p-6 sm:p-8">
        <div class="flex rounded-xl bg-zinc-100 dark:bg-zinc-800 p-1 mb-6" role="tablist" aria-label="Üyelik türü">
            @php $defaultRole = request('role', old('role', 'musteri')); $defaultRole = in_array($defaultRole, ['musteri', 'nakliyeci'], true) ? $defaultRole : 'musteri'; @endphp
            <button type="button" role="tab" id="tab-role-musteri" aria-selected="{{ $defaultRole === 'musteri' ? 'true' : 'false' }}" data-role="musteri" tabindex="{{ $defaultRole === 'musteri' ? 0 : -1 }}"
                class="register-role-tab flex-1 py-2.5 text-center text-sm font-medium rounded-lg transition-colors {{ $defaultRole === 'musteri' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                Müşteri kaydı
            </button>
            <button type="button" role="tab" id="tab-role-nakliyeci" aria-selected="{{ $defaultRole === 'nakliyeci' ? 'true' : 'false' }}" data-role="nakliyeci" tabindex="{{ $defaultRole === 'nakliyeci' ? 0 : -1 }}"
                class="register-role-tab flex-1 py-2.5 text-center text-sm font-medium rounded-lg transition-colors {{ $defaultRole === 'nakliyeci' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                Nakliyeci kaydı
            </button>
        </div>
        <h1 class="text-xl font-semibold text-zinc-900 dark:text-white mb-1">Hesap oluştur</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">Deftere yazın, teklif verin. Her gün 2.500'den fazla işin paylaşıldığı bu platforma katılın.</p>

    {{-- Step 1: Sadece rol seçimi (tek sayfa: form aşağıda) --}}
        <form method="POST" action="{{ route('register') }}" id="register-form" class="space-y-4">
            @csrf
            <input type="hidden" name="role" id="form-role" value="{{ $defaultRole }}">

            @php
                $nameParts = explode(' ', old('name', ''), 2);
                $firstName = old('first_name', $nameParts[0] ?? '');
                $lastName = old('last_name', $nameParts[1] ?? '');
            @endphp
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Adınız *</label>
                    <input id="first_name" type="text" name="first_name" value="{{ $firstName }}" required
                           class="input-touch @error('first_name') border-red-500 @enderror" placeholder="Adınız">
                    @error('first_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Soyadınız *</label>
                    <input id="last_name" type="text" name="last_name" value="{{ $lastName }}" required
                           class="input-touch @error('last_name') border-red-500 @enderror" placeholder="Soyadınız">
                    @error('last_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">E-posta *</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required inputmode="email" autocomplete="email"
                       class="input-touch @error('email') border-red-500 @enderror" placeholder="E-posta adresiniz">
                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Mobil numaranız</label>
                <div class="flex rounded-[var(--radius-button)] overflow-hidden border border-zinc-200 dark:border-zinc-600 bg-white dark:bg-zinc-800">
                    <span class="flex items-center px-3 text-zinc-500 dark:text-zinc-400 text-sm">+90</span>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" inputmode="numeric" pattern="[0-9]*" maxlength="10" autocomplete="tel-national"
                           class="min-h-[44px] flex-1 px-4 py-3 bg-transparent border-0 focus:outline-none focus:ring-0 text-zinc-900 dark:text-white @error('phone') ring-2 ring-red-500 @enderror" placeholder="5XX XXX XX XX">
                </div>
                @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Şifreniz *</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required
                           class="input-touch pr-12 @error('password') border-red-500 @enderror" placeholder="En az 8 karakter">
                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/30" aria-label="Şifreyi göster" data-password-toggle="password" title="Şifreyi göster">
                        <svg class="w-5 h-5 password-eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg class="w-5 h-5 password-eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Şifre (tekrar) *</label>
                <div class="relative">
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="input-touch pr-12" placeholder="Şifrenizi tekrar girin">
                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/30" aria-label="Şifreyi göster" data-password-toggle="password_confirmation" title="Şifreyi göster">
                        <svg class="w-5 h-5 password-eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg class="w-5 h-5 password-eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full gap-2">
                Kayıt ol
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"/></svg>
            </button>
        </form>
    </div>

    <p class="mt-6 text-center text-sm text-zinc-500">
        Zaten hesabınız var mı? <a href="{{ route('login') }}" class="link-muted">Giriş yapın</a>
    </p>
</div>

@push('scripts')
<script>
(function() {
    var formRole = document.getElementById('form-role');
    var tabs = document.querySelectorAll('.register-role-tab');
    if (!formRole || !tabs.length) return;

    function setActiveRole(role) {
        formRole.value = role;
        tabs.forEach(function(t) {
            var isActive = t.getAttribute('data-role') === role;
            t.setAttribute('aria-selected', isActive ? 'true' : 'false');
            t.setAttribute('tabindex', isActive ? 0 : -1);
            if (isActive) {
                t.classList.remove('text-zinc-600', 'dark:text-zinc-400');
                t.classList.add('bg-white', 'dark:bg-zinc-700', 'text-zinc-900', 'dark:text-white', 'shadow-sm');
            } else {
                t.classList.remove('bg-white', 'dark:bg-zinc-700', 'text-zinc-900', 'dark:text-white', 'shadow-sm');
                t.classList.add('text-zinc-600', 'dark:text-zinc-400');
            }
        });
    }

    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            setActiveRole(this.getAttribute('data-role'));
        });
        tab.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                setActiveRole(this.getAttribute('data-role'));
            }
        });
    });

    var phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });
        phoneInput.addEventListener('paste', function(e) {
            e.preventDefault();
            var text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 10);
            this.value = text;
        });
    }

    document.querySelectorAll('[data-password-toggle]').forEach(function(btn) {
        var inputId = btn.getAttribute('data-password-toggle');
        var input = document.getElementById(inputId);
        var openEye = btn.querySelector('.password-eye-open');
        var closedEye = btn.querySelector('.password-eye-closed');
        if (!input || !openEye || !closedEye) return;
        btn.addEventListener('click', function() {
            var isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            openEye.classList.toggle('hidden', isPassword);
            closedEye.classList.toggle('hidden', !isPassword);
            btn.setAttribute('aria-label', isPassword ? 'Şifreyi gizle' : 'Şifreyi göster');
            btn.setAttribute('title', isPassword ? 'Şifreyi gizle' : 'Şifreyi göster');
        });
    });

    var form = document.getElementById('register-form');
    if (form) {
        form.addEventListener('submit', function() {
            var first = document.getElementById('first_name');
            var last = document.getElementById('last_name');
            if (first && last) {
                var firstVal = first.value.trim();
                var lastVal = last.value.trim();
                var nameInput = form.querySelector('input[name="name"]');
                if (!nameInput) {
                    nameInput = document.createElement('input');
                    nameInput.type = 'hidden';
                    nameInput.name = 'name';
                    form.appendChild(nameInput);
                }
                nameInput.value = (firstVal + ' ' + lastVal).trim() || firstVal || lastVal;
            }
        });
    }
})();
</script>
@endpush
@endsection
