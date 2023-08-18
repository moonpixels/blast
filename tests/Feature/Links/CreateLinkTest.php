<?php

use App\Domain\Link\Models\Link;
use App\Domain\Team\Models\Team;
use App\Domain\User\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->user = User::factory()
        ->withStandardTeam()
        ->withTeamMembership()
        ->create();

    $this->standardTeam = $this->user->ownedTeams()->notPersonal()->first();
    $this->membershipTeam = $this->user->teams->first();

    $this->actingAs($this->user);
});

it('can create a link for a users personal team', function () {
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'team_id' => $this->user->personalTeam()->id,
    ])->assertRedirect();

    expect(Link::count())->toBe(1);
});

it('can create a link for a team the user owns', function () {
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'team_id' => $this->standardTeam->id,
    ])->assertRedirect();

    expect(Link::count())->toBe(1);
});

it('can create a link for a team the user is a member of', function () {
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'team_id' => $this->membershipTeam->id,
    ])->assertRedirect();

    expect(Link::count())->toBe(1);
});

it('flashes the shortened link to the session', function () {
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'team_id' => $this->standardTeam->id,
    ])->assertRedirect();

    expect(session()->has('shortened_link'))->toBeTrue();
});

it('does not create a link for teams the user is not a member of', function () {
    $team = Team::factory()->create();

    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'team_id' => $team->id,
    ])->assertInvalid('team_id');

    expect(Link::count())->toBe(0);
});

it('does not create a link for teams that do not exist', function () {
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'team_id' => Str::ulid(),
    ])->assertInvalid('team_id');

    expect(Link::count())->toBe(0);
});

it('does not create a link when the URL is invalid', function () {
    $this->post(route('links.store'), [
        'destination_url' => '',
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('destination_url');

    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to/'.str_repeat('a', 2033),
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('destination_url');

    expect(Link::count())->toBe(0);
});

it('does not create a link when the alias is already taken', function () {
    Link::factory()->create([
        'alias' => 'alreadyTaken',
    ]);

    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'alias' => 'alreadyTaken',
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('alias');

    expect(Link::count())->toBe(1);
});

it('does not create a link when the alias is already taken by a deleted link', function () {
    Link::factory()->create([
        'alias' => 'alreadyTaken',
        'deleted_at' => Carbon::now(),
    ]);

    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'alias' => 'alreadyTaken',
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('alias');

    expect(Link::count())->toBe(0);
});

it('does not create a link when the alias is too long', function () {
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'alias' => Str::random(21),
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('alias');

    expect(Link::count())->toBe(0);
});

it('does not create a link when the alias is on the reserved list', function () {
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'alias' => 'admin',
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('alias');

    expect(Link::count())->toBe(0);
});

it('does not create a link when the alias matches an app route', function () {
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'alias' => 'login',
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('alias');

    expect(Link::count())->toBe(0);
});

it('does not create a link when the alias contains invalid characters', function () {
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
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
        'destination_url' => 'https://blst.to',
        'alias' => 'AlreadyTaken',
        'team_id' => $this->standardTeam->id,
    ])->assertRedirect();

    expect(Link::count())->toBe(2);
});

it('can create a link with a password', function () {
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'password' => 'password',
        'team_id' => $this->standardTeam->id,
    ])->assertRedirect();

    expect(Link::count())->toBe(1);

    $link = Link::first();

    expect($link->has_password)->toBeTrue()
        ->and(Hash::check('password', $link->password))->toBeTrue();
});

it('can create a link with an expiry date', function () {
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'expires_at' => now()->addDay(),
        'team_id' => $this->standardTeam->id,
    ])->assertRedirect();

    expect(Link::count())->toBe(1);

    $link = Link::first();

    expect($link->expires_at)->toBeInstanceOf(Carbon::class)
        ->and($link->expires_at->isFuture())->toBeTrue();
});

it('does not create a link when the expiry date is invalid', function () {
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'expires_at' => 'invalid-date',
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('expires_at');

    expect(Link::count())->toBe(0);
});

it('does not create a link when the expiry date is in the past', function () {
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'expires_at' => now()->subDay(),
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('expires_at');

    expect(Link::count())->toBe(0);
});

it('can create a link with a visit limit', function () {
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'visit_limit' => 10,
        'team_id' => $this->standardTeam->id,
    ])->assertRedirect();

    expect(Link::count())->toBe(1);

    $link = Link::first();

    expect($link->visit_limit)->toBe(10);
});

it('does not create a link when the visit limit is invalid', function () {
    // Not an integer...
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'visit_limit' => 'invalid-limit',
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('visit_limit');

    expect(Link::count())->toBe(0);

    // Zero...
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'visit_limit' => 0,
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('visit_limit');

    expect(Link::count())->toBe(0);

    // Negative...
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'visit_limit' => -1,
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('visit_limit');

    expect(Link::count())->toBe(0);

    // Too large...
    $this->post(route('links.store'), [
        'destination_url' => 'https://blst.to',
        'visit_limit' => 16777216,
        'team_id' => $this->standardTeam->id,
    ])->assertInvalid('visit_limit');

    expect(Link::count())->toBe(0);
});
