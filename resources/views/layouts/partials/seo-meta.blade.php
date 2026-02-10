{{-- Google, Yandex, Bing SEO + Open Graph + Twitter Cards --}}
@php
    $canonical = $canonical_url ?? url()->current();
    $title = $seo_title ?? $site_meta_title ?? config('seo.site_name', 'NakliyePark');
    $description = $seo_description ?? $site_meta_description ?? config('seo.default_description');
    $image = $og_image ?? $site_logo_url ?? asset('icons/icon-192.png');
    $robots = $meta_robots ?? 'index, follow';
@endphp
<link rel="canonical" href="{{ $canonical }}">
<meta name="robots" content="{{ $robots }}">
<meta name="googlebot" content="{{ $robots }}">
<meta name="yandex" content="{{ $robots }}">
@if(!empty($description))
<meta name="description" content="{{ Str::limit(strip_tags($description), 160) }}">
@endif
@if(!empty($site_meta_keywords ?? config('seo.default_keywords')))
<meta name="keywords" content="{{ $site_meta_keywords ?? config('seo.default_keywords') }}">
@endif

{{-- Open Graph (Facebook, LinkedIn, Google) --}}
<meta property="og:type" content="{{ $og_type ?? 'website' }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($description), 200) }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:site_name" content="{{ config('seo.site_name') }}">
<meta property="og:locale" content="{{ config('seo.locale', 'tr_TR') }}">
@foreach(config('seo.locale_alternate', []) as $loc)
<meta property="og:locale:alternate" content="{{ $loc }}">
@endforeach

{{-- Twitter Card --}}
<meta name="twitter:card" content="{{ $twitter_card ?? 'summary_large_image' }}">
<meta name="twitter:url" content="{{ $canonical }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ Str::limit(strip_tags($description), 200) }}">
<meta name="twitter:image" content="{{ $image }}">
@if(!empty(config('seo.twitter_handle')))
<meta name="twitter:site" content="{{ config('seo.twitter_handle') }}">
@endif

{{-- Yandex / Bing doğrulama (opsiyonel) --}}
@if(!empty(config('seo.yandex_verification')))
<meta name="yandex-verification" content="{{ config('seo.yandex_verification') }}">
@endif
@if(!empty(config('seo.bing_verification')))
<meta name="msvalidate.01" content="{{ config('seo.bing_verification') }}">
@endif
@if(!empty(config('seo.google_site_verification')))
<meta name="google-site-verification" content="{{ config('seo.google_site_verification') }}">
@endif
