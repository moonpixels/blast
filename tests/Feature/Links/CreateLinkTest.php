<?php

use App\Actions\Links\CreateLink;
use App\Exceptions\InvalidUrlException;
use App\Models\Link;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->user = User::factory()
        ->withStandardTeam()
        ->withTeamMembership()
        ->create();

    $this->standardTeam = $this->user->ownedTeams()->where('personal_team', false)->first();
    $this->membershipTeam = $this->user->teams->first();

    $this->actingAs($this->user);
});

it('can create a link for a users personal team', function () {
    $this->post(route('links.store'), [
        'url' => 'https://blst.to',
        'team_id' => $this->user->personalTeam()->id,
    ])->assertRedirect();

    expect(Link::count())->toBe(1);
});

it('can create a link for a team the user owns', function () {
    $this->post(route('links.store'), [
        'url' => 'https://blst.to',
        'team_id' => $this->standardTeam->id,
    ])->assertRedirect();

    expect(Link::count())->toBe(1);
});

it('can create a link for a team the user is a member of', function () {
    $this->post(route('links.store'), [
        'url' => 'https://blst.to',
        'team_id' => $this->membershipTeam->id,
    ])->assertRedirect();

    expect(Link::count())->toBe(1);
});

it('flashes the shortened link to the session', function () {
    $this->post(route('links.store'), [
        'url' => 'https://blst.to',
        'team_id' => $this->standardTeam->id,
    ])->assertRedirect();

    expect(session()->has('shortened_link'))->toBeTrue();
});

it('does not create a link for teams the user is not a member of', function () {
    $team = Team::factory()->create();

    $this->post(route('links.store'), [
        'url' => 'https://blst.to',
        'team_id' => $team->id,
    ])->assertInvalid('team_id');

    expect(Link::count())->toBe(0);
});

it('does not create a link for teams that do not exist', function () {
    $this->post(route('links.store'), [
        'url' => 'https://blst.to',
        'team_id' => Str::ulid(),
    ])->assertInvalid('team_id');

    expect(Link::count())->toBe(0);
});

it('does not create a link when the URL is invalid', function () {
    $this->post(route('links.store'), [
        'url' => '',
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('url');

    $this->post(route('links.store'), [
        'url' => 'invalid-url',
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('url');

    $this->post(route('links.store'), [
        'url' => 'https://blst.to/'.str_repeat('a', 2033),
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('url');

    expect(Link::count())->toBe(0);
});

it('does not create a link when the alias is already taken', function () {
    Link::factory()->create([
        'alias' => 'alreadyTaken',
    ]);

    $this->post(route('links.store'), [
        'url' => 'https://blst.to',
        'alias' => 'alreadyTaken',
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('alias');

    expect(Link::count())->toBe(1);
});

it('does not create a link when the alias is too long', function () {
    $this->post(route('links.store'), [
        'url' => 'https://blst.to',
        'alias' => Str::random(21),
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('alias');

    expect(Link::count())->toBe(0);
});

it('does not create a link when the alias is on the reserved list', function () {
    $this->post(route('links.store'), [
        'url' => 'https://blst.to',
        'alias' => 'admin',
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('alias');

    expect(Link::count())->toBe(0);
});

it('does not create a link when the alias matches an app route', function () {
    $this->post(route('links.store'), [
        'url' => 'https://blst.to',
        'alias' => 'login',
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('alias');

    expect(Link::count())->toBe(0);
});

it('does not create a link when the alias contains invalid characters', function () {
    $this->post(route('links.store'), [
        'url' => 'https://blst.to',
        'alias' => '!@#$%^&*()',
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('alias');

    expect(Link::count())->toBe(0);
});

it('creates a link when the alias letter case is different to an existing alias', function () {
    Link::factory()->create([
        'alias' => 'alreadyTaken',
    ]);

    $this->post(route('links.store'), [
        'url' => 'https://blst.to',
        'alias' => 'AlreadyTaken',
        'team_id' => $this->standardTeam->id,
    ])->assertRedirect();

    expect(Link::count())->toBe(2);
});

it('does not create a link when the URL host is invalid', function () {
    CreateLink::shouldRun()
        ->once()
        ->andThrow(InvalidUrlException::invalidHost());

    $this->post(route('links.store'), [
        'url' => 'https://blst.to',
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('url');

    expect(Link::count())->toBe(0);
});
