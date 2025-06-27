<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Loan;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the loan.
     */
    public function view(User $user, Loan $loan): bool
    {
        return $user->id === $loan->user_id;
    }

    /**
     * Determine whether the user can update the loan.
     */
    public function update(User $user, Loan $loan): bool
    {
        return $user->id === $loan->user_id;
    }

    /**
     * Determine whether the user can delete the loan.
     */
    public function delete(User $user, Loan $loan): bool
    {
        return $user->id === $loan->user_id;
    }
}
