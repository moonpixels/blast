<?php

namespace Database\Factories\Domain\Link\Models;

use App\Domain\Link\Models\domain;
use Illuminate\Database\Eloquent\Factories\Factory;

class DomainFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Domain::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'host' => $this->faker->unique()->domainName,
        ];
    }

    /**
     * Indicate that the domain should be example.com.
     */
    public function toExampleDotCom(): self
    {
        return $this->state([
            'host' => 'example.com',
        ]);
    }
}
