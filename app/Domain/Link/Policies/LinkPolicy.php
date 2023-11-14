<?php

namespace Domain\Link\Policies;

use Domain\Link\Models\Link;
use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user): ?bool
    {
        if ($user->isBlocked()) {
            return false;
        }

        return null;
    }

    /**
     * Determine whether the user can view any links.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the link.
     */
    public function view(User $user, Link $link): bool
    {
        return $user->belongsToTeam($link->team);
    }

    /**
     * Determine whether the user can create links.
     */
    public function create(User $user): bool
    {
        return true;
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
}
