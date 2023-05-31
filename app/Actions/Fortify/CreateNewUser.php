<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Services\TeamService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Instantiate the action.
     */
    public function __construct(protected readonly TeamService $teamService)
    {
    }

    /**
     * Validate and create a newly registered user.
     */
    public function create(array $input): User
    {
        if (config('blast.disable_registration')) {
            abort(403, __('auth.register_disabled'));
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

            $this->teamService->createTeamForUser($user, [
                'name' => __('teams.personal_team_name'),
                'personal_team' => true,
            ]);

            return $user;
        });
    }
}
