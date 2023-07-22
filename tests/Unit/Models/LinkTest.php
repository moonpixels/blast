<?php

use App\Models\Domain;
use App\Models\Link;
use App\Models\Team;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->domain = Domain::factory()->create([
        'host' => 'blst.to',
    ]);

    $this->link = Link::factory()->for($this->domain)->create([
        'destination_path' => '/test',
    ]);
});

it('generates a short link based on the app url config', function () {
    config(['app.url' => 'https://blst.to']);

    expect($this->link->short_url)->toBe('https://blst.to/'.$this->link->alias);
});

it('generates the correct destination URL', function () {
    expect($this->link->destination_url)->toBe('https://blst.to/test');
});

it('increments the total visits for the link', function () {
    expect($this->link->total_visits)->toBe(0);

    $this->link->incrementTotalVisits();

    expect($this->link->total_visits)->toBe(1);
});

it('returns the correct indexable array', function () {
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

it('belongs to a domain', function () {
    expect($this->link->domain)->toBeInstanceOf(Domain::class);
});

it('belongs to a team', function () {
    expect($this->link->team)->toBeInstanceOf(Team::class);
});

it('has many visits', function () {
    Visit::factory(5)->for($this->link)->create();

    $visits = $this->link->visits;

    expect($visits)->toHaveCount(5)
        ->and($visits)->toBeInstanceOf(Collection::class)
        ->and($visits)->each->toBeInstanceOf(Visit::class);
});
