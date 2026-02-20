<?php

return [
    /*
    | Nakliye tahmini fiyat hesaplama parametreleri.
    | Mesafe kademeleri ve oda tipi çarpanları piyasa verilerine dayanır.
    | Admin panelinden Setting::get ile override edilebilir.
    */

    // Mesafe kademelerine göre km başına fiyat (₺/km)
    // Mesafe arttıkça birim fiyat düşer (uzun mesafe indirimi)
    'distance_tiers' => [
        170 => 110,
        200 => 85,
        220 => 90,
        250 => 88,
        300 => 85,
        350 => 80,
        400 => 80,
        450 => 79,
        500 => 59,
        600 => 46,
        700 => 42,
        800 => 39,
        900 => 38,
        1000 => 33,
        1100 => 30,
        1200 => 31,
        1300 => 29,
        1400 => 28,
        1500 => 25,
        'default' => 20, // 1500 km üzeri
    ],

    // Oda tipi (eşya durumu) çarpanları — ihale ile uyumlu
    'room_type_factors' => [
        '1+1' => 1.1,
        '2+1' => 1.3,
        '3+1' => 1.5,
        '4+1' => 2.0,
        '5+1' => 3.0,
        'Daha büyük' => 3.0,
        'Sadece birkaç eşya taşınacak' => 0.9,
        'Diğer' => 1.2,
    ],

    // Eşya durumu (diğer hizmetler için): fiyat çarpanı
    'esya_durumu' => [
        'basit' => 0.85,
        'normal' => 1.0,
        'agir' => 1.25,
        'ozel' => 1.4,
    ],

    // Kat bilgisi: asansör yoksa kat başına ek ücret (₺) — merdiven taşıma (insan gücü) daha yüksek
    'per_floor_no_elevator' => 280,

    // Asansör varsa kat başına düşük ücret (₺)
    'per_floor_with_elevator' => 40,

    // Oda tipine göre tahmini fiyat alt sınırı (₺) — hesaplanan fiyat bunun altına düşemez
    'room_minimum_price' => [
        '1+1' => 15000,
        '2+1' => 25000,
        '3+1' => 35000,
        '4+1' => 45000,
        '5+1' => 60000,
        'Daha büyük' => 60000,
        'Sadece birkaç eşya taşınacak' => 10000,
        'Diğer' => 20000,
    ],

    // Oda tipine göre hacim tahmini (m³) - evden eve dışındaki hizmetler için
    'room_volume_estimate' => [
        '1+1' => 25,
        '2+1' => 35,
        '3+1' => 50,
        '4+1' => 65,
        '5+1' => 90,
        'Daha büyük' => 90,
        'Sadece birkaç eşya taşınacak' => 15,
        'Diğer' => 40,
    ],


    // Şehir içi (0 km) mesajı - iletişim merkezinden fiyat alınması önerilir
    'local_transport_message' => 'Lütfen çağrı merkezimizden şehir içi nakliye fiyatlarını öğreniniz.',

    // Uluslararası nakliyat için ülke listesi (Türkçe)
    'countries' => [
        'Türkiye', 'Almanya', 'Hollanda', 'Belçika', 'Fransa', 'İngiltere', 'İsviçre', 'Avusturya',
        'İtalya', 'İspanya', 'Portekiz', 'Polonya', 'Romanya', 'Bulgaristan', 'Yunanistan', 'Çekya',
        'Macaristan', 'Hırvatistan', 'Slovenya', 'Slovakya', 'Danimarka', 'İsveç', 'Norveç', 'Finlandiya',
        'ABD', 'Kanada', 'Avustralya', 'Rusya', 'Ukrayna', 'Azerbaycan', 'Gürcistan', 'Kazakistan',
        'Suudi Arabistan', 'BAE', 'Katar', 'Kuveyt', 'Irak', 'İran', 'Mısır', 'Diğer',
    ],
];
