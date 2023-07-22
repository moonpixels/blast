<?php

namespace App\Actions\TeamMemberships;

use App\Models\Team;
use App\Models\TeamMembership;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTeamMembership
{
    use AsAction;

    /**
     * Create a new team membership.
     */
    public function handle(Team $team, User $user): TeamMembership
    {
        return $team->memberships()->create([
            'user_id' => $user->id,
        ]);
    }
}
