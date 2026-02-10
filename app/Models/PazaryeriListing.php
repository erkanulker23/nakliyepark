<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PazaryeriListing extends Model
{
    protected $fillable = [
        'company_id', 'title', 'vehicle_type', 'listing_type', 'price',
        'city', 'year', 'description', 'image_path', 'images', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'images' => 'array',
        ];
    }

    /** Tüm galeri görselleri (ana + ek) */
    public function getGalleryPathsAttribute(): array
    {
        $paths = [];
        if ($this->image_path) {
            $paths[] = $this->image_path;
        }
        foreach ($this->images ?? [] as $path) {
            if ($path && !in_array($path, $paths)) {
                $paths[] = $path;
            }
        }
        return $paths;
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
