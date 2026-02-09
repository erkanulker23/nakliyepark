<?php

namespace Database\Seeders;

use App\Models\RoomTemplate;
use Illuminate\Database\Seeder;

class RoomTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['name' => 'Salon', 'default_volume_m3' => 25, 'sort_order' => 1],
            ['name' => 'Mutfak', 'default_volume_m3' => 15, 'sort_order' => 2],
            ['name' => 'Yatak Odası', 'default_volume_m3' => 20, 'sort_order' => 3],
            ['name' => 'Çocuk Odası', 'default_volume_m3' => 12, 'sort_order' => 4],
            ['name' => 'Banyo', 'default_volume_m3' => 8, 'sort_order' => 5],
            ['name' => 'Balkon / Depo', 'default_volume_m3' => 10, 'sort_order' => 6],
        ];

        foreach ($rooms as $r) {
            RoomTemplate::updateOrCreate(
                ['name' => $r['name']],
                ['default_volume_m3' => $r['default_volume_m3'], 'sort_order' => $r['sort_order']]
            );
        }
    }
}
