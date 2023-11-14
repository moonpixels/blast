<?php

use Domain\Team\Models\Team;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->user = createUser(states: ['withTwoFactorAuthentication']);
});

it('belongs to a current team', function () {
    $this->user->current_team_id = null;

    expect($this->user->currentTeam)->toBeInstanceOf(Team::class)
        ->and($this->user->currentTeam->is($this->user->personalTeam()))->toBeTrue();
});

it('switches the users current team', function () {
    $team = getTeamForUser($this->user, 'Owned Team');

    expect($this->user->switchTeam($team))->toBeTrue()
        ->and($this->user->currentTeam->is($team))->toBeTrue();

    $anotherTeam = createTeam();

    expect($this->user->switchTeam($anotherTeam))->toBeFalse()
        ->and($this->user->currentTeam->is($team))->toBeTrue();
});

it('determines if the user belongs to the given team', function () {
    $team = getTeamForUser($this->user, 'Owned Team');

    expect($this->user->belongsToTeam($team))->toBeTrue();

    $anotherTeam = createTeam();

    expect($this->user->belongsToTeam($anotherTeam))->toBeFalse();
});

it('has one personal team', function () {
    expect($this->user->personalTeam())->toBeInstanceOf(Team::class);
});

it('has many owned teams', function () {
    expect($this->user->ownedTeams)->toBeInstanceOf(Collection::class)
        ->and($this->user->ownedTeams)->toHaveCount(2)
        ->and($this->user->ownedTeams)->each->toBeInstanceOf(Team::class);
});

it('determines if the user owns the given team', function () {
    $team = getTeamForUser($this->user, 'Owned Team');

    expect($this->user->ownsTeam($team))->toBeTrue();

    $anotherTeam = createTeam();

    expect($this->user->ownsTeam($anotherTeam))->toBeFalse();
});

it('creates a collection of all teams', function () {
    expect($this->user->allTeams())->toBeInstanceOf(Collection::class)
        ->and($this->user->allTeams())->toHaveCount(3)
        ->and($this->user->allTeams())->each->toBeInstanceOf(Team::class);
});

it('belongs to many teams', function () {
    expect($this->user->teams)->toBeInstanceOf(Collection::class)
        ->and($this->user->teams)->toHaveCount(1)
        ->and($this->user->teams)->each->toBeInstanceOf(Team::class);
});

it('can generate a two factor QR code', function () {
    expect($this->user->twoFactorQrCodeSvg())->toBeString()
        ->and($this->user->twoFactorQrCodeSvg())->toContain('svg');
});

it('determines if two factor authentication is enabled', function () {
    expect($this->user->two_factor_enabled)->toBeTrue();
});

it('has the users initials', function () {
    $this->user->update(['name' => 'Test User']);

    expect($this->user->initials)->toBe('TU');
});
