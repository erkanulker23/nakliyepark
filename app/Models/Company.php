<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'tax_number', 'tax_office', 'address', 'city', 'district',
        'phone', 'phone_2', 'whatsapp', 'email',
        'description', 'logo', 'approved_at', 'blocked_at',
        'seo_meta_title', 'seo_meta_description', 'seo_meta_keywords',
    ];

    public function contracts(): HasMany
    {
        return $this->hasMany(CompanyContract::class)->orderBy('sort_order');
    }

    public function vehicleImages(): HasMany
    {
        return $this->hasMany(CompanyVehicleImage::class)->orderBy('sort_order');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CompanyDocument::class)->orderBy('sort_order');
    }

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'blocked_at' => 'datetime',
        ];
    }

    public function isBlocked(): bool
    {
        return $this->blocked_at !== null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teklifler(): HasMany
    {
        return $this->hasMany(Teklif::class, 'company_id');
    }

    public function yukIlanlari(): HasMany
    {
        return $this->hasMany(YukIlani::class, 'company_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'company_id');
    }

    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }

    /** Kabul edilmiş teklifler (iş sayısı) */
    public function acceptedTeklifler()
    {
        return $this->teklifler()->where('status', 'accepted');
    }

    /** Toplam kazanç (kabul edilen tekliflerin tutarı) */
    public function getTotalEarningsAttribute(): float
    {
        return (float) $this->acceptedTeklifler()->sum('amount');
    }

    /** NakliyePark komisyon oranı (0-100) */
    public function getCommissionRateAttribute(): float
    {
        return (float) (\App\Models\Setting::get('commission_rate', 10));
    }

    /** Ödenen toplam komisyon */
    public function getTotalCommissionAttribute(): float
    {
        return round($this->total_earnings * ($this->commission_rate / 100), 2);
    }
}
