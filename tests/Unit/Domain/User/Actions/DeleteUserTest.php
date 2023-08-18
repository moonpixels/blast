<?php

use App\Domain\User\Actions\DeleteUser;
use App\Domain\User\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can delete a user', function () {
    $this->assertTrue(DeleteUser::run($this->user));

    $this->assertModelMissing($this->user);
});
