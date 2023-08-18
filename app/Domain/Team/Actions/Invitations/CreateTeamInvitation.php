<?php

namespace App\Domain\Team\Actions\Invitations;

use App\Domain\Team\Data\TeamInvitationData;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamInvitation;
use App\Domain\Team\Notifications\TeamInvitationNotification;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTeamInvitation
{
    use AsAction;

    /**
     * Create a new team invitation for the given team.
     */
    public function handle(Team $team, TeamInvitationData $data): TeamInvitation
    {
        $invitation = $team->invitations()->create([
            'email' => $data->email,
        ]);

        $invitation->notify(new TeamInvitationNotification($invitation));

        return $invitation;
    }
}
