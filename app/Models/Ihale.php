<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ihale extends Model
{
    use HasFactory, SoftDeletes;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static $slugServiceLabels = [
        self::SERVICE_EVDEN_EVE => 'evden-eve-nakliyat',
        self::SERVICE_SEHIRLERARASI => 'sehirlerarasi-nakliyat',
        self::SERVICE_PARCA_ESYA => 'parca-esya',
        self::SERVICE_DEPOLAMA => 'esya-depolama',
        self::SERVICE_OFIS => 'ofis-tasima',
    ];

    protected static function booted(): void
    {
        static::saving(function (Ihale $ihale) {
            if (empty($ihale->slug) && (!empty($ihale->from_city) || !empty($ihale->to_city))) {
                $ihale->slug = $ihale->generateSlug();
            }
        });
    }

    public function generateSlug(): string
    {
        $from = Str::slug($this->from_city ?: 'sehir');
        $to = Str::slug($this->to_city ?: 'sehir');
        $service = self::$slugServiceLabels[$this->service_type ?? self::SERVICE_EVDEN_EVE] ?? 'nakliyat';
        $base = $from . '-' . $to . '-arasi-' . $service . '-ihalesi';
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

    protected $table = 'ihaleler';

    public const SERVICE_EVDEN_EVE = 'evden_eve_nakliyat';
    public const SERVICE_SEHIRLERARASI = 'sehirlerarasi_nakliyat';
    public const SERVICE_PARCA_ESYA = 'parca_esya_tasimaciligi';
    public const SERVICE_DEPOLAMA = 'esya_depolama';
    public const SERVICE_OFIS = 'ofis_tasima';

    public static function serviceTypeLabels(): array
    {
        return [
            self::SERVICE_EVDEN_EVE => 'Evden eve nakliyat',
            self::SERVICE_SEHIRLERARASI => 'Şehirler arası',
            self::SERVICE_PARCA_ESYA => 'Parça eşya',
            self::SERVICE_DEPOLAMA => 'Eşya depolama',
            self::SERVICE_OFIS => 'Ofis taşıma',
        ];
    }

    protected $fillable = [
        'user_id', 'preferred_company_id', 'service_type', 'room_type',
        'guest_contact_name', 'guest_contact_email', 'guest_contact_phone',
        'from_city', 'from_address', 'from_district', 'from_neighborhood', 'from_postal_code',
        'to_city', 'to_address', 'to_district', 'to_neighborhood', 'to_postal_code', 'distance_km',
        'move_date', 'move_date_end', 'volume_m3', 'description', 'status', 'slug',
    ];

    public function isGuest(): bool
    {
        return $this->user_id === null;
    }

    protected function casts(): array
    {
        return [
            'move_date' => 'date',
            'move_date_end' => 'date',
            'volume_m3' => 'decimal:2',
            'distance_km' => 'decimal:2',
        ];
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function preferredCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'preferred_company_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(IhalePhoto::class, 'ihale_id')->orderBy('sort_order');
    }

    public function teklifler(): HasMany
    {
        return $this->hasMany(Teklif::class, 'ihale_id');
    }

    public function acceptedTeklif(): HasOne
    {
        return $this->hasOne(Teklif::class, 'ihale_id')->where('status', 'accepted');
    }

    public function disputes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Dispute::class);
    }
}
