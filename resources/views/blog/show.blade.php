@extends('layouts.app')

@php
    $metaTitle = $post->meta_title ?: ($post->title . ' - NakliyePark');
    $metaDesc = $post->meta_description ?: ($post->excerpt ?: Str::limit(strip_tags($post->content), 160));
    $canonicalUrl = route('blog.show', $post->slug);
    $imageUrl = $post->image
        ? (Str::startsWith($post->image, 'http') ? $post->image : asset('storage/'.$post->image))
        : asset('icons/icon-192.png');
@endphp

@section('title', $metaTitle)
@section('meta_description', $metaDesc)
@section('canonical_url', $canonicalUrl)
@section('og_image', $imageUrl)

@push('meta')
<meta property="og:type" content="article">
@if($post->published_at)
<meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
@endif
@endpush

@php
    $breadcrumbItems = [
        ['name' => 'Anasayfa', 'url' => route('home')],
        ['name' => 'Blog', 'url' => route('blog.index')],
        ['name' => $post->title, 'url' => null],
    ];
@endphp
@include('partials.structured-data-breadcrumb')

@push('structured_data')
<script type="application/ld+json">
{"@@context":"https://schema.org","@@type":"Article","headline":"{{ addslashes($post->title) }}","description":"{{ addslashes($metaDesc) }}","image":"{{ $imageUrl }}","datePublished":"{{ $post->published_at?->toIso8601String() }}","dateModified":"{{ $post->updated_at->toIso8601String() }}","author":{"@@type":"Organization","name":"NakliyePark"},"publisher":{"@@type":"Organization","name":"NakliyePark","logo":{"@@type":"ImageObject","url":"{{ asset('icons/icon-192.png') }}"}},"mainEntityOfPage":{"@@type":"WebPage","@@id":"{{ $canonicalUrl }}"}}}
</script>
@endpush

@push('scripts')
@vite('resources/js/blog.js')
@endpush

@section('content')
<div class="min-h-screen bg-[#f8f9fa] dark:bg-zinc-950" data-blog-page="show">
    {{-- Breadcrumb: kompakt --}}
    <div class="page-container py-4">
        <nav class="text-sm text-zinc-500 dark:text-zinc-400" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                <li><a href="{{ route('home') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Anasayfa</a></li>
                <li><span class="text-zinc-400">/</span></li>
                <li><a href="{{ route('blog.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Blog</a></li>
                <li><span class="text-zinc-400">/</span></li>
                <li class="text-zinc-700 dark:text-zinc-300 truncate max-w-[180px] sm:max-w-md" aria-current="page">{{ $post->title }}</li>
            </ol>
        </nav>
    </div>

    <div class="page-container pb-16 sm:pb-24">
        <div class="lg:grid lg:grid-cols-12 lg:gap-10 xl:gap-14">
            {{-- Ana içerik --}}
            <article class="lg:col-span-8" itemscope itemtype="https://schema.org/Article">
                <div class="overflow-hidden rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-sm">
                    {{-- Hero görsel: gradient overlay --}}
                    @if($post->image)
                        <figure class="relative aspect-[2/1] sm:aspect-[21/10] max-h-[320px] overflow-hidden">
                            <img
                                src="{{ Str::startsWith($post->image, 'http') ? $post->image : asset('storage/'.$post->image) }}"
                                alt="{{ $post->title }} - NakliyePark Blog"
                                class="w-full h-full object-cover object-center"
                                itemprop="image"
                                loading="eager"
                                fetchpriority="high"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-zinc-900/40 via-transparent to-transparent"></div>
                        </figure>
                    @endif

                    <div class="p-6 sm:p-8 lg:p-10">
                        {{-- Meta: kategori + tarih + okuma süresi --}}
                        <div class="flex flex-wrap items-center gap-3 mb-5">
                            @if($post->category)
                                <a href="{{ route('blog.index') }}?category={{ $post->category->slug }}" class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-sm font-medium bg-emerald-500/10 dark:bg-emerald-400/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20 dark:border-emerald-400/20 hover:bg-emerald-500/15 dark:hover:bg-emerald-400/15 transition-colors">
                                    {{ $post->category->name }}
                                </a>
                            @endif
                            <time datetime="{{ $post->published_at?->toIso8601String() }}" class="text-sm text-zinc-500 dark:text-zinc-400" itemprop="datePublished">
                                {{ $post->published_at?->locale('tr')->translatedFormat('d F Y') }}
                            </time>
                            @php $wordCount = str_word_count(strip_tags($post->content)); $readTime = max(1, (int) ceil($wordCount / 200)); @endphp
                            <span class="text-sm text-zinc-400 dark:text-zinc-500">· {{ $readTime }} dk okuma</span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-zinc-900 dark:text-white tracking-tight leading-[1.2] mb-4" itemprop="headline">{{ $post->title }}</h1>

                        @if($post->excerpt)
                            <p class="text-lg text-zinc-600 dark:text-zinc-300 leading-relaxed mb-8 font-medium" itemprop="description">{{ $post->excerpt }}</p>
                        @endif

                        <div class="blog-article-content prose prose-lg prose-zinc dark:prose-invert max-w-none
                            prose-headings:font-bold prose-headings:tracking-tight prose-headings:text-zinc-900 dark:prose-headings:text-white
                            prose-p:text-zinc-600 dark:prose-p:text-zinc-300 prose-p:leading-[1.8] prose-p:my-4
                            prose-ul:my-4 prose-ol:my-4 prose-li:text-zinc-600 dark:prose-li:text-zinc-300
                            prose-a:text-emerald-600 dark:prose-a:text-emerald-400 prose-a:no-underline hover:prose-a:underline
                            prose-strong:text-zinc-900 dark:prose-strong:text-white
                            prose-img:rounded-xl prose-img:shadow-md"
                            itemprop="articleBody">
                            {!! $post->content !!}
                        </div>

                        {{-- Paylaşım + Geri dön --}}
                        <div class="mt-10 pt-8 border-t border-zinc-200 dark:border-zinc-700 flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Paylaş:</span>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode($canonicalUrl) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl flex items-center justify-center bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors" aria-label="Twitter'da paylaş">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($canonicalUrl) }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl flex items-center justify-center bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors" aria-label="LinkedIn'de paylaş">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . $canonicalUrl) }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl flex items-center justify-center bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors" aria-label="WhatsApp'ta paylaş">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </a>
                            </div>
                            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                Tüm yazılar
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Sidebar: Diğer yazılar --}}
            <aside class="lg:col-span-4 mt-10 lg:mt-0">
                <div class="lg:sticky lg:top-24">
                    <div class="rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800/30">
                            <h2 class="text-base font-bold text-zinc-900 dark:text-white">Diğer yazılar</h2>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">İlginizi çekebilecek blog yazıları</p>
                        </div>
                        <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @forelse($otherPosts as $other)
                                <a href="{{ route('blog.show', $other->slug) }}" class="blog-reveal flex gap-3 p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors group">
                                    @if($other->image)
                                        <div class="w-14 h-14 shrink-0 rounded-lg overflow-hidden bg-zinc-100 dark:bg-zinc-800 ring-1 ring-zinc-200/50 dark:ring-zinc-700">
                                            <img src="{{ Str::startsWith($other->image, 'http') ? $other->image : asset('storage/'.$other->image) }}" alt="{{ $other->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                        </div>
                                    @else
                                        <div class="w-14 h-14 shrink-0 rounded-lg bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/40 dark:to-teal-900/30 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 line-clamp-2 transition-colors">{{ $other->title }}</h3>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">{{ $other->published_at?->format('d.m.Y') }}</p>
                                    </div>
                                    <span class="shrink-0 self-center text-zinc-400 group-hover:text-emerald-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </a>
                            @empty
                                <div class="p-6 text-center text-zinc-500 dark:text-zinc-400 text-sm">
                                    <p>Henüz başka yazı yok.</p>
                                    <a href="{{ route('blog.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline mt-2 inline-block">Bloga git</a>
                                </div>
                            @endforelse
                        </div>
                        @if($otherPosts->isNotEmpty())
                            <div class="p-4 border-t border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/30">
                                <a href="{{ route('blog.index') }}" class="block text-center text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors py-2">
                                    Tüm blog yazılarını görüntüle →
                                </a>
                            </div>
                        @endif
                    </div>
                    @php $blogShowSidebar = \App\Models\AdZone::getForPagePosition('blog_show', 'sidebar', 3); @endphp
                    @if($blogShowSidebar->isNotEmpty())
                        <div class="mt-6 space-y-4">
                            @foreach($blogShowSidebar as $reklam)
                                <div class="rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 p-4">
                                    @if($reklam->isCode()){!! $reklam->kod !!}@else
                                        @if($reklam->link)<a href="{{ $reklam->link }}" target="_blank" rel="noopener noreferrer nofollow" class="block">@endif
                                        @if($reklam->resim)<img src="{{ $reklam->resim }}" alt="{{ $reklam->baslik ?? 'Reklam' }}" class="w-full rounded-lg mb-2 max-h-32 object-cover" loading="lazy">@endif
                                        @if($reklam->baslik)<p class="font-medium text-zinc-900 dark:text-white text-sm">{{ $reklam->baslik }}</p>@endif
                                        @if($reklam->link)</a>@endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
