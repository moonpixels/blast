<?php

namespace App\Api\Requests;

use Domain\Team\DTOs\TeamData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('teams')
                    ->where('owner_id', $this->user()->id)
                    ->withoutTrashed(),
            ],
        ];
    }

    /**
     * Convert the request into a DTO.
     */
    public function toDTO(): TeamData
    {
        return TeamData::from([
            'name' => $this->validated('name'),
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
