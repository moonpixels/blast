<?php

use App\Enums\Visits\DeviceTypes;
use Jenssegers\Agent\Agent;

it('can return the desktop device type', function () {
    $agent = new Agent();
    $agent->setUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.107 Safari/537.36');

    expect(DeviceTypes::fromUserAgent($agent))->toBe(DeviceTypes::Desktop);
});

it('can return the mobile device type', function () {
    $agent = new Agent();
    $agent->setUserAgent('Mozilla/5.0 (Linux; Android 11; Pixel 3a) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.107 Mobile Safari/537.36');

    expect(DeviceTypes::fromUserAgent($agent))->toBe(DeviceTypes::Mobile);
});

it('can return the tablet device type', function () {
    $agent = new Agent();
    $agent->setUserAgent('Mozilla/5.0 (Linux; U; Android 4.0.3; en-us; ASUS Transformer Pad TF300T Build/IML74K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30');

    expect(DeviceTypes::fromUserAgent($agent))->toBe(DeviceTypes::Tablet);
});
