<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DefterApiEntry extends Model
{
    protected $fillable = [
        'external_id', 'firma', 'phone', 'phone_display', 'whatsapp', 'email',
        'icerik', 'profil_url', 'profil_resmi', 'tarih', 'uyelik', 'uye_tipi',
        'cevrimici', 'giris_gerekli', 'telefon_maskelenmis', 'raw_data', 'company_id',
    ];

    protected function casts(): array
    {
        return [
            'cevrimici' => 'boolean',
            'giris_gerekli' => 'boolean',
            'raw_data' => 'array',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /** Sisteme firma olarak aktarılmış mı */
    public function isImported(): bool
    {
        return $this->company_id !== null;
    }
}
