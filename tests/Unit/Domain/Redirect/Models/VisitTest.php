<?php

use Domain\Link\Models\Link;
use Domain\Team\Models\Team;

beforeEach(function () {
    $this->visit = createVisit();
});

it('belongs to a link', function () {
    expect($this->visit->link)->toBeInstanceOf(Link::class);
});

it('belongs to a team', function () {
    expect($this->visit->team)->toBeInstanceOf(Team::class);
});
