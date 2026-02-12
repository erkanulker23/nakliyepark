<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class YukIlani extends Model
{
    use HasFactory;

    protected $table = 'yuk_ilanlari';

    protected $fillable = [
        'company_id', 'from_city', 'to_city', 'load_type', 'load_date',
        'volume_m3', 'vehicle_type', 'description', 'status',
    ];

    protected function casts(): array
    {
        return [
            'load_date' => 'date',
            'volume_m3' => 'decimal:2',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function yanitlar(): HasMany
    {
        return $this->hasMany(DefterYaniti::class, 'yuk_ilani_id')->latest();
    }
}
