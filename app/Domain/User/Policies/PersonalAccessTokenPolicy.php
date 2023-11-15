<?php

namespace Domain\User\Policies;

use Domain\User\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class PersonalAccessTokenPolicy
{
    /**
     * Determine whether the user can view any tokens.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the token.
     */
    public function view(User $user, PersonalAccessToken $token): bool
    {
        return $user->tokens()->where('id', $token->id)->exists();
    }

    /**
     * Determine whether the user can create tokens.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the token.
     */
    public function delete(User $user, PersonalAccessToken $token): bool
    {
        return $user->tokens()->where('id', $token->id)->exists();
    }
}
