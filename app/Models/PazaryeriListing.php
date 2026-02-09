<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PazaryeriListing extends Model
{
    protected $fillable = [
        'company_id', 'title', 'vehicle_type', 'listing_type', 'price',
        'city', 'year', 'description', 'image_path', 'status',
    ];

    protected function casts(): array
    {
        return ['price' => 'decimal:2'];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public static function vehicleTypeLabels(): array
    {
        return [
            'kamyon' => 'Kamyon',
            'kamyonet' => 'Kamyonet',
            'panelvan' => 'Panelvan',
            'tir' => 'TIR',
            'lowbed' => 'Lowbed',
            'kapali_kasa' => 'Kapalı kasa',
        ];
    }

    public static function listingTypeLabels(): array
    {
        return [
            'sale' => 'Satılık',
            'rent' => 'Kiralık',
        ];
    }
}
