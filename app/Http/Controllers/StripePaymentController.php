<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripePaymentController extends Controller
{
    public function stripe()
    {
    	return view('stripe_payment');
    }

    public function post_stripe()
    {
		// Set your secret key: remember to change this to your live secret key in production
		// See your keys here: https://dashboard.stripe.com/account/apikeys
		\Stripe\Stripe::setApiKey('sk_test_EhVaJ9IZbKvne7Gb6EUz9diK00AvX8FhMF');

		// Token is created using Checkout or Elements!
		// Get the payment token ID submitted by the form:
		$token = $_POST['stripeToken'];
		$charge = \Stripe\Charge::create([
			'amount' => 999,
			'currency' => 'gbp',
			'description' => 'Example charge',
			'source' => $token,
		]);
    }
}
