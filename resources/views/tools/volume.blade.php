@extends('layouts.app')

@section('title', $metaTitle ?? 'Hacim Hesaplama - NakliyePark')
@section('meta_description', $metaDescription ?? 'Odaya göre eşya seçerek taşınacak hacmi hesaplayın. Nakliye ihalesi için toplam m³ ve araç ihtiyacını görün.')

@section('content')
<div class="page-container py-6 sm:py-8">
    <header class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Hacim Hesaplama</h1>
        <p class="mt-1.5 text-sm text-zinc-500 dark:text-zinc-400">Salon, yatak odası, mutfak ve diğer odalardaki eşyaları seçin; toplam hacim (m³) ve tahmini araç ihtiyacı otomatik hesaplansın.</p>
        <p class="mt-3 text-zinc-600 dark:text-zinc-400 text-base max-w-3xl">Nakliye hacim hesaplama aracı ile evinizdeki eşyaları oda oda işaretleyin. Her eşya için min–max m³ değerleri kullanılır; toplam hacme göre panelvan, kamyonet veya kamyon önerilir. Nakliye ihalesi açarken bu değeri kullanabilirsiniz.</p>
    </header>

    @include('tools.partials.volume-calculator')

    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-6">Bu değeri nakliye ihalesi oluştururken kullanabilirsiniz.</p>

    {{-- Embed kodu --}}
    <section class="mt-8 pt-8 border-t border-zinc-200 dark:border-zinc-800" aria-labelledby="embed-baslik">
        <h2 id="embed-baslik" class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Bu aracı sitenize ekleyin</h2>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-3">Aşağıdaki iframe kodunu kendi sitenize yapıştırarak hacim hesaplama aracını gösterebilirsiniz.</p>
        <div class="rounded-xl bg-zinc-900 dark:bg-zinc-950 p-4 overflow-x-auto">
            <code class="text-sm text-emerald-300 font-mono whitespace-pre break-all">&lt;iframe src="{{ $embedUrl }}" width="100%" height="600" frameborder="0" scrolling="no" title="Hacim Hesaplama - NakliyePark"&gt;&lt;/iframe&gt;</code>
        </div>
        <p class="text-xs text-zinc-500 dark:text-zinc-500 mt-2">İsterseniz <code class="px-1.5 py-0.5 rounded bg-zinc-200 dark:bg-zinc-700 text-xs">width</code> ve <code class="px-1.5 py-0.5 rounded bg-zinc-200 dark:bg-zinc-700 text-xs">height</code> değerlerini ihtiyacınıza göre değiştirin.</p>
    </section>

    <section class="mt-8 pt-6 border-t border-zinc-200 dark:border-zinc-800" aria-labelledby="nasil-calisir-vol">
        <h2 id="nasil-calisir-vol" class="text-lg font-semibold text-zinc-900 dark:text-white mb-3">Hacim hesaplama nasıl çalışır?</h2>
        <div class="prose prose-sm prose-zinc dark:prose-invert max-w-none text-zinc-600 dark:text-zinc-400">
            @if(!empty($toolContent))
                {!! $toolContent !!}
            @else
                <p>Hacim hesaplama aracı, odalara göre (salon, yatak odası, mutfak, banyo, diğer eşya) listelenen eşyalardan seçim yapmanızı sağlar. Her eşya için tahmini minimum ve maksimum metreküp (m³) değerleri vardır; seçimlerinize göre toplam hacim ve uygun araç boyutu otomatik hesaplanır.</p>
                <p><strong>Kullanım:</strong> Oda sekmelerinden birini seçin, taşınacak eşyaların üzerine tıklayarak ekleyin. Aynı eşyayı birden fazla kez ekleyebilirsiniz. &ldquo;Hesabı Sıfırla&rdquo; ile tüm seçimler temizlenir. Hacim aralığını nakliye ihalesi oluştururken kullanabilirsiniz.</p>
            @endif
        </div>
    </section>
</div>
@endsection
