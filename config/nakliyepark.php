<?php

return [
    /*
    | KVKK: Kişisel verilerin saklama süresi (ay). Kapalı ihaleler X ay sonra anonimleştirilebilir.
    */
    'data_retention_months' => (int) env('KVKK_DATA_RETENTION_MONTHS', 24),

    /*
    | Nakliyeci (firma) abonelik paketleri. Anasayfa ve /nakliyeci/paketler sayfalarında kullanılır.
    */
    'nakliyeci_paketler' => [
        [
            'id' => 'baslangic',
            'name' => 'Başlangıç',
            'price' => 99,
            'teklif_limit' => 50,
            'description' => 'Yeni başlayan nakliye firmaları için ideal paket. Aylık 50 teklif hakkı ile ihalelere katılın.',
            'features' => [
                'Aylık 50 teklif hakkı',
                'Firma profili ve galeri',
                'Temel istatistikler',
                'E-posta bildirimleri',
                'Müşteri değerlendirmeleri',
            ],
            'cta' => 'Başla',
        ],
        [
            'id' => 'profesyonel',
            'name' => 'Profesyonel',
            'price' => 249,
            'teklif_limit' => 200,
            'description' => 'Daha fazla ihaleye teklif verin, öne çıkın. Öncelikli listeleme ve gelişmiş araçlar.',
            'features' => [
                'Aylık 200 teklif hakkı',
                'Öncelikli listeleme',
                'Firma sayfasında "Öne çıkan" rozeti',
                'Detaylı raporlama',
                'Öncelikli destek',
                'Tüm Başlangıç özellikleri',
            ],
            'cta' => 'Profesyonel ol',
            'popular' => true,
        ],
        [
            'id' => 'kurumsal',
            'name' => 'Kurumsal',
            'price' => 499,
            'teklif_limit' => 999,
            'description' => 'Sınırsıza yakın teklif, reklam desteği ve kurumsal özellikler. Büyük filolar için.',
            'features' => [
                'Aylık 999 teklif hakkı',
                'Reklam ve tanıtım desteği',
                'Özel kurumsal rozet',
                'API erişimi (planlanan)',
                'Özel hesap yöneticisi',
                'Tüm Profesyonel özellikleri',
            ],
            'cta' => 'Kurumsal paket',
        ],
    ],
];
