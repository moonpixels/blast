<?php

use App\Domain\Team\Models\Team;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->user = User::factory()
        ->withTwoFactorAuthentication()
        ->withStandardTeam()
        ->withTeamMembership()
        ->create([
            'name' => 'John Doe',
            'email' => 'john.doe@blst.to',
        ]);

    $this->standardTeam = $this->user->ownedTeams()->notPersonal()->first();
    $this->membershipTeam = $this->user->teams->first();
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
    $this->user->switchTeam($this->standardTeam);

    expect($this->user->current_team_id)->toBe($this->standardTeam->id);
});

it('does not switch the users team if they do not belong to it', function () {
    $team = Team::factory()->create();

    $this->user->switchTeam($team);

    expect($this->user->current_team_id)->toBe($this->user->personalTeam()->id);
});

it('can determine if a user belongs to a team', function () {
    $team = Team::factory()->create();

    expect($this->user->belongsToTeam($this->standardTeam))->toBeTrue()
        ->and($this->user->belongsToTeam($this->membershipTeam))->toBeTrue()
        ->and($this->user->belongsToTeam($team))->toBeFalse();
});

it('can determine if the user owns the given team', function () {
    $team = Team::factory()->create();

    expect($this->user->ownsTeam($this->standardTeam))->toBeTrue()
        ->and($this->user->ownsTeam($this->membershipTeam))->toBeFalse()
        ->and($this->user->ownsTeam($team))->toBeFalse();
});

it('has many owned teams', function () {
    $ownedTeams = $this->user->ownedTeams;

    // The user's personal team is also included in the owned teams
    expect($ownedTeams)->toHaveCount(2)
        ->and($ownedTeams)->toBeInstanceOf(Collection::class)
        ->and($ownedTeams)->each->toBeInstanceOf(Team::class);
});

it('always has a current team', function () {
    $this->user->update(['current_team_id' => null]);

    expect($this->user->fresh()->currentTeam)->toBeInstanceOf(Team::class)
        ->and($this->user->currentTeam->id)->toBe($this->user->personalTeam()->id);
});

it('has a personal team', function () {
    expect($this->user->personalTeam())->toBeInstanceOf(Team::class);
});

it('can get all teams including owned teams', function () {
    $allTeams = $this->user->allTeams();

    expect($allTeams)->toHaveCount(3)
        ->and($allTeams)->toBeInstanceOf(Collection::class)
        ->and($allTeams)->each->toBeInstanceOf(Team::class);
});

it('has many teams', function () {
    $teams = $this->user->teams;

    expect($teams)->toHaveCount(1)
        ->and($teams)->toBeInstanceOf(Collection::class)
        ->and($teams)->each->toBeInstanceOf(Team::class);
});
