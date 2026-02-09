<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefterReklami extends Model
{
    protected $table = 'defter_reklamlari';

    protected $fillable = [
        'baslik', 'icerik', 'resim', 'link', 'konum', 'aktif', 'sira',
    ];

    protected function casts(): array
    {
        return ['aktif' => 'boolean'];
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeKonum($query, string $konum)
    {
        return $query->where('konum', $konum);
    }

    /** Sayfa yüklendiğinde rastgele N reklam getir (belirli konum için) */
    public static function rastgeleKonumdan(string $konum = 'sidebar', int $adet = 5)
    {
        return static::aktif()
            ->konum($konum)
            ->inRandomOrder()
            ->limit($adet)
            ->get();
    }
}
