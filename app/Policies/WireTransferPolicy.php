<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WireTransfer;
use Illuminate\Auth\Access\HandlesAuthorization;

class WireTransferPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the wire transfer.
     */
    public function view(User $user, WireTransfer $wire): bool
    {
        return $user->id === $wire->user_id;
    }

    /**
     * Determine whether the user can update the wire transfer.
     */
    public function update(User $user, WireTransfer $wire): bool
    {
        return $user->id === $wire->user_id;
    }

    /**
     * Determine whether the user can delete the wire transfer.
     */
    public function delete(User $user, WireTransfer $wire): bool
    {
        return $user->id === $wire->user_id;
    }
}
