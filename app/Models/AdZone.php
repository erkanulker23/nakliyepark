<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdZone extends Model
{
    protected $table = 'ad_zones';

    protected $fillable = [
        'sayfa', 'konum', 'baslik', 'tip', 'kod', 'resim', 'link', 'sira', 'aktif',
    ];

    protected function casts(): array
    {
        return ['aktif' => 'boolean'];
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeSayfa($query, string $sayfa)
    {
        return $query->where('sayfa', $sayfa);
    }

    public function scopeKonum($query, string $konum)
    {
        return $query->where('konum', $konum);
    }

    /**
     * Belirli sayfa + konum için reklamları getir (rastgele sıra, sınırlı adet).
     */
    public static function getForPagePosition(string $sayfa, string $konum, int $limit = 5)
    {
        return static::aktif()
            ->sayfa($sayfa)
            ->konum($konum)
            ->orderBy('sira')
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    public static function sayfaSecenekleri(): array
    {
        return [
            'defter'      => 'Defter sayfası',
            'blog'        => 'Blog liste sayfası',
            'blog_show'   => 'Blog yazı detay sayfası',
            'ihale_list'  => 'İhale liste sayfası',
            'ihale_show'  => 'İhale detay sayfası',
            'home'        => 'Anasayfa',
        ];
    }

    public static function konumSecenekleri(): array
    {
        return [
            'ust'           => 'Üst alan',
            'alt'           => 'Alt alan',
            'sidebar'       => 'Sidebar (yan sütun)',
            'icerik'       => 'Blog yazısı içeriği',
            'icerik_ustu'   => 'İçerik üstü',
            'icerik_alti'   => 'İçerik altı',
            'icerik_ortasi' => 'İçerik ortası',
        ];
    }

    public function isCode(): bool
    {
        return $this->tip === 'code';
    }

    public function isImage(): bool
    {
        return $this->tip === 'image';
    }
}
