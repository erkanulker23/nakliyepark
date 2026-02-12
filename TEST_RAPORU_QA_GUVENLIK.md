# NakliyePark — QA Lead + Senior Backend + Security Test Raporu

**Tarih:** 2026-02-12  
**Kapsam:** Kurulum, kimlik doğrulama, yetkilendirme, admin/müşteri/nakliyeci akışları, veritabanı, e-posta/bildirim, güvenlik, edge-case senaryoları.  
**Kural:** Hiçbir modül atlanmadı; bulunan hatalar düzeltildi ve test devam ettirildi.

---

## 1. Kurulum ve Ortam Testleri

| Test | Sonuç | Not |
|------|--------|-----|
| .env.example → .env | ✅ | Tüm gerekli değişkenler mevcut (DB, SESSION_DRIVER, CACHE_STORE, QUEUE_CONNECTION, MAIL_MAILER, KVKK, OPENAI_API_KEY). |
| Migration'lar | ✅ | `php artisan migrate --force` hatasız çalışıyor. |
| Seeder'lar | ⚠️→✅ | **Düzeltildi:** `SponsorSeeder` DatabaseSeeder içinde çağrılmıyordu; eklendi. DefterYanitiSeeder, DemoSeeder sırası uygun. |
| Queue / cache / session | ✅ | config: QUEUE_CONNECTION=database, CACHE_STORE=database, SESSION_DRIVER=database. Tutarlı. |
| Storage link | ✅ | `php artisan storage:link` çalışıyor (mevcut link hatası normal). |
| Feature test (GET /) | ❌→✅ | **Düzeltildi:** Test ortamında `settings` tablosu yoktu; `RefreshDatabase` kullanımı eklendi, test geçiyor. |

---

## 2. Kimlik Doğrulama ve Yetkilendirme Testleri

| Test | Sonuç | Not |
|------|--------|-----|
| Kayıt (admin rolü) | ✅ | `RegisterController`: validation `role => in:musteri,nakliyeci`; admin ile kayıt yapılamıyor. |
| Kayıt (nakliyeci/musteri) | ✅ | BlockedEmail, BlockedPhone, BlockedIp kontrolü yapılıyor; kayıt sonrası yönlendirme doğru. |
| Giriş / çıkış | ✅ | LoginController login + logout; session invalidate ve token regenerate var. |
| Admin girişi sadece /yonetici/admin | ✅ | Normal login'de admin hesabı kabul edilmiyor (bilerek "hatalı giriş" mesajı). |
| Normal girişte admin engelleniyor | ✅ | `if ($user->isAdmin()) { Auth::logout(); ... throw ValidationException }`. |
| E-posta doğrulaması zorunlu mu? | ⚠️ | **Risk:** `MustVerifyEmail` implement edilmiş ama `verified` middleware hiçbir route'ta uygulanmıyor. Kullanıcı doğrulama yapmadan panel kullanabiliyor. |
| E-posta doğrulama atlanabiliyor mu? | ⚠️ | Evet; zorunlu değil. İstenirse `verified` middleware musteri/nakliyeci route gruplarına eklenmeli. |
| Şifre sıfırlama linki | ✅ | ForgotPassword / ResetPassword standart Laravel; ResetPasswordNotification Türkçe özelleştirilmiş. |
| Engellenmiş kullanıcı | ✅ | LoginController ve EnsureNotBlocked: blocked_at, BlockedEmail, BlockedPhone, BlockedIp kontrolü; engelli ise logout + login'e yönlendirme. |

---

## 3. Rol ve Yetki Kontrolleri (Kritik)

| Test | Sonuç | Not |
|------|--------|-----|
| Admin tüm modüllere erişebiliyor mu? | ✅ | Tüm admin route'ları `middleware(['auth', 'role:admin'])` altında. |
| Nakliyeci admin alanına erişemiyor mu? | ✅ | Admin prefix `/admin`; role:admin olmadan 403. |
| Musteri nakliyeci/admin işlemi yapabiliyor mu? | ✅ | Hayır; route grupları role:musteri ve role:nakliyeci ile ayrılmış. |
| URL ile yetkisiz sayfa erişimi | ✅ | Middleware ile engelleniyor; 403. |
| IDOR – başka kullanıcının ihalesi | ✅ | Musteri IhaleController: `$ihale->user_id !== $request->user()->id` → abort(403). |
| IDOR – teklif kabul (başka ihale/teklif) | ✅ | ihale sahipliği + teklif->ihale_id kontrolü var. |
| IDOR – değerlendirme (review) | ❌→✅ | **Düzeltildi:** `ReviewController::store` içinde `company_id` sadece request'ten alınıyordu; ihale'nin kabul edilen teklifindeki firma ile eşleşme kontrolü yoktu. Artık `acceptedTeklif->company_id` ile doğrulanıyor; başka firmaya sahte değerlendirme engellendi. |
| Soft delete edilmiş kayıt erişimi | ✅ | Company, Ihale, Teklif, Review SoftDeletes kullanıyor; route model binding varsayılan olarak trashed hariç. Public listeler (FirmaController, IhaleController) scope'suz ama Eloquent global scope ile soft-deleted gelmiyor. |

---

## 4. Admin Panel ve İş Akışı Tutarlılığı

| Test | Sonuç | Not |
|------|--------|-----|
| Admin teklif durumunu "accepted" yapınca ihale kapanıyor mu? | ❌→✅ | **Düzeltildi:** Önceden sadece teklif güncelleniyordu; ihale "published" kalıyordu. `Admin\TeklifController::update` içinde status=accepted ise transaction ile ihale closed yapılıyor ve diğer teklifler rejected ediliyor. |
| Musteri teklif kabul – ihale zaten kapalıysa? | ❌→✅ | **Düzeltildi:** `acceptTeklif` başında `$ihale->status === 'closed'` kontrolü eklendi; çift kabul / race durumunda anlamlı mesaj. |
| Transaction – teklif kabul | ✅ | Musteri acceptTeklif ve Admin teklif update DB::transaction kullanıyor. |

---

## 5. Veritabanı ve Mantık Doğrulama

| Test | Sonuç | Not |
|------|--------|-----|
| Kayıt sonrası DB'ye yazılıyor mu? | ✅ | User::create, Company::create vb. standart Eloquent. |
| İlişkiler (FK) | ✅ | Migration'larda constrained / cascadeOnDelete tanımlı; unique(ihale_id, company_id) tekliflerde var. |
| Silinen kayıtlar soft delete mi? | ✅ | companies, ihaleler, teklifler, reviews, contact_messages. |
| Status alanları tutarlı mı? | ✅ | İhale closed + teklif accepted senkronize (yukarıdaki düzeltmelerle). |

---

## 6. E-posta ve Bildirim Testleri (Kod İncelemesi)

| Test | Sonuç | Not |
|------|--------|-----|
| Welcome mail | ✅ | Register sonrası `WelcomeNotification` tetikleniyor. |
| Verify email link | ✅ | VerifyEmailNotification imzalı URL (temporarySignedRoute) kullanıyor; doğru route. |
| Şifre sıfırlama maili | ✅ | ResetPasswordNotification. |
| İhale oluşturma → admin mail | ✅ | AdminNotifier::notify('ihale_created'); NewIhaleAdminNotification (admin'lere). |
| Teklif geldi → müşteri mail | ✅ | TeklifReceivedNotification (user veya guest e-posta). |
| Teklif kabul → nakliyeci mail | ✅ | UserNotification + TeklifAcceptedNotification. |
| Bildirimler DB + UI | ✅ | UserNotification / AdminNotification; AppServiceProvider composeHeaderNotifications ile dropdown dolduruluyor. |
| Mail ayarları admin'den | ✅ | AppServiceProvider applyMailSettingsFromDatabase; Setting'ten mail config override. |
| VerifyEmailNotification ShouldQueue | ⚠️ | Bildirim kuyruğa alınmış; queue worker çalışmıyorsa doğrulama maili gönderilmez. Prod'da worker veya sync driver kullanımı gerekir. |

---

## 7. Güvenlik Testleri

| Test | Sonuç | Not |
|------|--------|-----|
| CSRF | ✅ | Tüm formlarda @csrf / csrf_field(); Laravel web middleware VerifyCsrfToken. |
| Rate limit | ✅ | Login/register throttle:6,1; ihale store throttle:10,1; contact throttle:5,1; teklif/storeReply throttle:20,1 / 30,1; blog generate-ai throttle:10,1. |
| SQL Injection | ✅ | Eloquent ve binding kullanılıyor; orderByRaw parametreli. LIKE birleştirmeleri request'ten geliyor (validation ile sınırlı); ek filtre için parametre binding önerilir. |
| XSS | ✅ | Blade {{ }} escape; tutarlı kullanım. |
| Dosya upload | ✅ | Galeri: image|mimes:jpeg,png,jpg,webp|max:5120. Evraklar: mimes:pdf,jpeg,png,jpg|max:10240. İhale foto: image|max:5120. Review video: mimes:mp4,webm|max:51200. |
| Yetkisiz veri değiştirme | ✅ | Musteri/Nakliyeci kendi kaynaklarına sınırlı; admin role middleware ile ayrılmış. |

---

## 8. Edge-Case ve Risk Noktaları

| Senaryo | Sonuç | Not |
|---------|--------|-----|
| Aynı anda iki teklif kabul | ✅ | Transaction + "ihale closed" kontrolü ile ikinci istek anlamlı mesaj alıyor. |
| Admin ihale kapattığında açık teklifler | ⚠️ | Admin sadece ihale status'unu closed yapıyor; tekliflerin status'u değişmiyor. İstenirse admin "ihale kapat" aksiyonunda tüm teklifleri rejected yapabilir (ürün tercihi). |
| Firma engellendiğinde aktif teklifler | ✅ | Nakliyeci teklif verirken company isBlocked() kontrolü var; engelli firma yeni teklif veremez. Mevcut teklifler DB'de kalır (iş kuralına bırakıldı). |
| Mail gönderimi başarısız olursa | ⚠️ | Senkron gönderim; exception fırlarsa istek hata verir. Queue kullanılmadığı için kullanıcı "sayfa hata verdi" görür. Prod'da queue + failed job yönetimi önerilir. |
| Boş / sınır değer input | ✅ | Validasyon kuralları (required, max, in, exists) mevcut. |

---

## 9. Performans / Kod Kalitesi (Kısa)

| Konu | Not |
|------|-----|
| BlockedPhone::isBlocked | Tüm kayıtları çekip koleksiyonda arıyor; çok sayıda engelli telefon varsa sorgu ile (normalize edilmiş alan veya whereRaw) iyileştirilebilir. |
| Policy kullanımı | Yok; yetki controller/middleware'de. Merkezi Policy eklenmesi bakımı kolaylaştırır. |

---

## 10. Yapılan Düzeltmeler Özeti

1. **database/seeders/DatabaseSeeder.php**  
   - `SponsorSeeder` çağrısı eklendi.

2. **app/Http/Controllers/ReviewController.php**  
   - `store`: İhale'nin kabul edilen teklifindeki `company_id` ile request `company_id` eşleşmesi zorunlu; aksi halde 403. (IDOR / sahte değerlendirme kapatıldı.)

3. **app/Http/Controllers/Musteri/IhaleController.php**  
   - `acceptTeklif`: İhale zaten closed ise kabul yapılmıyor; anlamlı hata mesajı.

4. **app/Http/Controllers/Admin/TeklifController.php**  
   - `update`: Status "accepted" yapıldığında transaction içinde ihale "closed" yapılıyor ve diğer teklifler "rejected" ediliyor. (Veritabanı tutarlılığı.)

5. **tests/Feature/ExampleTest.php**  
   - `RefreshDatabase` kullanıldı; test ortamında migration'lar çalışıyor, `settings` tablosu mevcut, GET / 200 dönüyor.

---

## 11. Önceliklendirilmiş Öneri Listesi

| Öncelik | Öğe | Açıklama |
|---------|-----|----------|
| Yüksek | E-posta doğrulama zorunluluğu | İsteniyorsa musteri/nakliyeci route'larına `verified` middleware ekleyin. |
| Yüksek | Queue worker | VerifyEmailNotification ve diğer mailler queue'da ise prod'da worker çalıştırın veya mail için sync kullanın. |
| Orta | Admin ihale kapatma | İhale "closed" yapılırken açık tekliflerin "rejected" yapılması ürün kararı olarak değerlendirilebilir. |
| Orta | BlockedPhone performansı | Çok sayıda kayıt için sorgu tabanlı (normalize alan veya whereRaw) engel kontrolü. |
| Düşük | Policy sınıfları | Yetki kurallarını Policy ile merkezileştirme. |
| Düşük | Mail hata yönetimi | Kritik mailleri queue + failed job ile yönetme. |

---

## 12. Sonuç Özeti

- **Çalışmayan (düzeltildi):** SponsorSeeder eksikliği, Review company_id IDOR, Admin teklif accepted iken ihale kapanmaması, Musteri çift teklif kabul, Feature test settings tablosu.
- **Riskli ama çalışan:** E-posta doğrulama zorunlu değil, VerifyEmailNotification kuyrukta (worker gerekebilir), mail hata durumunda senkron hata.
- **Doğru çalışan:** Kurulum, migration, auth/admin ayrımı, CSRF, rate limit, dosya validasyonu, IDOR kontrolleri (review düzeltmesi sonrası), soft delete, transaction kullanımı, blocklist, bildirim ve mail tetikleyicileri.

Bu rapor, kod incelemesi ve yapılan düzeltmelerle üretilmiştir; otomatik E2E veya canlı ortam testi yapılmamıştır.
