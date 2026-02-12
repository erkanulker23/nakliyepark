<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DefterYaniti extends Model
{
    protected $table = 'defter_yanitlari';

    protected $fillable = ['yuk_ilani_id', 'company_id', 'body'];

    public function yukIlani(): BelongsTo
    {
        return $this->belongsTo(YukIlani::class, 'yuk_ilani_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
