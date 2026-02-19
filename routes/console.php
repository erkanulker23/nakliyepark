<?php

use App\Models\Company;
use App\Models\User;
use App\Services\CompanyLogoProcessor;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:ensure {email : E-posta adresi} {--password= : Şifre (yeni kullanıcı için zorunlu)}', function (string $email): void {
    $user = User::where('email', $email)->first();

    if (! $user) {
        $password = $this->option('password');
        if (! $password) {
            $this->error("Bu e-posta ile kayıtlı kullanıcı bulunamadı: {$email}");
            $this->info('Yeni süper admin oluşturmak için --password=ŞİFRE parametresini ekleyin.');
            $this->info('Örnek: php artisan admin:ensure '.$email.' --password=GizliSifrem123');

            return;
        }

        $name = explode('@', $email)[0];
        $user = User::create([
            'name' => ucfirst($name),
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        $this->info("Süper admin oluşturuldu: {$email}");
    } else {
        $updates = [];
        if ($user->role !== 'admin') {
            $updates['role'] = 'admin';
        }
        if ($password = $this->option('password')) {
            $updates['password'] = Hash::make($password);
        }

        if ($updates !== []) {
            $user->update($updates);
            $this->info('Güncellendi: '.$email.(isset($updates['role']) ? ' → admin' : '').(isset($updates['password']) ? ', şifre ayarlandı' : ''));
        } else {
            $this->info("{$email} zaten admin ve şifre verilmedi. Değişiklik yapılmadı.");
        }
    }

    $this->info('Admin giriş: /yonetici/admin');
})->purpose('Belirtilen e-postayı admin yapar veya yeni süper admin oluşturur (--password zorunlu)');

Artisan::command('companies:refresh-slugs', function (): void {
    $companies = Company::withoutGlobalScopes()->get();
    $updated = 0;
    foreach ($companies as $company) {
        $newSlug = $company->generateSlug();
        if ($company->slug !== $newSlug) {
            $company->slug = $newSlug;
            $company->saveQuietly();
            $updated++;
            $this->line("  {$company->name} → {$newSlug}");
        }
    }
    $this->info("Slug güncellendi: {$updated} firma.");
})->purpose('Firma slug\'larını Türkçe karakter desteği ile yeniden üretir (404 düzeltmek için)');

Artisan::command('companies:reprocess-logos', function (): void {
    $companies = Company::withoutGlobalScopes()->whereNotNull('logo')->where('logo', '!=', '')->get();
    $processor = app(CompanyLogoProcessor::class);
    $done = 0;
    $fail = 0;
    foreach ($companies as $company) {
        if ($processor->process($company->logo)) {
            $done++;
            $this->line("  OK: {$company->name} ({$company->logo})");
        } else {
            $fail++;
            $this->warn("  Skip/fail: {$company->name} ({$company->logo})");
        }
    }
    $this->info("Logolar işlendi: {$done} başarılı, {$fail} atlandı/hata.");
})->purpose('Tüm firma logolarını yeniden işler (şeffaf PNG/WebP beyaz arka plan)');
