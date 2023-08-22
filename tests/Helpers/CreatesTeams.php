<?php

use App\Domain\Team\Models\Team;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Tests\Support\Fty;

/**
 * Create a team with the given attributes.
 */
function createTeam(array $attributes = [], array $states = []): Team|Collection
{
    return Fty::build(Team::factory(), $states)->create(array_merge([
        'name' => 'Test Team',
    ], $attributes));
}

/**
 * Get the team for given user with the given name.
 */
function getTeamForUser(User $user, string $name): Team
{
    return Team::whereRelation('members', 'id', $user->id)
        ->orWhere('owner_id', $user->id)
        ->where('name', $name)
        ->firstOrFail();
}
