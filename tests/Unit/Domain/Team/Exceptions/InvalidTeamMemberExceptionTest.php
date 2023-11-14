<?php

use Domain\Team\Exceptions\InvalidTeamMemberException;

it('throws an already on team exception', function () {
    throw InvalidTeamMemberException::alreadyOnTeam();
})->throws(InvalidTeamMemberException::class, 'The user is already on the team.');
