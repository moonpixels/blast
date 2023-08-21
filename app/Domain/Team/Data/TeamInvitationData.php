<?php

namespace App\Domain\Team\Data;

use App\Domain\Team\Models\Team;
use App\Support\Data\Contracts\DataRules;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameterProperty;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class TeamInvitationData extends DataRules
{
    /**
     * Instantiate a new team invitation data instance.
     */
    public function __construct(
        #[FromRouteParameterProperty('team', 'id')]
        public string|Optional $teamId,
        public string|Optional $email,
    ) {
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public static function messages(): array
    {
        return [
            'email.unique' => __('You already have a pending invitation for this email address.'),
            'email.not_in' => __('There is already a team member with this email address.'),
        ];
    }

    /**
     * The validation rules that apply when updating the resource.
     */
    protected static function updateRules(): array
    {
        return [];
    }

    /**
     * The validation rules that apply when creating the resource.
     */
    protected static function createRules(): array
    {
        /**
         * @var Team $team
         */
        $team = request()->route('team');

        return [
            'team_id' => [
                'sometimes',
                'required',
                'ulid',
                Rule::exists('teams', 'id')->where('owner_id', auth()->user()->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('team_invitations', 'email')->where('team_id', $team->id),
                Rule::notIn($team->membersAndOwner()->pluck('email')->toArray()),
            ],
        ];
    }
}
