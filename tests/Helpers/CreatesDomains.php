<?php

use Domain\Link\Models\Domain;
use Illuminate\Database\Eloquent\Collection;
use Tests\Support\Fty;

/**
 * Create a domain with the given attributes.
 */
function createDomain(array $attributes = [], array $states = []): Domain|Collection
{
    return Fty::build(Domain::factory(), $states)->create($attributes);
}
