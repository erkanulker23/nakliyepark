@extends('layouts.guest')

@section('title', 'Yeni şifre oluştur - NakliyePark')
@section('meta_description', 'NakliyePark hesabınız için yeni şifre belirleyin.')

@section('content')
<div class="w-full max-w-sm">
    <div class="card p-6 sm:p-8">
        <h1 class="text-xl font-semibold text-zinc-900 dark:text-white mb-1">Yeni şifre oluştur</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">Hesabınız için yeni bir şifre girin.</p>

        @if($errors->any())
            <div class="mb-4 rounded-xl bg-red-50 text-red-800 px-4 py-3 text-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800 dark:text-red-200">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">E-posta</label>
                <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autofocus
                       inputmode="email" autocomplete="email"
                       class="input-touch @error('email') border-red-500 focus:ring-red-500/30 focus:border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Yeni şifre *</label>
                <input id="password" type="password" name="password" required
                       class="input-touch @error('password') border-red-500 focus:ring-red-500/30 focus:border-red-500 @enderror"
                       placeholder="En az 8 karakter" autocomplete="new-password">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Yeni şifre (tekrar) *</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="input-touch" placeholder="Şifreyi tekrar girin" autocomplete="new-password">
            </div>
            <button type="submit" class="btn-primary w-full">Şifremi güncelle</button>
        </form>
    </div>
    <p class="mt-6 text-center text-sm text-zinc-500">
        <a href="{{ route('login') }}" class="link-muted">Giriş sayfasına dön</a>
    </p>
</div>
@endsection
