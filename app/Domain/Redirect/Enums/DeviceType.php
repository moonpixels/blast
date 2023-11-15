<?php

namespace Domain\Redirect\Enums;

use Jenssegers\Agent\Agent;

enum DeviceType: string
{
    case Desktop = 'desktop';
    case Mobile = 'mobile';
    case Tablet = 'tablet';

    /**
     * Get the device type from the user agent.
     */
    public static function fromUserAgent(Agent $agent): ?self
    {
        return match (true) {
            $agent->isDesktop() => self::Desktop,
            $agent->isTablet() => self::Tablet,
            $agent->isMobile() => self::Mobile,
            default => null,
        };
    }
}
