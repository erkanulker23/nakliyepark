<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Müşteri kendi ihalesi için (kabul edilmiş teklif sonrası) değerlendirme oluşturabilir.
     */
    public function create(User $user, $ihale): bool
    {
        if (! $user->isMusteri()) {
            return false;
        }
        if ($ihale->user_id !== $user->id) {
            return false;
        }
        return true;
    }

    public function view(User $user, Review $review): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return $review->user_id === $user->id;
    }

    public function update(User $user, Review $review): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return $review->user_id === $user->id;
    }

    public function delete(User $user, Review $review): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return $review->user_id === $user->id;
    }
}
