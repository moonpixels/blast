<?php

namespace App\Domain\Team\Actions\Invitations;

use App\Domain\Team\Models\TeamInvitation;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteTeamInvitation
{
    use AsAction;

    /**
     * Delete the given team invitation.
     */
    public function handle(TeamInvitation $invitation): bool
    {
        return $invitation->delete();
    }
}
