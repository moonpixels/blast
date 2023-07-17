<?php

use App\Actions\Links\GetLinkForRedirectRequest;
use App\Models\Link;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    $this->link = Link::factory()->create();
});

it('can get a link for a redirect request', function () {
    $link = GetLinkForRedirectRequest::run($this->link->alias);

    expect($link->id)->toBe($this->link->id);
});

it('caches the link for an hour', function () {
    Cache::shouldReceive('remember')
        ->once()
        ->with("links:{$this->link->alias}", 3600, \Mockery::type('Closure'))
        ->andReturn($this->link);

    GetLinkForRedirectRequest::run($this->link->alias);
});

it('throws an exception if the link does not exist', function () {
    GetLinkForRedirectRequest::run('invalid-alias');
})->throws(ModelNotFoundException::class);
