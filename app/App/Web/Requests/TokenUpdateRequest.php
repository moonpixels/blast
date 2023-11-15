<?php

namespace App\Web\Requests;

use Domain\User\DTOs\PersonalAccessTokenData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;

class TokenUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        /** @var PersonalAccessToken $token */
        $token = $this->route('token');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('personal_access_tokens')
                    ->where('tokenable_id', $this->user()->id)
                    ->ignoreModel($token),
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
