<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IhalePhoto extends Model
{
    protected $fillable = ['ihale_id', 'path', 'sort_order'];

    public function ihale(): BelongsTo
    {
        return $this->belongsTo(Ihale::class);
    }
}
