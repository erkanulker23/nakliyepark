@extends('layouts.app')

@section('title', 'Nakliye Firmaları - NakliyePark')

@section('content')
<div class="min-h-screen bg-[#f8fafc] dark:bg-zinc-950">
    {{-- Hero: gradient mesh + typography --}}
    <section class="relative py-16 sm:py-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/8 via-transparent to-teal-500/5 dark:from-emerald-500/10 dark:to-teal-500/5"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_70%_40%_at_50%_-10%,rgba(16,185,129,0.12),transparent)] dark:bg-[radial-gradient(ellipse_70%_40%_at_50%_-10%,rgba(16,185,129,0.08),transparent)]"></div>
        <div class="absolute inset-0 opacity-[0.02] dark:opacity-[0.04]" style="background-image: linear-gradient(rgba(0,0,0,.15) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,.15) 1px, transparent 1px); background-size: 48px 48px;"></div>

        <div class="page-container relative">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 dark:bg-emerald-500/15 border border-emerald-500/20 text-emerald-700 dark:text-emerald-300 text-sm font-medium mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Onaylı firmalar
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-zinc-900 dark:text-white tracking-tight leading-[1.15]">
                    Nakliye firmaları
                </h1>
                <p class="text-zinc-600 dark:text-zinc-400 mt-4 text-lg sm:text-xl max-w-xl">
                    Onaylı taşıma firmalarına göz atın, güvenle iletişime geçin.
                </p>
            </div>

            {{-- Arama (görsel / ileride backend bağlanabilir) --}}
            <div class="mt-10 max-w-xl">
                <form action="{{ route('firmalar.index') }}" method="get" class="relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Firma veya şehir ara…" class="w-full h-14 pl-12 pr-5 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all shadow-sm">
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 h-10 px-4 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-500 transition-colors">Ara</button>
                </form>
            </div>
        </div>
    </section>

    <div class="page-container pb-20 sm:pb-28">
        @if($firmalar->count() > 0)
            {{-- Liste başlığı --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <p class="text-zinc-600 dark:text-zinc-400">
                    <span class="font-semibold text-zinc-900 dark:text-white">{{ $firmalar->total() }}</span> firma listeleniyor
                </p>
            </div>

            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6 sm:gap-8">
                @foreach($firmalar as $firma)
                    <a href="{{ route('firmalar.show', $firma) }}" class="group group/card block group-hover:no-underline">
                        <article class="h-full flex flex-col rounded-2xl sm:rounded-3xl border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900/80 shadow-sm hover:shadow-xl hover:shadow-zinc-200/40 dark:hover:shadow-none hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-all duration-300 overflow-hidden">
                            {{-- Kart üst: logo + badge --}}
                            <div class="p-6 sm:p-7 flex items-start justify-between gap-4">
                                <div class="flex items-center gap-4 min-w-0">
                                    @if($firma->logo)
                                        <img src="{{ asset('storage/'.$firma->logo) }}" alt="" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover ring-1 ring-zinc-200/80 dark:ring-zinc-700 shrink-0">
                                    @else
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-2xl font-bold text-white shrink-0 shadow-lg shadow-emerald-500/20">
                                            {{ mb_substr($firma->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <h2 class="font-bold text-lg sm:text-xl text-zinc-900 dark:text-white truncate group-hover/card:text-emerald-600 dark:group-hover/card:text-emerald-400 transition-colors">
                                            {{ $firma->name }}
                                        </h2>
                                        @if($firma->city)
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5 flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-zinc-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                                {{ $firma->city }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60 shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                    Onaylı
                                </span>
                            </div>

                            @if($firma->description)
                                <div class="px-6 sm:px-7 pb-4">
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2 leading-relaxed">{{ Str::limit($firma->description, 100) }}</p>
                                </div>
                            @endif

                            {{-- Kart alt: değerlendirme + CTA --}}
                            <div class="mt-auto px-6 sm:px-7 py-4 sm:py-5 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between gap-3">
                                @if($firma->reviews_count > 0)
                                    <span class="inline-flex items-center gap-1.5 text-sm text-zinc-500 dark:text-zinc-400">
                                        <span class="text-amber-500">★</span>
                                        {{ $firma->reviews_count }} değerlendirme
                                    </span>
                                @else
                                    <span class="text-sm text-zinc-400 dark:text-zinc-500">Henüz değerlendirme yok</span>
                                @endif
                                <span class="inline-flex items-center gap-2 text-sm font-medium text-emerald-600 dark:text-emerald-400 group-hover/card:gap-3 transition-all">
                                    Profili gör
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>

            @if($firmalar->hasPages())
                <div class="mt-14 flex justify-center">
                    <div class="flex items-center gap-1 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-1.5 shadow-sm">
                        {{ $firmalar->links() }}
                    </div>
                </div>
            @endif
        @else
            {{-- Boş durum --}}
            <div class="max-w-md mx-auto text-center py-16 sm:py-24">
                <div class="w-24 h-24 mx-auto rounded-3xl bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-700 flex items-center justify-center mb-8 shadow-inner">
                    <svg class="w-12 h-12 text-zinc-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white">Henüz onaylı firma yok</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-2">Yakında burada listelenecek.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mt-8 btn-primary rounded-xl">
                    Anasayfaya dön
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
