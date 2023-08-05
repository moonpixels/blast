<?php

use App\Actions\Redirects\GetLinkForRedirectRequest;
use App\Models\Link;
use Illuminate\Database\Eloquent\ModelNotFoundException;

beforeEach(function () {
    $this->link = Link::factory()->create();
});

it('can get a link for a redirect request', function () {
    $link = GetLinkForRedirectRequest::run($this->link->alias);

    expect($link->id)->toBe($this->link->id);
});

it('throws an exception if the link does not exist', function () {
    GetLinkForRedirectRequest::run('invalid-alias');
})->throws(ModelNotFoundException::class);
