<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\PazaryeriListing;
use Illuminate\Database\Seeder;

class PazaryeriSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::whereNotNull('approved_at')->get();
        if ($companies->isEmpty()) {
            return;
        }

        $titles = [
            '2019 Model Mercedes Actros Kamyon',
            '2020 Ford Transit Kamyonet',
            '2018 Iveco Stralis TIR',
            '2021 Fiat Ducato Panelvan',
            '2017 MAN TGX Kamyon',
            '2019 Renault Master Kapalı Kasa',
            '2020 Scania R450 TIR',
            '2018 Isuzu D-Max Kamyonet',
            '2022 Mercedes Sprinter Panelvan',
            '2016 Volvo FH Lowbed',
            '2020 DAF XF Kamyon',
            '2019 BMC Procity Kamyonet',
            '2021 Ford Ranger Çift Kabin',
            '2018 Mercedes Atego',
            '2020 Iveco Daily Van',
        ];

        $vehicleTypes = ['kamyon', 'kamyonet', 'panelvan', 'tir', 'lowbed', 'kapali_kasa'];
        $cities = ['İstanbul', 'Ankara', 'İzmir', 'Bursa', 'Kocaeli', 'Antalya', 'Adana', 'Gaziantep'];

        foreach ($titles as $i => $title) {
            $company = $companies->random();
            $type = $vehicleTypes[$i % count($vehicleTypes)];
            $listingType = $i % 3 === 0 ? 'rent' : 'sale';
            PazaryeriListing::firstOrCreate(
                [
                    'company_id' => $company->id,
                    'title' => $title,
                ],
                [
                    'vehicle_type' => $type,
                    'listing_type' => $listingType,
                    'price' => $listingType === 'sale' ? rand(450000, 2500000) : rand(2500, 15000),
                    'city' => $cities[array_rand($cities)],
                    'year' => (int) substr($title, 0, 4),
                    'description' => 'Nakliye ve taşımacılık için uygun. Bakımlı, sigorta ve ruhsat güncel. Detaylı bilgi için iletişime geçin.',
                    'status' => 'active',
                ]
            );
        }
    }
}
