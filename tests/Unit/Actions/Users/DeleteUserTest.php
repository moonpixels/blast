<?php

use App\Actions\Users\DeleteUser;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can delete a user', function () {
    $this->assertTrue(DeleteUser::execute($this->user));

    $this->assertModelMissing($this->user);
});
