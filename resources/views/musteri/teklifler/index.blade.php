@extends('layouts.musteri')

@section('title', 'Gelen Teklifler')
@section('page_heading', 'Gelen Teklifler')
@section('page_subtitle', 'İhalelerinize gelen teklifler')

@section('content')
<div class="max-w-4xl">
    @if($teklifler->isEmpty())
        <div class="admin-card p-8 text-center">
            <p class="text-slate-500">Henüz gelen teklif yok.</p>
            <a href="{{ route('ihale.create') }}" class="inline-block mt-4 admin-btn-primary px-4 py-2 rounded-lg">Yeni İhale Oluştur</a>
        </div>
    @else
        <ul class="space-y-4">
            @foreach($teklifler as $t)
                <li class="admin-card p-4 sm:p-5 flex flex-wrap items-center justify-between gap-4">
                    <div class="min-w-0">
                        <a href="{{ route('musteri.ihaleler.show', $t->ihale) }}" class="font-medium text-sky-600 hover:text-sky-700 dark:text-sky-400">
                            {{ $t->ihale->from_city ?? '-' }} → {{ $t->ihale->to_city ?? '-' }}
                        </a>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $t->company->name ?? 'Firma' }} · {{ number_format($t->amount, 0, ',', '.') }} ₺</p>
                        @if($t->message)
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 line-clamp-2">{{ $t->message }}</p>
                        @endif
                        <p class="text-xs text-slate-400 mt-1">{{ $t->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        @if($t->status === 'accepted')
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">Kabul edildi</span>
                        @elseif($t->status === 'rejected')
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">Reddedildi</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">Beklemede</span>
                        @endif
                        <a href="{{ route('musteri.ihaleler.show', $t->ihale) }}" class="admin-btn-primary text-sm py-2 px-4 rounded-lg">İhaleye git</a>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="mt-6">{{ $teklifler->links() }}</div>
    @endif
</div>
@endsection
