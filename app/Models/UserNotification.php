<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotification extends Model
{
    protected $table = 'user_notifications';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['id', 'user_id', 'type', 'title', 'message', 'data', 'read_at'];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    public static function notify(User $user, string $type, string $message, ?string $title = null, array $data = []): self
    {
        return self::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title ?? $type,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
