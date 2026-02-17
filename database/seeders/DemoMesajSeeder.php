<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\ContactMessage;
use App\Models\Ihale;
use App\Models\Teklif;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Müşteri panelindeki "Gelen Mesajlar" sayfasında örnek veri görünsün diye
 * musteri@nakliyepark.test kullanıcısı için nakliyeci (firma@) tarafından
 * gönderilmiş örnek mesajlar ekler.
 */
class DemoMesajSeeder extends Seeder
{
    private const MUSTERI_EMAIL = 'musteri@nakliyepark.test';

    private const NAKLIYECI_EMAIL = 'firma@nakliyepark.test';

    public function run(): void
    {
        $musteri = User::where('email', self::MUSTERI_EMAIL)->where('role', 'musteri')->first();
        $nakliyeci = User::where('email', self::NAKLIYECI_EMAIL)->where('role', 'nakliyeci')->first();
        $company = $nakliyeci ? Company::where('user_id', $nakliyeci->id)->first() : null;

        if (! $musteri || ! $nakliyeci || ! $company) {
            return;
        }

        $ihale = Ihale::where('user_id', $musteri->id)
            ->whereHas('teklifler', fn ($q) => $q->where('company_id', $company->id)->where('status', 'accepted'))
            ->first();

        if (! $ihale) {
            $ihale = $this->createIhaleWithAcceptedTeklif($musteri, $company);
        }

        $acceptedTeklif = $ihale?->teklifler()->where('company_id', $company->id)->where('status', 'accepted')->first();
        $alreadyEnough = $ihale && ContactMessage::where('ihale_id', $ihale->id)->where('from_user_id', $nakliyeci->id)->count() >= 3;

        if (! $acceptedTeklif || $alreadyEnough) {
            return;
        }

        $ornekMesajlar = [
            'Merhaba, teklifimizi kabul ettiğiniz için teşekkür ederiz. Taşınma tarihiniz için uygunuz. Detayları bu hafta içinde netleştirebiliriz.',
            'Taşınma günü sabah 08:00’da adresinizde olacağız. Özel eşya veya kırılacak eşya varsa önceden belirtirseniz ekstra özen gösteririz.',
            'Paketleme malzemesi (koli, streç, köşe koruyucu) firmamız tarafından sağlanacaktır. Ek bir ücret yok. Sorunuz olursa yazabilirsiniz.',
        ];

        foreach ($ornekMesajlar as $metin) {
            ContactMessage::firstOrCreate(
                [
                    'ihale_id' => $ihale->id,
                    'teklif_id' => $acceptedTeklif->id,
                    'from_user_id' => $nakliyeci->id,
                    'company_id' => $company->id,
                    'message' => $metin,
                ],
                []
            );
        }
    }

    private function createIhaleWithAcceptedTeklif(User $musteri, Company $company): ?Ihale
    {
        $ihale = Ihale::create([
            'user_id' => $musteri->id,
            'guest_contact_name' => null,
            'guest_contact_email' => null,
            'guest_contact_phone' => null,
            'service_type' => Ihale::SERVICE_EVDEN_EVE,
            'room_type' => '3+1',
            'from_city' => 'İstanbul',
            'from_address' => null,
            'from_district' => null,
            'from_neighborhood' => null,
            'from_postal_code' => null,
            'to_city' => 'Ankara',
            'to_address' => null,
            'to_district' => null,
            'to_neighborhood' => null,
            'to_postal_code' => null,
            'distance_km' => 450.5,
            'move_date' => now()->addDays(14),
            'move_date_end' => null,
            'volume_m3' => 45,
            'description' => '3+1 ev eşyası. Buzdolabı, çamaşır makinesi, koltuk takımı. Demo mesaj testi için oluşturuldu.',
            'status' => 'published',
        ]);

        Teklif::create([
            'ihale_id' => $ihale->id,
            'company_id' => $company->id,
            'amount' => 18500,
            'message' => 'Sigorta ve paketleme dahil. Tarih uygundur.',
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        return $ihale;
    }
}
