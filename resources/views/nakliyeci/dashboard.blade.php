@extends('layouts.nakliyeci')

@section('title', 'Dashboard')
@section('page_heading', 'Dashboard')
@section('page_subtitle', $company->name)

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="admin-card p-6">
        <p class="text-sm font-medium text-slate-500">Firma durumu</p>
        <p class="text-lg font-bold mt-1">
            @if($company->isApproved())
                <span class="text-emerald-600">Onaylı</span>
            @else
                <span class="text-amber-600">Onay bekliyor</span>
            @endif
        </p>
    </div>
    <div class="admin-card p-6">
        <p class="text-sm font-medium text-slate-500">Toplam teklif</p>
        <p class="text-2xl font-bold text-slate-800 dark:text-slate-200 mt-1">{{ $company->teklifler()->count() }}</p>
    </div>
    <div class="admin-card p-6">
        <p class="text-sm font-medium text-slate-500">Kabul edilen</p>
        <p class="text-2xl font-bold text-slate-800 dark:text-slate-200 mt-1">{{ $company->acceptedTeklifler()->count() }}</p>
    </div>
    <div class="admin-card p-6">
        <p class="text-sm font-medium text-slate-500">Toplam kazanç</p>
        <p class="text-2xl font-bold text-slate-800 dark:text-slate-200 mt-1">{{ number_format($company->total_earnings, 0, ',', '.') }} ₺</p>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="admin-card p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-slate-800 dark:text-slate-200">Son teklifler</h2>
            <a href="{{ route('nakliyeci.teklifler.index') }}" class="text-sm text-emerald-600 hover:underline font-medium">Tümü →</a>
        </div>
        @forelse($teklifler->take(5) as $t)
            <article class="flex justify-between items-center py-3 border-b border-slate-200 dark:border-slate-600 last:border-0">
                <div>
                    <p class="font-medium text-slate-800 dark:text-slate-200">{{ $t->ihale->from_city }} → {{ $t->ihale->to_city }}</p>
                    <p class="text-sm text-slate-500">{{ number_format($t->amount, 0, ',', '.') }} ₺</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full
                    @if($t->status === 'accepted') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50
                    @elseif($t->status === 'rejected') bg-red-100 text-red-700 dark:bg-red-900/50
                    @else bg-sky-100 text-sky-700 dark:bg-sky-900/50
                    @endif">
                    {{ $t->status === 'accepted' ? 'Onaylandı' : ($t->status === 'rejected' ? 'Reddedildi' : 'Beklemede') }}
                </span>
            </article>
        @empty
            <p class="text-slate-500 text-sm py-2">Henüz teklif yok.</p>
        @endforelse
    </div>
    <div class="admin-card p-6">
        <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-4">Firma bilgileri</h2>
        <p class="text-sm text-slate-500 mb-4">Firma adı, iletişim ve açıklama gibi bilgileri buradan güncelleyebilirsiniz.</p>
        <a href="{{ route('nakliyeci.company.edit') }}" class="admin-btn-primary inline-flex">Firma bilgilerini düzenle</a>
    </div>
</div>

{{-- Açık ihaleler - hızlı teklif --}}
<div class="admin-card p-6">
    <h2 class="font-semibold text-slate-800 dark:text-slate-200 mb-3">Açık ihaleler (tek tıkla teklif)</h2>
    @if($company->isApproved())
        @forelse($yayindakiIhaleler as $ihale)
            @php $benimTeklif = $ihale->teklifler()->where('company_id', $company->id)->first(); @endphp
            <article class="flex flex-wrap items-center gap-4 py-4 border-b border-slate-200 dark:border-slate-600 last:border-0">
                <div class="flex-1 min-w-[200px]">
                    <p class="font-medium text-slate-800 dark:text-slate-200">{{ $ihale->from_city }} → {{ $ihale->to_city }}</p>
                    <p class="text-sm text-slate-500">{{ $ihale->volume_m3 }} m³ · {{ $ihale->move_date?->format('d.m.Y') ?? '-' }}</p>
                </div>
                @if($benimTeklif)
                    <p class="text-sm text-slate-500">Teklifiniz: {{ number_format($benimTeklif->amount, 0, ',', '.') }} ₺ ({{ $benimTeklif->status }})</p>
                @else
                    <form method="POST" action="{{ route('nakliyeci.teklif.store') }}" class="flex gap-2 flex-wrap items-center">
                        @csrf
                        <input type="hidden" name="ihale_id" value="{{ $ihale->id }}">
                        <input type="number" name="amount" min="0" step="100" placeholder="Tutar (₺)" required
                               class="admin-input w-32">
                        <button type="submit" class="admin-btn-primary">Teklif Ver</button>
                    </form>
                @endif
            </article>
        @empty
            <p class="text-slate-500 text-sm py-2">Şu an açık ihale yok.</p>
        @endforelse
    @else
        <p class="text-slate-500 text-sm">Firmanız onaylandıktan sonra teklif verebilirsiniz.</p>
    @endif
</div>
@endsection
