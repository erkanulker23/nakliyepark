<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::whereNotNull('approved_at')->get();
        $musteriler = User::where('role', 'musteri')->get();
        if ($companies->isEmpty() || $musteriler->isEmpty()) {
            return;
        }

        $comments = [
            'Çok profesyonel ekip, eşyalarımız hiç zarar görmeden taşındı. Teşekkürler.',
            'Zamanında ve titiz çalıştılar. Fiyat performans açısından memnunum.',
            'İlk kez nakliye firması kullandım, her şey sorunsuz geçti. Öneririm.',
            'Paketleme ve taşıma konusunda çok dikkatliler. Teşekkür ederim.',
            'Fiyatı uygun, hizmet kaliteli. Eşyalarımız güvende hissettik.',
            'İletişim kurabilmek kolaydı, taşınma günü her şey planlandığı gibi oldu.',
            'Şehirler arası taşınmamızı sorunsuz tamamladılar. Memnun kaldık.',
        ];

        $created = 0;
        $target = min(35, $companies->count() * 3);
        while ($created < $target) {
            $company = $companies->random();
            $user = $musteriler->random();
            $exists = Review::where('company_id', $company->id)->where('user_id', $user->id)->exists();
            if ($exists) {
                continue;
            }
            Review::create([
                'user_id' => $user->id,
                'company_id' => $company->id,
                'ihale_id' => null,
                'rating' => (int) rand(3, 5),
                'comment' => $comments[array_rand($comments)],
            ]);
            $created++;
        }
    }
}
