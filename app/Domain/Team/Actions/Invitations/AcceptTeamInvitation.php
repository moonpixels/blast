<?php

namespace App\Domain\Team\Actions\Invitations;

use App\Domain\Team\Actions\Memberships\CreateTeamMembership;
use App\Domain\Team\Exceptions\InvalidTeamMembershipException;
use App\Domain\Team\Models\TeamInvitation;
use App\Domain\Team\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class AcceptTeamInvitation
{
    use AsAction;

    /**
     * Accept a team invitation.
     *
     * @throws InvalidTeamMembershipException
     */
    public function handle(TeamInvitation $invitation): bool
    {
        $user = User::whereEmail($invitation->email)->firstOrFail();

        if ($user->belongsToTeam($invitation->team)) {
            $invitation->delete();
            throw InvalidTeamMembershipException::alreadyOnTeam();
        }

        CreateTeamMembership::run($invitation->team, $user);

        $user->switchTeam($invitation->team);

        $invitation->delete();

        return true;
    }
}
