<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends Model
{
    use SoftDeletes;

    protected $fillable = ['ihale_id', 'teklif_id', 'from_user_id', 'company_id', 'message'];

    public function ihale(): BelongsTo
    {
        return $this->belongsTo(Ihale::class);
    }

    public function teklif(): BelongsTo
    {
        return $this->belongsTo(Teklif::class);
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
