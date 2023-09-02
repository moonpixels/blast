<?php

namespace App\Domain\User\Actions;

use Laravel\Sanctum\PersonalAccessToken;
use Lorisleiva\Actions\Concerns\AsAction;

class DeletePersonalAccessToken
{
    use AsAction;

    /**
     * Delete the given personal access token.
     */
    public function handle(PersonalAccessToken $token): bool
    {
        return $token->delete();
    }
}
