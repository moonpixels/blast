<?php

use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = login();
});

test('users can create personal access tokens', function () {
    $this->post(route('personal-access-tokens.store'), [
        'name' => 'Test Token',
    ])->assertRedirect()->assertSessionHas('plainTextToken');

    expect($this->user->tokens->count())->toBe(1)
        ->and($this->user->tokens->first()->name)->toBe('Test Token');
});

test('users can delete personal access tokens', function () {
    $this->user->createToken('Test Token');

    $token = $this->user->tokens->first();

    $this->delete(route('personal-access-tokens.destroy', $token))
        ->assertRedirect()->assertSessionHas('success', [
            'title' => 'Token deleted',
            'message' => 'The API token has been deleted.',
        ]);

    $this->user->refresh();

    expect($this->user->tokens->count())->toBe(0);
});

test('users can view personal access tokens', function () {
    $this->user->createToken('Test Token');

    $this->get(route('personal-access-tokens.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Settings/Api/Index')
            ->has('tokens', 1)
            ->has('tokens.0', fn (Assert $page) => $page
                ->where('id', $this->user->tokens->first()->id)
                ->where('name', 'Test Token')
                ->etc()
            )
            ->where('plainTextToken', null)
        );
});
