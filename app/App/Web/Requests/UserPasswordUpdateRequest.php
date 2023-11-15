<?php

namespace App\Web\Requests;

use Domain\User\DTOs\UserData;
use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Rules\Password;

class UserPasswordUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', new Password],
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
            'password' => $this->validated('password'),
        ]);
    }
}
