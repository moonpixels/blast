<?php

namespace App\Web\Requests;

use Domain\User\DTOs\UserData;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', "unique:users,email,{$this->user()->id}"],
        ];
    }

    /**
     * Convert the request into a DTO.
     */
    public function toDTO(): UserData
    {
        $user = $this->user();

        return UserData::from([
            'name' => $this->validated('name', $user->name),
            'email' => $this->validated('email', $user->email),
        ]);
    }
}
