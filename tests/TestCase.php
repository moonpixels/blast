<?php

namespace Tests;

use AllowDynamicProperties;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

#[AllowDynamicProperties] abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }
}
