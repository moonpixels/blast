<?php

namespace Database\Factories\Domain\Team\Models;

use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamMembership;
use App\Domain\Team\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamMembershipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = TeamMembership::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
        ];
    }
}
