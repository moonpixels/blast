<?php

namespace App\Http\Requests\CurrentTeam;

use App\Rules\BelongsToTeam;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * F
     */
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'team_id' => [
                'bail',
                'required',
                'ulid',
                'exists:teams,id',
                new BelongsToTeam($this->user()),
            ],
        ];
    }
}
