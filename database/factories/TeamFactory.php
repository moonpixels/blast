<?php

namespace Database\Factories;

use Domain\Team\Models\Team;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Team::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'name' => $this->faker->company,
            'personal_team' => false,
        ];
    }

    /**
     * Indicate that the team is a personal team.
     */
    public function personalTeam(): self
    {
        return $this->state(function () {
            return [
                'name' => 'Personal Team',
                'personal_team' => true,
            ];
        });
    }
}
