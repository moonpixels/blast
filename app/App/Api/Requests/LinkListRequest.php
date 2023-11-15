<?php

namespace App\Api\Requests;

use Domain\Team\Rules\BelongsToTeamRule;
use Illuminate\Foundation\Http\FormRequest;

class LinkListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'filter.team_id' => ['sometimes', 'ulid', new BelongsToTeamRule(request()->user())],
            'filter.search' => ['sometimes', 'string'],
        ];
    }
}
