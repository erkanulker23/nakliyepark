<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /** Anasayfada gösterilecek örnek müşteri memnuniyet videoları (harici URL). */
    private const VIDEO_SAMPLE_URLS = [
        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4',
    ];

    private const VIDEO_COMMENTS = [
        'Çok profesyonel ekip, eşyalarımız hiç zarar görmeden taşındı. Videoda anlattım.',
        'Zamanında ve titiz çalıştılar. Fiyat performans açısından çok memnunum, herkese öneririm.',
        'İlk kez nakliye firması kullandım, her şey sorunsuz geçti. Deneyimimi paylaştım.',
        'Paketleme ve taşıma konusunda çok dikkatliler. Teşekkür ederim, kesinlikle tekrar tercih ederim.',
        'Fiyat uygundu, hizmet kaliteli. Eşyalarımız güvende hissettik. Memnuniyetimi videoda anlattım.',
    ];

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

        // Anasayfa için örnek müşteri memnuniyet videoları (video_path dolu)
        $videoUrlCount = count(self::VIDEO_SAMPLE_URLS);
        $videoCommentCount = count(self::VIDEO_COMMENTS);
        for ($i = 0; $i < min(6, $companies->count(), $musteriler->count()); $i++) {
            $company = $companies->get($i % $companies->count());
            $user = $musteriler->get($i % $musteriler->count());
            $exists = Review::where('company_id', $company->id)->where('user_id', $user->id)->whereNotNull('video_path')->exists();
            if ($exists) {
                continue;
            }
            Review::create([
                'user_id' => $user->id,
                'company_id' => $company->id,
                'ihale_id' => null,
                'rating' => 5,
                'comment' => self::VIDEO_COMMENTS[$i % $videoCommentCount],
                'video_path' => self::VIDEO_SAMPLE_URLS[$i % $videoUrlCount],
            ]);
        }

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
