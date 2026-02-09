<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'default_volume_m3', 'sort_order'];

    protected function casts(): array
    {
        return ['default_volume_m3' => 'decimal:2'];
    }
}
