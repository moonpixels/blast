<?php

use Inertia\Testing\AssertableInertia as Assert;

it('shows the reached visit limit page', function () {
    $this->get(route('reached-visit-limit-redirect'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Redirects/ReachedVisits/Show')
        );
});
