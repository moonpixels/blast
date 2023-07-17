<?php

namespace Database\Factories;

use App\Models\Domain;
use App\Models\Link;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Link::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'domain_id' => Domain::factory(),
            'destination_path' => '/'.$this->faker->slug,
            'alias' => $this->faker->unique()->regexify('[a-zA-Z0-9]{7}'),
            'total_visits' => 0,
        ];
    }
}
