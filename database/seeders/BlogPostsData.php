<?php

namespace Database\Seeders;

class BlogPostsData
{
    public static function get(): array
    {
        $posts = [];
        $titles = self::titlesWithCategory();

        foreach ($titles as $item) {
            $title = $item['title'];
            $slug = $item['slug'];
            $cat = $item['category_slug'];
            $posts[] = [
                'title' => $title,
                'slug' => $slug,
                'excerpt' => self::excerptFor($title),
                'meta_title' => $title . ' | NakliyePark Blog',
                'meta_description' => self::metaDescFor($title),
                'content' => self::contentFor($title),
                'category_slug' => $cat,
            ];
        }
        return $posts;
    }

    private static function titlesWithCategory(): array
    {
        return [
            // I. Genel nakliyat
            ['title' => 'Evden eve nakliyat nedir, nasıl yapılır?', 'slug' => 'evden-eve-nakliyat-nedir-nasil-yapilir', 'category_slug' => 'genel-nakliyat'],
            ['title' => 'Ev taşıma fiyatları neye göre belirlenir?', 'slug' => 'ev-tasima-fiyatlari-neye-gore-belirlenir', 'category_slug' => 'genel-nakliyat'],
            ['title' => 'Nakliyat firması seçerken nelere dikkat edilmeli?', 'slug' => 'nakliyat-firmasi-secerken-nelere-dikkat-edilmeli', 'category_slug' => 'genel-nakliyat'],
            ['title' => 'Ev taşırken en sık yapılan hatalar nelerdir?', 'slug' => 'ev-tasirken-en-sik-yapilan-hatalar-nelerdir', 'category_slug' => 'genel-nakliyat'],
            ['title' => 'Sigortalı nakliyat nedir, neden önemlidir?', 'slug' => 'sigortali-nakliyat-nedir-neden-onemlidir', 'category_slug' => 'genel-nakliyat'],
            ['title' => 'Taşınma öncesi yapılması gerekenler nelerdir?', 'slug' => 'tasinma-oncesi-yapilmasi-gerekenler-nelerdir', 'category_slug' => 'genel-nakliyat'],
            ['title' => 'Ev taşıma kaç gün sürer?', 'slug' => 'ev-tasima-kac-gun-surer', 'category_slug' => 'genel-nakliyat'],
            ['title' => 'Nakliyat firmaları fiyat teklifini nasıl hesaplar?', 'slug' => 'nakliyat-firmalari-fiyat-teklifini-nasil-hesaplar', 'category_slug' => 'genel-nakliyat'],
            ['title' => 'Asansörlü taşımacılık nedir, zorunlu mu?', 'slug' => 'asansorlu-tasimacilik-nedir-zorunlu-mu', 'category_slug' => 'genel-nakliyat'],
            ['title' => 'Ev taşırken eşyalar nasıl paketlenir?', 'slug' => 'ev-tasirken-esyalar-nasil-paketlenir', 'category_slug' => 'genel-nakliyat'],
            // II. Fiyat
            ['title' => 'Evden eve nakliyat fiyatları 2026 yılında ne kadar?', 'slug' => 'evden-eve-nakliyat-fiyatlari-2026-yilinda-ne-kadar', 'category_slug' => 'fiyat'],
            ['title' => 'Şehirler arası nakliyat fiyatları nasıl hesaplanır?', 'slug' => 'sehirler-arasi-nakliyat-fiyatlari-nasil-hesaplanir', 'category_slug' => 'fiyat'],
            ['title' => 'Parça eşya taşıma ücretleri ne kadar?', 'slug' => 'parca-esya-tasima-ucretleri-ne-kadar', 'category_slug' => 'fiyat'],
            ['title' => 'Ofis taşıma fiyatları hangi kriterlere göre değişir?', 'slug' => 'ofis-tasima-fiyatlari-hangi-kriterlere-gore-degisir', 'category_slug' => 'fiyat'],
            ['title' => 'Asansörlü nakliyat fiyatı pahalı mı?', 'slug' => 'asansorlu-nakliyat-fiyati-pahali-mi', 'category_slug' => 'fiyat'],
            ['title' => 'Nakliyat firmaları neden farklı fiyat verir?', 'slug' => 'nakliyat-firmalari-neden-farkli-fiyat-verir', 'category_slug' => 'fiyat'],
            ['title' => 'En ucuz nakliyat firması nasıl bulunur?', 'slug' => 'en-ucuz-nakliyat-firmasi-nasil-bulunur', 'category_slug' => 'fiyat'],
            ['title' => 'Taşınma maliyetini düşürmenin yolları nelerdir?', 'slug' => 'tasinma-maliyetini-dusurmenin-yollari-nelerdir', 'category_slug' => 'fiyat'],
            ['title' => 'Ev taşıma fiyat teklifleri neden bu kadar değişken?', 'slug' => 'ev-tasima-fiyat-teklifleri-neden-bu-kadar-degisken', 'category_slug' => 'fiyat'],
            ['title' => 'Nakliyat fiyatı alırken nelere dikkat edilmeli?', 'slug' => 'nakliyat-fiyati-alirken-nelere-dikkat-edilmeli', 'category_slug' => 'fiyat'],
            // III. Güven & Karar
            ['title' => 'Güvenilir nakliyat firması nasıl anlaşılır?', 'slug' => 'guvenilir-nakliyat-firmasi-nasil-anlasilir', 'category_slug' => 'guven-karar'],
            ['title' => 'Nakliyat firması dolandırıcılığı nasıl anlaşılır?', 'slug' => 'nakliyat-firmasi-dolandiriciligi-nasil-anlasilir', 'category_slug' => 'guven-karar'],
            ['title' => 'Nakliyat sözleşmesi yapmadan taşınmak riskli mi?', 'slug' => 'nakliyat-sozlesmesi-yapmadan-tasinmak-riskli-mi', 'category_slug' => 'guven-karar'],
            ['title' => 'Taşınma sırasında eşya zarar görürse ne yapılmalı?', 'slug' => 'tasinma-sirasinda-esya-zarar-gorurse-ne-yapilmali', 'category_slug' => 'guven-karar'],
            ['title' => 'Nakliyat firmasıyla sorun yaşarsam ne yapabilirim?', 'slug' => 'nakliyat-firmasiyla-sorun-yasarsam-ne-yapabilirim', 'category_slug' => 'guven-karar'],
            ['title' => 'Nakliyat firmasıyla anlaşma iptal edilebilir mi?', 'slug' => 'nakliyat-firmasiyla-anlasma-iptal-edilebilir-mi', 'category_slug' => 'guven-karar'],
            ['title' => 'Taşınma sonrası hasar tazmini nasıl alınır?', 'slug' => 'tasinma-sonrasi-hasar-tazmini-nasil-alinir', 'category_slug' => 'guven-karar'],
            ['title' => 'İnternetten nakliyat firması bulmak güvenli mi?', 'slug' => 'internetten-nakliyat-firmasi-bulmak-guvenli-mi', 'category_slug' => 'guven-karar'],
            ['title' => 'Nakliyat firması yorumları ne kadar güvenilir?', 'slug' => 'nakliyat-firmasi-yorumlari-ne-kadar-guvenilir', 'category_slug' => 'guven-karar'],
            ['title' => 'Taşınırken sözleşme imzalamak zorunlu mu?', 'slug' => 'tasinirken-sozlesme-imzalamak-zorunlu-mu', 'category_slug' => 'guven-karar'],
            // IV. Bölgesel
            ['title' => 'İstanbul evden eve nakliyat fiyatları ne kadar?', 'slug' => 'istanbul-evden-eve-nakliyat-fiyatlari-ne-kadar', 'category_slug' => 'bolgesel-seo'],
            ['title' => 'Ankara şehirler arası nakliyat firmaları hangileri?', 'slug' => 'ankara-sehirler-arasi-nakliyat-firmalari-hangileri', 'category_slug' => 'bolgesel-seo'],
            ['title' => 'İzmir\'de güvenilir nakliyat firması nasıl bulunur?', 'slug' => 'izmirde-guvenilir-nakliyat-firmasi-nasil-bulunur', 'category_slug' => 'bolgesel-seo'],
            ['title' => 'İstanbul Anadolu Yakası en iyi nakliyat firmaları', 'slug' => 'istanbul-anadolu-yakasi-en-iyi-nakliyat-firmalari', 'category_slug' => 'bolgesel-seo'],
            ['title' => 'Avrupa Yakası ev taşıma hizmetleri nelerdir?', 'slug' => 'avrupa-yakasi-ev-tasima-hizmetleri-nelerdir', 'category_slug' => 'bolgesel-seo'],
            ['title' => 'İstanbul\'da aynı gün ev taşıma mümkün mü?', 'slug' => 'istanbulda-ayni-gun-ev-tasima-mumkun-mu', 'category_slug' => 'bolgesel-seo'],
            ['title' => 'Büyük şehirlerde taşınma neden daha pahalı?', 'slug' => 'buyuk-sehirlerde-tasinma-neden-daha-pahali', 'category_slug' => 'bolgesel-seo'],
            ['title' => 'İlçe bazlı nakliyat firması seçerken nelere dikkat edilmeli?', 'slug' => 'ilce-bazli-nakliyat-firmasi-secerken-nelere-dikkat-edilmeli', 'category_slug' => 'bolgesel-seo'],
            // V. Özel hizmet
            ['title' => 'Ofis taşıma nasıl planlanmalı?', 'slug' => 'ofis-tasima-nasil-planlanmali', 'category_slug' => 'ozel-hizmet'],
            ['title' => 'Fabrika taşıma süreci nasıl yönetilir?', 'slug' => 'fabrika-tasima-sureci-nasil-yonetilir', 'category_slug' => 'ozel-hizmet'],
            ['title' => 'Arşiv ve evrak taşıma nasıl yapılır?', 'slug' => 'arsiv-ve-evrak-tasima-nasil-yapilir', 'category_slug' => 'ozel-hizmet'],
            ['title' => 'Banka ve ATM taşıma nasıl olur?', 'slug' => 'banka-ve-atm-tasima-nasil-olur', 'category_slug' => 'ozel-hizmet'],
            ['title' => 'Parça eşya taşıma kimler için uygundur?', 'slug' => 'parca-esya-tasima-kimler-icin-uygundur', 'category_slug' => 'ozel-hizmet'],
            ['title' => 'Eşya depolama hizmeti nedir, kimler kullanmalı?', 'slug' => 'esya-depolama-hizmeti-nedir-kimler-kullanmali', 'category_slug' => 'ozel-hizmet'],
            ['title' => 'Öğrenci ev taşıma süreci nasıl olmalı?', 'slug' => 'ogrenci-ev-tasima-sureci-nasil-olmali', 'category_slug' => 'ozel-hizmet'],
            ['title' => 'Villa taşımacılığı neden farklıdır?', 'slug' => 'villa-tasimaciligi-neden-farklidir', 'category_slug' => 'ozel-hizmet'],
            // VI. Karşılaştırma & Rehber
            ['title' => 'Evden eve nakliyat mı, bireysel taşıma mı daha mantıklı?', 'slug' => 'evden-eve-nakliyat-mi-bireysel-tasima-mi-daha-mantikli', 'category_slug' => 'karsilastirma-rehber'],
            ['title' => 'Sigortalı nakliyat mı, sigortasız mı tercih edilmeli?', 'slug' => 'sigortali-nakliyat-mi-sigortasiz-mi-tercih-edilmeli', 'category_slug' => 'karsilastirma-rehber'],
            ['title' => 'Asansörlü ve asansörsüz taşıma arasındaki farklar', 'slug' => 'asansorlu-ve-asansorsuz-tasima-arasindaki-farklar', 'category_slug' => 'karsilastirma-rehber'],
            ['title' => 'Profesyonel nakliyat firması ile amatör firma arasındaki fark', 'slug' => 'profesyonel-nakliyat-firmasi-ile-amator-firma-arasindaki-fark', 'category_slug' => 'karsilastirma-rehber'],
            ['title' => 'Taşınırken kendiniz mi yapmalısınız, firma mı tutmalısınız?', 'slug' => 'tasinirken-kendiniz-mi-yapmalisiniz-firma-mi-tutmalisiniz', 'category_slug' => 'karsilastirma-rehber'],
        ];
    }

    private static function excerptFor(string $title): string
    {
        return $title . ' NakliyePark blogda: Evden eve nakliyat, fiyatlar ve güvenilir firma seçimi hakkında rehber.';
    }

    private static function metaDescFor(string $title): string
    {
        return $title . ' Detaylı cevap ve pratik öneriler. NakliyePark ile güvenilir nakliyat firmalarından teklif alın.';
    }

    private static function contentFor(string $title): string
    {
        $intro = '<p><strong>' . $title . '</strong> sorusu taşınma planı yapan birçok kişinin aklına gelir. Bu yazıda konuyu detaylı ele alıyoruz.</p>';
        $p1 = '<p>Evden eve nakliyat, eşyalarınızın eski adresinizden yeni adresinize profesyonel ekip ve araçlarla taşınması hizmetidir. Süreç genelde keşif veya fotoğraflı bilgi ile başlar, fiyat teklifi alınır, tarih belirlenir ve taşınma günü eşyalar paketlenip taşınır. Doğru firma seçimi hem güven hem bütçe açısından kritiktir.</p>';
        $p2 = '<p>NakliyePark, bu süreçte size yardımcı olmak için tasarlanmış bir platformdur. Site üzerinden ücretsiz nakliyat ihalesi açarak birden fazla nakliye firmasından teklif alabilirsiniz. Böylece hem fiyat hem hizmet kalitesi açısından karşılaştırma yapma imkânına kavuşursunuz. Özellikle şehirler arası veya büyük hacimli taşınmalarda birkaç farklı firmanın teklifini görmek, doğru kararı vermenizi kolaylaştırır.</p>';
        $p3 = '<p>Taşınmada dikkat edilmesi gereken başlıca noktalar: taşınacak hacmin doğru hesaplanması, kırılacak eşyaların uygun şekilde paketlenmesi, sigorta seçeneğinin değerlendirilmesi ve nakliyat sözleşmesinin yazılı olarak yapılmasıdır. Bu adımlar hasar ve anlaşmazlık riskini azaltır. NakliyePark üzerinden ulaştığınız firmalarla yapacağınız yazılı anlaşmalar da bu süreçte referans olarak kullanılabilir.</p>';
        $p4 = '<p>Fiyatlar taşınacak eşya hacmine (m³), mesafeye, kat bilgisine (asansörlü / asansörsüz), paketleme ve sigorta gibi ek hizmetlere göre değişir. Sabit bir "ev taşıma fiyatı" yoktur; her taşınma için özel teklif almak gerekir. Platformumuzda ihale açtığınızda firmalar sizin bilgilerinize göre size özel fiyat sunar. Bu sayede piyasa koşullarını görerek en uygun teklifi seçebilirsiniz.</p>';
        $p5 = '<p>Güvenilir nakliyat firması seçerken firmanın vergi levhası, fiziksel adresi, müşteri yorumları ve varsa sigorta poliçesi gibi bilgileri kontrol etmek faydalıdır. NakliyePark bünyesinde yer alan firmalar da bu tür bilgilerle değerlendirilebilir; böylece güvenle taşınma planı yapabilirsiniz.</p>';
        $p6 = '<p>Özetle ' . $title . ' sorusunun cevabı, doğru planlama ve güvenilir firma seçimiyle bir arada düşünüldüğünde anlam kazanır. NakliyePark ile birden fazla firmadan teklif alarak hem bilinçli hem güvenli bir taşınma süreci yönetebilirsiniz. Taşınma öncesi yapılacaklar listesini çıkarmak, firmalarla yazılı anlaşma yapmak ve eşyalarınızı uygun şekilde paketlemek, sürecin sorunsuz tamamlanmasına yardımcı olur.</p>';
        $p7 = '<p>NakliyePark olarak amacımız, evden eve nakliyat sürecinde müşterilerin doğru bilgiye ulaşmasını ve güvenilir firmalarla buluşmasını kolaylaştırmaktır. İhtiyacınız olan taşınma tipi ne olursa olsun—şehir içi, şehirler arası, ofis veya parça eşya—platformumuz üzerinden ücretsiz ihale açarak adım atabilirsiniz. Fiyat karşılaştırması yapmak, sözleşme ve sigorta konularını netleştirmek ve taşınma gününe hazırlıklı olmak, başarılı bir taşınmanın temel adımlarıdır.</p>';
        $p8 = '<p>Sonuç olarak taşınma sürecinde bilgi ve hazırlık büyük fark yaratır. NakliyePark blogda evden eve nakliyat, fiyatlar, güvenilir firma seçimi ve taşınma ipuçları hakkında daha fazla içerik bulabilirsiniz. Teklif almak için hemen platformumuzda ücretsiz ihale açabilir, size en uygun nakliyat firmasını seçebilirsiniz.</p>';

        return $intro . $p1 . $p2 . $p3 . $p4 . $p5 . $p6 . $p7 . $p8;
    }
}
