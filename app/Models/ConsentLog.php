<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsentLog extends Model
{
    protected $fillable = [
        'consent_type',
        'ip',
        'user_agent',
        'user_id',
        'ihale_id',
        'meta',
        'consented_at',
    ];

    protected function casts(): array
    {
        return [
            'consented_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ihale(): BelongsTo
    {
        return $this->belongsTo(Ihale::class);
    }

    public static function log(string $consentType, ?int $userId = null, ?int $ihaleId = null, array $meta = []): self
    {
        return self::create([
            'consent_type' => $consentType,
            'ip' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'user_id' => $userId,
            'ihale_id' => $ihaleId,
            'meta' => $meta,
            'consented_at' => now(),
        ]);
    }
}
