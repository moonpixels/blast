<?php

namespace App\Actions\Teams;

use App\Models\Team;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTeam
{
    use AsAction;

    /**
     * Update the given team.
     */
    public function handle(Team $team, array $attributes): bool
    {
        return $team->update($attributes);
    }
}
