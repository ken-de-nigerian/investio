<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserInvestment;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserInvestmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the investment.
     */
    public function view(User $user, UserInvestment $investment): bool
    {
        return $user->id === $investment->user_id;
    }

    /**
     * Determine whether the user can update the investment.
     */
    public function update(User $user, UserInvestment $investment): bool
    {
        return $user->id === $investment->user_id;
    }

    /**
     * Determine whether the user can delete the investment.
     */
    public function delete(User $user, UserInvestment $investment): bool
    {
        return $user->id === $investment->user_id;
    }

    /**
     * Determine whether the user can liquidate the investment.
     */
    public function liquidate(User $user, UserInvestment $investment): bool
    {
        return $user->id === $investment->user_id;
    }
}
