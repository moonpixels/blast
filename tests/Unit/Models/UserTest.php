<?php

use App\Models\Team;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()
        ->withTwoFactorAuthentication()
        ->create([
            'name' => 'John Doe',
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
    $memberTeam->users()->attach($this->user);

    $nonMemberTeam = Team::factory()->create();

    expect($this->user->belongsToTeam($ownedTeam))->toBeTrue()
        ->and($this->user->belongsToTeam($memberTeam))->toBeTrue()
        ->and($this->user->belongsToTeam($nonMemberTeam))->toBeFalse();
});

it('can determine if the user owns the given team', function () {
    $ownedTeam = Team::factory()->for($this->user, 'owner')->create();

    $memberTeam = Team::factory()->create();
    $memberTeam->users()->attach($this->user);

    $nonMemberTeam = Team::factory()->create();

    expect($this->user->ownsTeam($ownedTeam))->toBeTrue()
        ->and($this->user->ownsTeam($memberTeam))->toBeFalse()
        ->and($this->user->ownsTeam($nonMemberTeam))->toBeFalse();
});
