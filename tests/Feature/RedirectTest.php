<?php

use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->link = createLink();
});

test('users are redirected', function () {
    $this->get(route('redirects.show', $this->link))
        ->assertRedirect($this->link->destination_url)
        ->assertStatus(301)
        ->assertHeader('cache-control', 'no-cache, no-store, private');

    $this->link->refresh();

    expect($this->link->visits)->toHaveCount(1)
        ->and($this->link->total_visits)->toBe(1);
});

test('users are redirected when using protected links', function () {
    $this->link->update(['password' => Hash::make('password')]);

    $this->get(route('redirects.show', $this->link))
        ->assertInertia(fn (Assert $page) => $page
            ->component('redirects/index')
            ->where('alias', $this->link->alias)
            ->where('status', 'protected')
        );

    $this->post(route('redirects.authenticate', $this->link), [
        'password' => 'password',
    ])->assertRedirect($this->link->destination_url);

    $this->link->refresh();

    expect($this->link->visits)->toHaveCount(1)
        ->and($this->link->total_visits)->toBe(1);
});

test('redirect fails when password is incorrect', function () {
    $this->link->update(['password' => Hash::make('password')]);

    $this->post(route('redirects.authenticate', $this->link), [
        'password' => 'wrong-password',
    ])->assertInvalid(['password' => 'The provided password is incorrect.']);

    $this->link->refresh();

    expect($this->link->visits)->toHaveCount(0)
        ->and($this->link->total_visits)->toBe(0);
});

test('users are not redirected when the link has expired', function () {
    $this->link->update(['expires_at' => now()->subDay()]);

    $this->get(route('redirects.show', $this->link))
        ->assertInertia(fn (Assert $page) => $page
            ->component('redirects/index')
            ->where('alias', $this->link->alias)
            ->where('status', 'expired')
        );

    $this->link->refresh();

    expect($this->link->visits)->toHaveCount(0)
        ->and($this->link->total_visits)->toBe(0);
});

test('users are not redirected when the link has reached its visit limit', function () {
    $this->link->update(['visit_limit' => 1]);

    createVisit(attributes: ['link_id' => $this->link->id]);

    $this->get(route('redirects.show', $this->link))
        ->assertInertia(fn (Assert $page) => $page
            ->component('redirects/index')
            ->where('alias', $this->link->alias)
            ->where('status', 'limited')
        );

    $this->link->refresh();

    expect($this->link->visits)->toHaveCount(1)
        ->and($this->link->total_visits)->toBe(1);
});
