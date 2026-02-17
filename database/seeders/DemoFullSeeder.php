<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Ihale;
use App\Models\Teklif;
use App\Models\User;
use App\Models\YukIlani;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Tasarım görmek için örnek veri: 40 müşteri, 40 nakliye firması,
 * 40 ihale (her birine teklifler), 10 blog yazısı, 60 defter kaydı.
 */
class DemoFullSeeder extends Seeder
{
    private const PASSWORD = 'password';

    private array $iller = [
        'İstanbul', 'Ankara', 'İzmir', 'Bursa', 'Antalya', 'Adana', 'Konya', 'Gaziantep',
        'Kocaeli', 'Mersin', 'Diyarbakır', 'Kayseri', 'Eskişehir', 'Şanlıurfa', 'Malatya',
        'Samsun', 'Trabzon', 'Manisa', 'Balıkesir', 'Kahramanmaraş', 'Van', 'Aydın',
        'Denizli', 'Sakarya', 'Tekirdağ', 'Muğla', 'Çanakkale', 'Edirne', 'Kırklareli',
    ];

    private array $musteriAdlari = [
        'Ayşe Yılmaz', 'Mehmet Kaya', 'Fatma Demir', 'Ali Özkan', 'Zeynep Arslan', 'Mustafa Çelik',
        'Elif Şahin', 'Hüseyin Aydın', 'Merve Koç', 'Emre Yıldız', 'Selin Öztürk', 'Burak Kılıç',
        'Deniz Polat', 'Ceren Acar', 'Can Arslan', 'Ece Koçak', 'Barış Özdemir', 'İrem Yalçın',
        'Oğuz Güneş', 'Seda Erdoğan', 'Kaan Aslan', 'Derya Kurt', 'Efe Bayrak', 'Gamze Öz',
        'Berk Şimşek', 'Aslı Çetin', 'Emre Doğan', 'Pınar Korkmaz', 'Serkan Yılmaz', 'Melis Akyüz',
        'Volkan Tekin', 'Burcu Özer', 'Onur Güler', 'Tuğçe Demirci', 'Uğur Koç', 'Esra Karaca',
        'Murat Aksoy', 'Özlem Yıldırım', 'Koray Şen', 'Gülay Özkan',
    ];

    private array $firmaAdlari = [
        'Demir Nakliyat', 'Çelik Taşımacılık', 'Özkan Evden Eve', 'Yıldız Nakliye', 'Aslan Taşıma',
        'Şimşek Lojistik', 'Doğan Nakliyat', 'Kaya Taşımacılık', 'Polat Nakliye', 'Acar Lojistik',
        'Koçak Nakliyat', 'Özdemir Taşıma', 'Yalçın Evden Eve', 'Güneş Nakliye', 'Erdoğan Lojistik',
        'Aslan Taşımacılık', 'Kurt Nakliyat', 'Bayrak Taşıma', 'Öz Nakliye', 'Şimşek Evden Eve',
        'Çetin Lojistik', 'Doğan Nakliyat', 'Korkmaz Taşıma', 'Yılmaz Nakliye', 'Akyüz Lojistik',
        'Tekin Evden Eve', 'Özer Nakliyat', 'Güler Taşımacılık', 'Demirci Nakliye', 'Koç Lojistik',
        'Karaca Taşıma', 'Aksoy Nakliyat', 'Yıldırım Evden Eve', 'Şen Nakliye', 'Özkan Lojistik',
        'Hızlı Nakliyat', 'Güven Taşıma', 'Express Nakliye', 'Mobil Lojistik', 'Şehir Nakliyat',
    ];

    private array $odaTipleri = ['1+1', '2+1', '3+1', '4+1', 'Stüdyo'];

    private array $yukTipleri = ['Palet', 'Koli', 'Ev eşyası', 'Beyaz eşya', 'Mobilya', 'Ofis malzemesi', 'Parça yük'];

    private array $aracTipleri = ['Kamyonet', 'Panelvan', 'Kapalı kasa', 'TIR', 'Kamyon', 'Lowbed'];

    public function run(): void
    {
        $password = Hash::make(self::PASSWORD);

        // 40 Müşteri
        $musteriler = $this->ensureMusteriler($password);

        // 40 Nakliye firması (user + company)
        $firmalar = $this->ensureFirmalar($password);

        // 40 İhale + her birine 2-6 teklif
        $this->ensureIhalelerVeTeklifler($musteriler, $firmalar);

        // 60 Defter kaydı (yük ilanı)
        $this->ensureDefterKayitlari($firmalar);
    }

    private function ensureMusteriler(string $password): array
    {
        $musteriler = User::where('role', 'musteri')->get()->keyBy('id')->all();
        $need = 40 - count($musteriler);
        if ($need <= 0) {
            return array_slice(User::where('role', 'musteri')->orderBy('id')->get()->all(), 0, 40);
        }
        for ($i = 0; $i < $need; $i++) {
            $idx = count($musteriler) + $i;
            $name = $this->musteriAdlari[$idx % count($this->musteriAdlari)];
            $email = 'demo-musteri-' . ($idx + 1) . '@nakliyepark.test';
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => $password,
                    'role' => 'musteri',
                    'phone' => '5' . rand(30, 59) . ' ' . rand(100, 999) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                ]
            );
            $musteriler[$user->id] = $user;
        }
        return array_slice(User::where('role', 'musteri')->orderBy('id')->get()->all(), 0, 40);
    }

    private function ensureFirmalar(string $password): array
    {
        $companies = Company::with('user')->get();
        $need = 40 - $companies->count();
        if ($need > 0) {
            $iller = $this->iller;
            for ($i = 0; $i < $need; $i++) {
                $idx = $companies->count() + $i;
                $email = 'demo-firma-' . ($idx + 1) . '@nakliyepark.test';
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $this->firmaAdlari[$idx % count($this->firmaAdlari)] . ' Yetkilisi',
                        'password' => $password,
                        'role' => 'nakliyeci',
                        'phone' => '0' . rand(212, 432) . ' ' . rand(100, 999) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                    ]
                );
                $city = $iller[array_rand($iller)];
                Company::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $this->firmaAdlari[$idx % count($this->firmaAdlari)],
                        'tax_number' => (string) (1000000000 + $idx * 1111),
                        'tax_office' => $city . ' Vergi Dairesi',
                        'address' => 'Örnek Mah. Demo Sok. No:' . ($idx + 1),
                        'city' => $city,
                        'district' => ['Merkez', 'Kadıköy', 'Çankaya', 'Konak', 'Nilüfer'][$idx % 5],
                        'phone' => '0' . rand(212, 432) . ' ' . rand(100, 999) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                        'phone_2' => '5' . rand(30, 59) . ' ' . rand(100, 999) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                        'whatsapp' => '5' . rand(30, 59) . ' ' . rand(100, 999) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                        'email' => 'info@demo' . ($idx + 1) . '-nakliye.test',
                        'description' => 'Evden eve nakliyat, şehirler arası taşımacılık. Sigortalı ve güvenilir hizmet.',
                        'approved_at' => now(),
                    ]
                );
            }
        }
        return Company::with('user')->orderBy('id')->get()->all();
    }

    private function ensureIhalelerVeTeklifler(array $musteriler, array $firmalar): void
    {
        $ihaleSayisi = Ihale::count();
        $hedef = 40;
        if ($ihaleSayisi >= $hedef) {
            return;
        }
        $serviceTypes = [
            Ihale::SERVICE_EVDEN_EVE,
            Ihale::SERVICE_SEHIRLERARASI,
            Ihale::SERVICE_OFIS,
            Ihale::SERVICE_PARCA_ESYA,
        ];
        for ($i = $ihaleSayisi; $i < $hedef; $i++) {
            $fromCity = $this->iller[array_rand($this->iller)];
            do {
                $toCity = $this->iller[array_rand($this->iller)];
            } while ($toCity === $fromCity);
            $musteri = $musteriler[array_rand($musteriler)];
            $moveDate = now()->addDays(rand(3, 90));
            $volume = (float) [20, 25, 30, 35, 40, 45, 50, 60, 70][array_rand([20, 25, 30, 35, 40, 45, 50, 60, 70])];
            $roomType = $this->odaTipleri[array_rand($this->odaTipleri)];
            $statuses = ['published', 'published', 'published', 'closed', 'completed'];
            $ihale = Ihale::create([
                'user_id' => $musteri->id,
                'guest_contact_name' => null,
                'guest_contact_email' => null,
                'guest_contact_phone' => null,
                'service_type' => $serviceTypes[array_rand($serviceTypes)],
                'room_type' => $roomType,
                'from_city' => $fromCity,
                'from_address' => null,
                'from_district' => null,
                'from_neighborhood' => null,
                'from_postal_code' => null,
                'to_city' => $toCity,
                'to_address' => null,
                'to_district' => null,
                'to_neighborhood' => null,
                'to_postal_code' => null,
                'distance_km' => round(rand(50, 1200) + (rand(0, 99) / 100), 2),
                'move_date' => $moveDate,
                'move_date_end' => rand(0, 1) ? $moveDate->copy()->addDays(2) : null,
                'volume_m3' => $volume,
                'description' => $roomType . ' ev eşyası. ' . ['Buzdolabı, çamaşır makinesi.', 'Mobilya ve beyaz eşya.', 'Tam donanımlı ev.'][$i % 3],
                'status' => $statuses[array_rand($statuses)],
            ]);
            $teklifSayisi = rand(2, min(6, count($firmalar)));
            $indices = array_keys($firmalar);
            shuffle($indices);
            $secilenIndices = array_slice($indices, 0, $teklifSayisi);
            foreach ($secilenIndices as $companyIndex) {
                $company = $firmalar[$companyIndex] ?? null;
                if (! $company instanceof Company) {
                    continue;
                }
                $baseAmount = (int) ($volume * (rand(250, 450)));
                Teklif::firstOrCreate(
                    [
                        'ihale_id' => $ihale->id,
                        'company_id' => $company->id,
                    ],
                    [
                        'amount' => $baseAmount + rand(-2000, 5000),
                        'message' => ['Sigorta dahil.', 'Paketleme hizmeti sunuyoruz.', 'Tarih uygun.', null][rand(0, 3)],
                        'status' => ['pending', 'pending', 'accepted', 'rejected'][rand(0, 3)],
                    ]
                );
            }
        }
    }

    private function ensureDefterKayitlari(array $firmalar): void
    {
        $mevcut = YukIlani::count();
        $hedef = 60;
        if ($mevcut >= $hedef) {
            return;
        }
        for ($i = $mevcut; $i < $hedef; $i++) {
            $company = $firmalar[array_rand($firmalar)];
            if (! $company instanceof Company) {
                continue;
            }
            $fromCity = $this->iller[array_rand($this->iller)];
            do {
                $toCity = $this->iller[array_rand($this->iller)];
            } while ($toCity === $fromCity);
            YukIlani::create([
                'company_id' => $company->id,
                'from_city' => $fromCity,
                'to_city' => $toCity,
                'load_type' => $this->yukTipleri[array_rand($this->yukTipleri)],
                'load_date' => now()->addDays(rand(1, 60)),
                'volume_m3' => (float) rand(15, 80),
                'vehicle_type' => $this->aracTipleri[array_rand($this->aracTipleri)],
                'description' => $fromCity . '-' . $toCity . ' güzergahında yük paylaşımı veya boş kapasite. İletişime geçin.',
                'status' => 'active',
            ]);
        }
    }
}
