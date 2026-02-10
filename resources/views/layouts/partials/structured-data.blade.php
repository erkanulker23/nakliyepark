{{-- Varsayılan: Organization + WebSite (Google, Yandex, Bing schema.org) --}}
@php
    $baseUrl = config('app.url');
    $siteName = config('seo.site_name');
    $logoUrl = !empty($site_logo_url ?? null) ? $site_logo_url : asset('icons/icon-192.png');
@endphp
<script type="application/ld+json">
{"@@context":"https://schema.org","@@graph":[
{"@@type":"Organization","@@id":"{{ $baseUrl }}/#organization","name":"{{ addslashes($siteName) }}","url":"{{ $baseUrl }}","logo":{"@@type":"ImageObject","url":"{{ $logoUrl }}"}},
{"@@type":"WebSite","@@id":"{{ $baseUrl }}/#website","url":"{{ $baseUrl }}","name":"{{ addslashes($siteName) }}","publisher":{"@@id":"{{ $baseUrl }}/#organization"},"inLanguage":"tr"}
]}
</script>
@stack('structured_data')
