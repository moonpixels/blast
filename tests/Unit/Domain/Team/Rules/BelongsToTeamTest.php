<?php

use App\Domain\Team\Models\Team;
use App\Domain\Team\Rules\BelongsToTeam;
use App\Domain\User\Models\User;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();

    $this->team = $this->user->ownedTeams()->notPersonal()->first();

    $this->rule = new BelongsToTeam($this->user);
});

it('passes when the user belongs to the team', function () {
    $validator = Validator::make([
        'team_id' => $this->team->id,
    ], [
        'team_id' => $this->rule,
    ]);

    expect($validator->passes())->toBeTrue();
});

it('fails when the user does not belong to the team', function () {
    $validator = Validator::make([
        'team_id' => Team::factory()->create()->id,
    ], [
        'team_id' => $this->rule,
    ]);

    expect($validator->passes())->toBeFalse();
});
