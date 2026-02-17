<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::saving(function (Company $company) {
            if (empty($company->slug) && !empty($company->name)) {
                $company->slug = $company->generateSlug();
            }
        });
    }

    public function generateSlug(): string
    {
        $base = Str::slug($this->name ?: 'firma');
        $slug = $base;
        $n = 0;
        $query = static::query()->where('slug', $slug);
        if ($this->exists) {
            $query->where('id', '!=', $this->id);
        }
        while ($query->exists()) {
            $n++;
            $slug = $base . '-' . $n;
            $query = static::query()->where('slug', $slug);
            if ($this->exists) {
                $query->where('id', '!=', $this->id);
            }
        }
        return $slug;
    }

    protected $fillable = [
        'user_id', 'name', 'slug', 'tax_number', 'tax_office', 'address', 'city', 'district',
        'live_latitude', 'live_longitude', 'live_location_updated_at', 'map_visible',
        'phone', 'phone_2', 'whatsapp', 'email',
        'description', 'logo', 'logo_approved_at', 'services', 'approved_at', 'package', 'blocked_at', 'blocked_reason',
        'email_verified_at', 'phone_verified_at', 'official_company_verified_at',
        'seo_meta_title', 'seo_meta_description', 'seo_meta_keywords',
        'pending_changes', 'pending_changes_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'official_company_verified_at' => 'datetime',
            'blocked_at' => 'datetime',
            'logo_approved_at' => 'datetime',
            'live_location_updated_at' => 'datetime',
            'map_visible' => 'boolean',
            'services' => 'array',
            'pending_changes' => 'array',
            'pending_changes_at' => 'datetime',
        ];
    }

    public function hasPendingChanges(): bool
    {
        return !empty($this->pending_changes) && is_array($this->pending_changes);
    }

    /** Haritada görünür ve son 2 saat içinde konum güncellenmiş mi */
    public function scopeVisibleOnMap($query)
    {
        return $query->where('map_visible', true)
            ->whereNotNull('live_latitude')
            ->whereNotNull('live_longitude')
            ->where('live_location_updated_at', '>=', now()->subHours(2));
    }

    public static function serviceLabels(): array
    {
        return [
            'evden_eve_nakliyat' => 'Evden eve nakliyat',
            'sehirlerarasi_nakliyat' => 'Şehirlerarası nakliyat',
            'ofis_tasima' => 'Ofis taşımacılığı',
            'esya_depolama' => 'Eşya depolama',
            'uluslararasi_nakliyat' => 'Uluslararası nakliyat',
        ];
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(CompanyContract::class)->orderBy('sort_order');
    }

    public function vehicleImages(): HasMany
    {
        return $this->hasMany(CompanyVehicleImage::class)->orderBy('sort_order');
    }

    /** Sadece admin onaylı galeri görselleri (firma sayfasında gösterilir). */
    public function approvedVehicleImages(): HasMany
    {
        return $this->hasMany(CompanyVehicleImage::class)->whereNotNull('approved_at')->orderBy('sort_order');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CompanyDocument::class)->orderBy('sort_order');
    }


    public function isBlocked(): bool
    {
        return $this->blocked_at !== null;
    }

    /** Üyelik askıya alma sebepleri (admin panelinde kullanılır). */
    public static function blockedReasonLabels(): array
    {
        return [
            'borc' => 'Borç',
            'sozlesme_ihlali' => 'Sözleşme ihlali',
            'diger' => 'Diğer',
        ];
    }

    public static function blockedReasonLabel(?string $key): string
    {
        return self::blockedReasonLabels()[$key] ?? $key;
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

    public function pazaryeriListings(): HasMany
    {
        return $this->hasMany(PazaryeriListing::class, 'company_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'company_id');
    }

    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }

    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function isPhoneVerified(): bool
    {
        return $this->phone_verified_at !== null;
    }

    public function isOfficialCompanyVerified(): bool
    {
        return $this->official_company_verified_at !== null;
    }

    /** Config'deki paket tanımını döndürür (id: baslangic, profesyonel, kurumsal). */
    public function getPackageConfig(): ?array
    {
        if (! $this->package) {
            return null;
        }
        $paketler = config('nakliyepark.nakliyeci_paketler', []);
        foreach ($paketler as $p) {
            if (($p['id'] ?? null) === $this->package) {
                return $p;
            }
        }
        return null;
    }

    /** Paket sıralama ağırlığı: kurumsal > profesyonel > baslangic > yok */
    public static function packageOrderWeight(?string $package): int
    {
        return match ($package) {
            'kurumsal' => 3,
            'profesyonel' => 2,
            'baslangic' => 1,
            default => 0,
        };
    }

    /** Bu ay verilen teklif sayısı (paket limiti için). */
    public function teklifCountThisMonth(): int
    {
        return $this->teklifler()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    /** Pakete göre aylık teklif limiti (config). Paket yoksa ilk paket limiti kullanılır. */
    public function getTeklifLimitAttribute(): int
    {
        $paketler = config('nakliyepark.nakliyeci_paketler', []);
        $packageId = $this->package ?? ($paketler[0]['id'] ?? 'baslangic');
        foreach ($paketler as $p) {
            if (($p['id'] ?? null) === $packageId) {
                return (int) ($p['teklif_limit'] ?? 50);
            }
        }
        return 50;
    }

    /** Bu ay limit aşılmadıysa true. */
    public function canSendTeklif(): bool
    {
        return $this->teklifCountThisMonth() < $this->teklif_limit;
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

    public function commissionPayments(): HasMany
    {
        return $this->hasMany(CompanyCommissionPayment::class);
    }

    /** Ödenen komisyon toplamı (kredi kartı ile yapılan ödemeler). */
    public function getPaidCommissionAttribute(): float
    {
        return (float) $this->commissionPayments()->sum('amount');
    }

    /** Kalan borç (toplam komisyon - ödenen). */
    public function getOutstandingCommissionAttribute(): float
    {
        return max(0, round($this->total_commission - $this->paid_commission, 2));
    }
}
