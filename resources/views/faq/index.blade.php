@extends('layouts.app')

@section('title', 'Sıkça Sorulan Sorular - NakliyePark')
@section('meta_description', 'Nakliye ve NakliyePark platformu hakkında sık sorulan sorular ve cevapları.')

@php
    $breadcrumbItems = [
        ['name' => 'Anasayfa', 'url' => route('home')],
        ['name' => 'Sıkça Sorulan Sorular', 'url' => null],
    ];
@endphp
@include('partials.structured-data-breadcrumb')

@if($faqs->isNotEmpty())
@push('structured_data')
@php
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqs->map(fn($f) => [
            '@type' => 'Question',
            'name' => $f->question,
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f->answer)],
        ])->values()->all(),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
@endif

@push('scripts')
<script>
(function() {
    var searchInput = document.getElementById('sss-search');
    var items = document.querySelectorAll('.sss-item');
    var emptyMsg = document.getElementById('sss-empty-msg');
    var details = document.querySelectorAll('.sss-accordion details');

    if (searchInput && items.length) {
        searchInput.addEventListener('input', function() {
            var q = this.value.trim().toLowerCase();
            var visible = 0;
            items.forEach(function(el) {
                var text = (el.dataset.question + ' ' + (el.dataset.answer || '')).toLowerCase();
                var match = !q || text.indexOf(q) !== -1;
                el.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            if (emptyMsg) emptyMsg.style.display = visible ? 'none' : 'block';
        });
    }

    if (details.length) {
        details.forEach(function(d) {
            d.addEventListener('toggle', function() {
                if (d.open) {
                    details.forEach(function(other) {
                        if (other !== d) other.open = false;
                    });
                }
            });
        });
    }
})();
</script>
@endpush

@section('content')
<div class="min-h-screen bg-[#f8f9fa] dark:bg-zinc-950" data-sss-page="index">
    <div class="page-container py-12 sm:py-16 lg:py-20 max-w-3xl">
        {{-- Header --}}
        <header class="mb-10 sm:mb-12">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-sm font-medium mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                SSS
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-zinc-900 dark:text-white tracking-tight">
                Sıkça sorulan sorular
            </h1>
            <p class="mt-3 text-lg text-zinc-600 dark:text-zinc-400 max-w-2xl">
                Nakliye ve platform hakkında merak ettikleriniz.
            </p>

            @if($faqs->isNotEmpty())
                <div class="mt-6">
                    <label for="sss-search" class="sr-only">Soru ara</label>
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-zinc-400 dark:text-zinc-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="search" id="sss-search" placeholder="Sorular arasında ara..." autocomplete="off" class="input-touch w-full pl-12 pr-4 rounded-xl [appearance:none] [&::-webkit-search-cancel-button]:hidden [&::-webkit-search-decoration]:hidden">
                    </div>
                </div>
            @endif
        </header>

        {{-- FAQ Accordion --}}
        @if($faqs->isNotEmpty())
            <div class="sss-accordion space-y-3">
                @foreach($faqs as $faq)
                    <div class="sss-item rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/60 dark:border-zinc-800 shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md hover:border-zinc-300/80 dark:hover:border-zinc-700" data-question="{{ e($faq->question) }}" data-answer="{{ e(Str::limit(strip_tags($faq->answer), 500)) }}">
                        <details class="group">
                            <summary class="flex items-center justify-between gap-4 cursor-pointer list-none py-5 px-6 text-left font-semibold text-zinc-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                <span class="pr-4">{{ $faq->question }}</span>
                                <span class="shrink-0 w-10 h-10 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 group-open:bg-emerald-100 dark:group-open:bg-emerald-900/40 group-open:text-emerald-600 dark:group-open:text-emerald-400 transition-colors">
                                    <svg class="w-5 h-5 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </span>
                            </summary>
                            <div class="px-6 pb-5 pt-0">
                                <div class="pl-0 border-l-2 border-emerald-200 dark:border-emerald-800 pl-5 text-zinc-600 dark:text-zinc-400 leading-relaxed prose prose-sm dark:prose-invert max-w-none">
                                    {!! nl2br(e($faq->answer)) !!}
                                </div>
                            </div>
                        </details>
                    </div>
                @endforeach
            </div>

            <p id="sss-empty-msg" class="hidden py-12 text-center text-zinc-500 dark:text-zinc-400">Arama kriterlerinize uygun soru bulunamadı.</p>
        @else
            <div class="rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/60 dark:border-zinc-800 p-16 sm:p-24 text-center">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-zinc-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">Henüz SSS eklenmemiş</h2>
                <p class="mt-2 text-zinc-500 dark:text-zinc-400">Yakında sorular ve cevaplarla burada olacağız.</p>
            </div>
        @endif

        {{-- CTA --}}
        <div class="mt-14 sm:mt-16 p-6 sm:p-8 rounded-2xl bg-gradient-to-br from-emerald-500/10 to-teal-500/10 dark:from-emerald-900/20 dark:to-teal-900/20 border border-emerald-200/60 dark:border-emerald-800/40 text-center">
            <p class="text-zinc-700 dark:text-zinc-300 font-medium">Aradığınız cevabı bulamadınız mı?</p>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">İletişim sayfamızdan bize ulaşabilirsiniz.</p>
            <a href="#iletisim" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-xl transition-colors">
                İletişime geç
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</div>
@endsection
