@php
    $sayfa = $sayfa ?? 'defter';
    $konum = $konum ?? 'sidebar';
    $limit = $limit ?? 5;
    $ads = \App\Models\AdZone::getForPagePosition($sayfa, $konum, $limit);
    $wrapperClass = $wrapperClass ?? 'rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden bg-white dark:bg-zinc-800 p-4';
@endphp
@if($ads->isNotEmpty())
    <div class="ad-zone ad-zone-{{ $sayfa }}-{{ $konum }}" data-sayfa="{{ $sayfa }}" data-konum="{{ $konum }}">
        @foreach($ads as $ad)
            <div class="ad-zone-item {{ $loop->first ? '' : 'mt-4' }}">
                @if($ad->isCode())
                    <div class="ad-zone-code {{ $wrapperClass }}">
                        {!! $ad->kod !!}
                    </div>
                @else
                    <div class="ad-zone-image {{ $wrapperClass }}">
                        @if($ad->link)
                            <a href="{{ $ad->link }}" target="_blank" rel="noopener noreferrer nofollow" class="block">
                        @endif
                        @if($ad->resim)
                            <img src="{{ $ad->resim }}" alt="{{ $ad->baslik ?? 'Reklam' }}" class="w-full max-h-48 object-contain object-center rounded-lg" loading="lazy">
                        @endif
                        @if($ad->baslik && !$ad->resim)
                            <p class="font-medium text-zinc-900 dark:text-white">{{ $ad->baslik }}</p>
                        @endif
                        @if($ad->link)
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif
