<?php

namespace Domain\User\Actions;

use Domain\User\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformationAction implements UpdatesUserProfileInformation
{
    /**
     * Update the given user's profile information.
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ])->validate();

        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
        ])->save();

        session()->flash('success', [
            'title' => __('Profile updated'),
            'message' => __('Your profile has been updated successfully.'),
        ]);
    }
}
