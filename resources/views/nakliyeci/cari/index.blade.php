@extends('layouts.nakliyeci')

@section('title', 'Cari')
@section('page_heading', 'Cari hesap')
@section('page_subtitle', 'Alınan işler ve kazanç özeti')

@section('content')
<div class="max-w-4xl">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="admin-card p-6">
            <p class="text-sm font-medium text-slate-500">Toplam kazanç</p>
            <p class="text-2xl font-bold text-slate-800 dark:text-slate-200 mt-1">{{ number_format($toplamKazanc, 0, ',', '.') }} ₺</p>
            <p class="text-xs text-slate-500 mt-1">Kabul edilen tekliflerin toplamı</p>
        </div>
        <div class="admin-card p-6">
            <p class="text-sm font-medium text-slate-500">NakliyePark komisyonu</p>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ number_format($toplamKomisyon, 0, ',', '.') }} ₺</p>
            <p class="text-xs text-slate-500 mt-1">{{ $company->commission_rate }}% oran</p>
        </div>
        <div class="admin-card p-6">
            <p class="text-sm font-medium text-slate-500">Net kazanç</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($netKazanc, 0, ',', '.') }} ₺</p>
            <p class="text-xs text-slate-500 mt-1">Komisyon düşülmüş</p>
        </div>
    </div>
    <div class="admin-card overflow-hidden">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 p-4 border-b border-slate-200 dark:border-slate-600">Alınan işler</h2>
        <table class="admin-table w-full">
            <thead>
                <tr>
                    <th>İhale</th>
                    <th>Tarih</th>
                    <th>Tutar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($isler as $teklif)
                    <tr>
                        <td>
                            <span class="font-medium text-slate-800 dark:text-slate-200">{{ $teklif->ihale->from_city }} → {{ $teklif->ihale->to_city }}</span>
                        </td>
                        <td class="text-slate-600 dark:text-slate-400">{{ $teklif->created_at->format('d.m.Y') }}</td>
                        <td class="font-medium">{{ number_format($teklif->amount, 0, ',', '.') }} ₺</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-8 text-slate-500">Henüz kabul edilmiş iş yok.</td>
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
