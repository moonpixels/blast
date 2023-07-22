<?php

use App\Models\Link;
use App\Models\Team;
use App\Models\Visit;

beforeEach(function () {
    $this->visit = Visit::factory()->create();
});

it('belongs to a link', function () {
    expect($this->visit->link)->toBeInstanceOf(Link::class);
});

it('belongs to a team', function () {
    expect($this->visit->team)->toBeInstanceOf(Team::class);
});
