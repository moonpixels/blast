<?php

namespace App\Domain\Team\Actions\Memberships;

use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamMembership;
use App\Domain\Team\Models\User;
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
