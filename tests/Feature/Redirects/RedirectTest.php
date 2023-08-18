<?php

use App\Domain\Link\Models\Link;
use App\Domain\Redirect\Enums\DeviceTypes;
use App\Domain\Redirect\Models\Visit;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->link = Link::factory()->create();
});

it('redirects the user to the destination URL', function () {
    $this->get(route('redirects.show', $this->link->alias))
        ->assertRedirect($this->link->destination_url);
});

it('creates a visit for the link', function () {
    $this->get(route('redirects.show', $this->link->alias));

    expect($this->link->visits)->toHaveCount(1)
        ->and($this->link->fresh()->total_visits)->toBe(1);
});

it('uses the cache-control header to prevent caching', function () {
    $this->get(route('redirects.show', $this->link->alias))
        ->assertHeader('cache-control', 'no-cache, no-store, private');
});

it('uses a 301 redirect', function () {
    $this->get(route('redirects.show', $this->link->alias))
        ->assertStatus(301);
});

it('creates a visit with the user agent information', function () {
    $this->get(route('redirects.show', $this->link->alias), [
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
    $this->get(route('redirects.show', $this->link->alias), [
        'referer' => 'https://example.com/foo/bar?query=string#fragment',
    ]);

    expect($this->link->visits->first()->referer_host)->toBe('example.com')
        ->and($this->link->visits->first()->referer_path)->toBe('/foo/bar?query=string#fragment');
});

it('creates a visit with robot information', function () {
    $this->get(route('redirects.show', $this->link->alias), [
        'user-agent' => 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; Googlebot/2.1; +http://www.google.com/bot.html) Chrome/W.X.Y.Z Safari/537.36',
    ]);

    expect($this->link->visits->first()->is_robot)->toBeTrue()
        ->and($this->link->visits->first()->robot_name)->toBe('Googlebot');
});

it('shows the password page if the link is protected', function () {
    $this->link->update(['password' => Hash::make('password')]);

    $this->get(route('redirects.show', $this->link->alias))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Redirects/Protected')
            ->where('alias', $this->link->alias)
        );
});

it('redirects the user to the destination URL when the password is correct', function () {
    $this->link->update(['password' => Hash::make('password')]);

    $this->post(route('redirects.authenticate', $this->link->alias), [
        'password' => 'password',
    ])->assertRedirect($this->link->destination_url);
});

it('does not redirect the user when the password is incorrect', function () {
    $this->link->update(['password' => Hash::make('password')]);

    $this->post(route('redirects.authenticate', $this->link->alias), [
        'password' => 'wrong-password',
    ])->assertInvalid(['password']);
});

it('shows the expired page if the link has expired', function () {
    $this->link->update(['expires_at' => now()->subDay()]);

    $this->get(route('redirects.show', $this->link->alias))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Redirects/Expired')
        );
});

it('shows the reached visit limit page if the link has reached its visit limit', function () {
    $this->link->update(['visit_limit' => 1]);

    Visit::factory()->for($this->link)->create();

    $this->get(route('redirects.show', $this->link->alias))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Redirects/Limited')
        );
});
