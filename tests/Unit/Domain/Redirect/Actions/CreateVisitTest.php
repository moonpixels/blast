<?php

use App\Domain\Link\Models\Link;
use App\Domain\Redirect\Actions\CreateVisit;
use App\Domain\Redirect\Enums\DeviceTypes;

beforeEach(function () {
    $this->link = Link::factory()->create();
});

it('can create a visit', function () {
    $visit = CreateVisit::run($this->link);

    expect($visit->link_id)->toBe($this->link->id);
});

it('can create a visit with user agent information', function () {
    $visit = CreateVisit::run(
        $this->link,
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:109.0) Gecko/20100101 Firefox/115.0'
    );

    expect($visit->device_type)->toBe(DeviceTypes::Desktop)
        ->and($visit->browser)->toBe('Firefox')
        ->and($visit->browser_version)->toBe('115.0')
        ->and($visit->platform)->toBe('OS X')
        ->and($visit->platform_version)->toBe('10.15')
        ->and($visit->is_robot)->toBeFalse()
        ->and($visit->robot_name)->toBeNull();
});

it('can create a visit with refer information', function () {
    $visit = CreateVisit::run(link: $this->link, referer: 'https://example.com/foo/bar?query=string#fragment');

    expect($visit->referer_host)->toBe('example.com')
        ->and($visit->referer_path)->toBe('/foo/bar?query=string#fragment');
});

it('can create a visit with robot information', function () {
    $visit = CreateVisit::run(
        $this->link,
        'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; Googlebot/2.1; +http://www.google.com/bot.html) Chrome/W.X.Y.Z Safari/537.36'
    );

    expect($visit->is_robot)->toBeTrue()
        ->and($visit->robot_name)->toBe('Googlebot');
});

it('increments the links total visits when a visit is created', function () {
    expect($this->link->total_visits)->toBe(0);

    CreateVisit::run($this->link);

    $this->link->refresh();

    expect($this->link->total_visits)->toBe(1);
});

it('gracefully handles invalid user agent strings', function () {
    $visit = CreateVisit::run($this->link, 'invalid-user-agent');

    expect($visit->device_type)->toBe(DeviceTypes::Desktop)
        ->and($visit->browser)->toBeNull()
        ->and($visit->browser_version)->toBeNull()
        ->and($visit->platform)->toBeNull()
        ->and($visit->platform_version)->toBeNull()
        ->and($visit->is_robot)->toBeFalse()
        ->and($visit->robot_name)->toBeNull();
});
