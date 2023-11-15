<?php

use Domain\Redirect\Models\Visit;
use Illuminate\Database\Eloquent\Collection;
use Tests\Support\Fty;

/**
 * Create a visit with the given attributes.
 */
function createVisit(array $attributes = [], array $states = []): Visit|Collection
{
    return Fty::build(Visit::factory(), $states)->create($attributes);
}
