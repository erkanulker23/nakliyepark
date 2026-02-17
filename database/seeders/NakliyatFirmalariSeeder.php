<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Gerçek nakliyat firmalarını projeye ekler.
 * Kaynak: CihanTrans, Rıfatoğlu, Eczacıoğlu, Rota Nakliyat, Değirmencioğlu
 */
class NakliyatFirmalariSeeder extends Seeder
{
    private const PASSWORD = 'password';

    public function run(): void
    {
        $password = Hash::make(self::PASSWORD);

        $firmalar = [
            [
                'user' => [
                    'email' => 'cihantrans@nakliyepark.firma',
                    'name' => 'CihanTrans Yetkilisi',
                    'phone' => '0534 733 69 04',
                ],
                'company' => [
                    'name' => 'CihanTrans',
                    'package' => 'kurumsal',
                    'tax_number' => null,
                    'tax_office' => null,
                    'address' => 'Bağlarbaşı, Cemal Bey Cd. No:3, 34844 Maltepe/İstanbul',
                    'city' => 'İstanbul',
                    'district' => 'Maltepe',
                    'phone' => '0534 733 69 04',
                    'phone_2' => null,
                    'whatsapp' => '0534 733 69 04',
                    'email' => 'cihantrans@gmail.com',
                    'description' => 'Evden eve nakliyat, şehiriçi nakliyat, şehirlerarası nakliyat, parça eşya taşıma, uluslararası nakliyat, eşya depolama. Hastane, fabrika, kurum, banka, fuar ve ofis taşımacılığı. Antalya, İstanbul ve İzmir şubeleri.',
                    'services' => ['evden_eve_nakliyat', 'sehirlerarasi_nakliyat', 'ofis_tasima', 'esya_depolama', 'uluslararasi_nakliyat'],
                ],
            ],
            [
                'user' => [
                    'email' => 'rifatoglu@nakliyepark.firma',
                    'name' => 'Rıfatoğlu Yetkilisi',
                    'phone' => '0532 234 24 35',
                ],
                'company' => [
                    'name' => 'Rıfatoğlu Depolama',
                    'package' => 'profesyonel',
                    'tax_number' => null,
                    'tax_office' => null,
                    'address' => 'İnönü Mah. Kayışdağı Cad. Zümrüt Sok. No:2/5 Ataşehir/İstanbul',
                    'city' => 'İstanbul',
                    'district' => 'Ataşehir',
                    'phone' => '444 75 24',
                    'phone_2' => '0532 234 24 35',
                    'whatsapp' => '444 75 24',
                    'email' => 'info@rifatoglunakliyat.com.tr',
                    'description' => 'Kısa vadeli depolama, uzun vadeli depolama, iklim kontrollü depolama. Ev eşyası depolama, ofis mobilyası depolama, ofis arşiv depolama, mobilya depolama. Sanat ve antika depolama, paketleme ve ambalajlama, nakliye ve taşıma, sigortalı depolama hizmeti.',
                    'services' => ['esya_depolama', 'evden_eve_nakliyat'],
                ],
            ],
            [
                'user' => [
                    'email' => 'eczacioglu@nakliyepark.firma',
                    'name' => 'Eczacıoğlu Yetkilisi',
                    'phone' => '0532 406 56 19',
                ],
                'company' => [
                    'name' => 'Eczacıoğlu Nakliyat',
                    'package' => 'kurumsal',
                    'tax_number' => null,
                    'tax_office' => null,
                    'address' => 'Altayçeşme Mh. Atatürk Cd. No:89/7 Maltepe',
                    'city' => 'İstanbul',
                    'district' => 'Maltepe',
                    'phone' => '0532 406 56 19',
                    'phone_2' => null,
                    'whatsapp' => '0532 406 56 19',
                    'email' => 'info@eczacioglunakliyat.com',
                    'description' => 'Evden eve nakliyat, arşiv taşımacılığı, müze taşımacılığı, üniversite taşımacılığı, banka taşımacılığı, piyano taşımacılığı, şehiriçi ve şehirlerarası nakliyat, kütüphane taşımacılığı. Ev eşyası depolama, kurumsal depolama, ofis eşyası depolama, arşiv depolama. Sigortalı ofis taşıma, kurum taşımacılığı, uluslararası ev eşyası taşıma.',
                    'services' => ['evden_eve_nakliyat', 'sehirlerarasi_nakliyat', 'ofis_tasima', 'esya_depolama', 'uluslararasi_nakliyat'],
                ],
            ],
            [
                'user' => [
                    'email' => 'rotanakliyat@nakliyepark.firma',
                    'name' => 'Rota Nakliyat Yetkilisi',
                    'phone' => '0532 367 36 01',
                ],
                'company' => [
                    'name' => 'Rota Nakliyat',
                    'package' => 'profesyonel',
                    'tax_number' => '7351454229',
                    'tax_office' => 'Kozyatağı VD',
                    'address' => 'İnönü Mah. Yazıcı Sok. 1/1 Ataşehir/İstanbul',
                    'city' => 'İstanbul',
                    'district' => 'Ataşehir',
                    'phone' => '444 85 74',
                    'phone_2' => '0532 367 36 01',
                    'whatsapp' => '0532 367 36 01',
                    'email' => 'info@rotanakliyat.com.tr',
                    'description' => 'Ev eşyası taşımacılığı, ofis eşyası taşımacılığı, şehirler arası nakliyat, asansörlü nakliyat. Depolama hizmetleri.',
                    'services' => ['evden_eve_nakliyat', 'sehirlerarasi_nakliyat', 'ofis_tasima', 'esya_depolama'],
                ],
            ],
            [
                'user' => [
                    'email' => 'degirmencioglu@nakliyepark.firma',
                    'name' => 'Değirmencioğlu Yetkilisi',
                    'phone' => '0533 484 77 06',
                ],
                'company' => [
                    'name' => 'Değirmencioğlu Nakliyat',
                    'package' => 'profesyonel',
                    'tax_number' => null,
                    'tax_office' => null,
                    'address' => 'Uğurmumcu Mah. Akşemsettin Cad. No:61',
                    'city' => 'İstanbul',
                    'district' => null,
                    'phone' => '0533 484 77 06',
                    'phone_2' => null,
                    'whatsapp' => '0533 484 77 06',
                    'email' => 'degirmencioglunakliye@gmail.com',
                    'description' => 'Evden eve nakliyat, şehiriçi nakliyat, şehirlerarası nakliyat, parça eşya taşıma. Her gün Ege bölgesine eşya taşıma. Akçay, Edremit, İstanbul, Ayvalık parça eşya taşıma.',
                    'services' => ['evden_eve_nakliyat', 'sehirlerarasi_nakliyat'],
                ],
            ],
        ];

        foreach ($firmalar as $f) {
            $user = User::firstOrCreate(
                ['email' => $f['user']['email']],
                [
                    'name' => $f['user']['name'],
                    'password' => $password,
                    'role' => 'nakliyeci',
                    'phone' => $f['user']['phone'],
                ]
            );

            Company::updateOrCreate(
                ['user_id' => $user->id],
                array_merge($f['company'], [
                    'user_id' => $user->id,
                    'approved_at' => now(),
                ])
            );
        }
    }
}
