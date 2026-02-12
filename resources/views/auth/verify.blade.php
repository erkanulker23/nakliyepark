@extends('layouts.guest')

@section('title', 'E-posta doğrulama - NakliyePark')
@section('meta_description', 'E-posta adresinizi doğrulayın.')

@section('content')
<div class="w-full max-w-sm">
    <div class="card p-6 sm:p-8">
        <h1 class="text-xl font-semibold text-zinc-900 dark:text-white mb-1">E-posta doğrulama</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">Kayıt olurken kullandığınız e-posta adresine bir doğrulama linki gönderdik. Lütfen e-postanızı kontrol edip linke tıklayın.</p>
        @if(session('success'))
            <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3 text-sm border border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-200">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div class="mb-4 rounded-xl bg-blue-50 text-blue-800 px-4 py-3 text-sm border border-blue-200 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-200">{{ session('info') }}</div>
        @endif
        @if(session('warning'))
            <div class="mb-4 rounded-xl bg-amber-50 text-amber-800 px-4 py-3 text-sm border border-amber-200 dark:bg-amber-900/20 dark:border-amber-800 dark:text-amber-200">{{ session('warning') }}</div>
        @endif
        <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
            @csrf
            <button type="submit" class="btn-primary w-full">Doğrulama linkini tekrar gönder</button>
        </form>
        <p class="mt-4 text-center text-sm text-zinc-500">
            <a href="{{ route('logout') }}" class="link-muted" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Çıkış yap</a>
        </p>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </div>
</div>
@endsection
