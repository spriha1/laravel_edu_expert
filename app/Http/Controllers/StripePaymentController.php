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
            \Stripe\Transfer::create([
                "amount" => $amount,
                "currency" => $currency,
                "destination" => $result['stripe_account_id']
            ]);
        }
        
        catch (Exception $e) {
            Log::error($e->getMessage());
            return $e->getMessage();
        }

        return("succeeded");
    }


    public function connect()
    {
        // Account Id of account to be connected
        $result = $this->stripe_detail->where('user_id', Auth::id())->select('code')->first();
        $code = $result['code'];
        $stripe_sk = 'sk_test_e5fmzx4vnU7xDvy5fwtX1FeC00eyLRkRmP';
        $stripe_sk_client_id = '';

        $req_url = 'https://connect.stripe.com/oauth/token';

        $fields = array(
            'client_secret' => urlencode('sk_test_e5fmzx4vnU7xDvy5fwtX1FeC00eyLRkRmP'),
            'code' => urlencode($code),
            'grant_type' => urlencode('authorization_code')
        );

        $fields_string = '';

        //url-ify the data for the POST
        foreach ($fields as $key=>$value) { 
            $fields_string .= $key.'='.$value.'&'; 
        }

        rtrim($fields_string, '&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_URL, $req_url);

        //execute post
        $result = curl_exec($ch);
        $http_info = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $stripe_account_id = json_decode($result)->stripe_user_id;

        $this->stripe_detail->where('user_id', Auth::id())
        ->update([
            'stripe_account_id' => $stripe_account_id
        ])

        //close connection
        curl_close($ch);
    }
}
