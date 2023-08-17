<?php

namespace App\Domain\Team\Data;

use App\Domain\Team\Rules\BelongsToTeam;
use App\Support\DataTransformers\HashableTransformer;
use Laravel\Fortify\Rules\Password;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class UserData extends Data
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
     * Get the validation rules that apply to the request.
     */
    public static function rules(): array
    {
        $baseRules = collect([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', new Password],
        ]);

        if (request()->isMethod('PUT')) {
            return $baseRules->mergeRecursive([
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
            ])->toArray();
        }

        return $baseRules->toArray();
    }
}
