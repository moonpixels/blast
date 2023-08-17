<?php

namespace App\Domain\Redirect\Actions;

use App\Domain\Link\Models\Link;
use App\Domain\Redirect\Enums\DeviceTypes;
use App\Domain\Redirect\Models\Visit;
use App\Support\Concerns\HasUrlInput;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateVisit
{
    use AsAction, HasUrlInput;

    /**
     * Instantiate the action.
     */
    public function __construct(protected readonly Agent $agent)
    {
    }

    /**
     * Create a new visit for the given link.
     */
    public function handle(Link $link, string $userAgent = null, string $referer = null): Visit
    {
        $visit = new Visit;

        $visit->link()->associate($link);

        if ($userAgent) {
            $this->agent->setUserAgent($userAgent);

            $visit->device_type = DeviceTypes::fromUserAgent($this->agent);

            $visit->browser = $this->agent->browser() ?: null;
            $visit->browser_version = $this->agent->version($visit->browser) ?: null;

            $visit->platform = $this->agent->platform() ?: null;
            $visit->platform_version = $this->agent->version($visit->platform) ?: null;

            $visit->is_robot = $this->agent->isRobot();
            $visit->robot_name = $this->agent->robot() ?: null;
        }

        if ($referer) {
            $url = $this->parseUrlInput($referer);

            $visit->referer_host = $url['host'];
            $visit->referer_path = $url['path'];
        }

        DB::transaction(function () use ($link, $visit) {
            $visit->save();

            $link->incrementTotalVisits();
        });

        return $visit;
    }
}
