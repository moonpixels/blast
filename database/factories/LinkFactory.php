<?php

namespace Database\Factories;

use App\Domain\Link\Models\Domain;
use App\Domain\Link\Models\Link;
use App\Domain\Team\Models\Team;
use App\Support\Concerns\HasUrlInput;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class LinkFactory extends Factory
{
    use HasUrlInput;

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
            'visit_limit' => null,
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
     * Indicate that the link has the given destination URL.
     */
    public function withDestinationUrl(string $url): self
    {
        $url = $this->parseUrlInput($url);

        return $this->state([
            'domain_id' => Domain::firstOrCreate([
                'host' => $url['host'],
            ]),
            'destination_path' => $url['path'],
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

    /**
     * Indicate that the link has reached its visit limit.
     */
    public function withReachedVisitLimit(): self
    {
        return $this->state([
            'visit_limit' => 1,
            'total_visits' => 1,
        ]);
    }

    /**
     * Indicate that the link is blocked.
     */
    public function blocked(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'blocked' => true,
            ];
        });
    }
}
