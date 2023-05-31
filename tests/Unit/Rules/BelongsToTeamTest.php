<?php

use App\Models\Team;
use App\Models\User;
use App\Rules\BelongsToTeam;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->team = Team::factory()->for($this->user, 'owner')->create();

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
