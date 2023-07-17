<?php

namespace App\Actions\Visits;

use App\Concerns\HasUrlInput;
use App\Enums\Visits\DeviceTypes;
use App\Exceptions\InvalidUrlException;
use App\Models\Link;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateVisitForLink
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
        $visit = Visit::make([
            'link_id' => $link->id,
        ]);

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
            try {
                $url = $this->parseUrlInput($referer);

                $visit->referer_host = $url['host'];
                $visit->referer_path = $url['path'];
            } catch (InvalidUrlException) {
            }
        }

        DB::transaction(function () use ($link, $visit) {
            $visit->save();

            $link->incrementTotalVisits();
        });

        return $visit;
    }
}
