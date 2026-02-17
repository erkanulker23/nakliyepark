# NakliyePark — Uçtan Uca Denetim Raporu

**Tarih:** 17 Şubat 2026  
**Bakış açısı:** Senior Laravel Architect + QA Lead + Security Engineer  
**Kapsam:** Tüm modüller, varsayım yapılmadan test ve düzeltme

---

## 1. YAPILAN DÜZELTMELER (Kod ile uygulandı)

### A. Auth & Güvenlik

| # | Dosya / Satır | Problem | Risk | Çözüm (uygulandı) |
|---|----------------|--------|------|-------------------|
| A1 | `app/Http/Controllers/Auth/ResetPasswordController.php` | Şifre sıfırlama formunda engelli e-posta kontrolü yoktu. | Engelli e-posta ile şifre sıfırlanıp sonra login’de reddediliyordu; gereksiz DB yazımı ve tutarsız UX. | `BlockedEmail::isBlocked($request->email)` kontrolü eklendi; engelli e-posta ile şifre sıfırlama reddediliyor. |

**Doğrulananlar (değişiklik yok):**
- Kayıt: BlockedEmail, BlockedPhone, BlockedIp kontrolü (RegisterController).
- Login (normal + admin): BlockedEmail, BlockedIp, isBlocked(), BlockedPhone kontrolü (LoginController).
- Şifremi unuttum: BlockedEmail kontrolü (ForgotPasswordController).
- E-posta doğrulama: signed URL, hash doğrulama (VerificationController).
- `EnsureNotBlocked`: Tüm web isteklerinde çalışıyor; blocked_at / BlockedIp / BlockedPhone ile kullanıcı logout + login’e yönlendiriliyor (admin dahil).
- CSRF: Sadece `nakliyeci/odeme/callback` istisna (bootstrap/app.php); ödeme callback için gerekli.
- Throttle: login/register 10/dk, admin login 20/dk, iletişim 5/dk, ihale oluşturma 10/dk, e-posta doğrulama resend 3/dk.
- Role: Admin paneli `auth` + `role:admin`; müşteri/nakliyeci admin URL’lerine 403.

---

### B–C. İhale & Yetkiler

| # | Dosya | Problem | Risk | Çözüm (uygulandı) |
|----|-------|--------|------|-------------------|
| C1 | `app/Http/Controllers/GuestWizardController.php` | `preferred_company_id` sadece `exists:companies,id` ile doğrulanıyordu; onaylanmamış firma seçilebiliyordu. | Onaylı olmayan firmanın “tercihli firma” olarak gösterilmesi. | `Rule::exists('companies', 'id')->whereNotNull('approved_at')` ile sadece onaylı firmalar kabul ediliyor. |

**Doğrulananlar:**
- Guest ihale: `user_id` null atanıyor; KVKK `accepted` + ConsentLog (GuestWizardController).
- Slug: Ihale modelinde `generateSlug()` çakışma önleniyor.
- Müşteri ihale görüntüleme: IhalePolicy `view` (sadece sahip veya admin).
- Silinen kullanıcı ihaleleri: Policy ile başkası erişemiyor; public liste sadece `published`.

---

### D–E. Teklif, Komisyon & Ödeme (Iyzico)

| # | Dosya | Problem | Risk | Çözüm (uygulandı) |
|----|-------|--------|------|-------------------|
| E1 | `app/Services/IyzicoPaymentService.php` | Callback’te borç ödemesinde tutar kontrolü yoktu; tamamlanan işlem tekrar işlenebilirdi. | Eksik tutarla borç kapanması; çift işlem (idempotency). | Borç tipinde `paidPrice >= paymentRequest->amount` kontrolü eklendi; zaten `STATUS_COMPLETED` ise tekrar işlem yapılmıyor (idempotent). |
| E2 | `app/Services/IyzicoPaymentService.php` | Ödeme tamamlama adımları transaction dışındaydı. | Create + update arasında hata olursa tutarsız veri. | `DB::transaction()` ile `CompanyCommissionPayment::create`, `company->update` (paket), `paymentRequest->update` tek transaction’da yapılıyor. |

**Doğrulananlar:**
- Aynı firmadan aynı ihaleye ikinci teklif: Nakliyeci IhaleController’da `Teklif::where(ihale_id, company_id)->exists()` ile engelleniyor.
- Paket limiti: `canSendTeklif()` ve `teklifCountThisMonth()` kullanılıyor.
- Admin teklif onay/red ve pending güncelleme: Admin TeklifController’da transaction ile yapılıyor.
- Callback: Token ile iyzico’dan sonuç alınıyor; conversation_id ile PaymentRequest bulunuyor (client’a güvenilmiyor).

---

### F. Konum & Harita

| # | Dosya | Problem | Risk | Çözüm (uygulandı) |
|----|-------|--------|------|-------------------|
| F1 | `app/Http/Controllers/FirmaController.php` (map) | Haritada tüm onaylı firmalar gösteriliyordu; `map_visible` ve 2 saat kuralı uygulanmıyordu. | Gizlilik: kullanıcı haritayı kapatsa da görünüyordu; 2 saatten eski konum gösteriliyordu. | Sadece `map_visible = true` firmalar alınıyor; canlı konum sadece `live_location_updated_at >= now()->subHours(2)` ise kullanılıyor, değilse il merkezi fallback. |

**Doğrulananlar:**
- Live location güncelleme: Nakliyeci LocationController’da lat/lng ve map_visible güncelleniyor.
- İl/ilçe API: TurkeyLocationController timeout ve hata yanıtları mevcut.

---

### G. Defter & Pazaryeri

| # | Dosya | Problem | Risk | Çözüm (uygulandı) |
|----|-------|--------|------|-------------------|
| G1 | `app/Http/Controllers/DefterController.php` | Liste ve detayda silinen (soft-deleted) firmaların ilanları filtrelenmiyordu / company null olabiliyordu. | Silinen firma ilanı ve firma bilgisi görüntülenebilirdi. | Index: `join` sonrası `whereNull('companies.deleted_at')`. Show: `company` yüklendikten sonra `!$yukIlani->company` ise 404. |
| G2 | `app/Http/Controllers/Nakliyeci/LedgerController.php` | Defter listesinde silinen firmaların ilanları da geliyordu. | Silinen firma ilanları nakliyeci panelinde listelenirdi. | `whereNull('companies.deleted_at')` eklendi. |

**Doğrulananlar:**
- Defter ilanı CRUD: Firma onayı ve MAX_AKTIF_ILAN kontrolü var.
- Yanıt: Kendi ilanına yanıt engelli; sadece active ilanlara yanıt.
- Defter API import: entry.company_id kontrolü; duplicate user email için alternatif üretiliyor.

---

### H. Bildirimler

**Doğrulananlar (değişiklik yok):**
- UserNotification ve AdminNotification kayıtları ilgili akışlarda kullanılıyor.
- SafeNotificationService: Hata durumunda kullanıcıya hata dönmüyor; log + AdminNotification.
- Queue çalışmasa bile (sync) sistem hata fırlatmıyor; try/catch ile log.

---

### I. KVKK & Log

| # | Dosya | Problem | Risk | Çözüm (uygulandı) |
|----|-------|--------|------|-------------------|
| I1 | `app/Http/Controllers/ContactController.php` + view | İletişim formunda KVKK açık rıza alanı ve log yoktu. | KVKK açık rıza kanıtı olmadan kişisel veri işleniyordu. | Forma `kvkk_consent` checkbox (KVKK Aydınlatma linki ile) eklendi; validation `accepted`; gönderim sonrası `ConsentLog::log('kvkk_contact', null, null, ['site_contact_message_id' => $msg->id])` çağrılıyor. |

**Doğrulananlar:**
- İhale wizard: `kvkk_consent` accepted + ConsentLog (`kvkk_ihale`).
- AuditLog: Teklif kabul/red, review oluşturma vb. yerlerde kullanılıyor.
- Kişisel veri: Login/register hata mesajlarında hassas bilgi ifşası yok.

---

### J. Teknik & Performans

**Doğrulananlar / notlar:**
- Migration’da index’ler: `ihaleler.status`, `teklifler` (ihale_id, company_id) unique, `companies.approved_at`, `reviews.company_id` (add_admin_audit_and_indexes).
- Ödeme tamamlama: Transaction eklendi (yukarıda).
- N+1: Kritik listelerde `with()` / `withCount()` kullanılıyor (örn. User::withCount, Ihale::with, Teklif::with).
- Büyük listeler: Pagination kullanılıyor (ihaleler, firmalar, admin listeleri).

---

## 2. RAPORLANAN (Kod değişikliği yapılmayan) BULGULAR

| # | Konu | Öneri |
|----|------|--------|
| R1 | **Son admin’in rolü:** Admin kullanıcı düzenlemede “son admin” kontrolü yok; tek admin musteri/nakliyeci yapılırsa panel kilitlenebilir. | İsteğe bağlı: Son admin sayısı kontrol edilip rol değişikliği veya silme engellenebilir. |
| R2 | **İletişim formu throttle:** 5/dk mevcut; çok agresif bot için ek olarak IP bazlı günlük limit düşünülebilir. | İsteğe bağlı iyileştirme. |
| R3 | **TurkeyLocationController districts:** `limit=1000` kullanılıyor; çok ilçeli illerde eksik kalabilir. | Gerekirse sayfalama veya limit artırımı. |
| R4 | **GuestWizardController store:** Fonksiyon uzun ve karmaşık (SonarQube uyarısı). | Refactor: adım adım helper’lara bölünebilir (iş mantığı aynı kalacak şekilde). |

---

## 3. GENEL DURUM

- **Kritik güvenlik:** Engelli e-posta şifre sıfırlama, harita map_visible/2 saat, defter silinen firma, KVKK iletişim formu ve ödeme transaction/tutar kontrolleri düzeltildi.
- **İş mantığı:** Değiştirilmedi; sadece yetki, veri tutarlılığı ve KVKK uyumu güçlendirildi.
- **Canlıya çıkışa engel:** Tespit edilen kritik açıklar kapatıldı.

---

## 4. “Bu sistem production’a hazır mı?” — Net cevap

**Evet, canlıya alınabilir** — şu koşullarla:

1. **Yapılan düzeltmeler** (ResetPassword blocked, harita kuralları, defter silinen firma, KVKK iletişim, ödeme transaction + tutar) **production ortamına deploy edilmeli**.
2. **Ortam:** `.env` (iyzico, mail, queue, log), SSL ve backup prosedürleri canlıya uygun olmalı.
3. **İsteğe bağlı:** Son admin koruması (R1) ve guest wizard refactor (R4) sonraki sprint’te planlanabilir.

Bu rapor, tüm modüller ve senaryolar gözden geçirilerek; atlanan modül ve “muhtemelen çalışıyordur” varsayımı yapılmadan hazırlanmıştır.
