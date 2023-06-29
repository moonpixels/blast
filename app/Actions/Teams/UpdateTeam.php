<?php

namespace App\Actions\Teams;

use App\Concerns\Actionable;
use App\Models\Team;

class UpdateTeam
{
    use Actionable;

    /**
     * Update the given team.
     */
    public function handle(Team $team, array $attributes): bool
    {
        return $team->update($attributes);
    }
}
