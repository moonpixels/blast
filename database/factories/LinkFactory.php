<?php

namespace Database\Factories;

use App\Models\Domain;
use App\Models\Link;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

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
            'password' => null,
            'total_visits' => 0,
            'expires_at' => null,
        ];
    }

    /**
     * Indicate that the link has a password.
     */
    public function withPassword(): self
    {
        return $this->state([
            'password' => Hash::make('password'),
        ]);
    }

    /**
     * Indicate that the link should go to example.com.
     */
    public function toExampleDotCom(): self
    {
        $domain = Domain::where('host', 'example.com')->first();

        return $this->state([
            'domain_id' => $domain ? $domain->id : Domain::factory()->toExampleDotCom(),
            'destination_path' => null,
        ]);
    }

    /**
     * Indicate that the link has expired.
     */
    public function expired(): self
    {
        return $this->state([
            'expires_at' => now()->subDay(),
        ]);
    }
}
