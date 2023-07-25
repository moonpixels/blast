<?php

it('does not use debugging functions', function () {
    expect(['dump', 'die', 'ray', 'dd', 'var_dump', 'print_r'])
        ->not
        ->toBeUsed();
});

it('does not use env variables outside of config files', function () {
    expect(['env'])
        ->not
        ->toBeUsed();
});
