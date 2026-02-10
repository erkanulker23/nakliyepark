<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dispute extends Model
{
    public const STATUS_OPEN = 'open';
    public const STATUS_ADMIN_REVIEW = 'admin_review';
    public const STATUS_RESOLVED = 'resolved';

    public const REASON_CANCELLED = 'iptal';
    public const REASON_WRONG_ADDRESS = 'adres_hatasi';
    public const REASON_DID_NOT_COME = 'gelmedi';
    public const REASON_OFFENSIVE = 'hakaret';
    public const REASON_OTHER = 'diger';

    protected $fillable = [
        'ihale_id',
        'company_id',
        'opened_by_user_id',
        'opened_by_type',
        'reason',
        'description',
        'status',
        'admin_note',
        'resolved_by_user_id',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function ihale(): BelongsTo
    {
        return $this->belongsTo(Ihale::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function openedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by_user_id');
    }

    public function resolvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by_user_id');
    }

    public static function reasonLabels(): array
    {
        return [
            self::REASON_CANCELLED => 'İş iptal edildi',
            self::REASON_WRONG_ADDRESS => 'Yanlış adres verildi',
            self::REASON_DID_NOT_COME => 'Firma gelmedi / Müşteri gelmedi',
            self::REASON_OFFENSIVE => 'Hakaret / uygunsuz içerik',
            self::REASON_OTHER => 'Diğer',
        ];
    }
}
