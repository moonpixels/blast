<?php

namespace App\Http\Requests\TeamInvitation;

use App\Models\Team;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
                Rule::unique('team_invitations')->where(fn (Builder $query) => $query->where('team_id', $team->id)),
                Rule::notIn($team->allUsers()->pluck('email')->toArray()),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'email.unique' => __('You already have a pending invitation for this email address.'),
            'email.not_in' => __('There is already a team member with this email address.'),
        ];
    }
}
