<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = ['type', 'title', 'message', 'data', 'read_at'];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'read_at' => 'datetime',
        ];
    }

    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    public static function notify(string $type, string $message, ?string $title = null, array $data = []): self
    {
        return self::create([
            'type' => $type,
            'title' => $title ?? $type,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
