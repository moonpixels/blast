<?php

use App\Models\Link;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->link = Link::factory()->withPassword()->create();
});

it('shows the authenticated redirect page', function () {
    $this->get(route('authenticated-redirect', $this->link->alias))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Redirects/Authenticated/Create')
            ->where('alias', $this->link->alias)
        );
});

it('does not redirect the user when the password is incorrect', function () {
    $this->post(route('authenticated-redirect', $this->link->alias), [
        'password' => 'wrong-password',
    ])->assertInvalid(['password']);

    $this->assertFalse(session()->has("authenticated:{$this->link->alias}"));
});

it('redirects the user when the password is correct', function () {
    $this->post(route('authenticated-redirect', $this->link->alias), [
        'password' => 'password',
    ])->assertRedirect(route('redirect', $this->link->alias));

    $this->assertTrue(session()->has("authenticated:{$this->link->alias}"));
});

it('does not show the authenticated redirect page when the link does not have a password', function () {
    $link = Link::factory()->create();

    $this->get(route('authenticated-redirect', $link->alias))
        ->assertRedirect(route('redirect', $link->alias));
});

it('redirects to the redirects page if a password is submitted for a link that does not have a password', function () {
    $link = Link::factory()->create();

    $this->post(route('authenticated-redirect', $link->alias), [
        'password' => 'password',
    ])->assertRedirect(route('redirect', $link->alias));
});

it('does not show the authenticated redirect page when the link does not exist', function () {
    $this->get(route('authenticated-redirect', 'not-a-link'))
        ->assertNotFound();
});
