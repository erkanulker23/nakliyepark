@extends('layouts.app')

@section('title', $metaTitle ?? 'Tahmini Fiyat Hesaplama - NakliyePark')
@section('meta_description', $metaDescription ?? 'Km, eşya durumu ve kat bilgisine göre nakliye tahmini fiyatı hesaplayın. İhaleye benzer tüm bilgileri girin, anlık tahmin alın.')

@section('content')
<div class="page-container py-6 sm:py-8">
    <header class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Tahmini Fiyat Hesaplama</h1>
        <p class="mt-1.5 text-sm text-zinc-500 dark:text-zinc-400">İhaleye benzer tüm bilgileri girin: mesafe (km), eşya durumu (oda tipi), kat bilgisi. Tahmini nakliye fiyatı otomatik hesaplansın.</p>
        <p class="mt-3 text-zinc-600 dark:text-zinc-400 text-base max-w-3xl">Bu araç size yaklaşık fiyat fikri vermek içindir. Kesin fiyat için ihale açıp firmalardan teklif almanız önerilir. Şehir içi (0 km) için çağrı merkezimizden bilgi alabilirsiniz.</p>
    </header>

    <div class="max-w-3xl mx-auto">
        @include('tools.partials.price-estimator-widget', ['config' => $config, 'showEmbedLink' => true])
    </div>

    {{-- Embed kodu - iframe ile başka sitelere eklenebilir --}}
    <section class="mt-8 pt-8 border-t border-zinc-200 dark:border-zinc-800 max-w-3xl mx-auto" aria-labelledby="embed-baslik">
        <h2 id="embed-baslik" class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Bu aracı sitenize ekleyin</h2>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-3">Aşağıdaki iframe kodunu kendi sitenize yapıştırarak tahmini fiyat hesaplama aracını gösterebilirsiniz.</p>
        <div class="rounded-xl bg-zinc-900 dark:bg-zinc-950 p-4 overflow-x-auto">
            <code class="text-sm text-emerald-300 font-mono whitespace-pre break-all">&lt;iframe src="{{ $embedUrl }}" width="100%" height="680" frameborder="0" scrolling="no" title="Tahmini Fiyat Hesaplama - NakliyePark"&gt;&lt;/iframe&gt;</code>
        </div>
        <p class="text-xs text-zinc-500 dark:text-zinc-500 mt-2">İsterseniz <code class="px-1.5 py-0.5 rounded bg-zinc-200 dark:bg-zinc-700 text-xs">width</code> ve <code class="px-1.5 py-0.5 rounded bg-zinc-200 dark:bg-zinc-700 text-xs">height</code> değerlerini ihtiyacınıza göre değiştirin.</p>
    </section>

    <section class="mt-8 pt-6 border-t border-zinc-200 dark:border-zinc-800 max-w-3xl mx-auto" aria-labelledby="nasil-calisir-price">
        <h2 id="nasil-calisir-price" class="text-lg font-semibold text-zinc-900 dark:text-white mb-3">Fiyat tahmini nasıl hesaplanır?</h2>
        <div class="prose prose-sm prose-zinc dark:prose-invert max-w-none text-zinc-600 dark:text-zinc-400">
            @if(!empty($toolContent))
                {!! $toolContent !!}
            @else
                <p>Tahmini fiyat, mesafe (km), eşya durumu (oda tipi) ve kat bilgisine göre hesaplanır:</p>
                <ul class="list-disc pl-6 mt-2 space-y-1">
                    <li><strong>Mesafe kademeleri:</strong> Km arttıkça birim fiyat (₺/km) düşer; uzun mesafede indirim uygulanır.</li>
                    <li><strong>Eşya durumu:</strong> 1+1’den 5+1’e kadar oda tipi fiyatı çarpan olarak etkiler.</li>
                    <li><strong>Kat oranı:</strong> Asansör yoksa merdiven taşıma ek ücret gerektirir.</li>
                </ul>
                <p class="mt-3">Şehir içi nakliye (0 km) için lütfen çağrı merkezimizden fiyat alınız. Bu hesaplama sadece yaklaşık bir fikir verir.</p>
            @endif
        </div>
    </section>
</div>
@endsection
