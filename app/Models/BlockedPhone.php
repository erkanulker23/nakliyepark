<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedPhone extends Model
{
    protected $fillable = ['phone', 'reason'];

    public static function normalize(string $phone): string
    {
        return preg_replace('/\D/', '', $phone);
    }

    public static function isBlocked(?string $phone): bool
    {
        if ($phone === null || $phone === '') {
            return false;
        }
        $normalized = self::normalize($phone);
        if ($normalized === '') {
            return false;
        }
        return self::get()->contains(fn (BlockedPhone $b) => self::normalize($b->phone) === $normalized);
    }
}
