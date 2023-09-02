<?php

namespace App\Domain\User\Policies;

use App\Domain\User\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class PersonalAccessTokenPolicy
{
    /**
     * Determine whether the user can view any personal access tokens.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the personal access token.
     */
    public function view(User $user, PersonalAccessToken $token): bool
    {
        return $user->tokens()->where('id', $token->id)->exists();
    }

    /**
     * Determine whether the user can create personal access tokens.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the personal access token.
     */
    public function delete(User $user, PersonalAccessToken $token): bool
    {
        return $user->tokens()->where('id', $token->id)->exists();
    }
}
