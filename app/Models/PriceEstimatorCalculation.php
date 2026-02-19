<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceEstimatorCalculation extends Model
{
    protected $fillable = [
        'from_label', 'to_label', 'km', 'price', 'room_label', 'service_type', 'route_label',
    ];

    protected $casts = [
        'km' => 'integer',
        'price' => 'decimal:2',
    ];

    /** Son 10 tahmini fiyat hesaplaması (herkes için global). */
    public static function lastTen(): \Illuminate\Database\Eloquent\Collection
    {
        return static::query()
            ->orderByDesc('id')
            ->limit(10)
            ->get();
    }
}
