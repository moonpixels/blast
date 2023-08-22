<?php

namespace Database\Factories;

use App\Domain\Link\Models\Domain;
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
}
