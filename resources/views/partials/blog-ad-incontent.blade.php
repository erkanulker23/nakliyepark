<div class="blog-incontent-ad my-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800/50 p-4 text-center">
    @if($reklam->isCode())
        {!! $reklam->kod !!}
    @else
        @if($reklam->link)<a href="{{ $reklam->link }}" target="_blank" rel="noopener noreferrer nofollow" class="block">@endif
        @if($reklam->resim)<img src="{{ $reklam->resim }}" alt="{{ $reklam->baslik ?? 'Reklam' }}" class="mx-auto max-w-full max-h-40 object-contain rounded-lg" loading="lazy">@endif
        @if($reklam->baslik)<p class="font-medium text-zinc-900 dark:text-white text-sm mt-2">{{ $reklam->baslik }}</p>@endif
        @if($reklam->link)</a>@endif
    @endif
</div>
