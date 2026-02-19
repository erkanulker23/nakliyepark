<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistanceCalculation extends Model
{
    protected $fillable = ['from_label', 'to_label', 'km', 'route_label'];

    protected $casts = [
        'km' => 'integer',
    ];

    /** Son 10 mesafe hesaplaması (herkes için global). */
    public static function lastTen(): \Illuminate\Database\Eloquent\Collection
    {
        return static::query()
            ->orderByDesc('id')
            ->limit(10)
            ->get();
    }
}
