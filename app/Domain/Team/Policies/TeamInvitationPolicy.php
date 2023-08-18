<?php

namespace App\Domain\Team\Policies;

use App\Domain\Team\Models\TeamInvitation;
use App\Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamInvitationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the team invitation.
     */
    public function delete(User $user, TeamInvitation $teamInvitation): bool
    {
        return $user->ownsTeam($teamInvitation->team)
            || $user->email === $teamInvitation->email;
    }

    /**
     * Determine whether the user can resend the team invitation.
     */
    public function resend(User $user, TeamInvitation $teamInvitation): bool
    {
        return $user->ownsTeam($teamInvitation->team);
    }

    /**
     * Determine whether the user can accept the team invitation.
     */
    public function accept(User $user, TeamInvitation $teamInvitation): bool
    {
        return $user->email === $teamInvitation->email;
    }
}
