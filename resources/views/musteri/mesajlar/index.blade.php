@extends('layouts.musteri')

@section('title', 'Gelen Mesajlar')
@section('page_heading', 'Gelen Mesajlar')
@section('page_subtitle', 'Firmalardan gelen mesajlar')

@section('content')
<div class="max-w-4xl">
    @if($mesajlar->isEmpty())
        <div class="admin-card p-8 text-center">
            <p class="text-slate-500">Henüz gelen mesaj yok.</p>
            <p class="text-sm text-slate-400 mt-2">Kabul ettiğiniz teklifteki firmayla iletişim kurduğunuzda mesajlar burada listelenir.</p>
        </div>
    @else
        <ul class="space-y-4">
            @foreach($mesajlar as $m)
                <li class="admin-card p-4 sm:p-5">
                    <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                        <div>
                            <span class="font-medium text-slate-800 dark:text-slate-200">{{ $m->company->name ?? 'Firma' }}</span>
                            <span class="text-slate-400 mx-2">·</span>
                            <a href="{{ route('musteri.ihaleler.show', $m->ihale) }}" class="text-sm text-sky-600 hover:text-sky-700 dark:text-sky-400">
                                {{ $m->ihale->from_city }} → {{ $m->ihale->to_city }}
                            </a>
                        </div>
                        <span class="text-xs text-slate-400">{{ $m->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 whitespace-pre-line">{{ $m->message }}</p>
                    <a href="{{ route('musteri.ihaleler.show', $m->ihale) }}" class="inline-block mt-3 text-sm text-sky-600 hover:text-sky-700 dark:text-sky-400 font-medium">İhaleye git ve yanıtla →</a>
                </li>
            @endforeach
        </ul>
        <div class="mt-6">{{ $mesajlar->links() }}</div>
    @endif
</div>
@endsection
