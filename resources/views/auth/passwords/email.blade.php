@extends('layouts.guest')

@section('title', 'Şifremi unuttum - NakliyePark')
@section('meta_description', 'NakliyePark hesap şifrenizi sıfırlamak için e-posta adresinizi girin.')

@section('content')
<div class="w-full max-w-md">
    <div class="card p-6 sm:p-8">
        <h1 class="text-xl font-semibold text-zinc-900 dark:text-white mb-1">Şifremi unuttum</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">Kayıtlı e-posta adresinizi girin, size şifre sıfırlama linki gönderelim.</p>

        @if(session('status'))
            <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3 text-sm border border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-xl bg-red-50 text-red-800 px-4 py-3 text-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 rounded-xl bg-red-50 text-red-800 px-4 py-3 text-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800 dark:text-red-200">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">E-posta</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       inputmode="email" autocomplete="email"
                       class="input-touch @error('email') border-red-500 focus:ring-red-500/30 focus:border-red-500 @enderror"
                       placeholder="ornek@email.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn-primary w-full">Şifre sıfırlama linki gönder</button>
        </form>
    </div>
    <p class="mt-6 text-center text-sm text-zinc-500">
        <a href="{{ route('login') }}" class="link-muted">Giriş sayfasına dön</a>
    </p>
</div>
@endsection
