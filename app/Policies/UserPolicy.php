<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view their own profile.
     */
    public function viewProfile(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update their own profile.
     */
    public function updateProfile(User $user): bool
    {
        return true;
    }
}
