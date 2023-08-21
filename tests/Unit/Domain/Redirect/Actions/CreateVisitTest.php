<?php

use App\Domain\Redirect\Actions\CreateVisit;
use App\Domain\Redirect\Enums\DeviceTypes;

beforeEach(function () {
    $this->link = createLink();
});

it('creates a visit', function () {
    $visit = CreateVisit::run(
        $this->link,
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:109.0) Gecko/20100101 Firefox/115.0',
        'https://example.com/foo/bar?query=string#fragment'
    );

    expect($visit->link_id)->toBe($this->link->id)
        ->and($visit->device_type)->toBe(DeviceTypes::Desktop)
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

    CreateVisit::run($this->link);

    $this->link->refresh();

    expect($this->link->total_visits)->toBe(1);
});
