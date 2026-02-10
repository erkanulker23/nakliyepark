<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Site SEO defaults (Google, Yandex, Bing uyumlu)
    |--------------------------------------------------------------------------
    */
    'site_name' => env('SEO_SITE_NAME', 'NakliyePark'),
    'default_description' => env('SEO_DEFAULT_DESCRIPTION', 'NakliyePark - Akıllı nakliye ve yük borsası. Evden eve nakliyat ihaleleri, nakliye firmaları ve teklif alın.'),
    'default_keywords' => env('SEO_DEFAULT_KEYWORDS', 'nakliye, nakliyat, evden eve nakliyat, nakliye ihalesi, nakliye firmaları, yük taşıma, taşıma ihalesi'),
    'default_image' => null, // asset('icons/icon-192.png') layout'ta kullanılır
    'locale' => 'tr_TR',
    'locale_alternate' => [],
    'twitter_handle' => env('SEO_TWITTER_HANDLE', ''),
    'facebook_app_id' => env('SEO_FACEBOOK_APP_ID', ''),
    /*
    | Arama motoru doğrulama (isteğe bağlı)
    */
    'google_site_verification' => env('GOOGLE_SITE_VERIFICATION', ''),
    'yandex_verification' => env('YANDEX_VERIFICATION', ''),
    'bing_verification' => env('BING_VERIFICATION', ''),
];
