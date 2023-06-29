<?php

use App\Actions\Teams\UpdateTeam;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();

    $this->team = $this->user->ownedTeams()->where('personal_team', false)->first();
});

it('can update a team', function () {
    expect(UpdateTeam::execute($this->team, ['name' => 'Test Team Updated']))->toBeTrue()
        ->and($this->team->fresh()->name)->toBe('Test Team Updated');
});
