<?php

use App\Actions\TeamMemberships\CreateTeamMembership;
use App\Models\Team;
use App\Models\User;

beforeEach(function () {
    $this->team = Team::factory()->create();
    $this->user = User::factory()->create();
});

it('can create a team membership', function () {
    $membership = CreateTeamMembership::run($this->team, $this->user);

    expect($membership->user_id)->toBe($this->user->id)
        ->and($membership->team_id)->toBe($this->team->id);

    $this->assertDatabaseHas('team_memberships', [
        'user_id' => $this->user->id,
        'team_id' => $this->team->id,
    ]);
});
