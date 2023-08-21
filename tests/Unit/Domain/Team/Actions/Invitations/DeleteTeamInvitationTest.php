<?php

use App\Domain\Team\Actions\Invitations\DeleteTeamInvitation;

beforeEach(function () {
    $this->invitation = createTeamInvitation();
});

it('deletes a team invitation', function () {
    expect(DeleteTeamInvitation::run($this->invitation))->toBeTrue()
        ->and($this->invitation)->toBeDeleted();
});
