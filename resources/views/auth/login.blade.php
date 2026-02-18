@extends('layouts.guest')

@section('title', 'Giriş - NakliyePark')
@section('meta_description', 'NakliyePark hesabınıza giriş yapın. İhalelerinizi yönetin, teklif verin veya nakliye firması olarak kayıt olun.')

@section('content')
<div class="w-full max-w-md">
    <div class="card p-6 sm:p-8">
        @if(!isset($admin_login))
        <div class="flex rounded-xl bg-zinc-100 dark:bg-zinc-800 p-1 mb-6" role="tablist">
            <a href="{{ route('login') }}" class="flex-1 py-2.5 text-center text-sm font-medium rounded-lg transition-colors {{ !request('tab') || request('tab') === 'musteri' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">Müşteri girişi</a>
            <a href="{{ route('login', ['tab' => 'nakliyeci']) }}" class="flex-1 py-2.5 text-center text-sm font-medium rounded-lg transition-colors {{ request('tab') === 'nakliyeci' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">Nakliyeci girişi</a>
        </div>
        @endif
        <h1 class="text-xl font-semibold text-zinc-900 dark:text-white mb-1">@isset($admin_login) Yönetici / Admin girişi @else Giriş yap @endisset</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">@isset($admin_login) Yönetim paneline erişmek için giriş yapın. @else Hesabınızla devam edin. @endisset</p>

        @if($errors->any())
            <div class="mb-4 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 px-4 py-4 flex items-start gap-3" role="alert">
                <span class="shrink-0 w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <div class="min-w-0">
                    <p class="font-semibold text-red-800 dark:text-red-200">Giriş yapılamadı</p>
                    <ul class="mt-1 text-sm text-red-700 dark:text-red-300 space-y-0.5">
                        @foreach($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-xl bg-red-50 dark:bg-red-900/30 text-red-800 dark:text-red-200 px-4 py-3 text-sm border border-red-200 dark:border-red-800 flex items-start gap-3" role="alert">
                <span class="shrink-0 w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center"><svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif
        @if(session('status'))
            <div class="mb-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-200 px-4 py-3 text-sm border border-emerald-200 dark:border-emerald-800">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ isset($admin_login) ? route('admin.login.submit') : route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">E-posta</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       inputmode="email" autocomplete="email"
                       class="input-touch @error('email') border-red-500 focus:ring-red-500/30 focus:border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Şifre</label>
                <input id="password" type="password" name="password" required
                       class="input-touch @error('password') border-red-500 focus:ring-red-500/30 focus:border-red-500 @enderror">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1.5 text-sm">
                    <a href="{{ route('password.request') }}" class="link-muted">Şifremi unuttum</a>
                </p>
            </div>
            <label class="flex items-center gap-2 min-h-[44px] cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                <span class="text-sm text-zinc-600 dark:text-zinc-400">Beni hatırla</span>
            </label>
            <button type="submit" class="btn-primary w-full">Giriş yap</button>
        </form>
    </div>
    @if(!isset($admin_login))
        <p class="mt-6 text-center text-sm text-zinc-500">
            Hesabınız yok mu? <a href="{{ route('register') }}" class="link-muted">Kayıt olun</a>
        </p>
    @else
        <p class="mt-6 text-center text-sm text-zinc-500">
            <a href="{{ route('login') }}" class="link-muted">Müşteri / Nakliyeci girişi</a>
        </p>
    @endif
</div>
@endsection
