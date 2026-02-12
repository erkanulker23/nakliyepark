<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\DefterYaniti;
use App\Models\YukIlani;
use Illuminate\Database\Seeder;

class DefterYanitiSeeder extends Seeder
{
    /** Örnek yanıt metinleri (defter ilanlarına nakliyeci cevapları) */
    private array $ornekYanitlar = [
        'Bu güzergahta o gün boşum, yük birleştirebiliriz. İletişime geçin.',
        'Aynı tarihte Ankara\'ya gideceğim, yarım kamyon paylaşabiliriz.',
        'Biz de o hafta İzmir-Bursa yapıyoruz, dönüşte birlikte değerlendirebiliriz.',
        'Paletli taşıma yapıyoruz, bu yükü entegre edebiliriz. Fiyat teklifi vereyim mi?',
        'Panelvan ile aynı güzergahtayız, koli kısmını paylaşmak ister misiniz?',
        'Tam yükümüz yok, 20-25 m³ ekleyebiliriz. Detay için arayalım.',
        'Dönüş boş gidiyoruz, anlaşırsak maliyet düşer.',
        'Bu rota bizim sık kullandığımız güzergah, yük birleştirme yapalım.',
    ];

    public function run(): void
    {
        $ilanlar = YukIlani::where('status', 'active')
            ->with('company')
            ->latest()
            ->take(10)
            ->get();

        $companies = Company::whereNotNull('approved_at')->get();
        if ($companies->count() < 2) {
            return;
        }

        $yanitIndex = 0;
        foreach ($ilanlar as $ilan) {
            // Bu ilana zaten yanıt varsa atla (tekrar seed'de çoğalmasın)
            if (DefterYaniti::where('yuk_ilani_id', $ilan->id)->exists()) {
                continue;
            }

            $digerFirmalar = $companies->where('id', '!=', $ilan->company_id)->values();
            if ($digerFirmalar->isEmpty()) {
                continue;
            }

            // İlana 1 veya 2 örnek yanıt ekle
            $adet = min(2, $digerFirmalar->count());
            for ($i = 0; $i < $adet; $i++) {
                $firma = $digerFirmalar->get($i);
                if (! $firma) {
                    break;
                }
                $body = $this->ornekYanitlar[$yanitIndex % count($this->ornekYanitlar)];
                $yanitIndex++;

                DefterYaniti::create([
                    'yuk_ilani_id' => $ilan->id,
                    'company_id' => $firma->id,
                    'body' => $body,
                ]);
            }
        }
    }
}
