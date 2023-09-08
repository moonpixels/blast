<?php

use App\Domain\User\Actions\DeleteUser;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->user = createUser();
});

it('deletes the user', function () {
    expect(DeleteUser::run($this->user))->toBeTrue()
        ->and($this->user)->toBeDeleted()
        ->and(DB::table('team_user')->where('user_id', $this->user->id)->exists())->toBeFalse()
        ->and(DB::table('teams')->where('owner_id', $this->user->id)->exists())->toBeFalse();
});
