@extends('layouts.nakliyeci')

@section('title', 'Nakliyat Defteri')
@section('page_heading', 'Nakliyat Defteri (Yük Borsası)')
@section('page_subtitle', 'Diğer firmaların yük ilanları')

@section('content')
<div class="max-w-4xl">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <p class="text-sm text-slate-500">Firmaların paylaştığı yük ilanları. Boş dönüş veya yük ilanı ekleyebilirsiniz.</p>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('nakliyeci.ledger.create') }}" class="admin-btn-primary inline-flex">+ Deftere yaz</a>
        </div>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="divide-y divide-slate-200 dark:divide-slate-600">
            @forelse($ilanlar as $ilan)
                <article class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $ilan->from_city }} → {{ $ilan->to_city }}</p>
                        <p class="text-sm text-slate-500">{{ $ilan->company->name }}</p>
                        @if($ilan->description)
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed line-clamp-3">{{ $ilan->description }}</p>
                        @endif
                    </div>
                    <div class="mt-2 flex flex-wrap items-center gap-3">
                        <a href="{{ route('defter.show', $ilan) }}" class="text-sm text-sky-600 dark:text-sky-400 hover:underline">Defterde görüntüle ve yanıtla →</a>
                    </div>
                </article>
            @empty
                <div class="p-8 text-center text-slate-500">Henüz yük ilanı yok.</div>
            @endforelse
        </div>
    </div>

    @if($ilanlar->hasPages())
        <div class="mt-6">{{ $ilanlar->links() }}</div>
    @endif
</div>
@endsection
