<?php

namespace Database\Seeders;

use App\Models\Sponsor;
use Illuminate\Database\Seeder;

class SponsorSeeder extends Seeder
{
    public function run(): void
    {
        $sponsors = [
            ['name' => 'Nakliye Pro', 'url' => 'https://example.com', 'sort_order' => 1],
            ['name' => 'Evden Eve Taşımacılık', 'url' => 'https://example.com', 'sort_order' => 2],
            ['name' => 'Şehir Nakliyat', 'url' => 'https://example.com', 'sort_order' => 3],
            ['name' => 'Anadolu Lojistik', 'url' => 'https://example.com', 'sort_order' => 4],
            ['name' => 'Marmara Nakliye', 'url' => null, 'sort_order' => 5],
            ['name' => 'Ege Taşımacılık', 'url' => null, 'sort_order' => 6],
        ];

        foreach ($sponsors as $item) {
            Sponsor::updateOrCreate(
                ['name' => $item['name']],
                [
                    'url' => $item['url'] ?? null,
                    'sort_order' => $item['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
