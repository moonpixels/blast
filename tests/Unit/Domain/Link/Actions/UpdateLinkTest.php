<?php

use Domain\Link\Actions\UpdateLinkAction;
use Domain\Link\DTOs\DomainData;
use Domain\Link\DTOs\LinkData;

beforeEach(function () {
    $this->link = createLink();

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

it('updates a link', function () {
    $result = UpdateLinkAction::run($this->link, $this->linkData);

    $this->link->refresh();

    expect($result)->toBeTrue()
        ->and($this->link->team_id)->toBe($this->linkData->team_id)
        ->and($this->link->destination_url)->toBe('https://example.com/this-is-a-path?query=string#fragment')
        ->and($this->link->alias)->toBe('testing')
        ->and($this->link->passwordMatches('password'))->toBeTrue()
        ->and($this->link->visit_limit)->toBe(10)
        ->and($this->link->expires_at->isSameAs($this->linkData->expires_at))->toBeTrue();
});
