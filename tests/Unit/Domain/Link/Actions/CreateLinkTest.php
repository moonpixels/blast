<?php

use App\Domain\Link\Actions\CreateLink;
use App\Domain\Link\Data\LinkData;
use Spatie\LaravelData\Optional;

beforeEach(function () {
    $this->linkData = LinkData::from([
        'team_id' => createTeam()->id,
        'destination_url' => 'https://example.com/this-is-a-path?query=string#fragment',
        'alias' => 'testing',
        'password' => 'password',
        'visit_limit' => 10,
        'expires_at' => now()->addDay(),
    ]);
});

it('creates a new link', function () {
    $link = CreateLink::run($this->linkData);

    expect($link)->toExistInDatabase()
        ->and($link->team_id)->toBe($this->linkData->teamId)
        ->and($link->destination_url)->toBe('https://example.com/this-is-a-path?query=string#fragment')
        ->and($link->alias)->toBe('testing')
        ->and($link->passwordMatches('password'))->toBeTrue()
        ->and($link->visit_limit)->toBe(10)
        ->and($link->expires_at->toIso8601String())->toBe($this->linkData->expiresAt->toIso8601String());
});

it('generates an alias if none is provided', function () {
    $this->linkData->alias = Optional::create();

    $link = CreateLink::run($this->linkData);

    expect($link->alias)->toBeString();
});
