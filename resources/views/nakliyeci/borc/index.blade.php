@extends('layouts.nakliyeci')

@section('title', 'Borç / Ödeme')
@section('page_heading', 'Borç ve ödeme')
@section('page_subtitle', 'NakliyePark komisyon borcu')

@section('content')
<div class="max-w-2xl">
    <div class="admin-card p-6 mb-6">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4">Komisyon borcu</h2>
        <p class="text-sm text-slate-500 mb-2">Kabul edilen işlerinizden NakliyePark komisyonu ({{ $company->commission_rate }}%). Ödenen: {{ number_format($company->paid_commission, 2, ',', '.') }} ₺</p>
        <p class="text-3xl font-bold text-slate-800 dark:text-slate-200">{{ number_format($komisyonBorcu, 2, ',', '.') }} ₺</p>
        @if($komisyonBorcu > 0)
            @if($paymentEnabled ?? false)
                <div class="mt-6 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800">
                    <p class="text-sm text-emerald-800 dark:text-emerald-200 mb-3">Borcunuzu kredi kartı ile güvenle ödeyebilirsiniz.</p>
                    <form method="POST" action="{{ route('nakliyeci.odeme.start-borc') }}" class="inline">
                        @csrf
                        <button type="submit" class="admin-btn-primary">Kredi kartı ile öde</button>
                    </form>
                </div>
            @else
                <div class="mt-6 p-4 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
                    <p class="text-sm text-amber-800 dark:text-amber-200">Ödeme alımı admin tarafından henüz açılmamıştır. Borcunuzu ödemek için destek ile iletişime geçebilirsiniz.</p>
                </div>
            @endif
        @else
            <p class="mt-4 text-sm text-emerald-600 dark:text-emerald-400">Şu an ödenecek borcunuz bulunmuyor.</p>
        @endif
    </div>
    <div class="admin-card p-6">
        <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-2">Özet</h3>
        <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
            <li>Toplam kazanç: <strong class="text-slate-800 dark:text-slate-200">{{ number_format($company->total_earnings, 0, ',', '.') }} ₺</strong></li>
            <li>Alınan iş sayısı: <strong>{{ $company->acceptedTeklifler()->count() }}</strong></li>
        </ul>
    </div>
</div>
@endsection
