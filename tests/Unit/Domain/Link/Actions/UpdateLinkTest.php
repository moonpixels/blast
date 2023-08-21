<?php

use App\Domain\Link\Actions\UpdateLink;
use App\Domain\Link\Data\LinkData;
use Spatie\LaravelData\Optional;

beforeEach(function () {
    $this->link = createLink();
});

it('updates a link', function () {
    $team = createTeam();
    $expiresAt = now()->subDay();

    $result = UpdateLink::run($this->link, LinkData::from([
        'team_id' => $team->id,
        'destination_url' => 'https://example.com',
        'alias' => 'testing',
        'password' => 'password',
        'visit_limit' => 10,
        'expires_at' => $expiresAt,
    ]));

    $this->link->refresh();

    expect($result)->toBeTrue()
        ->and($this->link->team_id)->toBe($team->id)
        ->and($this->link->destination_url)->toBe('https://example.com')
        ->and($this->link->alias)->toBe('testing')
        ->and($this->link->passwordMatches('password'))->toBeTrue()
        ->and($this->link->visit_limit)->toBe(10)
        ->and($this->link->expires_at->toIso8601String())->toBe($expiresAt->toIso8601String());
});

it('does not update attributes that are not provided', function () {
    $result = UpdateLink::run($this->link, LinkData::from([
        'team_id' => Optional::create(),
        'destination_url' => Optional::create(),
        'alias' => Optional::create(),
        'password' => Optional::create(),
        'visit_limit' => Optional::create(),
        'expires_at' => Optional::create(),
    ]));

    $originalLink = $this->link->replicate();

    $this->link->refresh();

    expect($result)->toBeTrue()
        ->and($this->link->team_id)->toBe($originalLink->team_id)
        ->and($this->link->destination_url)->toBe($originalLink->destination_url)
        ->and($this->link->alias)->toBe($originalLink->alias)
        ->and($this->link->password)->toBeNull()
        ->and($this->link->visit_limit)->toBeNull()
        ->and($this->link->expires_at)->toBeNull();
});
