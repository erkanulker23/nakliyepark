<?php

namespace App\Policies;

use App\Models\Ihale;
use App\Models\User;

class IhalePolicy
{
    /**
     * Müşteri kendi ihalesini, admin tümünü görebilir.
     */
    public function view(User $user, Ihale $ihale): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->isMusteri() && $ihale->user_id === $user->id) {
            return true;
        }
        return false;
    }

    public function update(User $user, Ihale $ihale): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Ihale $ihale): bool
    {
        return $user->isAdmin();
    }

    /**
     * Müşteri kendi ihalesi için değerlendirme oluşturabilir (kabul edilmiş teklif gerekir, kontrol controller'da).
     */
    public function createReview(User $user, Ihale $ihale): bool
    {
        return $user->isMusteri() && $ihale->user_id === $user->id;
    }
}
