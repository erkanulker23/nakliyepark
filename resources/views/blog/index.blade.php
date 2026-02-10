@extends('layouts.app')

@section('title', 'Bloglar - NakliyePark')
@section('meta_description', 'Nakliye ve taşıma hakkında ipuçları, rehberler ve güncel bilgiler.')

@push('scripts')
@vite('resources/js/blog.js')
@endpush

@push('styles')
<style>
.blog-card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
.blog-card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgb(0 0 0 / 0.12), 0 0 0 1px rgb(0 0 0 / 0.04); }
.dark .blog-card-hover:hover { box-shadow: 0 20px 40px -12px rgb(0 0 0 / 0.4), 0 0 0 1px rgb(63 63 63 / 0.5); }
.blog-featured-hover .blog-img-wrap { transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
.blog-featured-hover:hover .blog-img-wrap { transform: scale(1.04); }
@media (prefers-reduced-motion: reduce) {
  .blog-card-hover:hover { transform: none; }
  .blog-featured-hover:hover .blog-img-wrap { transform: none; }
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#f8f9fa] dark:bg-zinc-950" data-blog-page="index">
    {{-- Hero alanı: gradient + badge --}}
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-emerald-50/80 via-transparent to-transparent dark:from-emerald-950/20 dark:via-transparent"></div>
        <div class="absolute top-0 right-0 w-1/2 h-full bg-[radial-gradient(ellipse_80%_60%_at_100%_0%,rgba(16,185,129,0.08),transparent)] dark:bg-[radial-gradient(ellipse_80%_60%_at_100%_0%,rgba(16,185,129,0.12),transparent)]"></div>
        <div class="page-container relative py-12 sm:py-16 lg:py-20 max-w-6xl">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
                <header>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 dark:bg-emerald-400/10 border border-emerald-500/20 dark:border-emerald-400/20 text-emerald-700 dark:text-emerald-300 text-sm font-medium mb-6">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                        Blog
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-zinc-900 dark:text-white tracking-tight leading-[1.15]">
                        Bloglar
                    </h1>
                    <p class="mt-3 text-lg text-zinc-600 dark:text-zinc-400 max-w-xl">
                        Nakliye ve taşıma hakkında ipuçları, rehberler ve güncel bilgiler.
                    </p>
                </header>
                @if($categories->isNotEmpty())
                    <nav class="flex flex-wrap gap-2 lg:justify-end" aria-label="Blog kategorileri">
                        <a href="{{ route('blog.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ !$selectedCategory ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 shadow-md' : 'bg-white/80 dark:bg-zinc-800/80 text-zinc-600 dark:text-zinc-400 hover:bg-white dark:hover:bg-zinc-800 border border-zinc-200/80 dark:border-zinc-700 backdrop-blur-sm' }}">
                            Tümü
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('blog.index', ['category' => $cat->slug]) }}" class="px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ $selectedCategory === $cat->slug ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 shadow-md' : 'bg-white/80 dark:bg-zinc-800/80 text-zinc-600 dark:text-zinc-400 hover:bg-white dark:hover:bg-zinc-800 border border-zinc-200/80 dark:border-zinc-700 backdrop-blur-sm' }}">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </nav>
                @endif
            </div>
        </div>
    </div>

    <div class="page-container pb-16 sm:pb-24 max-w-6xl">
        @php $blogUst = \App\Models\AdZone::getForPagePosition('blog', 'ust', 2); @endphp
        @if($blogUst->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                @foreach($blogUst as $reklam)
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden bg-white dark:bg-zinc-800 p-4">
                        @if($reklam->isCode()){!! $reklam->kod !!}@else
                            @if($reklam->link)<a href="{{ $reklam->link }}" target="_blank" rel="noopener noreferrer nofollow" class="block">@endif
                            @if($reklam->resim)<img src="{{ $reklam->resim }}" alt="{{ $reklam->baslik ?? 'Reklam' }}" class="w-full h-24 object-cover rounded-lg mb-2" loading="lazy">@endif
                            @if($reklam->baslik)<p class="font-medium text-zinc-900 dark:text-white">{{ $reklam->baslik }}</p>@endif
                            @if($reklam->link)</a>@endif
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
        @forelse($posts as $index => $post)
            @if($index === 0)
                {{-- Öne çıkan yazı: büyük kart --}}
                <a href="{{ route('blog.show', $post->slug) }}" class="blog-reveal blog-featured-hover group block mb-12 sm:mb-14">
                    <article class="relative overflow-hidden rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200/60 dark:border-zinc-800 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex flex-col lg:flex-row min-h-[320px] lg:min-h-[380px]">
                            <div class="lg:w-[52%] relative">
                                <div class="blog-img-wrap absolute inset-0">
                                    @if($post->image)
                                        <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" class="h-64 sm:h-80 lg:h-full w-full object-cover" loading="eager" fetchpriority="high">
                                    @else
                                        <div class="h-64 sm:h-80 lg:h-full w-full bg-gradient-to-br from-emerald-100 via-teal-50 to-cyan-100 dark:from-emerald-950/60 dark:via-teal-950/40 dark:to-cyan-950/50 flex items-center justify-center">
                                            <svg class="w-24 h-24 text-emerald-400/50 dark:text-emerald-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/0 to-white dark:from-transparent dark:to-zinc-900 lg:bg-gradient-to-r lg:from-transparent lg:via-transparent lg:to-white lg:dark:to-zinc-900"></div>
                                <div class="absolute top-4 left-4 flex items-center gap-2">
                                    <span class="px-3 py-1 rounded-lg bg-white/90 dark:bg-zinc-800/90 backdrop-blur-sm text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-700/50">Öne çıkan</span>
                                    @if($post->category)
                                        <span class="px-3 py-1 rounded-lg bg-white/90 dark:bg-zinc-800/90 backdrop-blur-sm text-xs font-medium text-zinc-600 dark:text-zinc-400">{{ $post->category->name }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-col justify-center p-6 sm:p-8 lg:p-10 lg:pl-12">
                                <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-zinc-900 dark:text-white leading-tight group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors line-clamp-2">
                                    {{ $post->title }}
                                </h2>
                                @if($post->excerpt)
                                    <p class="mt-3 text-zinc-600 dark:text-zinc-400 line-clamp-2 sm:line-clamp-3 text-base">{{ $post->excerpt }}</p>
                                @endif
                                <div class="mt-6 flex items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                                    <time datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->published_at?->locale('tr')->translatedFormat('d F Y') }}</time>
                                    @php $wc = str_word_count(strip_tags($post->content ?? '')); $rt = max(1, (int) ceil($wc / 200)); @endphp
                                    <span>· {{ $rt }} dk okuma</span>
                                    <span class="ml-auto inline-flex items-center gap-1.5 font-medium text-emerald-600 dark:text-emerald-400">
                                        Oku
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                </a>
            @elseif($index === 1)
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    <a href="{{ route('blog.show', $post->slug) }}" class="blog-reveal group block">
                        <article class="h-full overflow-hidden rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/60 dark:border-zinc-800 shadow-sm blog-card-hover">
                            @if($post->image)
                                <div class="aspect-[16/10] overflow-hidden">
                                    <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                </div>
                            @else
                                <div class="aspect-[16/10] bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-950/50 dark:to-teal-950/50 flex items-center justify-center">
                                    <svg class="w-14 h-14 text-emerald-400/50 dark:text-emerald-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                </div>
                            @endif
                            <div class="p-5 sm:p-6">
                                <div class="flex items-center gap-3 mb-2">
                                    @if($post->category)
                                        <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">{{ $post->category->name }}</span>
                                    @endif
                                    <time class="text-sm text-zinc-400 dark:text-zinc-500" datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->published_at?->format('d.m.Y') }}</time>
                                </div>
                                <h2 class="text-lg font-bold text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors line-clamp-2">{{ $post->title }}</h2>
                                @if($post->excerpt)
                                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2">{{ $post->excerpt }}</p>
                                @endif
                            </div>
                        </article>
                    </a>
            @else
                    <a href="{{ route('blog.show', $post->slug) }}" class="blog-reveal group block">
                        <article class="h-full overflow-hidden rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/60 dark:border-zinc-800 shadow-sm blog-card-hover">
                            @if($post->image)
                                <div class="aspect-[16/10] overflow-hidden">
                                    <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                </div>
                            @else
                                <div class="aspect-[16/10] bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-950/50 dark:to-teal-950/50 flex items-center justify-center">
                                    <svg class="w-14 h-14 text-emerald-400/50 dark:text-emerald-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                </div>
                            @endif
                            <div class="p-5 sm:p-6">
                                <div class="flex items-center gap-3 mb-2">
                                    @if($post->category)
                                        <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">{{ $post->category->name }}</span>
                                    @endif
                                    <time class="text-sm text-zinc-400 dark:text-zinc-500" datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->published_at?->format('d.m.Y') }}</time>
                                </div>
                                <h2 class="text-lg font-bold text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors line-clamp-2">{{ $post->title }}</h2>
                                @if($post->excerpt)
                                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2">{{ $post->excerpt }}</p>
                                @endif
                            </div>
                        </article>
                    </a>
            @endif
            @if($index === $posts->count() - 1 && $index >= 1)
                </div>
            @endif
        @empty
            <div class="rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200/60 dark:border-zinc-800 shadow-sm p-16 sm:p-24 text-center">
                <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-950/50 dark:to-teal-950/50 flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Henüz yazı yok</h2>
                <p class="mt-2 text-zinc-500 dark:text-zinc-400 max-w-sm mx-auto">Yakında yeni içeriklerle burada olacağız.</p>
            </div>
        @endforelse

        @php $blogAlt = \App\Models\AdZone::getForPagePosition('blog', 'alt', 2); @endphp
        @if($blogAlt->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-10 pt-8 border-t border-zinc-200 dark:border-zinc-700">
                @foreach($blogAlt as $reklam)
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden bg-white dark:bg-zinc-800 p-4">
                        @if($reklam->isCode()){!! $reklam->kod !!}@else
                            @if($reklam->link)<a href="{{ $reklam->link }}" target="_blank" rel="noopener noreferrer nofollow" class="block">@endif
                            @if($reklam->resim)<img src="{{ $reklam->resim }}" alt="{{ $reklam->baslik ?? 'Reklam' }}" class="w-full h-20 object-cover rounded-lg mb-2" loading="lazy">@endif
                            @if($reklam->baslik)<p class="font-medium text-zinc-900 dark:text-white">{{ $reklam->baslik }}</p>@endif
                            @if($reklam->link)</a>@endif
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @if($posts->hasPages())
            <div class="mt-14 pt-10 border-t border-zinc-200/80 dark:border-zinc-800 flex justify-center">{{ $posts->links() }}</div>
        @endif
    </div>
</div>
@endsection
