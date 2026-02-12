<?php

return [
    'rooms' => [
        'salon' => [
            'label' => 'Salon',
            'items' => [
                ['id' => 'kanepe_3', 'name' => '3 Kişilik Kanepe', 'icon' => '🛋️', 'min_m3' => 1.2, 'max_m3' => 1.8],
                ['id' => 'kanepe_2', 'name' => '2 Kişilik Kanepe', 'icon' => '🛋️', 'min_m3' => 0.8, 'max_m3' => 1.2],
                ['id' => 'koltuk', 'name' => 'Koltuk / Tekli', 'icon' => '🪑', 'min_m3' => 0.25, 'max_m3' => 0.5],
                ['id' => 'sehpa', 'name' => 'Sehpa / Konsol', 'icon' => '🪵', 'min_m3' => 0.15, 'max_m3' => 0.4],
                ['id' => 'yemek_masasi', 'name' => 'Yemek Masası', 'icon' => '🪑', 'min_m3' => 0.5, 'max_m3' => 1.0],
                ['id' => 'yemek_sandalyeleri', 'name' => 'Sandalyeler (4\'lü)', 'icon' => '🪑', 'min_m3' => 0.2, 'max_m3' => 0.4],
                ['id' => 'kitaplik', 'name' => 'Kitaplık', 'icon' => '📚', 'min_m3' => 0.4, 'max_m3' => 1.2],
                ['id' => 'tv_ustu', 'name' => 'TV Ünitesi', 'icon' => '📺', 'min_m3' => 0.2, 'max_m3' => 0.6],
                ['id' => 'tv', 'name' => 'Televizyon', 'icon' => '📺', 'min_m3' => 0.15, 'max_m3' => 0.4],
                ['id' => 'cicek_saksi', 'name' => 'Çiçek / Saksı', 'icon' => '🪴', 'min_m3' => 0.05, 'max_m3' => 0.15],
                ['id' => 'halı', 'name' => 'Halı', 'icon' => '🧶', 'min_m3' => 0.1, 'max_m3' => 0.5],
                ['id' => 'ayna', 'name' => 'Ayna / Tablo', 'icon' => '🪞', 'min_m3' => 0.05, 'max_m3' => 0.2],
                ['id' => 'avize', 'name' => 'Avize', 'icon' => '💡', 'min_m3' => 0.05, 'max_m3' => 0.2],
                ['id' => 'lamba', 'name' => 'Lamba / Abajur', 'icon' => '🪔', 'min_m3' => 0.03, 'max_m3' => 0.1],
            ],
        ],
        'yatak_odasi' => [
            'label' => 'Yatak Odası',
            'items' => [
                ['id' => 'cift_kisilik_yatak', 'name' => 'Çift Kişilik Yatak', 'icon' => '🛏️', 'min_m3' => 1.0, 'max_m3' => 1.5],
                ['id' => 'tek_kisilik_yatak', 'name' => 'Tek Kişilik Yatak', 'icon' => '🛏️', 'min_m3' => 0.5, 'max_m3' => 0.8],
                ['id' => 'bebek_yatagi', 'name' => 'Bebek Yatağı', 'icon' => '🛏️', 'min_m3' => 0.2, 'max_m3' => 0.4],
                ['id' => 'gardrop', 'name' => 'Gardırop', 'icon' => '🚪', 'min_m3' => 1.2, 'max_m3' => 2.5],
                ['id' => 'komidin', 'name' => 'Komidin', 'icon' => '🪵', 'min_m3' => 0.1, 'max_m3' => 0.25],
                ['id' => 'sirasi', 'name' => 'Şıraşı / Konsol', 'icon' => '🪞', 'min_m3' => 0.2, 'max_m3' => 0.5],
                ['id' => 'yatak_odasi_halisi', 'name' => 'Halı', 'icon' => '🧶', 'min_m3' => 0.1, 'max_m3' => 0.4],
                ['id' => 'ayna_yatak', 'name' => 'Ayna', 'icon' => '🪞', 'min_m3' => 0.05, 'max_m3' => 0.15],
            ],
        ],
        'oturma_odasi' => [
            'label' => 'Oturma Odası',
            'items' => [
                ['id' => 'oturma_kanepe', 'name' => 'Kanepe', 'icon' => '🛋️', 'min_m3' => 0.8, 'max_m3' => 1.5],
                ['id' => 'oturma_koltuk', 'name' => 'Koltuk', 'icon' => '🪑', 'min_m3' => 0.25, 'max_m3' => 0.5],
                ['id' => 'calisma_masasi', 'name' => 'Çalışma Masası', 'icon' => '🪵', 'min_m3' => 0.4, 'max_m3' => 0.8],
                ['id' => 'ofis_koltugu', 'name' => 'Ofis Koltuğu', 'icon' => '🪑', 'min_m3' => 0.15, 'max_m3' => 0.3],
                ['id' => 'bilgisayar', 'name' => 'Bilgisayar / Monitör', 'icon' => '💻', 'min_m3' => 0.08, 'max_m3' => 0.2],
                ['id' => 'kitaplik_oturma', 'name' => 'Kitaplık', 'icon' => '📚', 'min_m3' => 0.4, 'max_m3' => 1.0],
                ['id' => 'tv_oturma', 'name' => 'TV', 'icon' => '📺', 'min_m3' => 0.1, 'max_m3' => 0.35],
            ],
        ],
        'genc_odasi' => [
            'label' => 'Genç Odası',
            'items' => [
                ['id' => 'genc_yatak', 'name' => 'Yatak', 'icon' => '🛏️', 'min_m3' => 0.5, 'max_m3' => 1.0],
                ['id' => 'genc_masa', 'name' => 'Çalışma Masası', 'icon' => '🪵', 'min_m3' => 0.35, 'max_m3' => 0.7],
                ['id' => 'genc_dolap', 'name' => 'Dolap', 'icon' => '🚪', 'min_m3' => 0.5, 'max_m3' => 1.2],
                ['id' => 'genc_kitaplik', 'name' => 'Kitaplık', 'icon' => '📚', 'min_m3' => 0.3, 'max_m3' => 0.8],
                ['id' => 'bilgisayar_genc', 'name' => 'Bilgisayar', 'icon' => '💻', 'min_m3' => 0.08, 'max_m3' => 0.2],
                ['id' => 'oyuncak', 'name' => 'Oyuncak / Kutu', 'icon' => '📦', 'min_m3' => 0.1, 'max_m3' => 0.4],
            ],
        ],
        'mutfak' => [
            'label' => 'Mutfak',
            'items' => [
                ['id' => 'buzdolabi', 'name' => 'Buzdolabı', 'icon' => '🧊', 'min_m3' => 0.5, 'max_m3' => 1.0],
                ['id' => 'firin', 'name' => 'Fırın / Ankastre', 'icon' => '🔥', 'min_m3' => 0.15, 'max_m3' => 0.35],
                ['id' => 'bulaşik_makinesi', 'name' => 'Bulaşık Makinesi', 'icon' => '🍽️', 'min_m3' => 0.2, 'max_m3' => 0.4],
                ['id' => 'mutfak_dolabi', 'name' => 'Mutfak Dolabı', 'icon' => '🚪', 'min_m3' => 0.4, 'max_m3' => 1.0],
                ['id' => 'mutfak_masasi', 'name' => 'Mutfak Masası', 'icon' => '🪵', 'min_m3' => 0.3, 'max_m3' => 0.6],
                ['id' => 'camasir_makinesi', 'name' => 'Çamaşır Makinesi', 'icon' => '🧺', 'min_m3' => 0.25, 'max_m3' => 0.45],
                ['id' => 'kurutma_makinesi', 'name' => 'Kurutma Makinesi', 'icon' => '🧺', 'min_m3' => 0.2, 'max_m3' => 0.35],
                ['id' => 'kucuk_ev_alesi', 'name' => 'Küçük Ev Aleti', 'icon' => '🔌', 'min_m3' => 0.05, 'max_m3' => 0.15],
                ['id' => 'mutfak_detay', 'name' => 'Tencere / Tava / Kutu', 'icon' => '📦', 'min_m3' => 0.1, 'max_m3' => 0.3],
            ],
        ],
        'banyo' => [
            'label' => 'Banyo',
            'items' => [
                ['id' => 'banyo_dolabi', 'name' => 'Banyo Dolabı', 'icon' => '🚪', 'min_m3' => 0.2, 'max_m3' => 0.5],
                ['id' => 'lavabo', 'name' => 'Lavabo Ünitesi', 'icon' => '🚿', 'min_m3' => 0.1, 'max_m3' => 0.25],
                ['id' => 'wc', 'name' => 'WC / Klozet', 'icon' => '🚽', 'min_m3' => 0.08, 'max_m3' => 0.2],
                ['id' => 'kurutma_rafi', 'name' => 'Kurutma Rafı', 'icon' => '🪣', 'min_m3' => 0.05, 'max_m3' => 0.15],
                ['id' => 'banyo_esya', 'name' => 'Banyo Eşyası (kutu)', 'icon' => '📦', 'min_m3' => 0.05, 'max_m3' => 0.2],
            ],
        ],
        'diger' => [
            'label' => 'Diğer Eşya',
            'items' => [
                ['id' => 'piano', 'name' => 'Piyano', 'icon' => '🎹', 'min_m3' => 1.5, 'max_m3' => 2.5],
                ['id' => 'klima', 'name' => 'Klima', 'icon' => '❄️', 'min_m3' => 0.1, 'max_m3' => 0.25],
                ['id' => 'vantilator', 'name' => 'Vantilatör', 'icon' => '🌀', 'min_m3' => 0.05, 'max_m3' => 0.15],
                ['id' => 'nakliye_kutusu', 'name' => 'Nakliye Kutusu', 'icon' => '📦', 'min_m3' => 0.05, 'max_m3' => 0.12],
                ['id' => 'valiz', 'name' => 'Valiz / Bavul', 'icon' => '🧳', 'min_m3' => 0.08, 'max_m3' => 0.2],
                ['id' => 'bisiklet', 'name' => 'Bisiklet', 'icon' => '🚲', 'min_m3' => 0.2, 'max_m3' => 0.5],
                ['id' => 'spor_ekipmani', 'name' => 'Spor Ekipmanı', 'icon' => '🏋️', 'min_m3' => 0.1, 'max_m3' => 0.4],
                ['id' => 'diger_kutu', 'name' => 'Diğer (kutu / koli)', 'icon' => '📦', 'min_m3' => 0.08, 'max_m3' => 0.25],
            ],
        ],
    ],

    'vehicles' => [
        ['min_m3' => 0, 'max_m3' => 12, 'label' => 'Panelvan', 'range' => '8–12 m³', 'icon' => '🚐'],
        ['min_m3' => 12, 'max_m3' => 22, 'label' => 'Küçük Kamyonet', 'range' => '14–22 m³', 'icon' => '🚐'],
        ['min_m3' => 22, 'max_m3' => 40, 'label' => 'Kamyonet', 'range' => '18–40 m³', 'icon' => '🚚'],
        ['min_m3' => 40, 'max_m3' => 90, 'label' => 'Kamyon', 'range' => '50–90 m³', 'icon' => '🚛'],
        ['min_m3' => 90, 'max_m3' => 999, 'label' => 'Tır', 'range' => '90+ m³', 'icon' => '🚛'],
    ],
];
