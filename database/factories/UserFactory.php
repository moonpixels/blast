<?php

namespace Database\Factories;

use Domain\Team\Models\Team;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Fortify\RecoveryCode;
use LemonSqueezy\Laravel\Subscription;
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
            'email_verified_at' => now(),
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
        return $this->state(function () {
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
     * Indicate that the user has a team which they own.
     */
    public function withOwnedTeam(): self
    {
        return $this->afterCreating(function (User $user) {
            Team::factory()->for($user, 'owner')->create([
                'name' => 'Owned Team',
            ]);
        });
    }

    /**
     * Indicate that the user is a member of a team.
     */
    public function withMemberTeam(): self
    {
        return $this->afterCreating(function (User $user) {
            $user->teams()->attach(
                Team::factory()->create([
                    'name' => 'Member Team',
                ])
            );
        });
    }

    /**
     * Indicate that the user has not verified their email address.
     */
    public function unverified(): self
    {
        return $this->state(function () {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user has a subscription.
     */
    public function subscribed(): self
    {
        return $this->afterCreating(function (User $user) {
            Subscription::factory()->create([
                'billable_id' => $user->id,
                'billable_type' => User::class,
            ]);
        });
    }

    /**
     * Indicate that the user is blocked.
     */
    public function blocked(): self
    {
        return $this->state(function () {
            return [
                'blocked' => true,
            ];
        });
    }
}
