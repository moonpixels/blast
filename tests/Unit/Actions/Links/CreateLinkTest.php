<?php

use App\Actions\Links\CreateLink;
use App\Exceptions\InvalidUrlException;
use App\Models\Team;

beforeEach(function () {
    $this->team = Team::factory()->create();
    $this->url = 'https://blst.to/this-is-a-path?query=string#fragment';
});

it('can create a new link for a valid URL', function () {
    $link = CreateLink::run([
        'team_id' => $this->team->id,
        'url' => $this->url,
    ]);

    $this->assertDatabaseHas('domains', [
        'host' => 'blst.to',
    ]);

    $this->assertDatabaseHas('links', [
        'team_id' => $this->team->id,
        'domain_id' => $link->domain_id,
        'destination_path' => '/this-is-a-path?query=string#fragment',
    ]);

    expect($link->alias)->toBeString();
});

it('stores the domain host in lowercase', function () {
    $link = CreateLink::run([
        'team_id' => $this->team->id,
        'url' => 'https://BLST.TO',
    ]);

    expect($link->domain->host)->toBe('blst.to');

    $link = CreateLink::run([
        'team_id' => $this->team->id,
        'url' => 'https://BlSt.To',
    ]);

    expect($link->domain->host)->toBe('blst.to');
});

it('does not create a new domain if it already exists', function () {
    $link1 = CreateLink::run([
        'team_id' => $this->team->id,
        'url' => $this->url,
    ]);

    $link2 = CreateLink::run([
        'team_id' => $this->team->id,
        'url' => 'https://blst.to/another-path',
    ]);

    $this->assertDatabaseCount('domains', 1);

    expect($link1->domain_id)->toBe($link2->domain_id);
});

it('throws an exception if the host is invalid', function () {
    CreateLink::run([
        'team_id' => $this->team->id,
        'url' => 'https://',
    ]);
})->throws(InvalidUrlException::class);

it('stores the destination path in the same format as the input', function () {
    $link = CreateLink::run([
        'team_id' => $this->team->id,
        'url' => $this->url,
    ]);

    expect($link->destination_path)->toBe('/this-is-a-path?query=string#fragment');
});

it('allows a custom alias to be set', function () {
    $link = CreateLink::run([
        'team_id' => $this->team->id,
        'url' => $this->url,
        'alias' => 'custom-alias',
    ]);

    expect($link->alias)->toBe('custom-alias');
});

it('can create new links with case sensitive aliases', function () {
    $link = CreateLink::run([
        'team_id' => $this->team->id,
        'url' => $this->url,
        'alias' => 'CustomAlias',
    ]);

    expect($link->alias)->toBe('CustomAlias');

    $link = CreateLink::run([
        'team_id' => $this->team->id,
        'url' => $this->url,
        'alias' => 'customalias',
    ]);

    expect($link->alias)->toBe('customalias');
});
