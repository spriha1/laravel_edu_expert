<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use App\StripeDetail;
use Illuminate\Support\Facades\Auth;

class StripePaymentController extends Controller
{
    protected $stripe_detail;

    public function __construct()
    {
        $this->stripe_detail    = new StripeDetail;
    }

    /**
    * 
    * @method stripe() 
    * 
    * @param Request object
    * @return string [describing the success or failure of the connection of the two stripe accounts] 
    * Desc : This method fetches the information required on dashboard and returns the admin dashboard
    */

    public function stripe(Request $request)
    {
        return view('stripe_payment', [
            'amount'   => $request->input('amount'),
            'pay_to'   => $request->input('pay_to'),
            'currency' => $request->input('currency')
        ]);
    }

    public function post_stripe(Request $request)
    {
        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey('sk_test_e5fmzx4vnU7xDvy5fwtX1FeC00eyLRkRmP');
        // Token is created using Checkout or Elements!
        // Get the payment token ID submitted by the form:
        $token    = $request->input('stripeToken');
        $amount   = $request->input('amount') * 100;
        $currency = $request->input('currency');
        $pay_to   = $request->input('pay_to');
        $result   = $this->stripe_detail
                    ->where('user_id', $pay_to)
                    ->select('stripe_account_id')
                    ->first();
        try {
            $result = \Stripe\Transfer::create([
                "amount"      => $amount,
                "currency"    => $currency,
                "destination" => $result['stripe_account_id']
            ]);
        }
        
        catch (Exception $e) {
            Log::error($e->getMessage());
            return $e->getMessage();
        }

        return("succeeded");
    }
}
