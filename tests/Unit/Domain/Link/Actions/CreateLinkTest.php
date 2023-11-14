<?php

use Domain\Link\Actions\CreateLinkAction;
use Domain\Link\DTOs\DomainData;
use Domain\Link\DTOs\LinkData;

beforeEach(function () {
    $this->linkData = LinkData::from([
        'team_id' => createTeam()->id,
        'domain' => DomainData::from(['host' => 'example.com']),
        'destination_path' => '/this-is-a-path?query=string#fragment',
        'alias' => 'testing',
        'password' => 'password',
        'visit_limit' => 10,
        'expires_at' => now()->addDay()->toAtomString(),
    ]);
});

it('creates a new link', function () {
    $link = CreateLinkAction::run($this->linkData);

    expect($link)->toExistInDatabase()
        ->and($link->team_id)->toBe($this->linkData->team_id)
        ->and($link->destination_url)->toBe('https://example.com/this-is-a-path?query=string#fragment')
        ->and($link->alias)->toBe('testing')
        ->and($link->passwordMatches('password'))->toBeTrue()
        ->and($link->visit_limit)->toBe(10)
        ->and($link->expires_at->isSameAs($this->linkData->expires_at))->toBeTrue();
});

it('generates an alias if none is provided', function () {
    $this->linkData->alias = null;

    $link = CreateLinkAction::run($this->linkData);

    expect($link->alias)->toBeString();
});
