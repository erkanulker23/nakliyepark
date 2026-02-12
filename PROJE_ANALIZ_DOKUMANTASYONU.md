# NakliyePark — Kapsamlı Proje Analiz ve Teknik Dokümantasyon

Bu doküman, NakliyePark projesinin sıfırdan incelenmesiyle üretilmiş teknik analiz ve dökümantasyondur. Projeyi hiç bilmeyen bir yazılımcının mimariyi, teknolojileri, veritabanını ve iş akışlarını anlaması hedeflenmiştir.

---

## 1. Genel Mimari Analizi

### 1.1 Projenin Çalışma Mantığı

**NakliyePark**, taşınacak müşteriler (müşteri) ile nakliye firmaları (nakliyeci) arasında aracılık yapan bir **nakliye ve yük borsası** uygulamasıdır. Temel akış:

1. **Müşteri / misafir** taşınma talebi oluşturur (ihale wizard: nereden–nereye, tarih, hacim, iletişim).
2. Talep **admin** tarafından onaylanır; onay sonrası ihale **yayında** olur.
3. **Nakliyeci** firmalar yayındaki ihalelere **teklif** (fiyat + mesaj) verir.
4. Müşteri bir teklifi **kabul** eder; ihale kapanır, müşteri ile firma iletişime geçer.
5. İş sonrası müşteri firmaya **değerlendirme** (review) bırakabilir.
6. Ek olarak **defter** modülü: nakliyeciler “yük ilanı” (nereden–nereye, tarih, hacim) yazar; diğer firmalar bu ilanlara **yanıt** (DefterYaniti) verebilir.

Yan modüller: **Blog**, **SSS (FAQ)**, **iletişim formu**, **nakliye firmaları listesi/harita**, **pazaryeri** (araç ilanları), **araçlar** (hacim hesaplama, mesafe, kontrol listesi, takvim). Site ayarları, reklam alanları, sponsorlar ve anasayfa bölüm sırası admin panelden yönetilir.

### 1.2 Çözülen Problem

- Taşınacak kişilerin **tek platformdan** birden fazla nakliye firmasına ulaşması.
- Nakliye firmalarının **ihale/teklif** ve **defter** ile iş bulması.
- İhale–teklif–kabul–iletişim–değerlendirme sürecinin **izlenebilir** ve **kayıtlı** yürütülmesi.
- KVKK uyumu (açık rıza logu, veri saklama süresi ayarı).

### 1.3 Mimari Türü

- **Monolitik** tek uygulama; modüller aynı codebase içinde (admin, musteri, nakliyeci, guest).
- **Katmanlı**: HTTP isteği → Route → Middleware → Controller → Model / Service → View; ayrıca Notification ve Service sınıfları kullanılıyor.

### 1.4 Backend Mimarisi

- **MVC tabanlı**: Controller’lar doğrudan Model kullanıyor; karmaşık işler bazen Service (örn. `AdminNotifier`, `BlogAiService`, `MailTemplateService`) ile ayrılmış.
- **Repository katmanı yok**: Veri erişimi doğrudan Eloquent Model üzerinden.
- **CQRS yok**: Okuma/yazma ayrımı yapılmıyor.
- **Rol tabanlı erişim**: `role:musteri`, `role:nakliyeci`, `role:admin` middleware’leri ile route grupları ayrılmış.

---

## 2. Kullanılan Teknolojiler ve Altyapı

### 2.1 Dil ve Framework

| Bileşen | Versiyon / Not |
|--------|-----------------|
| PHP | ^8.2 |
| Laravel | ^12.0 |
| Composer | Proje bağımlılık yönetimi |

### 2.2 Önemli Paketler (composer.json)

| Paket | Amaç |
|-------|------|
| laravel/framework | Çekirdek framework |
| laravel/tinker | REPL / debug |
| openai-php/client, openai-php/laravel | Blog AI içerik üretimi (admin panel) |
| fakerphp/faker | Test/seed verisi |
| laravel/pint | Kod stili |
| phpunit/phpunit | Birim/entegrasyon testleri |

### 2.3 Frontend

| Bileşen | Versiyon | Amaç |
|--------|----------|------|
| Vite | ^7.0.7 | Derleme, HMR |
| Tailwind CSS | ^4.0.0 | Stil |
| @tailwindcss/vite | ^4.0.0 | Vite entegrasyonu |
| laravel-vite-plugin | ^2.0.0 | Laravel + Vite |
| axios | ^1.11.0 | HTTP istekleri |
| chart.js | ^4.5.1 | Grafik (dashboard vb.) |
| concurrently | ^9.0.1 | Aynı anda server, queue, vite çalıştırma |

- **SPA değil**: Sayfalar Blade şablonları ile sunulur; gerektiğinde Axios ile AJAX kullanılır.

### 2.4 Auth, Middleware ve Guard

- **Guard**: Tek guard `web` (session tabanlı), provider `users` (Eloquent, `App\Models\User`).
- **Rol**: `users.role` alanı: `admin`, `nakliyeci`, `musteri`. Policy sınıfı yok; yetki kontrolü controller ve middleware ile.
- **Middleware (özel)**:
  - `role` → `EnsureRole`: Belirtilen rollere (örn. `admin`, `nakliyeci`) izin verir.
  - `not.nakliyeci` → `EnsureNotNakliyeci`: İhale oluşturma sayfasında nakliyecileri engeller.
  - `firmalar.visible` → `EnsureFirmalarPageVisible`: “Nakliye firmaları” sayfası ayarla kapatılmışsa 404 döner.
  - `EnsureNotBlocked`: Giriş yapmış kullanıcı/IP/telefon engelli mi kontrol eder; engelli ise logout + login’e yönlendirir.
  - `SecurityHeaders`: X-Frame-Options, X-Content-Type-Options, X-XSS-Protection, Referrer-Policy, Permissions-Policy, HSTS (HTTPS’te) ekler.

- **E-posta doğrulama**: `User` implements `MustVerifyEmail`; doğrulama sayfası ve imzalı link `VerificationController` ile. Şifre sıfırlama ve hoş geldin e-postaları özelleştirilmiş Notification’lar ile Türkçe gönderilir.

---

## 3. Klasör ve Dosya Yapısı Analizi

### 3.1 Kök Dizin

| Öğe | Açıklama |
|-----|----------|
| `app/` | Uygulama kodu (Controller, Model, Service, Notification, Middleware, Provider) |
| `bootstrap/` | `app.php` (uygulama yapılandırması, middleware alias, exception), `providers.php` |
| `config/` | Tüm config dosyaları (app, auth, database, mail, nakliyepark, seo, queue, turkey_city_coordinates, volume_calculator vb.) |
| `database/` | migrations, seeders, factories |
| `lang/` | Türkçe çeviriler (pagination, passwords, tr.json) |
| `public/` | index.php, .htaccess, favicon, manifest, robots, sw.js |
| `resources/` | views (Blade), css, js |
| `routes/` | web.php, console.php |
| `storage/` | Log, cache, session, yüklenen dosyalar |
| `tests/` | Feature, Unit testleri |
| `vite.config.js` | Vite yapılandırması |
| `composer.json`, `package.json` | Bağımlılıklar |

### 3.2 app/ Yapısı

**app/Http/Controllers/**  
- **Auth**: Login (normal + admin girişi), Register, ForgotPassword, ResetPassword, Verification.  
- **Admin**: Dashboard, User, Company, Ihale, Teklif, YukIlani, Review, Dispute, Blog (CRUD + AI üretim), BlogCategory, Faq, RoomTemplate, Setting, AdZone, DefterReklami, Blocklist, ConsentLog, HomepageEditor, Sponsor, SiteContactMessage, Notification, Profile, Musteri, ihale/teklif/review yönetimi.  
- **Musteri**: Dashboard, Profile, Teklif listesi, Mesaj, Ihale (detay, teklif kabul/geri al, iletişim mesajı, uyuşmazlık), Notification.  
- **Nakliyeci**: Dashboard, Company (create/edit), Teklifler, Ledger (defter ilanları + yanıt), Galeri, Evraklar, Cari, Borc, Paketler, Ihale (liste, detay, teklif ver, teklif güncelleme talebi), Notification, Location.  
- **Kök**: Home, Defter, Firma (liste, harita, detay), Ihale (liste, detay), Pazaryeri, Blog, Faq, Contact, Tool (hacim, mesafe, kontrol listesi, takvim), Kvkk, GuestWizard (ihale oluşturma), TurkeyLocation (il/ilçe API).

**app/Http/Middleware/**  
Yukarıda listelenen 5 özel middleware.

**app/Models/**  
User, Company, Ihale, Teklif, YukIlani, DefterYaniti, Review, Dispute, ContactMessage, BlogPost, BlogCategory, Faq, RoomTemplate, Setting, AdZone, DefterReklami, Sponsor, SiteContactMessage, AdminNotification, UserNotification, AuditLog, ConsentLog, BlockedEmail, BlockedPhone, BlockedIp, IhalePhoto, IhaleJob, CompanyDocument, CompanyContract, CompanyVehicleImage, PazaryeriListing.

**app/Services/**  
- **AdminNotifier**: Admin bildirimi oluşturur (AdminNotification::notify).  
- **BlogAiService**: OpenAI ile blog başlık, özet, içerik, meta alanları üretir.  
- **MailTemplateService**: E-posta şablonları (admin ayarlarından) ile ilgili servis.

**app/Notifications/**  
VerifyEmailNotification, WelcomeNotification, ResetPasswordNotification, IhaleCreatedNotification, NewIhaleAdminNotification, TeklifReceivedNotification, TeklifAcceptedNotification, ContactMessageToCompanyNotification, IhalePreferredCompanyPublishedNotification, IhalePublishedNotification (ve benzeri e-posta/bildirimler).

**app/Providers/AppServiceProvider.php**  
- `applyMailSettingsFromDatabase`: Mail config’i settings tablosundan okur.  
- `shareSiteSettingsToViews`: Site meta, logo, iletişim, SEO, custom header/footer/scripts, show_firmalar_page vb. tüm view’lara paylaşır.  
- `applyPackagesFromDatabase`: Nakliyeci paketlerini settings’ten config’e yükler.  
- `composeHeaderNotifications`: Layout’taki bildirim dropdown için admin veya kullanıcı bildirimlerini view’a enjekte eder.

### 3.3 resources/views/

- **layouts**: app, admin, musteri, nakliyeci, guest, embed; partials (header, footer, nav, notifications-dropdown, seo-meta, structured-data).
- **auth**: login, register, verify, password (request, reset).
- **admin/**: Her modül için index/create/edit/show (blog, faq, companies, ihaleler, teklifler, yuk-ilanlari, reviews, disputes, settings, sponsors, site-contact-messages, homepage-editor vb.).
- **musteri/**, **nakliyeci/**: Dashboard, profil, teklifler, ihaleler, ledger, galeri, evraklar, bildirimler vb.
- **home**, **defter**, **firmalar**, **ihaleler**, **pazaryeri**, **blog**, **faq**, **contact**, **tools**, **kvkk**, **emails**, **vendor** (mail şablonları), **partials**, **wizard**.

### 3.4 config/

- **nakliyepark.php**: KVKK veri saklama süresi, nakliyeci paketleri (baslangic, profesyonel, kurumsal; fiyat, teklif limiti, özellikler).  
- **seo.php**: Meta, site doğrulama kodları (Google, Yandex, Bing).  
- **turkey_city_coordinates.php**: İl koordinatları (firma haritasında fallback).  
- **volume_calculator.php**: Hacim hesaplama aracı ayarları.

---

## 4. İş Akışları (Flow Analysis)

### 4.1 Kullanıcı Giriş ve Yetkilendirme

1. **Kayıt** (`/register`):  
   - name, email, phone, password, role (musteri | nakliyeci).  
   - BlockedEmail, BlockedPhone, BlockedIp kontrolü.  
   - User oluşturulur, `Registered` event, WelcomeNotification, AdminNotifier::notify('user_registered').  
   - Nakliyeci ise `nakliyeci.company.create`, değilse `musteri.dashboard` yönlendirmesi.

2. **Normal giriş** (`/login`):  
   - BlockedEmail, BlockedIp kontrolü → Auth::attempt.  
   - Admin hesabı burada kabul edilmez (bilerek “hatalı giriş” mesajı).  
   - Blocked kullanıcı/telefon kontrolü.  
   - Role’e göre: admin → admin.dashboard, nakliyeci → nakliyeci.dashboard, musteri → musteri.dashboard.

3. **Admin girişi** (`/yonetici/admin`):  
   - Sadece role=admin kabul; diğerleri “Bu sayfa sadece yöneticiler içindir” ile çıkış.

4. **Çıkış** (`/logout`): Session invalidate, token regenerate, `/` yönlendirme.

5. **E-posta doğrulama**:  
   - `MustVerifyEmail`; doğrulanmamış kullanıcılar `verification.notice` sayfasına yönlendirilebilir.  
   - `verification.verify` (signed URL) ile doğrulama; sonrası role’e göre dashboard.

6. **Şifre sıfırlama**: Standart Laravel flow; ResetPasswordNotification Türkçe özelleştirilmiş.

7. **Her istekte**: `EnsureNotBlocked` (web middleware) ile kullanıcı/IP/telefon engeli kontrol edilir; engelli ise logout + login’e yönlendirme.

### 4.2 Admin İşlemleri (Özet)

- **İhaleler**: Liste, oluşturma, düzenleme, silme, **durum güncelleme** (draft → published → closed vb.).  
- **Teklifler**: Liste, düzenleme, silme, **bekleyen güncelleme onay/red** (nakliyeci tutar güncelleme talebi).  
- **Firmalar**: Onay/red, paket atama, engelleme, düzenleme, silme.  
- **Kullanıcılar**: Düzenleme, silme; blocklist ile e-posta/telefon/IP engelleme, kullanıcı/firma engelleme.  
- **Defter**: Yuk ilanları CRUD.  
- **İçerik**: Blog (AI üretim dahil), kategori, FAQ, oda şablonları.  
- **Ayarlar**: Genel site, mail, paketler, araç sayfaları, test mail.  
- **Reklam**: Ad zones, defter reklamları.  
- **Sponsorlar**, **iletişim mesajları** (site contact), **anasayfa bölüm sırası**.  
- **Uyuşmazlıklar**: Liste, detay, çözüm (resolve).  
- **Değerlendirmeler**: Düzenleme, silme.  
- **Consent log**: KVKK açık rıza kayıtları.  
- **Bildirimler**: Admin bildirimleri listesi, okundu işaretleme.

### 4.3 Veri Ekleme – Güncelleme – Silme (Tipik Akışlar)

- **İhale oluşturma** (GuestWizard): Validasyon → Ihale::create (status=pending) → ConsentLog::log → AdminNotifier → IhaleCreatedNotification (müşteri/misafir e-posta) → NewIhaleAdminNotification (admin’lere) → Fotoğraflar (IhalePhoto).  
- **İhale yayınlama**: Admin ihale status → published.  
- **Teklif verme** (Nakliyeci): Firma onaylı mı, engelli mi, aylık teklif limiti → Teklif::create → AdminNotifier → UserNotification + TeklifReceivedNotification (müşteri/misafir).  
- **Teklif kabul** (Musteri): Diğer teklifler rejected, seçilen accepted, ihale closed → AuditLog::log → UserNotification + TeklifAcceptedNotification (nakliyeci).  
- **Teklif kabul geri alma**: 10 dakika içinde; teklif pending, ihale published.  
- **Defter ilanı** (Ledger): YukIlani::create (company_id, status=active). Limit: firma başına 300 aktif ilan.  
- **Defter yanıtı**: DefterYaniti::create (yuk_ilani_id, company_id, body).  
- **İletişim mesajı** (müşteri → firma): ContactMessage::create → ContactMessageToCompanyNotification.  
- **Uyuşmazlık**: Dispute::create (reason, description); admin resolve eder.  
- **Değerlendirme**: Review::create; admin review düzenleyebilir/silebilir.  
- **Site iletişim**: SiteContactMessage::create (contact formu).  
- Kritik tablolarda **soft delete** kullanılır: companies, ihaleler, teklifler, reviews, contact_messages.

### 4.4 Arka Planda Çalışan İşlemler

- **Cron / schedule**: `routes/console.php` içinde yalnızca `inspire` komutu tanımlı; **zamanlanmış görev (ihale otomatik kapatma, veri silme vb.) yok**.  
- **Queue**: Config’te `QUEUE_CONNECTION` varsayılan `database`; `jobs` tablosu migration’da var. Uygulama kodunda `Queue::` / `dispatch` / Job sınıfı kullanımı **yok**; bildirimler ve mail’ler senkron gönderiliyor.  
- **Özet**: Arka planda otomatik job/cron akışı tanımlı değil.

### 4.5 Loglama ve Hata Yakalama

- **Loglama**: Laravel standart `Log`; `config/logging.php` içinde `admin_actions` kanalı (daily, 90 gün) tanımlı. Login hataları `Log::warning('Failed login attempt', ...)` ile loglanıyor.  
- **Hata yakalama**: `bootstrap/app.php` içinde `withExceptions` boş; Laravel varsayılan exception handler kullanılıyor.  
- **Audit**: Kritik işlemler (örn. teklif kabul) `AuditLog::log` ile kaydediliyor; admin işlemleri `AuditLog::adminAction` ile (before/after state, reason).

---

## 5. Veritabanı Analizi

### 5.1 Tablolar ve Amaçları

| Tablo | Amaç |
|-------|------|
| **users** | Kullanıcılar (admin, nakliyeci, musteri). name, email, password, role, phone, avatar, email_verified_at, blocked_at. |
| **password_reset_tokens** | Şifre sıfırlama token’ları. |
| **sessions** | Oturumlar. |
| **companies** | Nakliye firmaları. user_id (FK), name, slug, tax_number, tax_office, address, city, district, phone, email, description, logo, services (JSON), approved_at, package, blocked_at, e-posta/telefon/resmi firma doğrulama, SEO alanları, live_latitude/longitude, map_visible. Soft deletes. |
| **ihaleler** | Taşınma ihaleleri. user_id (nullable; misafir ihale), preferred_company_id, service_type, room_type, from/to (city, address, district, neighborhood, postal_code), distance_km, move_date, move_date_end, volume_m3, description, status (draft/pending/published/closed/completed), slug, guest_contact_* . Soft deletes. |
| **ihale_photos** | İhale fotoğrafları. ihale_id (FK), path, sort_order. |
| **teklifler** | İhalelere verilen teklifler. ihale_id, company_id, amount, message, status (pending/rejected/accepted), pending_amount, pending_message, reject_reason, accepted_at. Soft deletes. Unique(ihale_id, company_id). |
| **yuk_ilanlari** | Defter ilanları (yük ilanı). company_id, from_city, to_city, load_type, load_date, volume_m3, vehicle_type, description, status (active/closed). |
| **defter_yanitlari** | Defter ilanına firma yanıtları. yuk_ilani_id, company_id, body. |
| **reviews** | Müşteri değerlendirmeleri. user_id, company_id, ihale_id, rating, comment, video_path. Soft deletes. |
| **blog_posts** | Blog yazıları. category_id, title, slug, meta_title, excerpt, content, image, published_at, meta_description, featured. |
| **blog_categories** | Blog kategorileri. (Migration: 2026_02_09_200000) |
| **faqs** | SSS. question, answer, sort_order, audience (musteri/nakliyeci/null). |
| **room_templates** | Oda şablonları (ihale wizard). name, default_volume_m3, sort_order. |
| **settings** | Key-value site/mail/paket ayarları. key, value, group. |
| **blocked_emails** | Engelli e-posta listesi. email, reason. |
| **blocked_phones** | Engelli telefon listesi. phone, reason. |
| **blocked_ips** | Engelli IP listesi. ip, reason. |
| **defter_reklamlari** | Defter sayfası reklam alanları. baslik, icerik, resim, link, konum, aktif, sira. |
| **ad_zones** | Genel reklam alanları. sayfa, konum, baslik, tip (code/image), kod, resim, link, sira, aktif. |
| **admin_notifications** | Admin panel bildirimleri. type, title, message, data (JSON), read_at. |
| **user_notifications** | Kullanıcı (müşteri/nakliyeci) bildirimleri. id (UUID), user_id, type, title, message, data, read_at. |
| **pazaryeri_listings** | Pazaryeri araç ilanları. company_id (nullable), title, vehicle_type, listing_type (sale/rent), price, city, year, description, image_path, status. |
| **contact_messages** | Müşteri–firma iletişim mesajları (ihale + kabul edilen teklif bağlamında). ihale_id, teklif_id, from_user_id, company_id, message. Soft deletes. |
| **disputes** | Uyuşmazlık kayıtları. ihale_id, company_id, opened_by_user_id, opened_by_type, reason, description, status (open/admin_review/resolved), admin_note, resolved_by_user_id, resolved_at. |
| **consent_logs** | KVKK açık rıza logları. consent_type, ip, user_agent, user_id, ihale_id, meta (JSON), consented_at. |
| **audit_logs** | Denetim kayıtları. action, actor_type, actor_id, user_id, subject_type, subject_id, old_values, new_values, action_reason, ip, user_agent. |
| **ihale_jobs** | İş takibi (komisyon/iptal için). ihale_id, teklif_id, company_id, started_at, completed_at, cancelled_at, cancelled_reason, agreed_amount, final_amount, status (active/completed/cancelled). |
| **site_contact_messages** | İletişim formu mesajları. name, email, subject, message, read_at. |
| **sponsors** | Sponsorlar. name, logo, url, sort_order, is_active. |
| **cache**, **cache_locks** | Laravel cache (migration 0001_01_01_000001). |
| **jobs**, **job_batches**, **failed_jobs** | Queue (Laravel varsayılan). |
| **company_documents**, **company_contracts**, **company_vehicle_images** | Firma evrakları, sözleşmeler, araç görselleri (migration 2026_02_09_100001). |

### 5.2 İlişkiler (ER Mantığı)

- **User** 1–1 **Company** (nakliyeci için).  
- **User** 1–N **Ihale** (müşteri ihaleleri; misafir ihalelerde user_id null).  
- **User** 1–N **Review**.  
- **User** 1–N **UserNotification**.  
- **Company** N–1 **User**.  
- **Company** 1–N **Teklif**, 1–N **YukIlani**, 1–N **Review**, 1–N **ContactMessage** (alıcı taraf), 1–N **DefterYaniti**, 1–N **CompanyDocument**, **CompanyContract**, **CompanyVehicleImage**.  
- **Ihale** N–1 **User** (nullable), N–1 **Company** (preferred_company_id), 1–N **IhalePhoto**, 1–N **Teklif**, 1–1 **Teklif** (acceptedTeklif), 1–N **Dispute**, 1–N **ContactMessage**.  
- **Teklif** N–1 **Ihale**, N–1 **Company**.  
- **YukIlani** N–1 **Company**, 1–N **DefterYaniti**.  
- **DefterYaniti** N–1 **YukIlani**, N–1 **Company**.  
- **Review** N–1 **User**, N–1 **Company**, N–1 **Ihale**.  
- **ContactMessage** N–1 **Ihale**, N–1 **Teklif**, N–1 **User** (from_user_id), N–1 **Company**.  
- **Dispute** N–1 **Ihale**, N–1 **Company**, N–1 **User** (opened_by, resolved_by).  
- **BlogPost** N–1 **BlogCategory**.  
- **ConsentLog** N–1 **User** (nullable), N–1 **Ihale** (nullable).  
- **AuditLog** N–1 **User** (nullable).  
- **IhaleJob** N–1 **Ihale**, N–1 **Teklif**, N–1 **Company**.  
- **PazaryeriListing** N–1 **Company** (nullable).

Pivot tablo yok; many-to-many ilişki kullanılmıyor. Tüm ilişkiler foreign key ile 1–N veya N–1.

### 5.3 Primary / Foreign Key Özeti

- Tüm ana tablolar `id` (bigInteger, auto increment) primary key.  
- `user_notifications.id` UUID.  
- Foreign key’ler: user_id, company_id, ihale_id, teklif_id, yuk_ilani_id, from_user_id, opened_by_user_id, resolved_by_user_id vb. Constraint’ler migration’larda tanımlı (constrained, cascadeOnDelete veya nullOnDelete).  
- Unique: users.email; teklifler (ihale_id, company_id); blocked_emails.email; blocked_phones.phone; blocked_ips.ip; settings.key; blog_posts.slug.

---

## 6. Kod Kalitesi ve Standartlar

### 6.1 Tekrarlar

- Firma/nakliyeci kontrolü birçok Nakliyeci controller’da tekrarlanıyor (“firma var mı, onaylı mı, engelli mi”).  
- Benzer şekilde “ihale bana ait mi” kontrolü Musteri IhaleController’da tekrar eden if’lerle yapılıyor.  
- Bu kontroller **trait veya base controller / middleware** ile toplanabilir.

### 6.2 SOLID

- **S**: Service’ler (AdminNotifier, BlogAiService) tek sorumluluk veriyor; controller’lar hâlâ bazen “şişman”.  
- **O**: Genişleme için interface/abstract kullanımı yok; yeni bildirim/kanal eklemek doğrudan kod değişikliği gerektirir.  
- **L/D**: Soyutlama az; özellikle repository/query object yok.  
- **I**: Ayrı arayüzler yok.  

### 6.3 Güvenlik

- **SQL Injection**: Eloquent ve named binding kullanıldığı için doğrudan raw SQL birleştirme yok; **LIKE** ile kullanıcı girişi birleştirilen yerler (örn. DefterController, FirmaController `where('from_city', 'like', '%'.$request->nereden.'%')`) var. Parametreler request’ten geliyor; validation ile sınırlı. Ek koruma için input sanitize veya strict validation düşünülebilir.  
- **Auth**: Rol kontrolü middleware ve controller’da; Policy kullanılmadığı için yetki kuralları dağınık. IDOR riski: Route model binding ile ilgili kayıt yükleniyor; “bu ihale bana ait mi” gibi kontroller mevcut ama merkezi değil.  
- **CSRF**: Laravel web route’ları için CSRF token kullanılıyor.  
- **XSS**: Blade `{{ }}` escape ediyor; `{!! !!}` kullanımları kontrol edilmeli (zengin içerik varsa sanitize).  
- **Rate limit**: Login, register, ihale oluşturma, teklif, defter yanıtı, iletişim formu vb. throttle ile sınırlı.  
- **Blocklist**: E-posta, telefon, IP ve kullanıcı/firma engelleme ile kötüye kullanım kısmen engelleniyor.

### 6.4 Performans

- **N+1**: Birçok yerde `with()` / `withCount()` kullanılmış; bazı listelerde ilişkiler eksik olabilir (inceleme önerilir).  
- **Settings**: Setting::get Cache::remember (300 sn) kullanıyor; iyi.  
- **Index**: status, approved_at, company_id, (sayfa, konum, aktif) gibi alanlara index migration’larda eklenmiş.  
- **Büyük listeler**: Pagination kullanılıyor (ihaleler, defter, firmalar vb.).  
- **Mail/Bildirim**: Senkron gönderim; yoğun trafikte queue’ya alınması performans ve güvenilirlik açısından faydalı olur.

---

## 7. Eksikler ve Riskler

### 7.1 Mantıksal / İş Kuralları

- İhale durumu “pending” → “published” geçişi sadece admin üzerinden; otomatik yayınlama veya zamanlı yayın yok.  
- Teklif kabulünden sonra “iş tamamlandı / iptal” akışı için **IhaleJob** tablosu var ama bu akışın controller/job tarafında tam kullanılıp kullanılmadığı ayrıca kontrol edilmeli (komisyon hesaplama, raporlama).  
- Misafir ihale: guest_contact bilgileri ile oluşturuluyor; teklif geldiğinde e-posta ile bilgilendirme var; misafirin “ihalemi görüntüle” gibi güvenli bir link ile takip etmesi için mekanizma dokümante edilmeli veya geliştirilmeli.

### 7.2 Güvenlik Riskleri

- Policy olmaması: Tüm yetki kurallarının controller’da tutarlı uygulandığının periyodik gözden geçirilmesi gerekir.  
- Hassas ayarlar (mail şifresi, API key’ler) settings tablosunda saklanıyorsa, veritabanı sızıntısında risk oluşur; kritik gizliler .env’de kalmalı.  
- E-posta doğrulama zorunlu mu (middleware ile korunan route’lar var mı) netleştirilmeli.

### 7.3 Ölçeklenebilirlik

- Tek sunucu monolit; yatay ölçekleme için session/cache (örn. Redis) ve queue worker ayrımı düşünülebilir.  
- Mail ve bildirimler queue’ya alınarak hem yanıt süresi hem sunucu yükü iyileştirilebilir.  
- Büyük listelerde filtreleme (şehir, tarih, status) ve index kullanımı gözden geçirilebilir.

### 7.4 İyileştirme Önerileri

- **Form Request** sınıfları: Validasyon kurallarını controller’dan çıkarıp tekrar kullanılabilir hale getirmek.  
- **Policy** sınıfları: Ihale, Teklif, Company, Review vb. için “view”, “update”, “delete” kurallarını merkezileştirmek.  
- **Queue**: Bildirim ve e-posta gönderimini job’lara taşımak.  
- **Schedule**: Eski/kapalı ihaleleri arşivleme, KVKK saklama süresi dolan verileri anonimleştirme gibi görevler eklenebilir.  
- **API / Test**: İleride mobil veya harici entegrasyon için API katmanı ve test coverage artırılabilir.  
- **Kod tekrarları**: Nakliyeci/Musteri ortak kontrolleri trait veya middleware ile toplamak.

---

## 8. Teknik Dokümantasyon Özeti

Bu doküman, NakliyePark projesinin:

- **Genel mimarisini** (monolitik, MVC, rol tabanlı),
- **Teknoloji stack’ini** (Laravel 12, PHP 8.2, Tailwind, Vite, OpenAI entegrasyonu),
- **Klasör ve dosya yapısını** (controller, model, service, notification, middleware),
- **İş akışlarını** (giriş/kayıt, admin, ihale–teklif–kabul, defter, bildirimler),
- **Veritabanını** (tüm tablolar, ilişkiler, PK/FK, soft delete),
- **Kod kalitesi ve güvenlik** gözlemlerini,
- **Eksikler ve iyileştirme önerilerini**

tek bir referans metinde toplar. Projeye yeni giren bir yazılımcı bu dokümanı takip ederek uygulamanın nasıl çalıştığını ve nerede neyin bulunduğunu anlayabilir. Detaylı davranış için ilgili controller, model ve route dosyalarına doğrudan bakılmalıdır.

---

*Belge, proje kaynak koduna dayalı analiz ile üretilmiştir; varsayım yapılmamıştır.*
