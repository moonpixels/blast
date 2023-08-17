<?php

namespace App\Domain\Link\Policies;

use App\Domain\Link\Models\Link;
use App\Domain\Team\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the link.
     */
    public function view(User $user, Link $link): bool
    {
        return $user->belongsToTeam($link->team);
    }

    /**
     * Determine whether the user can update the link.
     */
    public function update(User $user, Link $link): bool
    {
        return $user->belongsToTeam($link->team);
    }

    /**
     * Determine whether the user can delete the link.
     */
    public function delete(User $user, Link $link): bool
    {
        return $user->belongsToTeam($link->team);
    }

    /**
     * Determine whether the user can restore the link.
     */
    public function restore(User $user, Link $link): bool
    {
        return $user->belongsToTeam($link->team);
    }
}
