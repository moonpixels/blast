<?php

namespace App\Actions\Users;

use App\Concerns\Actionable;
use App\Models\User;

class DeleteUser
{
    use Actionable;

    /**
     * Delete the given user.
     */
    public function handle(User $user): bool
    {
        return (bool) $user->delete();
    }
}
