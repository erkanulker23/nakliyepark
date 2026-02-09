<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teklif extends Model
{
    use HasFactory;

    protected $table = 'teklifler';

    protected $fillable = ['ihale_id', 'company_id', 'amount', 'message', 'status'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2'];
    }

    public function ihale(): BelongsTo
    {
        return $this->belongsTo(Ihale::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
