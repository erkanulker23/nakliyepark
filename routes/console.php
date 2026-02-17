<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:ensure {email : E-posta adresi} {--password= : Şifre (verilirse güncellenir)}', function (string $email): void {
    $user = User::where('email', $email)->first();

    if (! $user) {
        $this->error("Bu e-posta ile kayıtlı kullanıcı bulunamadı: {$email}");
        $this->info('Önce bu e-posta ile normal kayıt olun, sonra bu komutu tekrar çalıştırın.');

        return;
    }

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
    $this->info('Admin giriş: /yonetici/admin');
})->purpose('Belirtilen e-postayı admin yapar; isteğe bağlı --password ile şifre atar');
