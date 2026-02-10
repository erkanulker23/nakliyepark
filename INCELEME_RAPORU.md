# NakliyePark Proje İnceleme Raporu

**Tarih:** 9 Şubat 2026 · **Son güncelleme:** 10 Şubat 2026

---

## 1. Proje Özeti ve Çalışma Durumu

NakliyePark, **Laravel (PHP)** tabanlı bir **nakliye ihaleleri ve teklif platformu**dur. Müşteriler taşınma talebi (ihale) oluşturur, admin onayladıktan sonra yayına alınır; nakliyeci firmalar yayındaki ihalelere teklif verir. Genel akış doğru kurgulanmış ve **veriler doğru şekilde veritabanına bağlanıyor**.

### Doğru Çalışan Akışlar
- **İhale oluşturma:** Misafir veya üye (müşteri) `/ihale/olustur` ile ihale açar; `ihaleler` tablosuna kayıt düşer.
- **Admin onayı:** İhale `pending` → `published` yapılınca nakliyeciler listeyi görür.
- **Teklif verme:** Nakliyeci sadece **onaylı firması** ile, sadece **yayındaki** ihaleye teklif verebilir. Teklif `teklifler` tablosuna `ihale_id` ve `company_id` ile kaydedilir.
- **İlişkiler:** `Teklif` → `Ihale`, `Teklif` → `Company`; `Ihale` → `User` (opsiyonel, misafir ihale için null); `Company` → `User`. Foreign key’ler migration’larda tanımlı, cascade delete kullanılıyor.

---

## 2. Veritabanı ve Veri Bütünlüğü

| Tablo        | Özet |
|-------------|------|
| **users**   | role: admin, musteri, nakliyeci; blocked_at; doğru kullanılıyor. |
| **companies** | user_id, approved_at, blocked_at; nakliyeci teklif verebilmek için approved gerekli. |
| **ihaleler** | user_id (nullable – misafir), guest_contact_*, service_type, room_type, status (pending/draft/published/closed/cancelled). |
| **teklifler** | ihale_id, company_id, amount, message, status (pending/accepted/rejected). |

- Teklifler doğru ihale ve firmaya bağlı; nakliyeciden gelen teklifler `company_id` olarak giriş yapan kullanıcının firması ile kaydediliyor (başka firma adına teklif verilemiyor).
- Admin panelinden teklif listesi, düzenleme ve silme çalışıyor; ihale detayında ilgili ihalenin teklifleri listeleniyor.

---

## 3. Admin Panel Kapsamı

Aşağıdaki modüller admin panelinden yönetilebiliyor:

- **Dashboard** – Özet istatistikler, son firmalar/ihaleler  
- **Kullanıcılar** – Liste, düzenleme, silme  
- **Müşteriler** – Müşteri listesi ve detay  
- **Firmalar** – Liste, düzenleme, onay/red, silme, engelleme  
- **İhaleler** – CRUD, durum güncelleme (pending/draft/published/closed/cancelled), ihale detayında teklif listesi  
- **Teklifler** – Liste, düzenleme (tutar, mesaj, status), silme  
- **Yük ilanları** – Resource CRUD  
- **Defter reklamları** – Yönetim  
- **Değerlendirmeler (reviews)** – Liste, silme  
- **Blog / Blog kategorileri / SSS / Oda şablonları** – İçerik yönetimi  
- **Engellemeler (blocklist)** – E-posta, telefon, IP; kullanıcı/firma engelleme  
- **Ayarlar** – Mail, araç sayfaları, test mail  
- **Profil / Bildirimler** – Admin hesabı ve bildirimler  

Yapılan düzeltmeyle admin **ihale oluştururken/düzenlerken** artık **hizmet tipi (service_type)** ve **oda/büyüklük (room_type)** alanlarını da görebiliyor ve güncelleyebiliyor.

---

## 4. Yapılan Düzeltmeler

### 4.1 Müşteri paneli – Route hatası
- **Sorun:** Müşteri panelinde “Yeni İhale” linki `route('wizard.index')` kullanıyordu; bu isimde route tanımlı değildi.
- **Çözüm:** Linkler `route('ihale.create')` olacak şekilde güncellendi (`resources/views/musteri/dashboard.blade.php`).

### 4.2 Engelli firmanın teklif vermesi
- **Sorun:** Firma `blocked_at` ile engellense bile nakliyeci teklif gönderebiliyordu.
- **Çözüm:** `Nakliyeci\IhaleController::storeTeklif` ve `Nakliyeci\TeklifController::store` içinde `$company->isBlocked()` kontrolü eklendi; engelli firmaya “Firmanız engellenmiştir. Teklif veremezsiniz.” mesajı dönülüyor.

### 4.3 Admin ihale formları – service_type ve room_type
- **Sorun:** Admin panelinde ihale oluşturma/düzenlemede hizmet tipi ve oda büyüklüğü alanları yoktu.
- **Çözüm:** `Admin\IhaleController::validateIhale` içine `service_type` ve `room_type` kuralları eklendi; create ve edit view’larına ilgili alanlar eklendi. Admin artık bu alanları da yönetebiliyor.

### 4.4 Brute force koruması
- **Sorun:** Login ve register için istek sınırı yoktu; deneme-yanılma saldırılarına açıktı.
- **Çözüm:** `routes/web.php` içinde login/register route’ları `throttle:6,1` middleware ile sınırlandı (dakikada 6 deneme).

### 4.5 Admin ihale create – user_id (misafir)
- **Sorun:** “Misafir” seçildiğinde form boş string gönderiyordu; `exists:users,id` validasyonu hata verebiliyordu.
- **Çözüm:** Store ve update’te `user_id` boşsa `null` yapılıyor (`$request->merge(['user_id' => $request->input('user_id') ?: null])`), böylece misafir ihale kaydı tutarlı çalışıyor.

### 4.6 Şifre sıfırlama – PHP sınıf adı çakışması (10 Şubat 2026)
- **Sorun:** `Auth\ResetPasswordController` içinde hem `Illuminate\Support\Facades\Password` hem de `Illuminate\Validation\Rules\Password` aynı isimle (`Password`) kullanılıyordu. PHP "Cannot use ... as Password because the name is already in use" hatası veriyordu; `php artisan route:list` ve şifre sıfırlama sayfası çalışmıyordu.
- **Çözüm:** Validation kuralları sınıfı `PasswordRule` olarak alias’landı: `use Illuminate\Validation\Rules\Password as PasswordRule;` ve validasyonda `PasswordRule::min(8)->letters()->numbers()` kullanıldı.

### 4.7 Slug ile route – Eksik alan (10 Şubat 2026)
- **Sorun:** `Ihale` ve `Company` modelleri `getRouteKeyName()` ile `slug` kullanıyor. Defter sayfasında “son ihaleler” listesi `get(['id', 'from_city', ...])` ile çekildiği için `slug` yüklenmiyordu; `route('ihaleler.show', $ihale)` hatalı/boş URL üretiyordu. Ana sayfada haritadaki firmalar da `get(['id', 'name', 'city', ...])` ile çekildiği için `slug` yoktu; `route('firmalar.show', $c)` boş slug ile kırılıyordu.
- **Çözüm:** `DefterController`: `sonIhaleler` sorgusuna `slug` sütunu eklendi. `HomeController`: `firmalarHaritada` sorgusuna `slug` sütunu eklendi.

### 4.8 Not
- Admin ihale **create** formunda `move_date_end` alanı zaten mevcuttur (rapor 7. maddede “create formunda da olsun” denmişti; kontrol edildi, alan create view’da var).

---

## 5. Güvenlik Değerlendirmesi

### Güçlü Yönler
- **Kimlik doğrulama:** Laravel auth; şifre hash’li.
- **Yetkilendirme:** `role:admin`, `role:nakliyeci`, `role:musteri` middleware ile ayrım var.
- **Engelleme:** Blocklist (e-posta, telefon, IP); `EnsureNotBlocked` ile giriş sonrası kontrol; login/register’da blocklist kontrolü.
- **CSRF:** Web route’ları için Laravel CSRF token kullanılıyor.
- **SQL injection:** Sorgular Eloquent ve query builder ile; parametreler bağlı.
- **XSS:** Blade’da `{{ }}` kullanımı çıktıyı escape ediyor.
- **Mass assignment:** Modellerde `$fillable` tanımlı; sadece izin verilen alanlar dolduruluyor.
- **Teklif yetkisi:** Teklif sadece giriş yapan kullanıcının kendi firması (`company_id`) ile oluşturuluyor; başka firma adına teklif verilemiyor.

### İyileştirilmiş / Eklenen
- **Throttle:** Login ve register için 6 istek/dakika sınırı eklendi.
- **Engelli firma:** Engelli firmaların teklif vermesi kapatıldı.

### Önerilen Ek Güvenlik Adımları
1. **HTTPS:** Canlıda TLS zorunlu olsun.
2. **Güvenlik başlıkları:** HSTS, X-Frame-Options, X-Content-Type-Options vb. (Laravel’de middleware veya sunucu ile).
3. **Şifre politikası:** Minimum 8 karakter var; isteğe bağlı güçlü şifre kuralları eklenebilir.
4. **Rate limit:** İhale oluşturma veya teklif gönderme gibi hassas aksiyonlara dakikada makul bir üst sınır konulabilir.
5. **Loglama:** Başarısız giriş, admin işlemleri ve kritik değişiklikler loglansın.

Genel olarak sistem **küçük/orta ölçekli bir web uygulaması için makul güvenlik seviyesinde**; yapılan düzeltmeler ve önerilerle daha da sağlamlaştırılabilir.

---

## 6. NakliyePark Para Kazanma Modeli Önerileri

1. **Komisyon (mevcut altyapı)**  
   - `Company` modelinde `commission_rate` ve `total_commission` hesaplaması var; kabul edilen teklif tutarı üzerinden yüzde komisyon alınabilir.  
   - Örnek: Her kapanan taşıma işinden %5–10 komisyon.

2. **Abonelik / Paket (nakliyeci)**  
   - Aylık/yıllık “premium firma” paketi: Öne çıkan listing, daha fazla ihale görüntüleme, öncelikli teklif listesi.  
   - Mevcut “paketler” sayfası bu modelle genişletilebilir.

3. **Reklam geliri**  
   - **Defter reklamları** zaten var; ek olarak ana sayfa, ihale listesi veya firma sayfalarında banner/öne çıkan firma alanları.  
   - Şehir veya hizmet tipine göre hedefli reklam fiyatları.

4. **Yük ilanları**  
   - Nakliyecilerin boş araç/rotası için ilan vermesi; ilan başı veya aylık ücret.

5. **Öne çıkan firma / “Firmanı öne çıkar”**  
   - Firma sayfasında “Öne çıkan” rozeti veya arama sonuçlarında üst sırada gösterme; aylık sabit ücret.

6. **Lead / iletişim ücreti (opsiyonel)**  
   - Müşteri bilgisi (iletişim) nakliyeciye açılırken lead başına ücret (dikkat: kullanıcı deneyimi ve şeffaflık önemli).

7. **Kurumsal / ofis taşıma paketleri**  
   - Büyük hacimli veya kurumsal taşıma talepleri için özel paket fiyatları veya komisyon oranları.

Mevcut kodda komisyon oranı `Setting::get('commission_rate', 10)` ile alınıyor; admin panelinden bu oran ve diğer ticari kurallar yönetilebilir.

---

## 7. Geliştirme Önerileri

### Kısa vadeli
- **Müşteri ihale detayı:** Müşteri panelinde ihale bazında gelen teklifleri görme, bir teklifi “kabul” etme (teklif status’unu `accepted` yapma) ve gerekirse iletişim bilgisi paylaşma (bu akış mevcut).
- **Bildirimler:** Yeni teklif geldiğinde müşteriye e-posta veya in-app bildirim; ihale onaylandığında müşteriye bilgi.

### Orta vadeli
- **Arama / filtre:** İhale listesinde şehir, tarih, hizmet tipi, hacim; teklif listesinde ihale/firma/tarih filtreleri.
- **Raporlar:** Aylık ihale sayısı, teklif sayısı, kapanan işler, komisyon özeti; Excel/PDF export.
- **E-posta şablonları:** Admin panelinden düzenlenebilir e-posta şablonları (ihale onayı, teklif bildirimi vb.).

### Uzun vadeli
- **Ödeme entegrasyonu:** Komisyon veya abonelik ödemeleri için ödeme altyapısı (iyzico, PayTR vb.).
- **Mobil uyum / PWA:** Mevcut responsive yapı; gerekirse PWA ile bildirim ve “uygulama gibi” deneyim.
- **API:** Mobil uygulama veya üçüncü taraf entegrasyonu için REST/API endpoint’leri (rate limit ve auth ile).

---

## 8. Özet

- **Veri akışı ve ilişkiler doğru:** İhaleler, teklifler ve firmalar veritabanında doğru şekilde bağlı; nakliyeciden gelen teklifler doğru firmaya ve ihaleye kaydediliyor.  
- **Admin paneli:** İhaleler, teklifler, firmalar, kullanıcılar, içerik ve ayarlar yönetilebiliyor; service_type/room_type eksikliği giderildi.  
- **Düzeltilen hatalar:** Müşteri paneli route, engelli firma teklifi, admin ihale formları, throttle, misafir ihale user_id; **10 Şubat 2026:** ResetPasswordController PHP sınıf çakışması, Defter/Home slug ile route URL hataları.  
- **Güvenlik:** Temel önlemler mevcut; throttle ve engelli firma kontrolü eklendi; HTTPS ve ek rate limit önerildi.  
- **Gelir modeli:** Komisyon altyapısı var; abonelik, reklam ve öne çıkan firma modelleri rapor içinde önerildi.

Bu rapor, projenin mevcut durumunu ve yapılan iyileştirmeleri özetler; ileride yapılacak geliştirmeler için referans olarak kullanılabilir.
