<?php

use App\Domain\Link\Actions\DeleteLinksForTeam;

beforeEach(function () {
    $this->team = createTeam(states: ['hasLinks' => 10]);
});

it('deletes all links from a team', function () {
    expect(DeleteLinksForTeam::run($this->team))->toBeTrue()
        ->and($this->team->links)->each(fn ($link) => $this->assertSoftDeleted($link));
});
