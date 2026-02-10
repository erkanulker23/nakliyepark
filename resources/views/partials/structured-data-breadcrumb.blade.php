{{-- BreadcrumbList schema.org - $breadcrumbItems: [['name'=>'...', 'url'=>'...'], ...] (son öğe url boş olabilir) --}}
@if(!empty($breadcrumbItems) && is_array($breadcrumbItems))
@php
    $list = [];
    $pos = 1;
    foreach ($breadcrumbItems as $item) {
        $el = ['@type' => 'ListItem', 'position' => $pos, 'name' => $item['name'] ?? ''];
        if (!empty($item['url'])) {
            $el['item'] = $item['url'];
        }
        $list[] = $el;
        $pos++;
    }
    $breadcrumbSchema = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $list];
@endphp
@push('structured_data')
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
@endif
