<?php

use App\Actions\Links\UpdateLink;
use App\Data\LinkData;
use App\Models\Link;
use App\Models\Team;

beforeEach(function () {
    $this->link = Link::factory()->create();
});

it('can update a link with a new destination URL', function () {
    $newUrl = 'https://blst.to/new-url';

    UpdateLink::run($this->link, LinkData::from(array_merge($this->link->toArray(), [
        'destination_url' => $newUrl,
    ])));

    $this->link->refresh();

    expect($this->link->destination_url)->toBe($newUrl);
});

it('can update a link with a new alias', function () {
    $newAlias = 'new-alias';

    UpdateLink::run($this->link, LinkData::from(array_merge($this->link->toArray(), [
        'alias' => $newAlias,
    ])));

    $this->link->refresh();

    expect($this->link->alias)->toBe($newAlias);
});

it('can update a link with a new password', function () {
    $newPassword = 'new-password';

    UpdateLink::run($this->link, LinkData::from(array_merge($this->link->toArray(), [
        'password' => $newPassword,
    ])));

    $this->link->refresh();

    expect(Hash::check($newPassword, $this->link->password))->toBeTrue();
});

it('can remove the password from a link', function () {
    $this->link->update([
        'password' => Hash::make('password'),
    ]);

    UpdateLink::run($this->link, LinkData::from(array_merge($this->link->toArray(), [
        'password' => null,
    ])));

    $this->link->refresh();

    expect($this->link->password)->toBeNull();
});

it('does not update the password if one is not provided', function () {
    $this->link->update([
        'password' => Hash::make('password'),
    ]);

    UpdateLink::run($this->link, LinkData::from($this->link->toArray()));

    $this->link->refresh();

    expect($this->link->password)->not->toBeNull()
        ->and(Hash::check('password', $this->link->password))->toBeTrue();
});

it('can update a link with a new expiry date', function () {
    $newExpiryDate = now()->addDay();

    UpdateLink::run($this->link, LinkData::from(array_merge($this->link->toArray(), [
        'expires_at' => $newExpiryDate,
    ])));

    $this->link->refresh();

    expect($this->link->expires_at->toIso8601String())->toBe($newExpiryDate->toIso8601String());
});

it('can remove the expiry date from a link', function () {
    $this->link->update([
        'expires_at' => now()->addDay(),
    ]);

    UpdateLink::run($this->link, LinkData::from(array_merge($this->link->toArray(), [
        'expires_at' => null,
    ])));

    $this->link->refresh();

    expect($this->link->expires_at)->toBeNull();
});

it('can update a link with a new visit limit', function () {
    $newVisitLimit = 10;

    UpdateLink::run($this->link, LinkData::from(array_merge($this->link->toArray(), [
        'visit_limit' => $newVisitLimit,
    ])));

    $this->link->refresh();

    expect($this->link->visit_limit)->toBe($newVisitLimit);
});

it('can remove the visit limit from a link', function () {
    $this->link->update([
        'visit_limit' => 10,
    ]);

    UpdateLink::run($this->link, LinkData::from(array_merge($this->link->toArray(), [
        'visit_limit' => null,
    ])));

    $this->link->refresh();

    expect($this->link->visit_limit)->toBeNull();
});

it('can update a link with a new team', function () {
    $newTeam = Team::factory()->for($this->link->team->owner, 'owner')->create();

    UpdateLink::run($this->link, LinkData::from(array_merge($this->link->toArray(), [
        'team_id' => $newTeam->id,
    ])));

    $this->link->refresh();

    expect($this->link->team_id)->toBe($newTeam->id);
});
