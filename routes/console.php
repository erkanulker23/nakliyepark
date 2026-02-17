<?php

use App\Models\User;
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
