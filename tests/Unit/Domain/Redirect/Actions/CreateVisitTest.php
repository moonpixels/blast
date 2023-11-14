<?php

use Domain\Redirect\Actions\CreateVisitAction;
use Domain\Redirect\DTOs\VisitData;
use Domain\Redirect\Enums\DeviceType;

beforeEach(function () {
    $this->link = createLink();
});

it('creates a visit', function () {
    $visit = CreateVisitAction::run($this->link, VisitData::from([
        'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:109.0) Gecko/20100101 Firefox/115.0',
        'referer' => 'https://example.com/foo/bar?query=string#fragment',
    ]));

    expect($visit->link_id)->toBe($this->link->id)
        ->and($visit->device_type)->toBe(DeviceType::Desktop)
        ->and($visit->browser)->toBe('Firefox')
        ->and($visit->browser_version)->toBe('115.0')
        ->and($visit->platform)->toBe('OS X')
        ->and($visit->platform_version)->toBe('10.15')
        ->and($visit->is_robot)->toBeFalse()
        ->and($visit->robot_name)->toBeNull()
        ->and($visit->referer_host)->toBe('example.com')
        ->and($visit->referer_path)->toBe('/foo/bar?query=string#fragment');
});

it('increments the links total visits', function () {
    expect($this->link->total_visits)->toBe(0);

    CreateVisitAction::run($this->link, VisitData::from([
        'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:109.0) Gecko/20100101 Firefox/115.0',
        'referer' => 'https://example.com/foo/bar?query=string#fragment',
    ]));

    $this->link->refresh();

    expect($this->link->total_visits)->toBe(1);
});
