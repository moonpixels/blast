<?php

use Domain\Team\Actions\Invitations\DeleteTeamInvitationAction;

beforeEach(function () {
    $this->invitation = createTeamInvitation();
});

it('deletes a team invitation', function () {
    expect(DeleteTeamInvitationAction::run($this->invitation))->toBeTrue()
        ->and($this->invitation)->toBeDeleted();
});
