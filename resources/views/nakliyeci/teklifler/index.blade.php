@extends('layouts.nakliyeci')

@section('title', 'Tekliflerim')
@section('page_heading', 'Tekliflerim')
@section('page_subtitle', 'Verdiğiniz tüm teklifler (tüm ihalelere verdiğiniz teklifler)')

@section('content')
<div class="max-w-4xl">
    <div class="admin-card overflow-hidden">
        <table class="admin-table w-full">
            <thead>
                <tr>
                    <th>İhale</th>
                    <th>Tutar</th>
                    <th>Durum</th>
                    <th>Tarih</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teklifler as $t)
                    <tr>
                        <td>
                            <span class="font-medium text-slate-800 dark:text-slate-200">{{ $t->ihale->from_city }} → {{ $t->ihale->to_city }}</span>
                            <span class="block text-sm text-slate-500">{{ $t->ihale->volume_m3 }} m³ · {{ $t->ihale->move_date?->format('d.m.Y') ?? '-' }}</span>
                        </td>
                        <td class="font-medium">{{ number_format($t->amount, 0, ',', '.') }} ₺</td>
                        <td>
                            <span class="text-xs px-2 py-1 rounded-full
                                @if($t->status === 'accepted') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300
                                @elseif($t->status === 'rejected') bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300
                                @else bg-sky-100 text-sky-700 dark:bg-sky-900/50 dark:text-sky-300
                                @endif">
                                {{ $t->status === 'accepted' ? 'Onaylandı' : ($t->status === 'rejected' ? 'Reddedildi' : 'Beklemede') }}
                            </span>
                        </td>
                        <td class="text-sm text-slate-500">{{ $t->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-slate-500">Henüz teklif vermediniz.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($teklifler->hasPages())
        <div class="mt-4">{{ $teklifler->links() }}</div>
    @endif
</div>
@endsection
