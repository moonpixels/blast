<?php

namespace App\Api\Requests;

use Domain\Team\DTOs\TeamInvitationData;
use Domain\Team\Models\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamInvitationCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        /** @var Team $team */
        $team = $this->route('team');

        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('team_invitations')->where('team_id', $team->id),
                Rule::notIn($team->membersAndOwner()->pluck('email')->toArray()),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.unique' => __('You already have a pending invitation for this email address.'),
            'email.not_in' => __('There is already a team member with this email address.'),
        ];
    }

    /**
     * Convert the request into a DTO.
     */
    public function toDTO(): TeamInvitationData
    {
        return TeamInvitationData::from([
            'email' => $this->validated('email'),
        ]);
    }
}
