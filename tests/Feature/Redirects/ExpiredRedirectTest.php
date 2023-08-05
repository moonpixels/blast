<?php

use Inertia\Testing\AssertableInertia as Assert;

it('shows the expired redirect page', function () {
    $this->get(route('expired-redirect'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Redirects/Expired/Show')
        );
});
