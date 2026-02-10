<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IhaleJob extends Model
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'ihale_id',
        'teklif_id',
        'company_id',
        'started_at',
        'completed_at',
        'cancelled_at',
        'cancelled_reason',
        'agreed_amount',
        'final_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'agreed_amount' => 'decimal:2',
            'final_amount' => 'decimal:2',
        ];
    }

    public function ihale(): BelongsTo
    {
        return $this->belongsTo(Ihale::class);
    }

    public function teklif(): BelongsTo
    {
        return $this->belongsTo(Teklif::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /** Komisyon kesinleşmiş tutar (completed ise final_amount ?? agreed_amount) */
    public function getCommissionableAmount(): ?float
    {
        if ($this->status !== self::STATUS_COMPLETED) {
            return null;
        }
        return (float) ($this->final_amount ?? $this->agreed_amount ?? 0);
    }
}
