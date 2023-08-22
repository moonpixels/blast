<?php

use App\Domain\Link\Models\Domain;
use App\Domain\Redirect\Models\Visit;
use App\Domain\Team\Models\Team;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->link = createLink(states: ['withDestinationUrl' => 'https://example.com/testing']);
});

it('belongs to a domain', function () {
    expect($this->link->domain)->toBeInstanceOf(Domain::class)
        ->and($this->link->domain->host)->toBe('example.com');
});

it('belongs to a team', function () {
    expect($this->link->team)->toBeInstanceOf(Team::class);
});

it('has many visits', function () {
    createVisit(
        attributes: ['link_id' => $this->link->id],
        states: ['count' => 5]
    );

    expect($this->link->visits)->toBeInstanceOf(Collection::class)
        ->and($this->link->visits)->toHaveCount(5)
        ->and($this->link->visits)->each->toBeInstanceOf(Visit::class);
});

it('increments total visits', function () {
    expect($this->link->total_visits)->toBe(0);

    $this->link->incrementTotalVisits();

    expect($this->link->total_visits)->toBe(1);
});

it('has a searchable array', function () {
    expect($this->link->toSearchableArray())->toHaveKeys([
        'id',
        'team_id',
        'alias',
        'destination_path',
        'destination_url',
        'short_url',
        'created_at',
        'updated_at',
    ]);
});

it('determines if a password matches', function () {
    $this->link->update(['password' => Hash::make('password')]);

    expect($this->link->passwordMatches('password'))->toBeTrue()
        ->and($this->link->passwordMatches('wrong-password'))->toBeFalse();
});

it('determines if the link has expired', function () {
    $this->link->update(['expires_at' => now()->addDay()]);

    expect($this->link->hasExpired())->toBeFalse();

    $this->travel(1)->days();

    expect($this->link->hasExpired())->toBeTrue();
});

it('determines if the link has reached its visit limit', function () {
    $this->link->update(['visit_limit' => 1]);

    expect($this->link->hasReachedVisitLimit())->toBeFalse();

    $this->link->incrementTotalVisits();

    expect($this->link->hasReachedVisitLimit())->toBeTrue();
});

it('has a destination URL', function () {
    expect($this->link->destination_url)->toBe('https://example.com/testing');
});

it('has a short URL', function () {
    expect($this->link->short_url)->toBe(config('app.url')."/{$this->link->alias}");
});

it('determines if the link has a password', function () {
    expect($this->link->has_password)->toBeFalse();

    $this->link->update(['password' => Hash::make('password')]);

    expect($this->link->has_password)->toBeTrue();
});
