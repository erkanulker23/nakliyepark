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
            'k1' => 'K1 belgesi',
            'marka_tescil' => 'Marka tescil belgesi',
            'ode' => 'ODE belgesi',
            'psikoteknik' => 'Psikoteknik belgesi',
            'faaliyet' => 'Faaliyet belgesi',
            'vergi_levhasi' => 'Vergi levhası',
            'ticaret_odasi' => 'Ticaret odası',
            default => $this->title ?? str_replace('_', ' ', ucfirst($this->type ?? '')),
        };
    }
}
