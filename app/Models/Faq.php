<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'answer', 'sort_order', 'audience'];

    /** Hedef kitle: null = her ikisi, musteri, nakliyeci */
    public const AUDIENCE_MUSTERI = 'musteri';
    public const AUDIENCE_NAKLIYECI = 'nakliyeci';
}
