<?php

use Domain\Link\Actions\DeleteAllLinksForTeamAction;

beforeEach(function () {
    $this->team = createTeam(states: ['hasLinks' => 10]);
});

it('deletes all links from a team', function () {
    expect(DeleteAllLinksForTeamAction::run($this->team))->toBeTrue()
        ->and($this->team->links)->each(fn ($link) => $this->assertSoftDeleted($link));
});
