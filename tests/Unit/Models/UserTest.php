<?php

use App\Models\Team;
use App\Models\TeamMembership;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->user = User::factory()
        ->withTwoFactorAuthentication()
        ->create([
            'name' => 'John Doe',
            'email' => 'john.doe@blst.to',
        ]);
});

it('generates a two-factor QR code SVG for the user', function () {
    $this->assertStringStartsWith(
        '<svg',
        $this->user->twoFactorQrCodeSvg()
    );
});

it('gives the status of two-factor authentication', function () {
    expect($this->user->two_factor_enabled)->toBeTrue();

    $this->user->two_factor_confirmed_at = null;

    expect($this->user->two_factor_enabled)->toBeFalse();
});

it('generates initials for the user', function () {
    expect($this->user->initials)->toBe('JD');

    $this->user->name = 'John';
    expect($this->user->initials)->toBe('J');

    $this->user->name = 'John William Doe';
    expect($this->user->initials)->toBe('JD');
});

it('can switch the user to another team', function () {
    $team = Team::factory()->for($this->user, 'owner')->create();

    $this->user->switchTeam($team);

    expect($this->user->current_team_id)->toBe($team->id);
});

it('does not switch the users team if they do not belong to it', function () {
    $team = Team::factory()->create();

    $this->user->switchTeam($team);

    expect($this->user->current_team_id)->toBe($this->user->personalTeam()->id);
});

it('can determine if a user belongs to a team', function () {
    $ownedTeam = Team::factory()->for($this->user, 'owner')->create();

    $memberTeam = Team::factory()->create();
    TeamMembership::factory()->for($this->user)->for($memberTeam)->create();

    $nonMemberTeam = Team::factory()->create();

    expect($this->user->belongsToTeam($ownedTeam))->toBeTrue()
        ->and($this->user->belongsToTeam($memberTeam))->toBeTrue()
        ->and($this->user->belongsToTeam($nonMemberTeam))->toBeFalse();
});

it('can determine if the user owns the given team', function () {
    $ownedTeam = Team::factory()->for($this->user, 'owner')->create();

    $memberTeam = Team::factory()->create();
    TeamMembership::factory()->for($this->user)->for($memberTeam)->create();

    $nonMemberTeam = Team::factory()->create();

    expect($this->user->ownsTeam($ownedTeam))->toBeTrue()
        ->and($this->user->ownsTeam($memberTeam))->toBeFalse()
        ->and($this->user->ownsTeam($nonMemberTeam))->toBeFalse();
});

it('has many owned teams', function () {
    Team::factory(5)->for($this->user, 'owner')->create();

    $ownedTeams = $this->user->ownedTeams;

    // The user's personal team is also included in the owned teams
    expect($ownedTeams)->toHaveCount(6)
        ->and($ownedTeams)->toBeInstanceOf(Collection::class)
        ->and($ownedTeams)->each->toBeInstanceOf(Team::class);
});

it('always belongs to a current team', function () {
    $this->user->update(['current_team_id' => null]);

    expect($this->user->fresh()->currentTeam)->toBeInstanceOf(Team::class)
        ->and($this->user->currentTeam->id)->toBe($this->user->personalTeam()->id);

    $team = Team::factory()->for($this->user, 'owner')->create();

    $this->user->switchTeam($team);

    expect($this->user->currentTeam)->toBeInstanceOf(Team::class)
        ->and($this->user->currentTeam)->toBe($team);
});

it('has a personal team', function () {
    expect($this->user->personalTeam())->toBeInstanceOf(Team::class);
});

it('can get all teams including owned teams', function () {
    Team::factory(5)->for($this->user, 'owner')->create();

    $allTeams = $this->user->allTeams();

    // The user's personal team is also included in the owned teams
    expect($allTeams)->toHaveCount(6)
        ->and($allTeams)->toBeInstanceOf(Collection::class)
        ->and($allTeams)->each->toBeInstanceOf(Team::class);
});

it('has many team memberships', function () {
    TeamMembership::factory(5)->for($this->user)->create();

    $memberships = $this->user->teamMemberships;

    expect($memberships)->toHaveCount(5)
        ->and($memberships)->toBeInstanceOf(Collection::class)
        ->and($memberships)->each->toBeInstanceOf(TeamMembership::class);
});

it('has many teams', function () {
    TeamMembership::factory(5)->for($this->user)->create();

    $teams = $this->user->teams;

    expect($teams)->toHaveCount(5)
        ->and($teams)->toBeInstanceOf(Collection::class)
        ->and($teams)->each->toBeInstanceOf(Team::class);
});
