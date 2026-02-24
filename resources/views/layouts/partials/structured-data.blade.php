{{-- Varsayılan: Organization + WebSite (Google, Yandex, Bing schema.org) — Türkçe karakter uyumlu --}}
@php
    $baseUrl = config('app.url');
    $siteName = config('seo.site_name');
    $logoUrl = !empty($site_logo_url ?? null) ? $site_logo_url : asset('icons/icon-192.png');
    $structuredGraph = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => $baseUrl . '/#organization',
                'name' => $siteName,
                'url' => $baseUrl,
                'logo' => ['@type' => 'ImageObject', 'url' => $logoUrl],
            ],
            [
                '@type' => 'WebSite',
                '@id' => $baseUrl . '/#website',
                'url' => $baseUrl,
                'name' => $siteName,
                'publisher' => ['@id' => $baseUrl . '/#organization'],
                'inLanguage' => 'tr',
            ],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($structuredGraph, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@stack('structured_data')
