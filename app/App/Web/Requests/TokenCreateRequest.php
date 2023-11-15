<?php

namespace App\Web\Requests;

use Domain\User\DTOs\PersonalAccessTokenData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TokenCreateRequest extends FormRequest
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
                Rule::unique('personal_access_tokens')
                    ->where('tokenable_id', $this->user()->id),
            ],
        ];
    }

    /**
     * Convert the request into a DTO.
     */
    public function toDTO(): PersonalAccessTokenData
    {
        return PersonalAccessTokenData::from([
            'name' => $this->validated('name'),
        ]);
    }
}
