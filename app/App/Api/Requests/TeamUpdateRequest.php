<?php

namespace App\Api\Requests;

use Domain\Team\DTOs\TeamData;
use Domain\Team\Models\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        /** @var Team $team */
        $team = $this->route('team');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('teams')
                    ->where('owner_id', $this->user()->id)
                    ->ignoreModel($team)
                    ->withoutTrashed(),
            ],
        ];
    }

    /**
     * Convert the request into a DTO.
     */
    public function toDTO(): TeamData
    {
        /** @var Team $team */
        $team = $this->route('team');

        return TeamData::from([
            'name' => $this->validated('name', $team->name),
            'personal_team' => $team->personal_team,
        ]);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.unique' => __('You already have a team called :team_name.', [
                'team_name' => $this->input('name'),
            ]),
        ];
    }
}
