<?php

namespace Database\Factories\Domain\Redirect\Models;

use App\Domain\Link\Models\Link;
use App\Domain\Redirect\Enums\DeviceTypes;
use App\Domain\Redirect\Models\Visit;
use App\Support\Concerns\HasUrlInput;
use Illuminate\Database\Eloquent\Factories\Factory;
use Jenssegers\Agent\Agent;

class VisitFactory extends Factory
{
    use HasUrlInput;

    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Visit::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'link_id' => Link::factory(),
            'device_type' => null,
            'browser' => null,
            'browser_version' => null,
            'platform' => null,
            'platform_version' => null,
            'is_robot' => false,
            'robot_name' => null,
            'referer_host' => null,
            'referer_path' => null,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): self
    {
        return $this->afterCreating(function (Visit $visit) {
            $visit->link->incrementTotalVisits();
        });
    }

    /**
     * Indicate that the visit has user agent information.
     */
    public function withUserAgent(string $userAgent = null): self
    {
        $agent = new Agent();
        $agent->setUserAgent($userAgent ?: $this->faker->userAgent());

        return $this->state(function () use ($agent) {
            return [
                'device_type' => DeviceTypes::fromUserAgent($agent),
                'browser' => $agent->browser() ?: null,
                'browser_version' => $agent->version($agent->browser()) ?: null,
                'platform' => $agent->platform() ?: null,
                'platform_version' => $agent->version($agent->platform()) ?: null,
            ];
        });
    }

    /**
     * Indicate that the visit is by a robot.
     */
    public function isRobot(): self
    {
        return $this->state(function () {
            return [
                'is_robot' => true,
                'robot_name' => 'Googlebot',
            ];
        });
    }

    /**
     * Indicate that the visit has a referer.
     */
    public function withReferer(string $referer = 'https://blst.to'): self
    {
        $url = $this->parseUrlInput($referer);

        return $this->state(function () use ($url) {
            return [
                'referer_host' => $url['host'],
                'referer_path' => $url['path'],
            ];
        });
    }
}
