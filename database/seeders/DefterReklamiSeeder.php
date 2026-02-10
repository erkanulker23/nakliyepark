<?php

namespace Database\Seeders;

use App\Models\DefterReklami;
use Illuminate\Database\Seeder;

class DefterReklamiSeeder extends Seeder
{
    public function run(): void
    {
        $reklamlar = [
            // --- Üst alan reklamları ---
            [
                'baslik' => 'Evden Eve Nakliyat – Güvenilir Taşıma',
                'icerik' => 'Türkiye geneli ev eşyası taşıma. Profesyonel paketleme, sigortalı nakliyat. Ücretsiz keşif.',
                'resim' => 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6e3?w=600&h=200&fit=crop',
                'link' => '#',
                'konum' => 'ust',
                'aktif' => true,
                'sira' => 10,
            ],
            [
                'baslik' => 'Kurumsal Ofis Taşıma Hizmeti',
                'icerik' => 'Büro taşıma, ekipman ve arşiv nakliyesi. Hafta sonu ve tatil taşıma seçenekleri.',
                'resim' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&h=200&fit=crop',
                'link' => '#',
                'konum' => 'ust',
                'aktif' => true,
                'sira' => 20,
            ],
            // --- Sidebar reklamları ---
            [
                'baslik' => 'Nakliyat Kutusu & Ambalaj Malzemeleri',
                'icerik' => 'Karton kutu, streç film, köpük. Toplu alımda indirim. Hızlı kargo.',
                'resim' => null,
                'link' => '#',
                'konum' => 'sidebar',
                'aktif' => true,
                'sira' => 5,
            ],
            [
                'baslik' => 'Nakliye Sigortası',
                'icerik' => 'Eşyanızı güvence altına alın. Taşıma sigortası teklifi alın, anında karşılaştırın.',
                'resim' => null,
                'link' => '#',
                'konum' => 'sidebar',
                'aktif' => true,
                'sira' => 10,
            ],
            [
                'baslik' => 'Tır & Kamyon Kiralama',
                'icerik' => 'Kapalı kasa, açık kasa, lowbed. Günlük ve aylık kiralama. Tüm Türkiye.',
                'resim' => 'https://images.unsplash.com/photo-1519003722824-194d4455a60c?w=400&h=150&fit=crop',
                'link' => '#',
                'konum' => 'sidebar',
                'aktif' => true,
                'sira' => 15,
            ],
            [
                'baslik' => 'Depolama & Antrepo',
                'icerik' => 'Kısa ve uzun süreli depolama. İstanbul ve Ankara merkezli tesisler.',
                'resim' => null,
                'link' => '#',
                'konum' => 'sidebar',
                'aktif' => true,
                'sira' => 20,
            ],
            [
                'baslik' => 'Nakliyat Yazılımı – Fatura & Rota',
                'icerik' => 'Nakliye firmaları için CRM, teklif ve fatura modülü. 14 gün ücretsiz deneyin.',
                'resim' => null,
                'link' => '#',
                'konum' => 'sidebar',
                'aktif' => true,
                'sira' => 25,
            ],
            // --- Alt alan reklamları ---
            [
                'baslik' => 'Uluslararası Nakliyat',
                'icerik' => 'Avrupa ve Orta Asya güzergâhları. Gümrük işlemleri dahil kapıdan kapıya hizmet.',
                'resim' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=600&h=180&fit=crop',
                'link' => '#',
                'konum' => 'alt',
                'aktif' => true,
                'sira' => 10,
            ],
            [
                'baslik' => 'Özel Eşya Taşıma',
                'icerik' => 'Piyano, antika, sanat eseri taşıma. Özel paketleme ve sigorta seçenekleri.',
                'resim' => null,
                'link' => '#',
                'konum' => 'alt',
                'aktif' => true,
                'sira' => 20,
            ],
        ];

        foreach ($reklamlar as $r) {
            DefterReklami::updateOrCreate(
                [
                    'baslik' => $r['baslik'],
                    'konum' => $r['konum'],
                ],
                $r
            );
        }
    }
}
