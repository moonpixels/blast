<?php

namespace App\Actions\Users;

use App\Models\User;
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
