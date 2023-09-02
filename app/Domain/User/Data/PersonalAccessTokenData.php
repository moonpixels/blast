<?php

namespace App\Domain\User\Data;

use App\Support\Data\Contracts\DataRules;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class PersonalAccessTokenData extends DataRules
{
    /**
     * Instantiate a new personal access token data instance.
     */
    public function __construct(
        public string|Optional $name
    ) {
    }

    /**
     * The validation rules that apply when creating the resource.
     */
    public static function creationRules(): array
    {
        return array_merge_recursive(self::baseRules(), [
            'name' => [
                Rule::unique('personal_access_tokens')
                    ->where('tokenable_id', request()->user()->id),
            ],
        ]);
    }

    /**
     * The validation rules that apply when updating the resource.
     */
    public static function updateRules(): array
    {
        /** @var PersonalAccessToken $token */
        $token = request()->route('token');

        return array_merge_recursive(self::baseRules(), [
            'name' => [
                Rule::unique('personal_access_tokens')
                    ->where('tokenable_id', request()->user()->id)
                    ->ignore($token->id),
            ],
        ]);
    }

    /**
     * Base validation rules.
     */
    protected static function baseRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
