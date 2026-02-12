@extends('layouts.app')

@section('title', 'Pazaryeri - Nakliye Araç İlanları')
@section('meta_description', 'Nakliye araç kiralama ve satılık ilanları. Kamyon, kamyonet, tır ve nakliye araçları. NakliyePark pazaryerinde ilan verin veya arayın.')

@section('content')
<div class="min-h-screen bg-[#fafafa] dark:bg-zinc-900/50">
    <section class="page-container py-10 sm:py-14">
        <div class="max-w-3xl mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white tracking-tight">Pazaryeri</h1>
            <p class="text-zinc-600 dark:text-zinc-400 mt-2 text-base sm:text-lg">Nakliyecilerin paylaştığı araç ilanları. Satılık veya kiralık kamyon, kamyonet, TIR ve nakliye araçları.</p>
        </div>

        {{-- Filtreler --}}
        <form method="get" action="{{ route('pazaryeri.index') }}" class="mb-8 p-4 sm:p-5 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="flex flex-wrap items-end gap-3">
                <div class="w-full sm:w-40">
                    <label for="listing_type" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">İlan tipi</label>
                    <select name="listing_type" id="listing_type" class="input-touch text-sm py-2.5 w-full">
                        <option value="">Tümü</option>
                        @foreach($listingTypes as $key => $label)
                            <option value="{{ $key }}" {{ request('listing_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-44">
                    <label for="vehicle_type" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Araç tipi</label>
                    <select name="vehicle_type" id="vehicle_type" class="input-touch text-sm py-2.5 w-full">
                        <option value="">Tümü</option>
                        @foreach($vehicleTypes as $key => $label)
                            <option value="{{ $key }}" {{ request('vehicle_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-40">
                    <label for="city" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Şehir</label>
                    <input type="text" name="city" id="city" value="{{ request('city') }}" placeholder="Şehir" class="input-touch text-sm py-2.5 w-full" list="city_list">
                    <datalist id="city_list">
                        @foreach($cities as $c)
                            <option value="{{ $c }}">
                        @endforeach
                    </datalist>
                </div>
                <button type="submit" class="btn-primary text-sm py-2.5 px-5 rounded-xl">Filtrele</button>
            </div>
        </form>

        @if($listings->isEmpty())
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-12 text-center">
                <p class="text-zinc-500 dark:text-zinc-400 mb-2">Henüz araç ilanı yok.</p>
                <p class="text-sm text-zinc-400">Nakliyeci hesabı ile giriş yapıp araç ilanı verebileceksiniz.</p>
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($listings as $item)
                    <article class="group rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm hover:shadow-md hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-all">
                        <a href="{{ route('pazaryeri.show', [$item, \Illuminate\Support\Str::slug($item->title)]) }}" class="block">
                            {{-- Görsel alanı (placeholder veya gerçek) --}}
                            <div class="relative aspect-[16/10] bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-700 flex items-center justify-center">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="text-zinc-400 dark:text-zinc-500">
                                        <svg class="w-16 h-16 mx-auto mb-2 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        <span class="text-sm font-medium">{{ $vehicleTypes[$item->vehicle_type] ?? $item->vehicle_type }}</span>
                                    </div>
                                @endif
                                <span class="absolute top-3 left-3 inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $item->listing_type === 'rent' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' }}">
                                    {{ $listingTypes[$item->listing_type] ?? $item->listing_type }}
                                </span>
                            </div>
                            <div class="p-5">
                                <h2 class="font-bold text-lg text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors line-clamp-2">{{ $item->title }}</h2>
                                @if($item->company)
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">{{ $item->company->name }}</p>
                                @endif
                                <div class="flex flex-wrap items-center gap-2 mt-3">
                                    @if($item->city)
                                        <span class="text-xs text-zinc-500 dark:text-zinc-400 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                            {{ $item->city }}
                                        </span>
                                    @endif
                                    @if($item->year)
                                        <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $item->year }} model</span>
                                    @endif
                                </div>
                                @if($item->price)
                                    <p class="mt-3 text-xl font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ number_format($item->price, 0, ',', '.') }} ₺
                                        @if($item->listing_type === 'rent')<span class="text-sm font-normal text-zinc-500">/ gün</span>@endif
                                    </p>
                                @endif
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            @if($listings->hasPages())
                <div class="mt-8">{{ $listings->links() }}</div>
            @endif
        @endif
    </section>
</div>
@endsection
