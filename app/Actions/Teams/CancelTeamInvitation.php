<?php

namespace App\Actions\Teams;

use App\Concerns\Actionable;
use App\Models\TeamInvitation;

class CancelTeamInvitation
{
    use Actionable;

    /**
     * Cancel the given team invitation.
     */
    public function handle(TeamInvitation $invitation): bool
    {
        return $invitation->delete();
    }
}
