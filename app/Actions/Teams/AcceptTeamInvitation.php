<?php

namespace App\Actions\Teams;

use App\Exceptions\InvalidTeamMembershipException;
use App\Models\TeamInvitation;
use App\Models\User;
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

        $user->teams()->attach($invitation->team_id);

        $user->switchTeam($invitation->team);

        $invitation->delete();

        return true;
    }
}
