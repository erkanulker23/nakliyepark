<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyContract extends Model
{
    protected $fillable = ['company_id', 'title', 'file_path', 'sort_order'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
