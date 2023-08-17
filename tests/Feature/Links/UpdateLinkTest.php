<?php

use App\Domain\Link\Models\Link;
use App\Domain\Team\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->user = User::factory()
        ->withStandardTeam()
        ->withTeamMembership()
        ->create();

    $this->standardTeam = $this->user->ownedTeams()->where('personal_team', false)->first();
    $this->membershipTeam = $this->user->teams->first();

    $this->personalTeamLink = Link::factory()->for($this->user->personalTeam())->create();
    $this->standardTeamLink = Link::factory()->for($this->standardTeam)->create();
    $this->membershipTeamLink = Link::factory()->for($this->membershipTeam)->create();

    $this->updateData = [
        'destination_url' => 'https://blst.to/new-url',
        'team_id' => $this->standardTeam->id,
        'alias' => 'newAlias',
        'visit_limit' => 10,
        'password' => 'newPassword',
        'expires_at' => now()->toIso8601String(),
    ];

    $this->actingAs($this->user);
});

it('updates a link for a users personal team', function () {
    $this->put(route('links.update', $this->personalTeamLink), $this->updateData)
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->personalTeamLink->refresh();

    expect($this->personalTeamLink->destination_url)->toBe($this->updateData['destination_url'])
        ->and($this->personalTeamLink->team_id)->toBe($this->updateData['team_id'])
        ->and($this->personalTeamLink->alias)->toBe($this->updateData['alias'])
        ->and($this->personalTeamLink->visit_limit)->toBe($this->updateData['visit_limit'])
        ->and(Hash::check($this->updateData['password'], $this->personalTeamLink->password))->toBeTrue()
        ->and($this->personalTeamLink->expires_at->toIso8601String())->toBe($this->updateData['expires_at']);
});

it('updates a link for a team the user owns', function () {
    $this->put(route('links.update', $this->standardTeamLink), $this->updateData)
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->standardTeamLink->refresh();

    expect($this->standardTeamLink->destination_url)->toBe($this->updateData['destination_url'])
        ->and($this->standardTeamLink->team_id)->toBe($this->updateData['team_id'])
        ->and($this->standardTeamLink->alias)->toBe($this->updateData['alias'])
        ->and($this->standardTeamLink->visit_limit)->toBe($this->updateData['visit_limit'])
        ->and(Hash::check($this->updateData['password'], $this->standardTeamLink->password))->toBeTrue()
        ->and($this->standardTeamLink->expires_at->toIso8601String())->toBe($this->updateData['expires_at']);
});

it('updates a link for a team the user is a member of', function () {
    $this->put(route('links.update', $this->membershipTeamLink), $this->updateData)
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->membershipTeamLink->refresh();

    expect($this->membershipTeamLink->destination_url)->toBe($this->updateData['destination_url'])
        ->and($this->membershipTeamLink->team_id)->toBe($this->updateData['team_id'])
        ->and($this->membershipTeamLink->alias)->toBe($this->updateData['alias'])
        ->and($this->membershipTeamLink->visit_limit)->toBe($this->updateData['visit_limit'])
        ->and(Hash::check($this->updateData['password'], $this->membershipTeamLink->password))->toBeTrue()
        ->and($this->membershipTeamLink->expires_at->toIso8601String())->toBe($this->updateData['expires_at']);
});

it('does not update a link for a team the user does not belong to', function () {
    $this->link = Link::factory()->create();

    $this->put(route('links.update', $this->link), $this->updateData)->assertForbidden();
});

it('does not update a link when the URL is invalid', function () {
    $this->put(route('links.update', $this->personalTeamLink), array_merge($this->updateData, [
        'destination_url' => '',
    ]))->assertInvalid('destination_url');

    $this->put(route('links.update', $this->personalTeamLink), array_merge($this->updateData, [
        'destination_url' => 'https://blst.to/'.str_repeat('a', 2033),
    ]))->assertInvalid('destination_url');
});

it('does not update the link if the alias is already taken', function () {
    $this->put(route('links.update', $this->personalTeamLink), array_merge($this->updateData, [
        'alias' => $this->standardTeamLink->alias,
    ]))->assertInvalid('alias');
});

it('does not update the link if the alias is already taken by a deleted link', function () {
    $this->standardTeamLink->delete();

    $this->put(route('links.update', $this->personalTeamLink), array_merge($this->updateData, [
        'alias' => $this->standardTeamLink->alias,
    ]))->assertInvalid('alias');
});

it('does not update the link if the alias is invalid', function () {
    // Too long...
    $this->put(route('links.update', $this->personalTeamLink), array_merge($this->updateData, [
        'alias' => Str::random(21),
    ]))->assertInvalid('alias');

    // Reserved...
    $this->put(route('links.update', $this->personalTeamLink), array_merge($this->updateData, [
        'alias' => 'admin',
    ]))->assertInvalid('alias');

    // App route...
    $this->put(route('links.update', $this->personalTeamLink), array_merge($this->updateData, [
        'alias' => 'login',
    ]))->assertInvalid('alias');

    // Invalid characters...
    $this->put(route('links.update', $this->personalTeamLink), array_merge($this->updateData, [
        'alias' => '!@#$%^&*()',
    ]))->assertInvalid('alias');
});

it('updates a link when the alias has different letter casing', function () {
    Link::factory()->create(['alias' => 'test']);

    $this->put(route('links.update', $this->standardTeamLink), array_merge($this->updateData, [
        'alias' => 'TEST',
    ]))->assertRedirect()->assertSessionHas('success');
});
