<?php

use App\Domain\Link\Actions\DeleteLinksForTeam;
use App\Domain\Team\Models\Team;

beforeEach(function () {
    $this->team = Team::factory()->hasLinks(10)->create();
});

it('can soft delete all links for a team', function () {
    expect(DeleteLinksForTeam::run($this->team))->toBe(10)
        ->and($this->team->links)->each(fn ($link) => $this->assertSoftDeleted($link));
});
