<?php

use App\Enums\Visits\DeviceTypes;
use App\Models\Link;

beforeEach(function () {
    $this->link = Link::factory()->create();
});

it('redirects the user to the destination URL', function () {
    $this->get(route('redirect', $this->link->alias))
        ->assertRedirect($this->link->destination_url);
});

it('caches the link for 1 hour', function () {
    $this->get(route('redirect', $this->link->alias))
        ->assertRedirect($this->link->destination_url);

    expect(Cache::has("links:{$this->link->alias}"))->toBeTrue();

    $this->travel(61)->minutes();

    expect(Cache::has("links:{$this->link->alias}"))->toBeFalse();

});

it('creates a visit for the link', function () {
    $this->get(route('redirect', $this->link->alias));

    expect($this->link->visits)->toHaveCount(1)
        ->and($this->link->fresh()->total_visits)->toBe(1);
});

it('uses the cache-control header to prevent caching', function () {
    $this->get(route('redirect', $this->link->alias))
        ->assertHeader('cache-control', 'no-cache, no-store, private');
});

it('uses a 301 redirect', function () {
    $this->get(route('redirect', $this->link->alias))
        ->assertStatus(301);
});

it('creates a visit with the user agent information', function () {
    $this->get(route('redirect', $this->link->alias), [
        'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:109.0) Gecko/20100101 Firefox/115.0',
    ]);

    expect($this->link->visits->first()->device_type)->toBe(DeviceTypes::Desktop)
        ->and($this->link->visits->first()->browser)->toBe('Firefox')
        ->and($this->link->visits->first()->browser_version)->toBe('115.0')
        ->and($this->link->visits->first()->platform)->toBe('OS X')
        ->and($this->link->visits->first()->platform_version)->toBe('10.15')
        ->and($this->link->visits->first()->is_robot)->toBeFalse()
        ->and($this->link->visits->first()->robot_name)->toBeNull();
});

it('creates a visit with referer information', function () {
    $this->get(route('redirect', $this->link->alias), [
        'referer' => 'https://example.com/foo/bar?query=string#fragment',
    ]);

    expect($this->link->visits->first()->referer_host)->toBe('example.com')
        ->and($this->link->visits->first()->referer_path)->toBe('/foo/bar?query=string#fragment');
});

it('creates a visit with robot information', function () {
    $this->get(route('redirect', $this->link->alias), [
        'user-agent' => 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; Googlebot/2.1; +http://www.google.com/bot.html) Chrome/W.X.Y.Z Safari/537.36',
    ]);

    expect($this->link->visits->first()->is_robot)->toBeTrue()
        ->and($this->link->visits->first()->robot_name)->toBe('Googlebot');
});

it('redirects the user to the authenticated redirect route if the link has a password', function () {
    $link = Link::factory()->withPassword()->create();

    $this->get(route('redirect', $link->alias))
        ->assertRedirectToRoute('authenticated-redirect', $link->alias);
});

it('redirects the user to the destination URL if the link has a password and the user has authenticated', function () {
    $link = Link::factory()->withPassword()->create();

    session()->put("authenticated:{$link->alias}", true);

    $this->get(route('redirect', $link->alias))
        ->assertRedirect($link->destination_url);
});
