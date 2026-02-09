<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyVehicleImage extends Model
{
    protected $fillable = ['company_id', 'path', 'caption', 'sort_order'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
