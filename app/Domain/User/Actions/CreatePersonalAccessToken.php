<?php

namespace App\Domain\User\Actions;

use App\Domain\User\Data\PersonalAccessTokenData;
use App\Domain\User\Models\User;
use Laravel\Sanctum\NewAccessToken;
use Lorisleiva\Actions\Concerns\AsAction;

class CreatePersonalAccessToken
{
    use AsAction;

    /**
     * Create a new personal access token for the given user.
     */
    public function handle(User $user, PersonalAccessTokenData $data): NewAccessToken
    {
        return $user->createToken($data->name);
    }
}
