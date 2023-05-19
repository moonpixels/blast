<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Delete the given user.
     */
    public function deleteUser(User $user): bool
    {
        return (bool) $user->delete();
    }
}
