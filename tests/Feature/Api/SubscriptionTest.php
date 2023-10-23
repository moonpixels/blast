<?php

test('unsubscribed users are rate limited', function () {
    login();

    for ($i = 0; $i < 10; $i++) {
        $this->get(route('api.links.index'))->assertOk();
    }

    $this->get(route('api.links.index'))->assertStatus(429);
});

test('subscribed users have a higher rate limit', function () {
    login(states: ['subscribed']);

    for ($i = 0; $i < 180; $i++) {
        $this->get(route('api.links.index'))->assertOk();
    }

    $this->get(route('api.links.index'))->assertStatus(429);
});
