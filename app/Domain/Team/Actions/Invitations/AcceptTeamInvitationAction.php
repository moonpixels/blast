<?php

namespace Domain\Team\Actions\Invitations;

use Domain\Team\Actions\Members\CreateTeamMemberAction;
use Domain\Team\Exceptions\InvalidTeamMemberException;
use Domain\Team\Models\TeamInvitation;
use Domain\User\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class AcceptTeamInvitationAction
{
    use AsAction;

    /**
     * Accept a team invitation.
     *
     * @throws InvalidTeamMemberException
     */
    public function handle(TeamInvitation $invitation): bool
    {
        $user = User::whereEmail($invitation->email)->firstOrFail();

        if ($user->belongsToTeam($invitation->team)) {
            $invitation->delete();
            throw InvalidTeamMemberException::alreadyOnTeam();
        }

        CreateTeamMemberAction::run($invitation->team, $user);

        $user->switchTeam($invitation->team);

        $invitation->delete();

        return true;
    }
}
