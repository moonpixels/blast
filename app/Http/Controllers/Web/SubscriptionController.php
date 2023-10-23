<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use LemonSqueezy\Laravel\Checkout;
use LemonSqueezy\Laravel\Exceptions\InvalidCustomer;

class SubscriptionController extends Controller
{
    /**
     * Take the user to the checkout page.
     */
    public function create(): Checkout
    {
        return auth()->user()->subscribe(config('services.lemon_squeezy.sub_variant_id'));
    }

    /**
     * Take the user to the billing portal.
     */
    public function edit(): RedirectResponse
    {
        try {
            return auth()->user()->redirectToCustomerPortal();
        } catch (InvalidCustomer) {
            return redirect()->back();
        }
    }
}
