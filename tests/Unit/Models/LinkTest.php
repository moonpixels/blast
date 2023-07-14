<?php

use App\Models\Domain;
use App\Models\Link;

beforeEach(function () {
    $this->domain = Domain::factory()->create([
        'host' => 'blst.to',
    ]);

    $this->link = Link::factory()->for($this->domain)->create([
        'destination_path' => '/test',
    ]);
});

it('generates a short link based on the app url config', function () {
    config(['app.url' => 'https://blst.to']);

    expect($this->link->short_url)->toBe('https://blst.to/'.$this->link->alias);
});

it('generates the correct destination URL', function () {
    expect($this->link->destination_url)->toBe('https://blst.to/test');
});
