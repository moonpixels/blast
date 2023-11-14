<?php

namespace Domain\Redirect\Actions;

use Domain\Link\Models\Link;
use Domain\Link\Support\Helpers\Url;
use Domain\Redirect\DTOs\VisitData;
use Domain\Redirect\Enums\DeviceType;
use Domain\Redirect\Models\Visit;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateVisitAction
{
    use AsAction;

    public function __construct(
        protected readonly Agent $agent
    ) {
    }

    /**
     * Create a visit.
     */
    public function handle(Link $link, VisitData $data): Visit
    {
        $userAgent = $this->parseUserAgent($data->user_agent);
        $referer = $this->parseReferer($data->referer);

        return DB::transaction(function () use ($link, $userAgent, $referer) {
            $visit = $link->visits()->create([
                'device_type' => $userAgent['device_type'],
                'browser' => $userAgent['browser'],
                'browser_version' => $userAgent['browser_version'],
                'platform' => $userAgent['platform'],
                'platform_version' => $userAgent['platform_version'],
                'is_robot' => $userAgent['is_robot'],
                'robot_name' => $userAgent['robot_name'],
                'referer_host' => $referer['referer_host'],
                'referer_path' => $referer['referer_path'],
            ]);

            $link->incrementTotalVisits();

            return $visit;
        });
    }

    /**
     * Parse the user agent.
     */
    protected function parseUserAgent(?string $userAgent): array
    {
        $data = [
            'device_type' => null,
            'browser' => null,
            'browser_version' => null,
            'platform' => null,
            'platform_version' => null,
            'is_robot' => null,
            'robot_name' => null,
        ];

        if (! $userAgent) {
            return $data;
        }

        $this->agent->setUserAgent($userAgent);

        $data['device_type'] = DeviceType::fromUserAgent($this->agent);
        $data['browser'] = $this->agent->browser() ?: null;
        $data['browser_version'] = $this->agent->version($data['browser']) ?: null;
        $data['platform'] = $this->agent->platform() ?: null;
        $data['platform_version'] = $this->agent->version($data['platform']) ?: null;
        $data['is_robot'] = $this->agent->isRobot();
        $data['robot_name'] = $this->agent->robot() ?: null;

        return $data;
    }

    /**
     * Parse the referer.
     */
    protected function parseReferer(?string $referer): array
    {
        $data = [
            'referer_host' => null,
            'referer_path' => null,
        ];

        if (! $referer) {
            return $data;
        }

        $url = Url::parseUrl($referer);

        $data['referer_host'] = $url['host'];
        $data['referer_path'] = $url['path'];

        return $data;
    }
}
