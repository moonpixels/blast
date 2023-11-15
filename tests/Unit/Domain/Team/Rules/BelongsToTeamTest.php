<?php

use Domain\Team\Models\Team;
use Domain\Team\Rules\BelongsToTeamRule;

beforeEach(function () {
    $this->user = createUser();

    $this->team = getTeamForUser($this->user, 'Member Team');

    $this->rule = new BelongsToTeamRule($this->user);
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

    expect($validator->passes())->toBeFalse()
        ->and($validator->errors()->first('team_id'))->toBe('The team id field must contain a valid team ID that you belong to.');
});
