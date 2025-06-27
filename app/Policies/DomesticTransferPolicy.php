<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DomesticTransfer;
use Illuminate\Auth\Access\HandlesAuthorization;

class DomesticTransferPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the domestic transfer.
     */
    public function view(User $user, DomesticTransfer $domestic): bool
    {
        return $user->id === $domestic->user_id;
    }

    /**
     * Determine whether the user can update the domestic transfer.
     */
    public function update(User $user, DomesticTransfer $domestic): bool
    {
        return $user->id === $domestic->user_id;
    }

    /**
     * Determine whether the user can delete the domestic transfer.
     */
    public function delete(User $user, DomesticTransfer $domestic): bool
    {
        return $user->id === $domestic->user_id;
    }
}
