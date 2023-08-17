<?php

namespace App\Domain\Team\Data;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameterProperty;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class TeamData extends Data
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
     * Get the validation rules that apply to the request.
     */
    public static function rules(): array
    {
        $baseRules = collect([
            'name' => ['required', 'string', 'max:255'],
        ]);

        if (request()->isMethod(Request::METHOD_PUT)) {
            return $baseRules->mergeRecursive([
                'name' => [
                    Rule::unique('teams', 'name')
                        ->where('owner_id', auth()->user()->id)
                        ->ignore(request()->route('team'))
                        ->withoutTrashed(),
                ],
            ])->toArray();
        }

        return $baseRules->mergeRecursive([
            'name' => [
                Rule::unique('teams', 'name')
                    ->where('owner_id', auth()->user()->id)
                    ->withoutTrashed(),
            ],
        ])->toArray();
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public static function messages(): array
    {
        return [
            'name.unique' => __('You already have a team called :team_name.',
                ['team_name' => request()->input('name')]),
        ];
    }
}
