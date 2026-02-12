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
                'email_verified_at' => now(),
            ]);
        }

        $this->call(DemoSeeder::class);
        $this->call(FaqSeeder::class);
        $this->call(DemoIhaleSeeder::class);
        $this->call(DemoFullSeeder::class);
        $this->call(PazaryeriSeeder::class);
        $this->call(ReviewSeeder::class);
        $this->call(DefterReklamiSeeder::class);
        $this->call(DefterYanitiSeeder::class);
        $this->call(SponsorSeeder::class);
    }
}
