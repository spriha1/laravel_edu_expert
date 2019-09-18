<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use App\StripeDetail;
use Stripe\{Stripe, Balance, Transfer};
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
    * @return string [html view of stripe payment where the user needs to enter card details] 
    * Desc : This method returns the stripe payment view
    */

    public function stripe(Request $request)
    {
        return view('stripe_payment', [
            'amount'   => $request->input('amount'),
            'pay_to'   => $request->input('pay_to'),
            'currency' => $request->input('currency')
        ]);
    }

    /**
    * 
    * @method post_stripe() 
    * 
    * @param Request object
    * @return string [describing the success or failure of the transfer of money between the two stripe accounts] 
    * Desc : This method transfers amount from admin's stripe account to teacher's stripe account
    */

    public function post_stripe(Request $request)
    {
        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
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
            $result = Transfer::create([
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

    /**
    * 
    * @method stripe_account_details() 
    * 
    * @param void
    * @return string [html view of stripe account balance details] 
    * Desc : This method retrieves details of admin's stripe account balance
    */

    public function stripe_account_details()
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $result = Balance::retrieve();
        return view('stripe_details', [
            'amount' => $result->available[0]->amount
        ]);
    }
}
