<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'title', 'slug', 'meta_title', 'excerpt', 'content', 'image', 'published_at', 'meta_description', 'featured'];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
}
