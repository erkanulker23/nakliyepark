@extends('layouts.nakliyeci')

@section('title', 'Açık ihaleler')
@section('page_heading', 'Açık ihaleler')
@section('page_subtitle', 'Teklif verebileceğiniz ihaleler')

@section('content')
<div class="max-w-4xl">
    <p class="text-sm text-slate-500 mb-6">İhale detayını görmek ve teklif vermek için ilgili satıra tıklayın.</p>
    <div class="admin-card overflow-hidden">
        <table class="admin-table w-full">
            <thead>
                <tr>
                    <th>Güzergah</th>
                    <th>Hacim</th>
                    <th>Taşıma tarihi</th>
                    <th>Teklif sayısı</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($ihaleler as $ihale)
                    @php $benimTeklif = $ihale->teklifler()->where('company_id', $company->id)->first(); @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td>
                            <a href="{{ route('nakliyeci.ihaleler.show', $ihale) }}" class="font-medium text-emerald-600 dark:text-emerald-400 hover:underline">
                                {{ $ihale->from_location_text }} → {{ $ihale->to_location_text }}
                            </a>
                        </td>
                        <td class="text-slate-600 dark:text-slate-400">{{ $ihale->volume_m3 }} m³</td>
                        <td class="text-slate-600 dark:text-slate-400">{{ $ihale->move_date?->format('d.m.Y') ?? '—' }}</td>
                        <td>{{ $ihale->teklifler_count }}</td>
                        <td class="text-right">
                            @if($benimTeklif)
                                <span class="text-xs px-2 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400">Teklif verdiniz</span>
                            @else
                                <a href="{{ route('nakliyeci.ihaleler.show', $ihale) }}" class="admin-btn-primary text-sm py-1.5 px-3">Detay & teklif</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate-500">Şu an açık ihale yok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ihaleler->hasPages())
        <div class="mt-4">{{ $ihaleler->links() }}</div>
    @endif
</div>
@endsection
