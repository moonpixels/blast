<?php

namespace App\Domain\Team\Data;

use App\Support\Data\Contracts\DataRules;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameterProperty;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class TeamData extends DataRules
{
    /**
     * Instantiate a new team data instance.
     */
    public function __construct(
        public string|Optional $name,
        #[FromRouteParameterProperty('user', 'id')]
        public string|Optional $ownerId,
    ) {
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public static function messages(): array
    {
        return [
            'name.unique' => __('You already have a team called :team_name.', [
                'team_name' => request()->input('name'),
            ]),
        ];
    }

    /**
     * The validation rules that apply when creating the resource.
     */
    public static function creationRules(): array
    {
        return array_merge_recursive(self::baseRules(), [
            'name' => [
                Rule::unique('teams', 'name')
                    ->where('owner_id', auth()->user()->id)
                    ->withoutTrashed(),
            ],
        ]);
    }

    /**
     * The validation rules that apply when updating the resource.
     */
    public static function updateRules(): array
    {
        return array_merge_recursive(self::baseRules(), [
            'name' => [
                Rule::unique('teams', 'name')
                    ->where('owner_id', auth()->user()->id)
                    ->ignore(request()->route('team'))
                    ->withoutTrashed(),
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
