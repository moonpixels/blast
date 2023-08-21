<?php

use App\Support\Concerns\HasUrlInput;

beforeEach(function () {
    $this->trait = $this->mock(HasUrlInput::class);
});

it('parses a URL', function () {
    expect($this->trait->parseUrlInput('https://example.com/this-is-a-path?query=string#fragment'))
        ->toBe([
            'host' => 'example.com',
            'path' => '/this-is-a-path?query=string#fragment',
        ]);
});

it('parses a URL without a path', function () {
    expect($this->trait->parseUrlInput('https://example.com'))
        ->toBe([
            'host' => 'example.com',
            'path' => null,
        ]);
});

it('converts the host to lowercase while retaining the path casing', function () {
    expect($this->trait->parseUrlInput('https://EXAMPLE.com/this-is-a-PATH?query=STRING#fragMENT'))
        ->toBe([
            'host' => 'example.com',
            'path' => '/this-is-a-PATH?query=STRING#fragMENT',
        ]);
});
