<?php

namespace App\Domain\Team\Actions\Invitations;

use App\Domain\Team\Actions\Members\CreateTeamMember;
use App\Domain\Team\Exceptions\InvalidTeamMemberException;
use App\Domain\Team\Models\TeamInvitation;
use App\Domain\User\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class AcceptTeamInvitation
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

        CreateTeamMember::run($invitation->team, $user);

        $user->switchTeam($invitation->team);

        $invitation->delete();

        return true;
    }
}
