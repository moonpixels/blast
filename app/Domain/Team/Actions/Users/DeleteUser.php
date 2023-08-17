<?php

namespace App\Domain\Team\Actions\Users;

use App\Domain\Team\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteUser
{
    use AsAction;

    /**
     * Delete the given user.
     */
    public function handle(User $user): bool
    {
        return (bool) $user->delete();
    }
}
