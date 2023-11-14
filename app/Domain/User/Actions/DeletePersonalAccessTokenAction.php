<?php

namespace Domain\User\Actions;

use Laravel\Sanctum\PersonalAccessToken;
use Lorisleiva\Actions\Concerns\AsAction;

class DeletePersonalAccessTokenAction
{
    use AsAction;

    /**
     * Delete a personal access token.
     */
    public function handle(PersonalAccessToken $token): bool
    {
        return $token->delete();
    }
}
