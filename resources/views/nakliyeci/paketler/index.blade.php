@extends('layouts.nakliyeci')

@section('title', 'Paketler')
@section('page_heading', 'Paketler')
@section('page_subtitle', 'Teklif limiti ve özellikler')

@section('content')
<div class="max-w-4xl">
    <p class="text-sm text-slate-500 mb-6">Daha fazla teklif verebilmek ve öne çıkmak için bir paket seçin.</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($paketler as $paket)
            <div class="admin-card p-6 flex flex-col">
                <h3 class="font-semibold text-lg text-slate-800 dark:text-slate-200">{{ $paket['name'] }}</h3>
                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">{{ number_format($paket['price'], 0, ',', '.') }} ₺<span class="text-sm font-normal text-slate-500">/ay</span></p>
                <p class="text-sm text-slate-500 mt-2">{{ $paket['description'] }}</p>
                <p class="text-xs text-slate-400 mt-1">Aylık {{ $paket['teklif_limit'] }} teklif hakkı</p>
                <div class="mt-auto pt-6">
                    <button type="button" class="admin-btn-primary w-full" disabled>Satın al (yakında)</button>
                </div>
            </div>
        @endforeach
    </div>
    <p class="mt-6 text-sm text-slate-500 text-center">Ödeme entegrasyonu eklendiğinde paket satın alınabilecektir.</p>
</div>
@endsection
