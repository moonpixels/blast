<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\TeamInvitation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamInvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = TeamInvitation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
