<?php

namespace App\Policies;

use App\Models\Teklif;
use App\Models\User;

class TeklifPolicy
{
    /**
     * Admin tümünü; müşteri kendi ihalesinin tekliflerini; nakliyeci kendi firmasının teklifini görebilir.
     */
    public function view(User $user, Teklif $teklif): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->isMusteri() && $teklif->ihale && $teklif->ihale->user_id === $user->id) {
            return true;
        }
        if ($user->isNakliyeci() && $user->company && $teklif->company_id === $user->company->id) {
            return true;
        }
        return false;
    }

    public function update(User $user, Teklif $teklif): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->isNakliyeci() && $user->company && $teklif->company_id === $user->company->id && $teklif->status === 'pending') {
            return true;
        }
        return false;
    }

    public function delete(User $user, Teklif $teklif): bool
    {
        return $user->isAdmin();
    }
}
