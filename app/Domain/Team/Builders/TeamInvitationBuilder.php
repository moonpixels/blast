<?php

namespace Domain\Team\Builders;

use Domain\Team\Models\Team;
use Domain\Team\Models\TeamInvitation;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin TeamInvitation
 */
class TeamInvitationBuilder extends Builder
{
    /**
     * Filter invitations by team.
     */
    public function forTeam(Team $team): static
    {
        return $this->where('team_id', $team->id);
    }
}
