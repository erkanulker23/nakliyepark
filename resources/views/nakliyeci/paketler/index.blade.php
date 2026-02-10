@extends('layouts.nakliyeci')

@section('title', 'Paketler')
@section('page_heading', 'Paketler')
@section('page_subtitle', 'Teklif limiti ve özellikler')

@section('content')
<div class="max-w-5xl">
    <p class="text-slate-600 dark:text-slate-400 mb-8">Daha fazla teklif verebilmek, ihalelerde öne çıkmak ve müşterilere ulaşmak için size uygun paketi seçin. Tüm paketlerde firma profili, galeri ve temel araçlar dahildir.</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($paketler as $paket)
            <div class="admin-card p-6 flex flex-col relative {{ isset($paket['popular']) && $paket['popular'] ? 'ring-2 ring-emerald-500 dark:ring-emerald-400' : '' }}">
                @if(isset($paket['popular']) && $paket['popular'])
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-full bg-emerald-500 text-white text-xs font-semibold">En popüler</span>
                @endif
                <h3 class="font-semibold text-lg text-slate-800 dark:text-slate-200">{{ $paket['name'] }}</h3>
                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">{{ number_format($paket['price'], 0, ',', '.') }} ₺<span class="text-sm font-normal text-slate-500">/ay</span></p>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-3 leading-relaxed">{{ $paket['description'] }}</p>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-2">Aylık {{ $paket['teklif_limit'] }} teklif hakkı</p>
                @if(!empty($paket['features']))
                    <ul class="mt-4 space-y-2 flex-1">
                        @foreach($paket['features'] as $feature)
                            <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-400">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                @endif
                <div class="mt-auto pt-6">
                    <button type="button" class="admin-btn-primary w-full" disabled>Satın al (yakında)</button>
                </div>
            </div>
        @endforeach
    </div>
    <p class="mt-8 text-sm text-slate-500 dark:text-slate-400 text-center">Ödeme entegrasyonu eklendiğinde paket satın alınabilecektir. Sorularınız için destek ekibimizle iletişime geçebilirsiniz.</p>
</div>
@endsection
