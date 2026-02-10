@extends('layouts.app')

@section('title', 'Hesap Oluştur - NakliyePark')
@section('meta_description', 'NakliyePark hesabı oluşturun. Müşteri olarak ihale açın veya nakliye firması olarak teklif verin. Ücretsiz kayıt.')

@section('content')
<div class="page-container py-8 max-w-lg mx-auto">
    <div class="card p-6 sm:p-8">
        <div class="flex items-center justify-between gap-4 mb-2">
        <div class="flex items-center gap-3">
            <span class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </span>
            <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">Hesap oluştur</h1>
        </div>
        <button type="button" id="back-btn" class="btn-ghost w-10 h-10 rounded-xl hidden" aria-label="Geri">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
    </div>

    {{-- Step 1: Üyelik türü --}}
    <div id="step1" class="step-panel">
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">Deftere yazın, teklif verin. Her gün 2.500’den fazla işin paylaşıldığı bu platforma katılın. İş fırsatlarını kaçırmayın.</p>
        <div class="space-y-3 mb-6">
            <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-slate-200 dark:border-slate-600 cursor-pointer hover:border-emerald-300 transition-colors has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-900/20">
                <input type="radio" name="role" value="musteri" {{ old('role', 'musteri') === 'musteri' ? 'checked' : '' }} class="w-4 h-4 text-emerald-500">
                <span class="font-medium text-zinc-900 dark:text-white">Müşteri üyeliği</span>
            </label>
            <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-slate-200 dark:border-slate-600 cursor-pointer hover:border-emerald-300 transition-colors has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-900/20">
                <input type="radio" name="role" value="nakliyeci" {{ old('role') === 'nakliyeci' ? 'checked' : '' }} class="w-4 h-4 text-emerald-500">
                <span class="font-medium text-zinc-900 dark:text-white">Nakliyeci üyeliği</span>
            </label>
        </div>
        <button type="button" id="next-to-step2" class="btn-primary w-full gap-2">
            Devam et
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>
    </div>

    {{-- Step 2: Kişisel bilgiler --}}
    <div id="step2" class="step-panel hidden">
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">Talep oluşturabilir, teklif alabilir ve işlemlerinizi takip edebilirsiniz.</p>
        <div class="h-1.5 bg-zinc-200 dark:bg-zinc-700 rounded-full mb-6 overflow-hidden">
            <div id="progress-bar" class="h-full bg-sky-600 rounded-full transition-all duration-300" style="width: 50%"></div>
        </div>

        <form method="POST" action="{{ route('register') }}" id="register-form" class="space-y-4">
            @csrf
            <input type="hidden" name="role" id="form-role" value="{{ old('role', 'musteri') }}">

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
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" inputmode="tel"
                           class="min-h-[44px] flex-1 px-4 py-3 bg-transparent border-0 focus:outline-none focus:ring-0 text-zinc-900 dark:text-white @error('phone') ring-2 ring-red-500 @enderror" placeholder="5XX XXX XX XX">
                </div>
                @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Şifreniz *</label>
                <input id="password" type="password" name="password" required
                       class="input-touch @error('password') border-red-500 @enderror" placeholder="En az 8 karakter">
                @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Şifre (tekrar) *</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="input-touch" placeholder="Şifrenizi tekrar girin">
            </div>

            <button type="submit" class="btn-primary w-full gap-2">
                Kayıt ol
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>
    </div>
    </div>

    <p class="mt-6 text-center text-sm text-zinc-500">
        Zaten hesabınız var mı? <a href="{{ route('login') }}" class="link-muted">Giriş yapın</a>
    </p>
</div>

@push('scripts')
<script>
(function() {
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const backBtn = document.getElementById('back-btn');
    const nextBtn = document.getElementById('next-to-step2');
    const formRole = document.getElementById('form-role');
    const progressBar = document.getElementById('progress-bar');
    const roleInputs = document.querySelectorAll('input[name="role"]');

    function syncRole() {
        const r = document.querySelector('input[name="role"]:checked');
        if (r) formRole.value = r.value;
    }

    nextBtn.addEventListener('click', function() {
        syncRole();
        step1.classList.add('hidden');
        step2.classList.remove('hidden');
        backBtn.classList.remove('hidden');
        progressBar.style.width = '100%';
    });

    backBtn.addEventListener('click', function() {
        step2.classList.add('hidden');
        step1.classList.remove('hidden');
        backBtn.classList.add('hidden');
        progressBar.style.width = '50%';
    });

    roleInputs.forEach(el => el.addEventListener('change', syncRole));

    // Build name from first_name + last_name before submit if backend expects "name"
    const form = document.getElementById('register-form');
    form.addEventListener('submit', function() {
        const first = document.getElementById('first_name').value.trim();
        const last = document.getElementById('last_name').value.trim();
        if (first || last) {
            let nameInput = form.querySelector('input[name="name"]');
            if (!nameInput) {
                nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.name = 'name';
                form.appendChild(nameInput);
            }
            nameInput.value = (first + ' ' + last).trim() || first || last;
        }
    });
})();
</script>
@endpush
@endsection
