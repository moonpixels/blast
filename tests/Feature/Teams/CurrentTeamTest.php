<?php

use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\User;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();
    $this->team = $this->user->ownedTeams()->where('personal_team', false)->first();

    $this->actingAs($this->user);
});

it('switches the users current team', function () {
    $this->put(route('user.current-team.update'), ['current_team_id' => $this->team->id])
        ->assertRedirectToRoute('links.index');

    $this->assertEquals($this->team->id, $this->user->current_team_id);
});

it('does not switch the users current team if they are not a member', function () {
    $team = Team::factory()->create();

    $this->put(route('user.current-team.update'), ['current_team_id' => $team->id])
        ->assertInvalid('current_team_id');

    $this->assertNotEquals($team->id, $this->user->current_team_id);
});

it('does not switch the users current team if it does not exist', function () {
    $this->put(route('user.current-team.update'), ['current_team_id' => 2])
        ->assertInvalid('current_team_id');

    $this->assertNotEquals(2, $this->user->current_team_id);

    $this->put(route('user.current-team.update'), ['current_team_id' => 'foo'])
        ->assertInvalid('current_team_id');

    $this->assertNotEquals('foo', $this->user->current_team_id);

    $ulid = Str::ulid();

    $this->put(route('user.current-team.update'), ['current_team_id' => $ulid])
        ->assertInvalid('current_team_id');

    $this->assertNotEquals($ulid, $this->user->current_team_id);
});
