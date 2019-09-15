<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class StripePaymentController extends Controller
{
    public function stripe(Request $request)
    {
        return view('stripe_payment', [
            'amount'   => $request->input('amount'),
            'currency' => $request->input('currency')
        ]);
    }

    public function post_stripe(Request $request)
    {
        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey('sk_test_EhVaJ9IZbKvne7Gb6EUz9diK00AvX8FhMF');
        // Token is created using Checkout or Elements!
        // Get the payment token ID submitted by the form:
        $token    = $request->input('stripeToken');
        $amount   = $request->input('amount') * 100;
        $currency = $request->input('currency');
        try {
            $charge = \Stripe\Charge::create([
                'amount'      => $amount,
                'currency'    => $currency,
                'description' => 'Example charge',
                'source'      => $token,
            ]);
        }
        
        catch (Exception $e) {
            Log::error($e->getMessage());
            return $e->getMessage();
        }

        return($charge->status);
    }
}
