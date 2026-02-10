<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'company_id', 'ihale_id', 'rating', 'comment', 'video_path'];

    protected static function booted(): void
    {
        static::deleting(function (Review $review) {
            if ($review->video_path && Storage::disk('public')->exists($review->video_path)) {
                Storage::disk('public')->delete($review->video_path);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function ihale(): BelongsTo
    {
        return $this->belongsTo(Ihale::class);
    }
}
