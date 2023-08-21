<?php

test('debugging')
    ->expect(['dump', 'die', 'ray', 'dd', 'var_dump', 'print_r'])
    ->not
    ->toBeUsed();

test('environment variables')
    ->expect(['env'])
    ->not
    ->toBeUsed();
