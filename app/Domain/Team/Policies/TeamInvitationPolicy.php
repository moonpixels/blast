<?php

namespace Domain\Team\Policies;

use Domain\Team\Models\TeamInvitation;
use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamInvitationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the team invitation.
     */
    public function view(User $user, TeamInvitation $teamInvitation): bool
    {
        return $user->ownsTeam($teamInvitation->team);
    }

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
