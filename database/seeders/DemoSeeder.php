<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Company;
use App\Models\Faq;
use App\Models\Ihale;
use App\Models\User;
use App\Models\YukIlani;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // Müşteriler
        $musteri1 = User::firstOrCreate(
            ['email' => 'musteri@nakliyepark.test'],
            ['name' => 'Ayşe Yılmaz', 'password' => $password, 'role' => 'musteri', 'phone' => '532 111 22 33']
        );
        $musteri2 = User::firstOrCreate(
            ['email' => 'ahmet@nakliyepark.test'],
            ['name' => 'Ahmet Kaya', 'password' => $password, 'role' => 'musteri', 'phone' => '533 444 55 66']
        );

        // Nakliyeciler
        $nakliyeci1 = User::firstOrCreate(
            ['email' => 'firma@nakliyepark.test'],
            ['name' => 'Mehmet Demir', 'password' => $password, 'role' => 'nakliyeci', 'phone' => '534 777 88 99']
        );
        $nakliyeci2 = User::firstOrCreate(
            ['email' => 'tasima@nakliyepark.test'],
            ['name' => 'Fatma Çelik', 'password' => $password, 'role' => 'nakliyeci', 'phone' => '535 000 11 22']
        );
        $nakliyeci3 = User::firstOrCreate(
            ['email' => 'evden@nakliyepark.test'],
            ['name' => 'Ali Özkan', 'password' => $password, 'role' => 'nakliyeci', 'phone' => '536 333 44 55']
        );

        // Firmalar (nakliyeci başına bir firma)
        $firmalar = [
            [
                'user_id' => $nakliyeci1->id,
                'name' => 'Demir Nakliyat',
                'tax_number' => '1234567890',
                'tax_office' => 'Kadıköy Vergi Dairesi',
                'address' => 'Organize Sanayi Bölgesi 5. Cadde No:12',
                'city' => 'İstanbul',
                'district' => 'Kadıköy',
                'phone' => '0212 555 12 34',
                'phone_2' => '532 111 22 33',
                'whatsapp' => '532 111 22 33',
                'email' => 'info@demirnakliyat.com',
                'description' => 'Evden eve nakliyat, ofis taşıma ve eşya depolama hizmetleri. 15 yıllık tecrübe. Sigortalı taşıma.',
                'approved_at' => now(),
            ],
            [
                'user_id' => $nakliyeci2->id,
                'name' => 'Çelik Taşımacılık',
                'tax_number' => '9876543210',
                'tax_office' => 'Çankaya VD',
                'address' => 'Atatürk Mah. 100. Yıl Bulvarı No:45',
                'city' => 'Ankara',
                'phone' => '0312 444 56 78',
                'phone_2' => '533 444 55 66',
                'whatsapp' => '533 444 55 66',
                'email' => 'info@celiktasimacilik.com',
                'description' => 'Şehirler arası ve şehir içi nakliyat. Paletli ve koli taşıma. Profesyonel paketleme.',
                'approved_at' => now(),
            ],
            [
                'user_id' => $nakliyeci3->id,
                'name' => 'Özkan Evden Eve',
                'tax_number' => '5555555555',
                'tax_office' => 'Konak VD',
                'address' => 'Kordon Boyu No:78',
                'city' => 'İzmir',
                'phone' => '0232 333 22 11',
                'whatsapp' => '536 333 44 55',
                'email' => 'info@ozkanevdeneeve.com',
                'description' => 'Ev taşıma, büro taşıma, kurumsal nakliye. Asansörlü bina deneyimi.',
                'approved_at' => now(),
            ],
        ];

        foreach ($firmalar as $f) {
            Company::updateOrCreate(
                ['user_id' => $f['user_id']],
                $f
            );
        }

        $company1 = Company::where('user_id', $nakliyeci1->id)->first();
        $company2 = Company::where('user_id', $nakliyeci2->id)->first();
        $company3 = Company::where('user_id', $nakliyeci3->id)->first();

        // Örnek ihaleler (müşteri + misafir)
        $ihaleler = [
            ['user_id' => $musteri1->id, 'from_city' => 'İstanbul', 'to_city' => 'Ankara', 'volume_m3' => 45, 'move_date' => now()->addDays(14), 'description' => '3+1 ev eşyası. Buzdolabı, çamaşır makinesi, koltuk takımı.', 'status' => 'published'],
            ['user_id' => $musteri2->id, 'from_city' => 'Ankara', 'to_city' => 'İzmir', 'volume_m3' => 30, 'move_date' => now()->addDays(21), 'description' => '2+1 daire. Beyaz eşya ve mobilya.', 'status' => 'published'],
            ['user_id' => null, 'guest_contact_name' => 'Zeynep Arslan', 'guest_contact_email' => 'zeynep@test.com', 'guest_contact_phone' => '537 999 00 11', 'from_city' => 'İzmir', 'to_city' => 'Bursa', 'volume_m3' => 25, 'move_date' => now()->addDays(30), 'description' => '1+1 taşınma. Az eşya.', 'status' => 'published'],
            ['user_id' => $musteri1->id, 'from_city' => 'İstanbul', 'to_city' => 'Antalya', 'volume_m3' => 60, 'move_date' => now()->addDays(45), 'description' => '4+1 villa eşyası. Havuz ekipmanı dahil.', 'status' => 'published'],
            ['user_id' => null, 'guest_contact_name' => 'Can Öztürk', 'guest_contact_email' => 'can@test.com', 'from_city' => 'Bursa', 'to_city' => 'İstanbul', 'volume_m3' => 35, 'move_date' => null, 'description' => 'Ofis taşıma. 20 koli dosya.', 'status' => 'published'],
        ];

        if (Ihale::count() < 5) {
            foreach ($ihaleler as $i) {
                Ihale::create(array_merge($i, ['distance_km' => null]));
            }
        }

        // Yük ilanları (Defter)
        $defter = [
            ['company_id' => $company1->id, 'from_city' => 'İstanbul', 'to_city' => 'Ankara', 'load_type' => 'Palet', 'load_date' => now()->addDays(3), 'volume_m3' => 33, 'vehicle_type' => 'Kamyonet', 'description' => 'Yarım yük. İstanbul-Ankara arası paylaşılacak.', 'status' => 'active'],
            ['company_id' => $company2->id, 'from_city' => 'Ankara', 'to_city' => 'İzmir', 'load_type' => 'Koli', 'load_date' => now()->addDays(5), 'volume_m3' => 20, 'vehicle_type' => 'Panelvan', 'description' => 'Döküman ve koli. Aynı gün teslim.', 'status' => 'active'],
            ['company_id' => $company3->id, 'from_city' => 'İzmir', 'to_city' => 'Bursa', 'load_type' => 'Ev eşyası', 'load_date' => now()->addDays(7), 'volume_m3' => 40, 'vehicle_type' => 'Kapalı kasa', 'description' => 'Ev taşıma artığı. Yük paylaşımı aranıyor.', 'status' => 'active'],
            ['company_id' => $company1->id, 'from_city' => 'İstanbul', 'to_city' => 'Antalya', 'load_type' => 'Palet', 'load_date' => now()->addDays(10), 'volume_m3' => 50, 'vehicle_type' => 'TIR', 'description' => 'Tam yük. Fiyat paylaşımı yapılabilir.', 'status' => 'active'],
        ];

        foreach ($defter as $d) {
            if (YukIlani::where('company_id', $d['company_id'])->where('from_city', $d['from_city'])->where('to_city', $d['to_city'])->exists()) {
                continue;
            }
            YukIlani::create($d);
        }

        // Blog yazıları
        $blog = [
            ['title' => 'Ev Taşırken Dikkat Edilmesi Gerekenler', 'slug' => 'ev-tasirken-dikkat-edilmesi-gerekenler', 'excerpt' => 'Taşınma öncesi ve sonrası yapılacaklar listesi.', 'content' => "Taşınmadan önce eşyalarınızı kategorilere ayırın. Kırılacak eşyaları özel kutularda paketleyin. Nakliye firması ile taşınma tarihini netleştirin. Sigorta seçeneklerini değerlendirin.\n\nTaşınma günü su, elektrik ve doğalgaz aboneliklerini yeni adrese taşıyın. Eşya listesini kontrol ederek teslim alın.", 'published_at' => now()->subDays(5)],
            ['title' => 'Nakliyat Fiyatları Nasıl Hesaplanır?', 'slug' => 'nakliyat-fiyatlari-nasil-hesaplanir', 'excerpt' => 'Hacim, mesafe ve ek hizmetlerin fiyata etkisi.', 'content' => "Nakliyat fiyatları genelde taşınacak hacim (m³), mesafe ve ek hizmetlere (paketleme, sigorta) göre belirlenir.\n\nNakliyePark üzerinden ücretsiz ihale açarak birkaç firmadan teklif alabilir, en uygun fiyatı seçebilirsiniz.", 'published_at' => now()->subDays(3)],
            ['title' => 'Şehirler Arası Taşınma Rehberi', 'slug' => 'sehirler-arasi-tasinma-rehberi', 'excerpt' => 'İller arası nakliyette bilmeniz gerekenler.', 'content' => "Şehirler arası taşımada süre ve güzergah önemlidir. Firmalar genelde tek gün veya iki günlük program sunar.\n\nBüyük eşyalar için modüler asansör veya vinç talebi önceden bildirilmelidir.", 'published_at' => now()->subDay()],
        ];

        foreach ($blog as $b) {
            BlogPost::updateOrCreate(
                ['slug' => $b['slug']],
                $b
            );
        }

        // SSS
        $faqs = [
            ['question' => 'Üye olmadan ihale açabilir miyim?', 'answer' => 'Evet. NakliyePark’ta üye olmadan nakliye ihalesi başlatabilir, e-posta ve telefon bilginizle talebinizi oluşturabilirsiniz. Firmalar size dönüş yapacaktır.', 'sort_order' => 1],
            ['question' => 'Fiyatlar nasıl belirleniyor?', 'answer' => 'Firmalar taşınacak hacim, mesafe ve ek hizmetlere göre size özel teklif sunar. Birden fazla teklif alıp karşılaştırabilirsiniz.', 'sort_order' => 2],
            ['question' => 'Nakliyat Defteri nedir?', 'answer' => 'Nakliye firmalarının yük paylaşımı veya boş kapasite ilanı verdiği alandır. Aynı güzergahta yük birleştirme imkânı sunar.', 'sort_order' => 3],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}
