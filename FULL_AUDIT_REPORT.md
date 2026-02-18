# NakliyePark — Tam Fonksiyonel & Güvenlik Denetim Raporu

**Tarih:** 18 Şubat 2026  
**Rol:** Senior Full-Stack Engineer + QA Lead + Security Engineer  
**Kapsam:** Tüm modüller, tüm roller, tüm akışlar, edge-case’ler, güvenlik ve e-posta davranışı  
**Yöntem:** Varsayım yapılmadan, yalnızca kod üzerinden doğrulama

---

## 1. DOĞRULANAN DAVRANIŞLAR (Kod ile teyit edildi)

Aşağıdaki maddeler kod incelemesiyle **doğrulandı**; ek açık tespit edilmedi.

### 1.1 Roller ve yetki

- **Admin paneli:** `routes/web.php` satır 212: `Route::middleware(['auth', 'role:admin'])->prefix('admin')`. Sadece `role:admin` erişebilir; musteri/nakliyeci 403 (`EnsureRole`, satır 17–18).
- **Müşteri paneli:** `role:musteri` middleware; nakliyeci/admin bu route’lara 403.
- **Nakliyeci paneli:** `role:nakliyeci`; musteri/admin 403.
- **Normal login:** Admin hesabıyla `/login` POST yapılırsa giriş kabul edilmiyor, logout + “Girdiğiniz e-posta veya şifre hatalı” (`LoginController` satır 84–91).
- **Guest:** Korumalı panel route’ları `auth` ile korunuyor; guest login sayfasına yönlendiriliyor (`bootstrap/app.php` redirectGuestsTo).

### 1.2 İhale akışları

- **pending → published:** Sadece Admin (`Admin\IhaleController::updateStatus` veya `bulkPublish`). Müşteri/nakliyeci bu durumu değiştiremiyor.
- **published → closed:** Müşteri `close()` veya teklif kabulü; admin `updateStatus`/`bulkClose`. Bekleyen teklifler rejected yapılıyor (transaction ile).
- **published → draft:** Sadece müşteri `pause()`; sadece `published` iken geçerli.
- **draft/closed → published:** Sadece müşteri `open()`; `acceptedTeklif` varsa red.
- **Misafir ihale:** `user_id = null`, `guest_contact_*` dolu; `IhalePolicy::view` ile `user_id === $user->id` kontrolü nedeniyle müşteri panelinde başkasının/guest ihalesi görünmüyor (403). Guest için “talep sahibi” paneli yok; bildirim/e-posta `guest_contact_email`’e gidiyor.
- **Tek teklif kabulü:** `Musteri\IhaleController::acceptTeklif` içinde transaction: diğer teklifler `rejected`, ihale `closed`.
- **Kabul geri alma:** `undoAcceptTeklif`; `Teklif::canUndoAccept()` 10 dakika kuralı (`Teklif::ACCEPT_UNDO_MINUTES`); süre dışında “Kabul geri alınamaz” mesajı.
- **draft görünürlük:** Public liste `Ihale::where('status', 'published')` (`IhaleController::index`); draft listede yok. Müşteri sadece kendi ihalelerini `IhalePolicy::view` ile görüyor.

### 1.3 Teklif sistemi

- **Aynı firma, aynı ihale, ikinci teklif:** `Nakliyeci\IhaleController::storeTeklif` ve `Nakliyeci\TeklifController::store` içinde `Teklif::where('ihale_id', $ihale->id)->where('company_id', $company->id)->exists()` ile engelleniyor.
- **Paket limiti:** `$company->canSendTeklif()` kontrolü var; limit doluysa “Bu ay için teklif limitiniz dolmuştur” ile red.
- **Teklif güncelleme talebi:** Nakliyeci `pending_amount`/`pending_message` gönderiyor; `Admin\TeklifController::approvePendingUpdate` onaylayınca `amount`/`message` güncelleniyor; `rejectPendingUpdate` sadece pending’i temizliyor. Admin panelden teklif status’unu `accepted` yapınca ihale kapatılıyor, diğer teklifler rejected (`TeklifController::update` transaction).
- **Admin teklif reddi:** `Admin\IhaleController::rejectTeklif` sadece `AdminNotifier::notify` çağırıyor; nakliyeciye e-posta/bildirim **gönderilmiyor** (istenen davranış).

### 1.4 Müşteri yetkileri

- **Sadece kendi ihaleleri:** Tüm müşteri ihale aksiyonları `$this->authorize('view', $ihale)` ile korunuyor; `IhalePolicy::view` sadece admin veya `ihale->user_id === $user->id` izin veriyor.
- **Başkasının ihalesine view/update/close:** Policy ile 403; ayrıca `close`/`open`/`pause` sadece kendi ihalesinde çağrılabiliyor.
- **Kabul sonrası mesaj:** `storeContactMessage`; `teklif->ihale_id === $ihale->id` ve `teklif->status === 'accepted'` kontrolü; mesaj `ContactMessageToCompanyNotification` ile sadece o teklifin firmasına (company->user) gidiyor.
- **Review:** `authorize('createReview', $ihale)` (IhalePolicy); aynı ihale+firma için ikinci review `Review::where(user_id, company_id, ihale_id)->exists()` ile engelleniyor.

### 1.5 Nakliyeci yetkileri

- **Firma onaysız:** `storeTeklif` ve `TeklifController::store` içinde `$company->isApproved()` kontrolü; defter `create`/`store`/`storeReply` içinde “firmanızın onaylanmış olması gerekir” ile engelleniyor.
- **Sadece published ihaleler:** `Ihale::where('status', 'published')` (nakliyeci ihale listesi ve teklif için ihale seçimi).
- **Kendi defter ilanına yanıt:** `LedgerController::storeReply` içinde `$yukIlani->company_id === $company->id` ise “Kendi ilanınıza yanıt yazamazsınız.” ile red.
- **Harita:** `FirmaController::map` sadece `map_visible = true` firmaları alıyor; canlı konum yalnızca `live_location_updated_at >= now()->subHours(2)` ise kullanılıyor, aksi halde il merkezi fallback (kod satır 69–96).

### 1.6 Admin yetkileri ve toplu işlemler

- **Toplu yayınlama:** `bulkPublish` yalnızca `whereIn('id', $ids)->where('status', 'pending')` ihaleleri `published` yapıyor; her biri için müşteri/guest ve tercihli firmaya bildirim + e-posta tetikleniyor.
- **Toplu kapatma:** `bulkClose` yalnızca `published` ihaleleri kapatıyor; pending teklifler rejected.
- **Toplu silme:** `bulkDelete`; silinen kayıtlar için AuditLog yazılıyor.
- **Firma onayı:** İlk onayda (`!$wasApproved`) `CompanyApprovedNotification` firmaya (`SafeNotificationService::sendToUser`) gidiyor (`CompanyController::approve` ve `update` içinde checkbox).
- **Firma reddi:** `CompanyController::reject` sadece `AdminNotifier::notify`; nakliyeciye e-posta **gönderilmiyor** (istenen davranış).

### 1.7 E-posta – GİTMEMESİ gerekenler (doğrulandı)

- Admin teklif reddi → nakliyeci: Sadece `AdminNotifier::notify`; `SafeNotificationService::sendToUser` yok. **Doğru.**
- Müşteri teklif reddi → firma: `rejectTeklif` sadece DB güncellemesi + AuditLog; mail/notification yok. **Doğru.**
- Review sonrası → firma: `ReviewController::store` sadece `AdminNotifier::notify`; firmaya mail yok. **Doğru.**
- İletişim formu → müşteri: `ContactController::store` sadece `AdminNotifier::notify`; müşteriye otomatik mail yok. **Doğru.**

### 1.8 E-posta – GİTMESİ gerekenler (doğrulandı)

- **IhalePublishedNotification:** Admin `updateStatus`/`bulkPublish` ile published yapılınca müşteri veya `guest_contact_email`’e; tercihli firma varsa `IhalePreferredCompanyPublishedNotification` tercihli firmaya. Kod: `Admin\IhaleController` 142–156, 186–201.
- **TeklifReceivedNotification:** Nakliyeci teklif verince müşteri veya guest e-postasına. `Nakliyeci\IhaleController::storeTeklif` ve `Nakliyeci\TeklifController::store`.
- **TeklifAcceptedNotification:** Müşteri teklif kabul edince firmaya. `Musteri\IhaleController::acceptTeklif` 54.
- **CompanyApprovedNotification:** Firma ilk kez onaylanınca firmaya. `Admin\CompanyController::approve` ve `update` (checkbox).

### 1.9 Güvenlik (doğrulanan)

- **IDOR ihale:** Müşteri `/musteri/ihaleler/{ihale}` için `authorize('view', $ihale)`; slug ile başka ihale denense policy 403.
- **IDOR teklif:** `acceptTeklif(ihale, teklif)` içinde `teklif->ihale_id !== $ihale->id` ise 404; ihale sahibi kontrolü policy ile.
- **IDOR review:** `create`/`store` için `authorize('createReview', $ihale)`; sadece ihale sahibi. Duplicate review engeli var.
- **Mass assignment:** İhale oluşturmada `user_id` ve `status` request’ten alınmıyor; GuestWizardController’da sunucu tarafında set ediliyor. Teklif oluşturmada `company_id` auth’tan. Admin teklif update’te `request->only(['amount','message','status'])` ve status whitelist.
- **Mail hata:** `SafeNotificationService::sendToUser`/`sendToEmail` try/catch; hata durumunda kullanıcıya hata dönmüyor, log + AdminNotification. **Doğru.**

### 1.10 Diğer (önceki denetimle uyumlu)

- Şifre sıfırlama: `BlockedEmail::isBlocked` kontrolü (`ResetPasswordController` 45–49).
- Tercihli firma: `Rule::exists('companies','id')->whereNotNull('approved_at')` (`GuestWizardController` 85).
- Defter: Liste ve detayda `whereNull('companies.deleted_at')` ve show’da `!$yukIlani->company` ise 404 (`DefterController`, `Nakliyeci\LedgerController`).
- KVKK iletişim formu: `kvkk_consent` accepted + ConsentLog.

---

## 2. BULGULAR (Düzeltme veya iyileştirme önerisi)

### BULGU 1 — Admin panelden teklif kabul edildiğinde firmaya e-posta gitmiyor

**Etkilenen modül:** Teklif / Bildirim  
**Risk seviyesi:** Medium

**Açıklama:**  
Müşteri panelinden teklif kabul edildiğinde `TeklifAcceptedNotification` firmaya gidiyor. Admin, teklif düzenleme ekranından teklifin durumunu “kabul” yaptığında ihale kapanıyor ve diğer teklifler reddediliyor ancak **firmaya (nakliyeciye) TeklifAcceptedNotification gönderilmiyor.** Bu, davranış tutarsızlığı ve nakliyecinin bilgilendirilmemesi riski oluşturur.

**Nasıl tetiklenir:**  
Admin panel → Teklifler → Bir teklif düzenle → Durum: “Kabul edildi” → Kaydet. İhale kapanır ama ilgili firma kullanıcısına e-posta gitmez.

**Kanıt:**  
- `App\Http\Controllers\Admin\TeklifController::update` (satır 46–71): Transaction içinde `status = accepted` atanıyor, ihale closed, diğer teklifler rejected; **hiçbir yerde `SafeNotificationService::sendToUser(..., TeklifAcceptedNotification)` veya `UserNotification::notify` çağrısı yok.**
- Karşılaştırma: `App\Http\Controllers\Musteri\IhaleController::acceptTeklif` (satır 45–54): Aynı işlemden sonra `UserNotification::notify` ve `SafeNotificationService::sendToUser(..., new TeklifAcceptedNotification(...))` çağrılıyor.

**Çözüm önerisi:**  
- **Kod:** Admin `TeklifController::update` içinde `$newStatus === 'accepted'` branch’inde, transaction tamamlandıktan sonra (veya transaction içinde), ilgili teklifin `company->user`’ına hem panel bildirimi hem `TeklifAcceptedNotification` gönderin; mantığı `Musteri\IhaleController::acceptTeklif` ile aynı hale getirin.
- **Mimari:** Teklif kabul edildiğinde bildirim tetikleyen tek bir servis/event kullanılabilir; hem müşteri hem admin kabulü bu yoldan geçirilir (ileride refactor için).

---

### BULGU 2 — Uyuşmazlık (dispute) açma için rate limit yok

**Etkilenen modül:** Uyuşmazlık (Dispute) / Spam & Abuse  
**Risk seviyesi:** Low

**Açıklama:**  
Müşteri, kapalı ihale + kabul edilmiş teklif için uyuşmazlık açabiliyor. Aynı ihale için zaten açık dispute varsa “Bu ihale için zaten açık bir uyuşmazlık kaydınız var” ile engelleniyor; ancak **dispute açma isteği için throttle yok.** Farklı ihaleler için kısa sürede çok sayıda dispute açılarak panel ve bildirimler spam’lenebilir.

**Nasıl tetiklenir:**  
Müşteri olarak birden fazla kapalı ihale için art arda POST `musteri/ihaleler/{ihale}/uyusmazlik` gönderir; sadece “aynı ihale için tek açık dispute” kuralı var, istek sayısı sınırlı değil.

**Kanıt:**  
- `routes/web.php`: `Route::post('/ihaleler/{ihale}/uyusmazlik', ...)` için throttle tanımı yok (karşılaştırma: ihale store `throttle:10,1`, iletişim `throttle:5,1`, teklif `throttle:20,1`).
- `App\Http\Controllers\Musteri\IhaleController::storeDispute`: Sadece yetki, acceptedTeklif, status, duplicate dispute kontrolü; rate limit yok.

**Çözüm önerisi:**  
- **Kod:** Bu route’a örn. `->middleware('throttle:10,1')` veya `throttle:5,1` ekleyin.
- **Mimari:** Kritik müşteri aksiyonları (dispute, şikâyet, destek talebi) için ortak bir throttle/rate-limit politikası tanımlanabilir.

---

### BULGU 3 — Son admin’in rolünün değiştirilmesi engellenmiyor (isteğe bağlı)

**Etkilenen modül:** Admin yönetimi  
**Risk seviyesi:** Low (operasyonel kilitlenme)

**Açıklama:**  
Admin, kullanıcı düzenlemede herhangi bir kullanıcının rolünü (admin dahil) değiştirebiliyor. Eğer sistemde tek admin varsa ve bu admin kendi rolünü musteri/nakliyeci yaparsa veya silinirse admin paneline kimse giremez. (Önceki AUDIT_REPORT R1 ile aynı nokta.)

**Nasıl tetiklenir:**  
Admin → Kullanıcılar → (Kendi hesabı veya son admin) → Rolü musteri/nakliyeci yap → Kaydet. Veya toplu silmede son admin silinirse.

**Kanıt:**  
- `App\Http\Controllers\Admin\UserController::update` (satır 75–89): `role` alanı `required|in:admin,musteri,nakliyeci` ile validate ediliyor; “son admin’i koruma” kontrolü yok.
- `bulkDelete`: Kullanıcılar silinirken “en az bir admin kalmalı” kontrolü yok.

**Çözüm önerisi:**  
- **Kod:** Update’te hedef kullanıcı admin ise ve `role` admin’den farklı yapılıyorsa (veya kullanıcı silinecekse), `User::where('role','admin')->count() <= 1` ise işlemi reddedin ve anlamlı bir mesaj dönün. Aynı mantığı `bulkDelete` için uygulayın (silinecek listede son admin varsa engelleyin).
- **Mimari:** “Son admin” veya “kritik rol” kurallarını tek bir policy/rule sınıfında toplamak bakımı kolaylaştırır.

---

## 3. E-POSTA & BİLDİRİM ÖZET TABLOSU

| Tetikleyen olay | Alıcı | E-posta/Bildirim | Kodda durum |
|-----------------|--------|-------------------|-------------|
| İhale oluşturuldu (üye) | İhale sahibi | IhaleCreatedNotification + panel | Var |
| İhale oluşturuldu (misafir) | guest_contact_email | IhaleCreatedNotification | Var |
| Yeni ihale talebi | Tüm adminler + super_admin_email | NewIhaleAdminNotification + AdminNotifier | Var |
| İhale yayına alındı (müşteri/misafir) | İhale sahibi / guest | IhalePublishedNotification + panel | Var |
| İhale yayına alındı (tercihli firma) | Tercihli firma user | IhalePreferredCompanyPublishedNotification + panel | Var |
| Teklif verildi | Müşteri / guest | TeklifReceivedNotification + panel | Var |
| Teklif kabul (müşteri paneli) | Firma user | TeklifAcceptedNotification + panel | Var |
| Teklif kabul (admin paneli) | Firma user | TeklifAcceptedNotification | **YOK (BULGU 1)** |
| Müşteri → firma iletişim mesajı | Firma user | ContactMessageToCompanyNotification | Var |
| Firma ilk onay | Firma user | CompanyApprovedNotification | Var |
| Admin teklif reddi | Nakliyeci | — | Gönderilmiyor (doğru) |
| Müşteri teklif reddi | Firma | — | Gönderilmiyor (doğru) |
| Review oluşturuldu | Firma | — | Gönderilmiyor (doğru) |
| İletişim formu | Müşteri | — | Gönderilmiyor (doğru) |
| Firma reddi | Nakliyeci | — | Gönderilmiyor (doğru) |
| Mail/notification hatası | — | Kullanıcıya hata dönülmez; AdminNotification + log | SafeNotificationService ile doğru |

---

## 4. GÜVENLİK DENETİMİ ÖZETİ

- **IDOR:** İhale (müşteri paneli) IhalePolicy::view ile; teklif kabulü ihale sahibi + teklif-ihale eşleşmesi ile; review createReview + duplicate kontrolü ile korunuyor. Tespit edilen ek IDOR yok.
- **Rol atlama:** Admin route’ları `role:admin`; musteri/nakliyeci route’ları ilgili role ile korunuyor; normal login admin’i kabul etmiyor. Tespit edilen atlama yok.
- **Mass assignment:** İhale/teklif oluşturma ve ilgili update’lerde kritik alanlar (user_id, company_id, status) request’e açık bırakılmıyor veya whitelist kullanılıyor. Hidden field ile status/fiyat/user_id zorlama tespit edilmedi.
- **Spam/abuse:** İhale, iletişim, teklif, ledger yanıtı, verification resend için throttle var; dispute için throttle yok (BULGU 2).
- **Mail/queue:** Hata durumunda kullanıcıya hata dönülmüyor; log ve AdminNotification kullanılıyor.

---

## 5. RATE LİMİT ÖZETİ (Mevcut)

| Endpoint / Aksiyon | Throttle | Dosya/Konum |
|--------------------|----------|-------------|
| İhale oluştur (POST) | 10/dk | web.php ihale.store, wizard.store |
| İletişim formu | 5/dk | web.php contact.store |
| Login (normal) | 10/dk | web.php guest group |
| Admin login | 20/dk | web.php admin login group |
| E-posta doğrulama resend | 3/dk | web.php verification.send |
| Müşteri/nakliyeci şifre sıfırlama linki | 3/10 dk | bilgilerim.send-reset-link |
| Ledger yanıt | 30/dk | web.php ledger.reply.store |
| Teklif gönder (nakliyeci) | 20/dk | ihaleler.teklif.store, teklif.store |
| Dispute açma | **Yok** | — (BULGU 2) |

---

## 6. EK DOĞRULAMALAR — Önerilen Maddeler

Aşağıdaki maddeler denetimlerde sık sorulan konuları netleştirmek amacıyla kod ile doğrulanıp rapora eklenmiştir.

### 6.1 Transaction & Race Condition Güvencesi  
**Öncelik:** Orta  

Teklif kabulü ve ihale kapanışı işlemleri transaction içerisinde yürütülmekte olup, eş zamanlı isteklerde veri tutarsızlığı oluşmamaktadır.

**Kanıt:** Müşteri teklif kabulü (`Musteri\IhaleController::acceptTeklif`), kabul geri alma (`undoAcceptTeklif`), müşteri ihale kapatma (`close`), admin ihale kapatma (`Admin\IhaleController::updateStatus` / `bulkClose`) ve admin teklif güncelleme (`Admin\TeklifController::update`) işlemleri `DB::transaction()` ile sarılıdır; ihale durumu ile teklif durumları tek atomik blokta güncellenir.

---

### 6.2 Soft Delete Sonrası Yetki ve Akış Kontrolü  
**Öncelik:** Düşük  

Soft delete edilmiş firma ve kullanıcılar aktif akışlarda (teklif kabulü, review, dispute) işleme dahil edilmemektedir.

**Kanıt:** `Company` ve `User` modelleri `SoftDeletes` kullanır; Laravel ilişkileri varsayılan olarak soft silinen kayıtları döndürmez. Defter listesi ve detayda `whereNull('companies.deleted_at')` ile soft silinen firmalar hariç tutulur (`DefterController`, `Nakliyeci\LedgerController`). Firma listesi (`/nakliye-firmalari`) ve harita `approved_at`/`blocked_at` ile filtrelenir; silinen firmalar listelenmez.

---

### 6.3 Audit Log Kapsamının Açıkça Tanımlanması  
**Öncelik:** Düşük  

Kritik admin aksiyonları (ihale durumu değişikliği, teklif kabul/red, firma onay/red) AuditLog ile kayıt altına alınmaktadır.

**Kanıt:** `AuditLog::log` veya `AuditLog::adminAction` kullanılan yerler: teklif kabul/red (müşteri), review oluşturma, admin ihale silme, admin ihale kapatma (teklifler red), admin firma silme, admin teklif silme, admin review silme, firma paket değişikliği. İzlenebilirlik için bu aksiyonlar loglanmaktadır.

---

### 6.4 Queue / Mail Servisi Dayanıklılığı  
**Öncelik:** Düşük  

Bildirim ve e-posta gönderimleri queue üzerinden yürütülmekte olup, servis hatalarında sistemin ana işleyişi etkilenmemektedir.

**Kanıt:** İlgili Notification sınıfları `ShouldQueue` implement eder (IhaleCreated, IhalePublished, TeklifReceived, TeklifAccepted, CompanyApproved, ContactMessageToCompany, Welcome, ResetPassword, CompanyCreateReminder, NewIhaleAdmin, IhalePreferredCompanyPublished). E-posta doğrulama bildirimi (`VerifyEmailNotification`) anında gönderilir. Tüm kullanıcı/hedef e-posta gönderimleri `SafeNotificationService` ile sarılı olup, hata durumunda try/catch ile log ve AdminNotification yazılır; kullanıcıya hata dönülmez ve ana iş akışı (ihale kapatma, teklif kabulü vb.) kesilmez.

---

## 7. SONUÇ VE ÖNCELİK

- **Kritik açık:** Bu incelemede kritik güvenlik açığı tespit edilmedi.
- **Orta öncelik:** BULGU 1 (Admin teklif kabulünde firmaya e-posta) — tutarlılık ve iş kuralı için düzeltilmesi önerilir.
- **Düşük öncelik:** BULGU 2 (Dispute throttle), BULGU 3 (Son admin koruması) — isteğe bağlı iyileştirme.

Tüm fonksiyonel akışlar, yetkiler ve “e-posta gitsin/gitmesin” kuralları kod üzerinden tek tek kontrol edilmiş; yalnızca yukarıdaki bulgular raporlanmıştır. Varsayım yapılmamış, görülmeyen davranış rapora eklenmemiştir.
