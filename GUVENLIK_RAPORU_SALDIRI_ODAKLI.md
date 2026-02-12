# NakliyePark — Saldırı Odaklı Güvenlik Analiz Raporu

**Perspektif:** Senior Security Engineer + QA Lead + Laravel Architect  
**Referans:** OWASP Top 10 (2024), DDoS/bot, SEO/spam, mail/queue abuse, config/ENV  
**Kural:** Sadece analiz; kod değişikliği, refactor ve business logic değişikliği yapılmadı.

---

## 1. OWASP Top 10 (2024) Perspektifi

### 1.1 Broken Access Control

| Kontrol | Risk | Etki | Kod ile çözülebilir? | Kod dışı (infra)? |
|--------|------|------|------------------------|-------------------|
| Admin route'ları `role:admin` middleware ile korunuyor; müşteri/nakliyeci admin prefix'e erişemiyor. | Hayır | - | - | - |
| Musteri ihale/teklif ekranları Policy + authorize ile sahiplik kontrolü yapıyor. | Hayır | - | - | - |
| Public sayfalar (ihaleler, blog, defter, firmalar) herkese açık; liste/detay için yetki gerekmiyor (tasarlanmış davranış). | Hayır | - | - | - |

**Sonuç:** Erişim kontrolü route/middleware ve policy ile uygulanmış; ek kırık erişim senaryosu tespit edilmedi.

---

### 1.2 Injection (SQL / Komut)

| Nokta | Risk | Etki | Kod ile çözülebilir? | Kod dışı (infra)? |
|-------|------|------|------------------------|-------------------|
| Sorgularda **LIKE** ile kullanıcı girdisi: DefterController (nereden, nereye, ara), FirmaController (q, city), IhaleController (from_city, to_city), Admin Ihale/Company/Review/YukIlani/ConsentLog/BlogPost/Musteri (q, filtreler). Girdi doğrudan `'%'.$request->x.'%'` şeklinde birleştiriliyor; **parametre binding yok**. | Evet | Orta | Evet (LIKE için binding: `where('col', 'like', '%'.str_replace(['%','_'], ['\%','\_'], $q).'%')` veya benzeri güvenli birleştirme). | Hayır |
| **orderByRaw** yalnızca sabit parametrelerle kullanılıyor (HomeController, FirmaController: package sırası). | Hayır | - | - | - |
| Eloquent `where`, `whereIn`, `exists` vb. kullanımlarında binding kullanılıyor; ham SQL birleştirme yok. | Hayır | - | - | - |

**Özet:** LIKE birleştirmelerinde kullanıcı girdisi escape edilmediği için teorik SQL injection (özellikle özel karakterlerle) mümkün. Etki veritabanı sızıntısı veya hata tetikleme ile sınırlı; çoğu senaryoda validation ile uzunluk sınırı var.

---

### 1.3 XSS (Cross-Site Scripting)

| Nokta | Risk | Etki | Kod ile çözülebilir? | Kod dışı (infra)? |
|-------|------|------|------------------------|-------------------|
| **Blog içeriği** (`blog/show`): `{!! $post->content !!}` — HTML olarak render ediliyor. İçerik sadece admin tarafından oluşturuluyor. | Evet (düşük) | Düşük | Evet (HTML purifier / izin verilen etiketler; veya sadece Markdown + escape). | Hayır |
| **custom_header_html, custom_footer_html, custom_scripts, seo_head_codes** (layouts/app): Admin panelden Setting ile kaydediliyor, `{!! !!}` ile basılıyor. | Evet (düşük) | Düşük | Evet (aynı şekilde sanitize veya kısıtlı HTML). | Hayır |
| **Reklam alanları** (ad_zones kod, defter_reklamlari kod): Admin tarafından HTML/script kabul ediliyor; `{!! $ad->kod !!}` vb. | Evet (düşük) | Düşük | Evet (isteğe bağlı sanitize; reklam ağı script’leri için CSP ile kısıtlama). | Hayır |
| **Araç sayfaları** (tools/*): `$toolContent` Setting’ten geliyor, `{!! $toolContent !!}`. Admin kaynaklı. | Evet (düşük) | Düşük | Evet | Hayır |
| **FAQ cevabı**: `nl2br(e($faq->answer))` — escape var. | Hayır | - | - | - |
| **Kullanıcı kaynaklı çıktılar** (review comment, contact message, defter yanıt body, ihale description, firma description): Blade `{{ }}` veya `e()` / `nl2br(e())` ile çıktılanıyor. | Hayır | - | - | - |
| **Site contact mesajı** (admin show): `{{ $siteContactMessage->message }}` — escape var. | Hayır | - | - | - |
| **JSON-LD** (faq, pazaryeri, breadcrumb): `json_encode(..., JSON_UNESCAPED_UNICODE)` — script context’te; içerik kontrollü. | Hayır | - | - | - |

**Özet:** XSS riski büyük oranda **admin kaynaklı** ve **kasıtlı HTML/script** alanlarında (blog, ayarlar, reklam, araç içeriği). Kullanıcı/müşteri kaynaklı metinler escape ediliyor. Admin hesabı ele geçerse XSS mümkün; aksi halde risk düşük.

---

### 1.4 CSRF

| Kontrol | Risk | Etki | Kod ile çözülebilir? | Kod dışı (infra)? |
|--------|------|------|------------------------|-------------------|
| Web route’ları Laravel varsayılan **VerifyCsrfToken** middleware’i ile korunuyor. | Hayır | - | - | - |
| Form’larda `@csrf` / `csrf_field()` kullanımı mevcut. | Hayır | - | - | - |

**Sonuç:** CSRF koruması uygulama seviyesinde var; ek açık tespit edilmedi.

---

### 1.5 Auth Bypass

| Kontrol | Risk | Etki | Kod ile çözülebilir? | Kod dışı (infra)? |
|--------|------|------|------------------------|-------------------|
| Normal login’de admin hesabı kabul edilmiyor; admin sadece `/yonetici/admin` ile giriş yapıyor. | Hayır | - | - | - |
| Blocklist (e-posta, telefon, IP) login ve kayıt öncesi kontrol ediliyor. | Hayır | - | - | - |
| Şifre sıfırlama token’ları Laravel standart yapısı ile; süre sınırlı. | Hayır | - | - | - |
| E-posta doğrulama panel route’larında `verified.panel` ile zorunlu (musteri/nakliyeci). | Hayır | - | - | - |

**Sonuç:** Auth bypass’e yönelik ek açık tespit edilmedi.

---

### 1.6 Mass Assignment

| Nokta | Risk | Etki | Kod ile çözülebilir? | Kod dışı (infra)? |
|-------|------|------|------------------------|-------------------|
| **Admin UserController::update**: `$request->only(['name','email','role','phone'])` ve `role` validation `in:admin,musteri,nakliyeci`. Route sadece admin’e açık; rol değişikliği tasarlanmış. | Hayır | - | - | - |
| Diğer controller’larda create/update için ya `$request->validate` + açık alan listesi ya da `only()` ile sınırlı alan kullanılıyor; fillable dışı alanlar toplu atanmıyor. | Hayır | - | - | - |
| **Setting** model: key/value; admin panelden gelen key’ler kontrollü. | Hayır | - | - | - |

**Sonuç:** Kritik mass assignment (örn. yetkisiz rol atama) yalnızca admin route’unda ve bilinçli; dışarıdan privilege escalation görünmüyor.

---

### 1.7 File Upload

| Nokta | Risk | Etki | Kod ile çözülebilir? | Kod dışı (infra)? |
|-------|------|------|------------------------|-------------------|
| İhale foto: `image|max:5120` (5MB). | Evet (düşük) | Düşük | Evet (MIME/extension sıkı kontrolü, dosya adı sanitize, mümkse ayrı storage/domain). | Hayır |
| Galeri: `image|mimes:jpeg,png,jpg,webp|max:5120`. | Evet (düşük) | Düşük | Evet (aynı şekilde). | Hayır |
| Evraklar: `mimes:pdf,jpeg,png,jpg|max:10240`. | Evet (düşük) | Düşük | Evet | Hayır |
| Review video: `mimes:mp4,webm|max:51200`. | Evet (düşük) | Düşük | Evet (içerik/başlık kontrolü, virüs taraması infra’da). | Kısmen |
| Admin: site_logo, site_favicon, blog image, sponsor logo vb. image/file kuralları mevcut. | Evet (düşük) | Düşük | Evet | Hayır |
| Yüklenen dosyalar `storage/app/public` altında; path doğrudan kullanıcı girdisinden oluşmuyor (id/sabit prefix). | Hayır | - | - | - |

**Özet:** Yükleme kuralları (mimes, max) tanımlı; MIME spoofing veya nadir uzantı riski kod tarafında sıkılaştırılabilir; büyük dosya/bandwidth için rate limit veya infra kısıtı eklenebilir.

---

### 1.8 IDOR (Insecure Direct Object Reference)

| Nokta | Risk | Etki | Kod ile çözülebilir? | Kod dışı (infra)? |
|-------|------|------|------------------------|-------------------|
| Musteri ihale/teklif: Policy ve `authorize('view', $ihale)` ile sadece ihale sahibi erişebiliyor. | Hayır | - | - | - |
| Review create/store: İhale sahipliği ve kabul edilen teklif firması kontrolü mevcut. | Hayır | - | - | - |
| Nakliyeci galeri/evrak silme: `$company->vehicleImages()->findOrFail($id)` ile kayıt firma ile eşleşiyor. | Hayır | - | - | - |
| Public sayfalar (ihale/blog/firma/defter detay): Slug veya id ile erişim; liste filtresiz “herkese açık” içerik (tasarlanmış). | Hayır | - | - | - |

**Sonuç:** Yetkili panel aksiyonlarında IDOR koruması var; ek IDOR senaryosu tespit edilmedi.

---

### 1.9 Rate Limit Bypass

| Nokta | Risk | Etki | Kod ile çözülebilir? | Kod dışı (infra)? |
|-------|------|------|------------------------|-------------------|
| Throttle Laravel’in varsayılan **IP tabanlı** sınırı ile uygulanıyor. Aynı IP için geçerli. | Evet | Orta | Kısmen (IP + user agent / fingerprint; captcha aşımda). | Evet (dağıtık IP’lerle bypass: WAF/rate limit, captcha). |
| Login/register/şifre sıfırlama: `throttle:6,1` (guest grubu). | - | - | - | - |
| İletişim: `throttle:5,1`. İhale store: `throttle:10,1`. Teklif/storeReply: 20–30/dk. Verification resend: 3,1. | - | - | - | - |
| **GET** istekleri (/, /ihaleler, /blog, /defter, /nakliye-firmalari, /api/turkey/*) üzerinde **throttle yok**. Yoğun istek ile kaynak tüketimi mümkün. | Evet | Orta | Evet (GET’e genel veya sayfa bazlı throttle). | Evet (reverse proxy/WAF rate limit, DDoS koruması). |

**Özet:** Rate limit uygulama tarafında POST/kritik aksiyonlarda var; bypass için dağıtık IP veya GET flood infra + isteğe bağlı uygulama throttle ile azaltılabilir.

---

## 2. DDoS ve Bot Saldırıları

| Endpoint / Grup | Throttle | Risk | Etki | Kod ile? | Infra? |
|-----------------|----------|------|------|----------|--------|
| POST /login, /register, /sifremi-unuttum | 6/dk (guest) | Düşük | Brute force kısıtlı | - | - |
| POST /iletisim | 5/dk | Düşük | Spam/kaynak kullanımı sınırlı | - | - |
| POST /ihale/olustur, /wizard | 10/dk | Düşük | - | - | - |
| POST verification resend | 3/dk | Düşük | - | - | - |
| GET /, /ihaleler, /blog, /defter, /nakliye-firmalari, /sss, /pazaryeri, araç sayfaları | Yok | Evet | Orta (yüksek istek ile CPU/DB/bandwidth) | Evet (genel veya path bazlı throttle). | Evet (WAF, rate limit, CDN, DDoS koruması). |
| GET /api/turkey/provinces, /api/turkey/districts | Yok | Evet | Düşük–Orta (proxy/yoğun çağrı) | Evet (throttle veya cache). | Evet |
| GET /ihaleler/{ihale}, /blog/{slug}, /nakliye-firmalari/{company}, /defter/ilan/{yukIlani} | Yok | Evet | Orta (slug enumeration veya yoğun detay isteği) | Evet (throttle). | Evet |

**Ek savunma (zorunlu değil):**  
- Login/register/iletişim için captcha (reCAPTCHA vb.) eklenebilir.  
- Şüpheli trafikte challenge (JavaScript/CAPTCHA) veya WAF kuralları.  
- GET istekleri için uygulama veya reverse proxy’de rate limit.

---

## 3. Hack SEO / Spam Senaryoları

| Alan / Akış | HTML/JS injection riski | Spam / arama motoru riski | Risk | Etki | Kod ile? | Infra? |
|-------------|--------------------------|----------------------------|------|------|----------|--------|
| İletişim formu (name, email, subject, message) | Çıktı admin panelde `{{ }}` ile; XSS yok. | Form 5/dk; spam sınırlı. İçerik arama motoruna indexlenmiyor (admin sayfası). | Hayır | - | - | - |
| Review (comment) | `{{ }}` / `Str::limit` ile escape. | Review sadece giriş yapan müşteri; bot için kayıt + ihale + teklif kabul gerekir; zor. | Hayır | - | - | - |
| Defter yanıt (body) | `nl2br(e($yanit->body))`. | Nakliyeci girişi gerekir; throttle 30/dk. | Hayır | - | - | - |
| Blog yorumu | Uygulama içinde blog yorumu yok. | - | - | - | - | - |
| Blog içeriği / meta | Admin kaynaklı; `{!! $post->content !!}`. Admin hesabı ele geçerse SEO spam / zararlı script eklenebilir. | Evet (admin compromise) | Orta | Evet (içerik sanitize, meta uzunluk). | Hayır |
| custom_* / seo_head_codes | Admin; `{!! !!}`. Aynı senaryo. | Evet (admin) | Orta | Evet | Hayır |
| Reklam alanları (kod) | Admin; kasıtlı script. | - | Düşük | Evet (CSP, izin verilen alanlar). | Hayır |
| Public liste sayfaları (ihaleler, firmalar, defter) | Filtreler validation/like ile; çıktılar escape. | Çok sayıda sahte ihale/firma ile spam içerik teorik; kayıt ve (firma) onay gerekir. | Düşük | Evet (captcha, onay süreci, rate limit). | Kısmen |

**Özet:** Kullanıcı kaynaklı form/yanıt çıktıları escape; SEO/spam riski büyük oranda admin hesabı veya toplu sahte kayıt senaryosunda. Kod tarafında sanitize ve sınırlar; infra’da captcha/rate limit eklenebilir.

---

## 4. Mail ve Queue Abuse

| Endpoint / Tetikleyici | Throttle / Sınır | Mail flood riski | Queue doldurma riski | Risk | Etki | Kod ile? | Infra? |
|------------------------|------------------|-------------------|----------------------|------|------|----------|--------|
| POST /sifremi-unuttum | 6/dk (guest) | 6 mail/dk/IP | 6 job/dk (queue kullanılıyorsa) | Düşük | - | - | - |
| POST /register | 6/dk (guest) | 6 welcome/dk | 6 job/dk | Düşük | - | - | - |
| POST /email/verification-notification | 3/dk | 3/dk | 3/dk | Düşük | - | - | - |
| POST /iletisim | 5/dk | Mail yok (sadece DB); bildirim varsa sınırlı. | - | Düşük | - | - | - |
| İhale oluşturma / teklif kabul vb. | 10/dk veya auth + iş akışı | Her aksiyonda sınırlı mail/job. | Throttle ile sınırlı. | Düşük | - | - | - |
| Dağıtık IP’lerle çok sayıda kayıt / şifre sıfırlama | IP bazlı throttle | Evet (çok IP = çok mail) | Evet (çok job) | Evet | Orta | Kısmen (captcha, device fingerprint). | Evet (WAF, global rate limit). |

**Özet:** Mail ve queue tetikleyen noktalar throttle’lı; tek IP için flood riski düşük. Dağıtık saldırıda mail/queue abuse’u kod tarafında captcha/ek doğrulama ile kısmen, büyük ölçüde infra (rate limit, WAF) ile azaltılabilir.

---

## 5. Config ve ENV Güvenliği

| Konu | Risk | Etki | Kod ile çözülebilir? | Kod dışı (infra)? |
|------|------|------|------------------------|-------------------|
| **APP_DEBUG** | Production’da true ise stack trace ve ortam bilgisi sızabilir. | Yüksek | Hayır (uygulama kodu env’i “production’da false zorla” yapmıyor). | Evet (deploy’da APP_DEBUG=false, .env güvenli). |
| **APP_ENV** | production dışı ise daha ayrıntılı hata sayfaları. | Orta | Hayır | Evet |
| **Log level / kanalları** | LOG_LEVEL=debug veya log dosyasının web’den erişilebilmesi. | Orta | Kısmen (log path’i public dışında). | Evet (log dizin izinleri, log yönetimi). |
| **Mail şifresi** | Setting tablosunda `mail_password` saklanıyor; DB sızıntısında SMTP şifresi açığa çıkar. | Evet | Orta | Evet (hassas bilgi sadece .env’de; panelde boş göster, opsiyonel). | Evet (DB erişim kısıtı, şifreleme). |
| **API anahtarları** | OPENAI_API_KEY vb. .env’de; config üzerinden okunuyor, sayfa çıktısında kullanılmıyor. | Hayır | - | - | - |
| **Hata sayfaları** | Laravel varsayılan; debug kapalıyken detay az. | Hayır | - | - | - |

**Özet:** En büyük risk production’da APP_DEBUG/APP_ENV yanlış ayarı ve mail şifresinin DB’de tutulması. Çoğu çözüm deploy ve env yönetimi (infra); mail şifresi tasarımı kod ile de iyileştirilebilir.

---

## 6. Özet Tablo

| Kategori | Risk var mı? | En yüksek etki | Kod ile çözülebilir? | Kod dışı (infra)? |
|----------|--------------|----------------|------------------------|-------------------|
| Broken Access Control | Hayır | - | - | - |
| SQL Injection (LIKE) | Evet | Orta | Evet | Hayır |
| XSS (admin / reklam / blog) | Evet (düşük) | Düşük | Evet | Hayır |
| CSRF | Hayır | - | - | - |
| Auth Bypass | Hayır | - | - | - |
| Mass Assignment | Hayır | - | - | - |
| File Upload | Evet (düşük) | Düşük | Evet | Kısmen |
| IDOR | Hayır | - | - | - |
| Rate limit bypass / GET DDoS | Evet | Orta | Evet | Evet |
| Mail/Queue abuse (dağıtık) | Evet | Orta | Kısmen | Evet |
| SEO/Spam (admin/sahte kayıt) | Evet (düşük–orta) | Orta | Evet | Kısmen |
| Config/ENV/Debug | Evet | Yüksek (debug açık) | Kısmen | Evet |

---

**Rapor sonu.**  
Bu dokümanda yalnızca mevcut kod ve yapılandırma incelenmiş; kod değişikliği, refactor veya business logic değişikliği yapılmamıştır.
