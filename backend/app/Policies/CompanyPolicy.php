<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Company $company)
    {
        return $user->id == $company->user_id;
    }

    public function delete(User $user, Company $company)
    {
        return $user->id == $company->user_id;
    }
}
