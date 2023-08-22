<?php

use App\Domain\Link\Models\Link;
use Illuminate\Database\Eloquent\Collection;
use Tests\Support\Fty;

/**
 * Create a link with the given attributes.
 */
function createLink(array $attributes = [], array $states = []): Link|Collection
{
    return Fty::build(Link::factory(), $states)->create($attributes);
}
