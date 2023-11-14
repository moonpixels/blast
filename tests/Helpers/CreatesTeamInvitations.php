<?php

use Domain\Team\Models\TeamInvitation;
use Illuminate\Database\Eloquent\Collection;
use Tests\Support\Fty;

/**
 * Create a team invitation with the given attributes.
 */
function createTeamInvitation(array $attributes = [], array $states = []): TeamInvitation|Collection
{
    return Fty::build(TeamInvitation::factory(), $states)->create($attributes);
}
