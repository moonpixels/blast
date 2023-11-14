<?php

namespace Domain\User\Actions;

use Domain\User\DTOs\PersonalAccessTokenData;
use Domain\User\Models\User;
use Laravel\Sanctum\NewAccessToken;
use Lorisleiva\Actions\Concerns\AsAction;

class CreatePersonalAccessTokenAction
{
    use AsAction;

    /**
     * Create a new personal access token for the user.
     */
    public function handle(User $user, PersonalAccessTokenData $data): NewAccessToken
    {
        return $user->createToken($data->name);
    }
}
