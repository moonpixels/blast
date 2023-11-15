<?php

namespace Domain\User\Actions;

use Domain\Team\Actions\CreateTeamAction;
use Domain\Team\DTOs\TeamData;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateUserAction implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     */
    public function create(array $input): User
    {
        if (config('blast.disable_registration')) {
            abort(403, __('Registration is currently disabled'));
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            CreateTeamAction::run($user, TeamData::from([
                'name' => __('Personal Team'),
                'personal_team' => true,
            ]));

            return $user;
        });
    }
}
