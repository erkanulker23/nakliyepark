<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $musteri = [
            ['question' => 'Üye olmadan ihale açabilir miyim?', 'answer' => 'Evet. NakliyePark\'ta üye olmadan nakliye ihalesi başlatabilir, e-posta ve telefon bilginizle talebinizi oluşturabilirsiniz. Firmalar size dönüş yapacaktır.', 'sort_order' => 1, 'audience' => 'musteri'],
            ['question' => 'Fiyatlar nasıl belirleniyor?', 'answer' => 'Firmalar taşınacak hacim, mesafe ve ek hizmetlere göre size özel teklif sunar. Birden fazla teklif alıp karşılaştırabilirsiniz.', 'sort_order' => 2, 'audience' => 'musteri'],
            ['question' => 'İhale nasıl açılır?', 'answer' => 'Ana sayfadaki "İhale Başlat" veya Araçlar bölümündeki hacim hesaplama ile taşınacak eşyayı seçin, çıkış ve varış bilgilerini girin. Talebiniz admin onayından sonra yayına alınır; firmalar size teklif gönderir.', 'sort_order' => 3, 'audience' => 'musteri'],
            ['question' => 'Teklifleri nasıl karşılaştırırım?', 'answer' => 'Giriş yaptıktan sonra "İhalelerim" bölümünden talebinize gelen teklifleri görebilir, fiyat ve firma bilgilerini inceleyerek birini kabul edebilirsiniz.', 'sort_order' => 4, 'audience' => 'musteri'],
            ['question' => 'Taşınma tarihimi değiştirebilir miyim?', 'answer' => 'Kabul ettiğiniz firmayla doğrudan iletişime geçerek tarih ve detayları birlikte güncelleyebilirsiniz.', 'sort_order' => 5, 'audience' => 'musteri'],
        ];
        $nakliyeci = [
            ['question' => 'Nasıl firma olarak kayıt olurum?', 'answer' => 'Sağ üstten "Hizmet ver" veya "Üyelik oluştur" ile kayıt olun, rol olarak nakliyeci/firma seçin. Firma bilgilerinizi doldurduktan sonra admin onayı ile yayına alınırsınız.', 'sort_order' => 10, 'audience' => 'nakliyeci'],
            ['question' => 'İhalelere teklif vermek ücretli mi?', 'answer' => 'Kayıt ve onay sonrası seçtiğiniz pakete göre aylık belirli sayıda teklif hakkınız olur. Paketler arasında Başlangıç, Profesyonel ve Kurumsal seçenekleri bulunur.', 'sort_order' => 11, 'audience' => 'nakliyeci'],
            ['question' => 'Komisyon oranı nedir?', 'answer' => 'Kabul edilen tekliflerinizden platform komisyonu alınır. Güncel oran ve paket detayları için kayıt sonrası panelinizden veya destekten bilgi alabilirsiniz.', 'sort_order' => 12, 'audience' => 'nakliyeci'],
            ['question' => 'İhaleleri nasıl görürüm?', 'answer' => 'Giriş yaptıktan sonra "İhaleler" sayfasından açık ihaleleri filtreleyebilir, hacim ve güzergaha göre inceleyip teklif verebilirsiniz.', 'sort_order' => 13, 'audience' => 'nakliyeci'],
            ['question' => 'Nakliyat Defteri nedir?', 'answer' => 'Nakliye firmalarının yük paylaşımı veya boş kapasite ilanı verdiği alandır. Aynı güzergahta yük birleştirme veya dönüş ilanı verebilirsiniz.', 'sort_order' => 14, 'audience' => 'nakliyeci'],
        ];

        foreach (array_merge($musteri, $nakliyeci) as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }

        // Mevcut "Nakliyat Defteri nedir?" ve diğer eski kayıtları müşteri/nakliyeciye göre güncelle (eşleşmeyen soruları musteri yap)
        $allQuestions = array_merge(array_column($musteri, 'question'), array_column($nakliyeci, 'question'));
        Faq::whereNull('audience')->whereNotIn('question', $allQuestions)->update(['audience' => 'musteri']);
    }
}
