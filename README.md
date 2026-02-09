# NakliyePark

**Mobile-First** akıllı nakliye ihalesi ve yük borsası. Laravel + MySQL + Tailwind CSS. PWA destekli, dokunmatik uyumlu, uygulama benzeri deneyim.

## Özellikler

- **Akıllı Nakliye Sihirbazı**: Adım adım (Nereden-Nereye → Hacim → Detay → Fotoğraf) ihale oluşturma
- **Nakliyeci Paneli**: Tek tıkla teklif, ihale takip (Beklemede/Reddedildi/Onaylandı), Nakliyat Defteri (yük ilanları)
- **Müşteri Paneli**: İhalelerim, değerlendirme (yazı + **video yorum**)
- **Admin**: Firma onayı, istatistikler, responsive grafik (Chart.js)
- **Yardımcı Araçlar**: Hacim hesaplama (kayıtlı odalar), mesafe hesaplama
- **Blog & SSS**: Mobil uyumlu içerik
- **PWA**: manifest.json + Service Worker — "Ana Ekrana Ekle"

## Gereksinimler

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL 8 (veritabanı adı: `nakliyepark`)

## Kurulum

```bash
# Bağımlılıklar
composer install
cp .env.example .env
php artisan key:generate

# .env içinde MySQL ayarları (zaten örnekte var):
# DB_CONNECTION=mysql
# DB_DATABASE=nakliyepark

# Veritabanı oluştur (MySQL'de):
# CREATE DATABASE nakliyepark;

php artisan migrate
php artisan db:seed

# Frontend
npm install
npm run build

# Storage link (yükleme dosyaları için)
php artisan storage:link
```

## Çalıştırma

```bash
php artisan serve
# Tarayıcı: http://127.0.0.1:8000
```

Geliştirme modunda Vite ile:

```bash
npm run dev   # bir terminalde
php artisan serve  # başka terminalde
```

## Varsayılan Admin

- **E-posta:** admin@nakliyepark.test  
- **Şifre:** password  

(Seed sonrası oluşturulur.)

## PWA İkonları

`public/icons/` klasörüne şu boyutlarda PNG ekleyin: 72, 96, 128, 192, 512. Manifest bu yolları kullanır.

## Proje Yapısı (Özet)

- **Auth:** Tek `users` tablosu, `role`: admin | nakliyeci | musteri. Middleware: `role:admin`, `role:nakliyeci`, `role:musteri`.
- **Mobil:** Tüm arayüz Tailwind ile mobile-first (önce mobil, sonra `sm:`, `lg:`). Butonlar min 44x44px (`btn-touch`), form alanları `input-touch`, `inputmode` uyumlu.
- **Sihirbaz:** Tek sayfa, JS ile adım geçişi; gönderimde tüm veri POST ile `wizard.store`.
- **Nakliyat Defteri:** `yuk_ilanlari` tablosu; nakliyeci panelde listelenir (kaydırılabilir kartlar).

## License

MIT.
