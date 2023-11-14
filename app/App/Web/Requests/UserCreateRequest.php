<?php

namespace App\Web\Requests;

use Domain\User\DTOs\UserData;
use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Rules\Password;

class UserCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', new Password],
        ];
    }

    /**
     * Convert the request into a DTO.
     */
    public function toDTO(): UserData
    {
        return UserData::from([
            'name' => $this->validated('name'),
            'email' => $this->validated('email'),
            'password' => $this->validated('password'),
        ]);
    }
}
