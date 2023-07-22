<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\TeamMembership;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Fortify\RecoveryCode;
use PragmaRX\Google2FA\Google2FA;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
            'remember_token' => Str::random(10),
            'current_team_id' => null,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): self
    {
        return $this->afterCreating(function (User $user) {
            $user->switchTeam(Team::factory()->for($user, 'owner')->personalTeam()->create());
        });
    }

    /**
     * Indicate that the user has two-factor authentication enabled.
     */
    public function withTwoFactorAuthentication(): self
    {
        return $this->state(function (array $attributes) {
            $tfaEngine = app(Google2FA::class);
            $userSecret = $tfaEngine->generateSecretKey();

            return [
                'two_factor_secret' => encrypt($userSecret),
                'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(7, function () {
                    return RecoveryCode::generate();
                })->push('recovery-code')->all())),
                'two_factor_confirmed_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the user has a standard team.
     */
    public function withStandardTeam(): self
    {
        return $this->afterCreating(function (User $user) {
            Team::factory()->for($user, 'owner')->create([
                'name' => 'Standard Team',
            ]);
        });
    }

    /**
     * Indicate that the user has membership in a team.
     */
    public function withTeamMembership(): self
    {
        return $this->afterCreating(function (User $user) {
            TeamMembership::factory()
                ->for($user)
                ->for(Team::factory()->create([
                    'name' => 'Membership Team',
                ]))->create();
        });
    }
}
