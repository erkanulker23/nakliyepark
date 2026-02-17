@extends('layouts.nakliyeci')

@section('title', 'Cari')
@section('page_heading', 'Cari hesap')
@section('page_subtitle', 'Alınan işler ve kazanç özeti')

@section('content')
<div class="max-w-4xl">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="admin-card p-6">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Toplam kazanç</p>
            <p class="text-2xl font-bold text-slate-800 dark:text-slate-200 mt-1">{{ number_format($toplamKazanc, 0, ',', '.') }} ₺</p>
            <p class="text-xs text-slate-500 mt-1">Kabul edilen işlerin toplam tutarı</p>
            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">Net (komisyon sonrası): {{ number_format($netKazanc, 0, ',', '.') }} ₺</p>
        </div>
        <div class="admin-card p-6">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Komisyon oranı</p>
            <p class="text-2xl font-bold text-slate-800 dark:text-slate-200 mt-1">% {{ number_format($company->commission_rate, 1, ',', '') }}</p>
            <p class="text-xs text-slate-500 mt-1">NakliyePark komisyon oranı</p>
        </div>
        <div class="admin-card p-6">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Toplam komisyon</p>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ number_format($toplamKomisyon, 0, ',', '.') }} ₺</p>
            <p class="text-xs text-slate-500 mt-1">Ödenecek toplam komisyon</p>
        </div>
        <div class="admin-card p-6">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Kalan borç</p>
            <p class="text-2xl font-bold {{ $kalanBorc > 0 ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }} mt-1">{{ number_format($kalanBorc, 2, ',', '.') }} ₺</p>
            <p class="text-xs text-slate-500 mt-1">Ödenen: {{ number_format($odenenKomisyon, 0, ',', '.') }} ₺</p>
            @if($kalanBorc > 0)
                <a href="{{ route('nakliyeci.borc.index') }}" class="inline-block mt-2 text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:underline">Borcu öde →</a>
            @endif
        </div>
    </div>
    <div class="admin-card overflow-hidden">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 p-4 border-b border-slate-200 dark:border-slate-600">Alınan işler</h2>
        <table class="admin-table w-full">
            <thead>
                <tr>
                    <th>İş (İhale)</th>
                    <th>Tarih</th>
                    <th class="text-right">İş tutarı</th>
                    <th class="text-right">Komisyon oranı</th>
                    <th class="text-right">Komisyon</th>
                    <th class="text-right">Net</th>
                </tr>
            </thead>
            <tbody>
                @forelse($isler as $teklif)
                    @php
                        $komisyonOrani = $company->commission_rate;
                        $komisyonTutar = round($teklif->amount * ($komisyonOrani / 100), 2);
                        $net = $teklif->amount - $komisyonTutar;
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('nakliyeci.ihaleler.show', $teklif->ihale) }}" class="font-medium text-slate-800 dark:text-slate-200 hover:text-emerald-600 dark:hover:text-emerald-400">{{ $teklif->ihale->from_location_text ?? '-' }} → {{ $teklif->ihale->to_location_text ?? '-' }}</a>
                        </td>
                        <td class="text-slate-600 dark:text-slate-400">{{ $teklif->created_at->format('d.m.Y') }}</td>
                        <td class="text-right font-medium">{{ number_format($teklif->amount, 0, ',', '.') }} ₺</td>
                        <td class="text-right text-slate-600 dark:text-slate-400">% {{ number_format($komisyonOrani, 1, ',', '') }}</td>
                        <td class="text-right text-amber-600 dark:text-amber-400">{{ number_format($komisyonTutar, 2, ',', '.') }} ₺</td>
                        <td class="text-right font-medium text-emerald-600 dark:text-emerald-400">{{ number_format($net, 2, ',', '.') }} ₺</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-slate-500">Henüz kabul edilmiş iş yok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($isler->hasPages())
        <div class="mt-4">{{ $isler->links() }}</div>
    @endif
</div>
@endsection
