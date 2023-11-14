<?php

namespace Domain\Team\Actions\Invitations;

use Domain\Team\DTOs\TeamInvitationData;
use Domain\Team\Mail\TeamInvitationMail;
use Domain\Team\Models\Team;
use Domain\Team\Models\TeamInvitation;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTeamInvitationAction
{
    use AsAction;

    /**
     * Create a team invitation.
     */
    public function handle(Team $team, TeamInvitationData $data): TeamInvitation
    {
        $invitation = $team->invitations()->create([
            'email' => $data->email,
        ]);

        Mail::to($invitation->email)->send(new TeamInvitationMail($invitation));

        return $invitation;
    }
}
