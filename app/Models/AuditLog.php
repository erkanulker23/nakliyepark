<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'action',
        'actor_type',
        'actor_id',
        'user_id',
        'subject_type',
        'subject_id',
        'old_values',
        'new_values',
        'action_reason',
        'ip',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(
        string $action,
        ?string $subjectType = null,
        ?int $subjectId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $actionReason = null
    ): self {
        $id = auth()->id();
        return self::create([
            'action' => $action,
            'actor_type' => 'user',
            'actor_id' => $id,
            'user_id' => $id,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'action_reason' => $actionReason,
            'ip' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }

    /** Kritik admin işlemleri: before_state, after_state ve sebep loglanır. */
    public static function adminAction(
        string $action,
        ?string $subjectType,
        ?int $subjectId,
        array $beforeState,
        ?array $afterState = null,
        ?string $reason = null
    ): self {
        $id = auth()->id();
        return self::create([
            'action' => $action,
            'actor_type' => 'admin',
            'actor_id' => $id,
            'user_id' => $id,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'old_values' => $beforeState,
            'new_values' => $afterState,
            'action_reason' => $reason,
            'ip' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}
