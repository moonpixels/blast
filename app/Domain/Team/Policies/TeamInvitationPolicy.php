<?php

namespace App\Domain\Team\Policies;

use App\Domain\Team\Models\TeamInvitation;
use App\Domain\Team\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamInvitationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the team invitation.
     */
    public function delete(User $user, TeamInvitation $teamInvitation): bool
    {
        return $user->id === $teamInvitation->team->owner_id;
    }

    /**
     * Determine whether the user can resend the team invitation.
     */
    public function resend(User $user, TeamInvitation $teamInvitation): bool
    {
        return $user->id === $teamInvitation->team->owner_id;
    }
}
