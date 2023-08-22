<?php

namespace App\Domain\User\Data;

use App\Domain\Team\Rules\BelongsToTeam;
use App\Support\Data\Contracts\DataRules;
use App\Support\Data\Transformers\HashableTransformer;
use Laravel\Fortify\Rules\Password;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class UserData extends DataRules
{
    /**
     * Instantiate a new user data instance.
     */
    public function __construct(
        public string|Optional $name,
        public string|Optional $email,
        #[WithTransformer(HashableTransformer::class)]
        public string|Optional $password,
        public string|Optional $currentTeamId,
    ) {
    }

    /**
     * The validation rules that apply when updating the resource.
     */
    protected static function updateRules(): array
    {
        return array_merge_recursive(self::baseRules(), [
            'name' => ['sometimes'],
            'email' => ['sometimes'],
            'password' => ['sometimes'],
            'current_team_id' => [
                'sometimes',
                'required',
                'ulid',
                'exists:teams,id',
                new BelongsToTeam(request()->user()),
            ],
        ]);
    }

    /**
     * The validation rules that apply when creating the resource.
     */
    protected static function createRules(): array
    {
        return self::baseRules();
    }

    /**
     * Base validation rules.
     */
    protected static function baseRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', new Password],
        ];
    }
}
