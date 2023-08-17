<?php

use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\User;
use App\Domain\Team\Rules\BelongsToTeam;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();

    $this->team = $this->user->ownedTeams()->where('personal_team', false)->first();

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
