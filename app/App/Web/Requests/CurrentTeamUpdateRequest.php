<?php

namespace App\Web\Requests;

use Domain\Team\Rules\BelongsToTeamRule;
use Domain\User\DTOs\UserData;
use Illuminate\Foundation\Http\FormRequest;

class CurrentTeamUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'current_team_id' => [
                'required',
                'ulid',
                'exists:teams,id',
                new BelongsToTeamRule(request()->user()),
            ],
        ];
    }

    /**
     * Convert the request into a DTO.
     */
    public function toDTO(): UserData
    {
        $user = $this->user();

        return UserData::from([
            'name' => $user->name,
            'email' => $user->email,
            'current_team_id' => $this->validated('current_team_id'),
        ]);
    }
}
