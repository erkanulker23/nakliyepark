<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Ihale;
use App\Models\IhalePhoto;
use App\Models\Teklif;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DemoIhaleSeeder extends Seeder
{
    /**
     * Ücretsiz stok görselleri (Unsplash - nakliye / ev taşıma).
     * Seeder çalışınca indirilip storage'a kaydedilir.
     */
    private array $demoImageUrls = [
        'https://images.unsplash.com/photo-1628481103102-01de5ffe556b?w=800', // nakliye kamyonu
        'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800',     // koliler
        'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800', // ev dış
        'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800', // salon
        'https://images.unsplash.com/photo-1600573472592-401b489a3cdc?w=800', // taşıma kolileri
    ];

    public function run(): void
    {
        $disk = 'public';
        $dir = 'ihale-photos';
        Storage::disk($disk)->makeDirectory($dir);

        $musteri = User::where('role', 'musteri')->first();
        $companies = Company::whereNotNull('approved_at')->get();
        if ($companies->isEmpty()) {
            return;
        }

        // Örnek ihale: Ankara - Şanlıurfa (referans sayfadaki gibi)
        $ihale = Ihale::updateOrCreate(
            [
                'from_city' => 'Ankara',
                'to_city' => 'Şanlıurfa',
                'status' => 'published',
            ],
            [
                'user_id' => $musteri?->id,
                'guest_contact_name' => null,
                'guest_contact_email' => null,
                'guest_contact_phone' => null,
                'service_type' => 'evden_eve_nakliyat',
                'room_type' => '3+1',
                'from_district' => 'Polatlı',
                'from_neighborhood' => null,
                'from_address' => null,
                'from_postal_code' => null,
                'to_district' => 'Şanlıurfa',
                'to_neighborhood' => null,
                'to_address' => null,
                'to_postal_code' => null,
                'distance_km' => 900.3,
                'move_date' => now()->addDays(14),
                'volume_m3' => 45,
                'description' => "3+1 ev eşyası taşınacak. Buzdolabı, çamaşır makinesi, koltuk takımı ve yatak odası takımı var. Yerinde eksper yapılabilir. Paketlemeyi ben veya firma yapacak şekilde teklif istiyorum.",
                'status' => 'published',
            ]
        );

        // Eski demo fotoğrafları kaldır (aynı ihale için)
        IhalePhoto::where('ihale_id', $ihale->id)->delete();

        // Görselleri indir ve kaydet; indirme başarısızsa URL doğrudan kaydedilir (view harici URL destekler)
        foreach ($this->demoImageUrls as $index => $url) {
            $path = $this->downloadImage($url, $dir, $index + 1);
            if (!$path) {
                $path = $url; // Harici URL olarak kullan (Unsplash)
            }
            IhalePhoto::create([
                'ihale_id' => $ihale->id,
                'path' => $path,
                'sort_order' => $index,
            ]);
        }

        // Nakliye firmalarından teklifler (veritabanından)
        $teklifler = [
            ['amount' => 18500, 'message' => 'Polatlı ve Şanlıurfa merkez dahil. Modüler asansör ek hizmeti sunuyoruz. Sigorta dahildir.'],
            ['amount' => 21200, 'message' => 'Profesyonel paketleme ve taşıma. 2 personel, 1 kamyon. Tarih uygunsa hemen çıkabiliriz.'],
            ['amount' => 19900, 'message' => 'Yerinde eksper yapabiliriz. Fiyata paketleme malzemesi dahil.'],
        ];

        foreach ($companies as $i => $company) {
            $data = $teklifler[$i] ?? ['amount' => 15000 + ($i * 2000), 'message' => null];
            Teklif::firstOrCreate(
                [
                    'ihale_id' => $ihale->id,
                    'company_id' => $company->id,
                ],
                [
                    'amount' => $data['amount'],
                    'message' => $data['message'],
                    'status' => 'pending',
                ]
            );
        }
    }

    private function downloadImage(string $url, string $dir, int $num): ?string
    {
        try {
            $response = Http::timeout(15)->get($url);
            if (!$response->successful()) {
                return null;
            }
            $ext = 'jpg';
            $filename = "demo-{$num}.{$ext}";
            $path = "{$dir}/{$filename}";
            Storage::disk('public')->put($path, $response->body());
            return $path;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
