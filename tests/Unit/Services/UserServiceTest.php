<?php

use App\Models\User;
use App\Services\UserService;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->userService = app(UserService::class);
});

it('can delete a user', function () {
    $this->userService->deleteUser($this->user);

    $this->assertModelMissing($this->user);
});
