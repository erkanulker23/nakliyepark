<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    protected $fillable = ['ip', 'reason'];

    public static function isBlocked(string $ip): bool
    {
        return self::where('ip', $ip)->exists();
    }
}
