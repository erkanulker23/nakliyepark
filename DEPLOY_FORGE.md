# Laravel Forge ile NakliyePark Kurulumu

Bu doküman, projeyi GitHub'dan Laravel Forge ile deploy etmek için gerekli adımları içerir.

## 1. Forge'da Site Oluşturma

- **Repository:** GitHub'dan `nakliyepark` repo'sunu seçin (URL: `https://github.com/erkanulker23/nakliyepark.git`)
- **Branch:** `main`
- **Web Directory:** Boş bırakın (Laravel `public` kullanır)
- **PHP Version:** 8.2 veya üzeri

## 2. Nginx / Web Root

Laravel için site root **proje dizini** olmalı. Forge genelde `~/nakliyepark.com` gibi bir path kullanır.  
Nginx'te document root'u **`/public`** yapın:

- Site ayarlarında "Web Directory" alanına: **`public`** yazın.

Böylece Nginx `.../current/public` (zero-downtime) veya `.../nakliyepark.com/public` (standart) kullanır.

## 3. Environment (.env)

Forge > Site > **Environment** sekmesinde `.env` değişkenlerini girin. En az şunlar gerekli:

```env
APP_NAME=NakliyePark
APP_ENV=production
APP_KEY=base64:...   # php artisan key:generate ile üretin veya Forge "Generate" ile
APP_DEBUG=false
APP_URL=https://SITE_DOMAIN

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

Forge'da **Database** ile MySQL oluşturup kullanıcı/şifre bilgilerini buraya yazın.

## 4. Deploy Script (Forge > Deployments)

Forge yeni sitelerde **zero-downtime** kullanıyorsa script'e şunlar zaten eklenir:  
`$CREATE_RELEASE()`, `cd $FORGE_RELEASE_DIRECTORY`, `$ACTIVATE_RELEASE()`.  
Aşağıdaki blok, **"Deploy Script"** alanına eklenmesi gereken kısımdır (git pull / release oluşturma Forge tarafından yapılır):

```bash
$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

if [ -f package.json ]; then
    npm ci
    npm run build
fi

$FORGE_PHP artisan migrate --force
$FORGE_PHP artisan storage:link 2>/dev/null || true
$FORGE_PHP artisan config:cache
$FORGE_PHP artisan route:cache
$FORGE_PHP artisan view:cache
$FORGE_PHP artisan queue:restart 2>/dev/null || true
```

Repo'daki `deploy.sh` dosyası referans içindir; asıl çalışan script Forge panelindeki "Deploy Script" alanıdır.

## 5. Shared Paths (Zero-Downtime kullanıyorsanız)

Varsayılan olarak `.env` paylaşılır. Ek olarak storage kalıcı olsun isterseniz:

- **storage** (veya Forge’un önerdiği `storage` shared path’i)

Forge Laravel şablonunda `storage` ve `.env` genelde otomatik eklenir.

## 6. Queue & Scheduler (İsteğe bağlı)

- **Queue:** Site > **Queue** ile bir worker ekleyin (örn. `php artisan queue:work`).
- **Scheduler:** Site > **Scheduler** ile cron ekleyin:  
  `* * * * * cd $FORGE_SITE_PATH && $FORGE_PHP artisan schedule:run >> /dev/null 2>&1`

## 7. İlk Deploy

1. Forge'da **Deploy Now** ile ilk deploy'u çalıştırın.
2. Hata alırsanız **Deployments** sekmesindeki log’a bakın.
3. `APP_KEY` boşsa Environment’ta "Generate" ile key oluşturup tekrar deploy edin.

## 8. Özet Kontrol Listesi

- [ ] GitHub repo: `nakliyepark`, branch: `main`
- [ ] Web Directory: `public`
- [ ] PHP 8.2+
- [ ] .env dolduruldu (APP_KEY, DB_*, APP_URL)
- [ ] Deploy script’te composer, npm build, migrate, cache komutları var
- [ ] Gerekirse Queue ve Scheduler ayarlandı
