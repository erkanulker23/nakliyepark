<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyDocument extends Model
{
    protected $fillable = ['company_id', 'type', 'title', 'file_path', 'expires_at', 'sort_order'];

    protected function casts(): array
    {
        return ['expires_at' => 'date'];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'k1' => 'K1 Belgesi',
            'gb' => 'GB (Yeşil Kart)',
            'sigorta' => 'Sigorta',
            'ruhsat' => 'Ruhsat',
            default => $this->title ?? ucfirst($this->type),
        };
    }
}
