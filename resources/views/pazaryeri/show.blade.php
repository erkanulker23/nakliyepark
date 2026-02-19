@extends('layouts.app')

@php
    $listingSlug = \Illuminate\Support\Str::slug($listing->title);
    $pazaryeriGallery = $listing->gallery_paths ?? [];
    $pazaryeriOgImage = count($pazaryeriGallery) > 0 ? asset('storage/'.$pazaryeriGallery[0]) : null;
@endphp
@section('title', $listing->title . ' - Pazaryeri')
@section('meta_description', Str::limit(strip_tags($listing->description ?? $listing->title), 160) ?: ($listing->title . ' - NakliyePark Pazaryeri ilanı.'))
@section('canonical_url', route('pazaryeri.show', [$listing, $listingSlug]))
@section('og_image', $pazaryeriOgImage)
@section('og_type', 'product')

@php
    $breadcrumbItems = [
        ['name' => 'Anasayfa', 'url' => route('home')],
        ['name' => 'Pazaryeri', 'url' => route('pazaryeri.index')],
        ['name' => $listing->title, 'url' => null],
    ];
@endphp
@include('partials.structured-data-breadcrumb')

@php
    $productSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $listing->title,
        'description' => Str::limit(strip_tags($listing->description ?? $listing->title), 300),
        'url' => route('pazaryeri.show', [$listing, $listingSlug]),
    ];
    if ($pazaryeriOgImage) {
        $productSchema['image'] = $pazaryeriOgImage;
    }
    if ($listing->price !== null) {
        $productSchema['offers'] = [
            '@type' => 'Offer',
            'price' => (float) $listing->price,
            'priceCurrency' => 'TRY',
            'availability' => 'https://schema.org/InStock',
        ];
        if ($listing->listing_type === 'rent') {
            $productSchema['offers']['priceSpecification'] = [
                '@type' => 'UnitPriceSpecification',
                'price' => (float) $listing->price,
                'priceCurrency' => 'TRY',
                'unitText' => 'Gün',
            ];
        }
    }
    if ($listing->city) {
        $productSchema['areaServed'] = ['@type' => 'Place', 'name' => $listing->city];
    }
@endphp
@push('structured_data')
<script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
<div class="min-h-screen bg-[#fafafa] dark:bg-zinc-900/50">
    <div class="page-container py-8 sm:py-12">
        {{-- Breadcrumb --}}
        <nav class="text-sm text-zinc-500 dark:text-zinc-400 mb-6" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                <li><a href="{{ route('home') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Anasayfa</a></li>
                <li><span class="text-zinc-400">/</span></li>
                <li><a href="{{ route('pazaryeri.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Pazaryeri</a></li>
                <li><span class="text-zinc-400">/</span></li>
                <li class="text-zinc-700 dark:text-zinc-300 truncate max-w-[200px] sm:max-w-md" aria-current="page">{{ $listing->title }}</li>
            </ol>
        </nav>

        <div class="max-w-4xl">
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
                @php
                    $galleryPaths = $listing->gallery_paths;
                    $hasGallery = count($galleryPaths) > 0;
                @endphp
                {{-- Resim galerisi --}}
                <div class="relative bg-zinc-100 dark:bg-zinc-800">
                    @if($hasGallery)
                        <div class="relative aspect-[2/1] sm:aspect-[21/9] max-h-[400px] overflow-hidden">
                            @foreach($galleryPaths as $idx => $path)
                                <img src="{{ asset('storage/'.$path) }}" alt="{{ $listing->title }} - {{ $idx + 1 }}"
                                    class="gallery-main w-full h-full object-cover {{ $idx === 0 ? '' : 'hidden' }}"
                                    data-gallery-index="{{ $idx }}">
                            @endforeach
                            <span class="absolute top-4 left-4 inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-medium {{ $listing->listing_type === 'rent' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' }}">
                                {{ $listingTypes[$listing->listing_type] ?? $listing->listing_type }}
                            </span>
                            @if(count($galleryPaths) > 1)
                                <span class="absolute top-4 right-4 inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-medium bg-black/50 text-white">
                                    {{ count($galleryPaths) }} fotoğraf
                                </span>
                            @endif
                        </div>
                        @if(count($galleryPaths) > 1)
                            <div class="flex gap-2 p-3 overflow-x-auto border-t border-zinc-200 dark:border-zinc-700 scrollbar-hide">
                                @foreach($galleryPaths as $idx => $path)
                                    <button type="button" class="gallery-thumb shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 transition-all {{ $idx === 0 ? 'border-emerald-500 ring-2 ring-emerald-500/30' : 'border-transparent hover:border-zinc-300 dark:hover:border-zinc-600' }}"
                                        data-gallery-index="{{ $idx }}" aria-label="Fotoğraf {{ $idx + 1 }}">
                                        <img src="{{ asset('storage/'.$path) }}" alt="" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="relative aspect-[2/1] sm:aspect-[21/9] max-h-[400px] bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-700 flex items-center justify-center">
                            <div class="text-zinc-400 dark:text-zinc-500 text-center p-8">
                                <svg class="w-24 h-24 mx-auto mb-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                <p class="font-medium">{{ $vehicleTypes[$listing->vehicle_type] ?? $listing->vehicle_type }}</p>
                            </div>
                            <span class="absolute top-4 left-4 inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-medium {{ $listing->listing_type === 'rent' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' }}">
                                {{ $listingTypes[$listing->listing_type] ?? $listing->listing_type }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">{{ $listing->title }}</h1>
                            <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                @if($listing->vehicle_type)
                                    <span>{{ $vehicleTypes[$listing->vehicle_type] ?? $listing->vehicle_type }}</span>
                                @endif
                                @if($listing->year)
                                    <span>{{ $listing->year }} model</span>
                                @endif
                                @if($listing->city)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        {{ $listing->city }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if($listing->price)
                            <div class="shrink-0">
                                <p class="text-2xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ number_format($listing->price, 0, ',', '.') }} ₺
                                </p>
                                @if($listing->listing_type === 'rent')
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">günlük kiralık</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if($listing->description)
                        <div class="mt-6 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Açıklama</h2>
                            <p class="text-zinc-600 dark:text-zinc-300 leading-relaxed whitespace-pre-line">{{ $listing->description }}</p>
                        </div>
                    @endif

                    {{-- Firma kartı --}}
                    @if($listing->company)
                        <div class="mt-8 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">İlan sahibi</h2>
                            <div class="flex flex-wrap items-center gap-4 p-4 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700">
                                @if($listing->company->logo && $listing->company->logo_approved_at)
                                    <div class="w-20 h-20 rounded-2xl shrink-0 overflow-hidden flex items-center justify-center bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-600 shadow-sm">
                                        <img src="{{ asset('storage/'.$listing->company->logo) }}" alt="{{ $listing->company->name }}" class="w-full h-full object-contain p-1">
                                    </div>
                                @else
                                    <div class="w-20 h-20 rounded-2xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-2xl font-bold text-emerald-700 dark:text-emerald-300 shadow-sm">
                                        {{ mb_substr($listing->company->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    @if($show_firmalar_page ?? true)
                                    <a href="{{ route('firmalar.show', $listing->company) }}" class="font-semibold text-zinc-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400">{{ $listing->company->name }}</a>
                                    @else
                                    <span class="font-semibold text-zinc-900 dark:text-white">{{ $listing->company->name }}</span>
                                    @endif
                                    @if($listing->company->city)
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $listing->company->city }}{{ $listing->company->district ? ', ' . $listing->company->district : '' }}</p>
                                    @endif
                                </div>
                                @if($show_firmalar_page ?? true)
                                <a href="{{ route('firmalar.show', $listing->company) }}" class="btn-primary text-sm py-2.5 px-5 rounded-xl shrink-0">
                                    Firma sayfası →
                                </a>
                                @endif
                            </div>
                            @if($listing->company->phone || $listing->company->whatsapp)
                                <div class="flex flex-wrap gap-3 mt-3">
                                    @if($listing->company->phone)
                                        <a href="tel:{{ preg_replace('/\D/', '', $listing->company->phone) }}" class="inline-flex items-center gap-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-emerald-600 dark:hover:text-emerald-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            {{ $listing->company->phone }}
                                        </a>
                                    @endif
                                    @if($listing->company->whatsapp)
                                        @php $wa = ltrim(preg_replace('/\D/', '', $listing->company->whatsapp), '0'); $wa = (str_starts_with($wa, '90') ? $wa : '90' . $wa); @endphp
                                        <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:underline">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            WhatsApp
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('pazaryeri.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Tüm ilanlara dön
                </a>
            </div>
        </div>
    </div>
</div>

@if($hasGallery && count($galleryPaths) > 1)
@push('scripts')
<script>
(function() {
    document.querySelectorAll('.gallery-thumb').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var idx = this.getAttribute('data-gallery-index');
            document.querySelectorAll('.gallery-main').forEach(function(img) {
                img.classList.toggle('hidden', img.getAttribute('data-gallery-index') !== idx);
            });
            document.querySelectorAll('.gallery-thumb').forEach(function(b) {
                b.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-500/30');
                b.classList.add('border-transparent');
                if (b.getAttribute('data-gallery-index') === idx) {
                    b.classList.remove('border-transparent');
                    b.classList.add('border-emerald-500', 'ring-2', 'ring-emerald-500/30');
                }
            });
        });
    });
})();
</script>
@endpush
@endif
@endsection
