<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    /**
     * Admin tümünü; nakliyeci sadece kendi firmasını (düzenleme için) görebilir.
     */
    public function view(User $user, Company $company): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->isNakliyeci() && $company->user_id === $user->id) {
            return true;
        }
        return false;
    }

    public function update(User $user, Company $company): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->isNakliyeci() && $company->user_id === $user->id) {
            return true;
        }
        return false;
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->isAdmin();
    }
}
