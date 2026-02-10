<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RoomTemplateSeeder::class);

        if (User::where('email', 'admin@nakliyepark.test')->doesntExist()) {
            User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@nakliyepark.test',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]);
        }

        $this->call(DemoSeeder::class);
        $this->call(DemoIhaleSeeder::class);
        $this->call(DemoFullSeeder::class);
        $this->call(PazaryeriSeeder::class);
        $this->call(ReviewSeeder::class);
        $this->call(DefterReklamiSeeder::class);
    }
}
