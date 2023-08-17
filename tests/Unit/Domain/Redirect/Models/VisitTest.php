<?php

use App\Domain\Link\Models\Link;
use App\Domain\Redirect\Models\Visit;
use App\Domain\Team\Models\Team;

beforeEach(function () {
    $this->visit = Visit::factory()->create();
});

it('belongs to a link', function () {
    expect($this->visit->link)->toBeInstanceOf(Link::class);
});

it('belongs to a team', function () {
    expect($this->visit->team)->toBeInstanceOf(Team::class);
});
