<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedPhone extends Model
{
    protected $fillable = ['phone', 'reason', 'normalized_phone'];

    protected static function booted(): void
    {
        static::saving(function (BlockedPhone $model) {
            if ($model->phone !== null && $model->phone !== '') {
                $model->normalized_phone = self::normalize($model->phone);
            }
        });
    }

    public static function normalize(string $phone): string
    {
        return preg_replace('/\D/', '', $phone);
    }

    /**
     * Tek sorgu ile engel kontrolü (normalized_phone indeksi kullanılır).
     * Türkiye formatı: 0555... ve 90555... aynı kabul edilir.
     */
    public static function isBlocked(?string $phone): bool
    {
        if ($phone === null || $phone === '') {
            return false;
        }
        $normalized = self::normalize($phone);
        if ($normalized === '') {
            return false;
        }
        if (self::query()->where('normalized_phone', $normalized)->exists()) {
            return true;
        }
        if (strlen($normalized) === 11 && str_starts_with($normalized, '0')) {
            if (self::query()->where('normalized_phone', '90' . substr($normalized, 1))->exists()) {
                return true;
            }
        }
        if (strlen($normalized) === 12 && str_starts_with($normalized, '90')) {
            if (self::query()->where('normalized_phone', '0' . substr($normalized, 2))->exists()) {
                return true;
            }
        }
        return false;
    }
}
