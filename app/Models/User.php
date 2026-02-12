<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'blocked_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'blocked_at' => 'datetime',
        ];
    }

    public function isBlocked(): bool
    {
        return $this->blocked_at !== null;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isNakliyeci(): bool
    {
        return $this->role === 'nakliyeci';
    }

    public function isMusteri(): bool
    {
        return $this->role === 'musteri';
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function ihaleler(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ihale::class, 'user_id');
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function userNotifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserNotification::class);
    }

    /**
     * Şifre sıfırlama e-postası (Türkçe, özelleştirilebilir konu).
     * Hata durumunda kullanıcıya hata göstermez, log yazar.
     */
    public function sendPasswordResetNotification(mixed $token): void
    {
        \App\Services\SafeNotificationService::sendToUser(
            $this,
            new \App\Notifications\ResetPasswordNotification($token),
            'password_reset'
        );
    }

    /**
     * E-posta doğrulama bildirimi (Türkçe, admin şablonu ile).
     * Hata durumunda kullanıcıya hata göstermez, log yazar.
     */
    public function sendEmailVerificationNotification(): void
    {
        \App\Services\SafeNotificationService::sendToUser(
            $this,
            new \App\Notifications\VerifyEmailNotification,
            'email_verification'
        );
    }
}
