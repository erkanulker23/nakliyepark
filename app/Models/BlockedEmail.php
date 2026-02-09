<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedEmail extends Model
{
    protected $fillable = ['email', 'reason'];

    public static function isBlocked(string $email): bool
    {
        return self::where('email', strtolower(trim($email)))->exists();
    }
}
