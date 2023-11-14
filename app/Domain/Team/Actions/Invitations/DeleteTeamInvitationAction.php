<?php

namespace Domain\Team\Actions\Invitations;

use Domain\Team\Models\TeamInvitation;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteTeamInvitationAction
{
    use AsAction;

    /**
     * Delete a team invitation.
     */
    public function handle(TeamInvitation $invitation): bool
    {
        return $invitation->delete();
    }
}
