<?php

use App\Domain\Link\Models\Link;
use Carbon\Carbon;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = login();
    $this->ownedTeam = getTeamForUser($this->user, 'Owned Team');
    $this->memberTeam = getTeamForUser($this->user, 'Member Team');
    $this->user->switchTeam($this->ownedTeam);
});

test('users can create links', function () {
    $this->post(route('links.store'), [
        'team_id' => $this->ownedTeam->id,
        'destination_url' => 'https://example.com',
        'alias' => 'testing',
        'password' => 'password',
        'visit_limit' => 10,
        'expires_at' => now()->addDay()->toIso8601String(),
    ])->assertSessionHas('success', [
        'title' => 'Link created',
        'message' => 'The link has been created.',
    ]);

    $link = session()->get('shortened_link');

    expect($link)->toExistInDatabase()
        ->and($link->team->is($this->ownedTeam))->toBeTrue()
        ->and($link->destination_url)->toBe('https://example.com')
        ->and($link->alias)->toBe('testing')
        ->and($link->has_password)->toBeTrue()
        ->and(Hash::check('password', $link->password))->toBeTrue()
        ->and($link->visit_limit)->toBe(10)
        ->and($link->expires_at)->toBeInstanceOf(Carbon::class)
        ->and($link->expires_at->isFuture())->toBeTrue();
});

test('link creation fails if required fields are missing', function () {
    $this->post(route('links.store'))->assertInvalid([
        'team_id' => 'required',
        'destination_url' => 'required',
    ]);

    expect(Link::count())->toBe(0);
});

test('users can update links', function () {
    $link = createLink(attributes: ['team_id' => $this->ownedTeam->id]);

    $this->put(route('links.update', $link), [
        'team_id' => $this->memberTeam->id,
        'destination_url' => 'https://example.com/updated',
        'alias' => 'updated',
        'password' => null,
        'visit_limit' => null,
        'expires_at' => null,
    ])->assertSessionHas('success', [
        'title' => 'Link updated',
        'message' => 'The link has been updated.',
    ]);

    $link->refresh();

    expect($link->team->is($this->memberTeam))->toBeTrue()
        ->and($link->destination_url)->toBe('https://example.com/updated')
        ->and($link->alias)->toBe('updated')
        ->and($link->has_password)->toBeFalse()
        ->and($link->password)->toBeNull()
        ->and($link->visit_limit)->toBeNull()
        ->and($link->expires_at)->toBeNull();
});

test('users can delete links', function () {
    $link = createLink(attributes: ['team_id' => $this->ownedTeam->id]);

    $this->delete(route('links.destroy', $link))->assertSessionHas('success', [
        'title' => 'Link deleted',
        'message' => 'The link has been deleted.',
    ]);

    expect($link)->toBeSoftDeleted();
});

test('users can view the list of links', function () {
    $this->get(route('links.index'))
        ->assertOK()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Links/Index')
            ->has('links')
            ->has('filters')
        );
});

test('users can filter the list of links', function () {
    createLink(
        attributes: ['team_id' => $this->ownedTeam->id],
        states: ['count' => 10],
    );

    createLink(
        attributes: ['team_id' => $this->ownedTeam->id],
        states: ['withDestinationUrl' => 'https://example.com/filtered'],
    );

    $this->get(route('links.index', ['query' => 'filtered']))
        ->assertOK()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Links/Index')
            ->has('links.data', 1)
            ->has('links.data.0', fn (Assert $page) => $page
                ->where('destination_url', 'https://example.com/filtered')
                ->etc()
            )
        );
});
