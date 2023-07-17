<?php

namespace App\Enums\Visits;

use Jenssegers\Agent\Agent;

enum DeviceTypes: string
{
    case Desktop = 'desktop';
    case Mobile = 'mobile';
    case Tablet = 'tablet';

    /**
     * Get device type from user agent.
     */
    public static function fromUserAgent(Agent $agent): self
    {
        if ($agent->isTablet()) {
            return self::Tablet;
        }
        
        if ($agent->isMobile()) {
            return self::Mobile;
        }

        return self::Desktop;
    }
}
